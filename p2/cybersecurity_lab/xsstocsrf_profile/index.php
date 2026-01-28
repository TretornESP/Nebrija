<?php
session_start();

// Database imitation
$db_file = 'users.json';
if (!file_exists($db_file)) {
    file_put_contents($db_file, json_encode(['admin' => ['password' => 'admin', 'email' => 'admin@company.com']]));
}
$users = json_decode(file_get_contents($db_file), true);

$message = "";

// Handle Profile Update (Vulnerable to CSRF - No Token)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_email'])) {
    if (isset($_SESSION['user'])) {
        $users[$_SESSION['user']]['email'] = $_POST['email'];
        file_put_contents($db_file, json_encode($users));
        $message = "Email updated to " . htmlspecialchars($_POST['email']);
    } else {
        $message = "You must be logged in.";
    }
}

// Handle Login
if (isset($_POST['login'])) {
    $u = $_POST['username'];
    $p = $_POST['password'];
    if (isset($users[$u]) && $users[$u]['password'] === $p) {
        $_SESSION['user'] = $u;
    } else {
        $message = "Invalid credentials";
    }
}

// Handle Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

// Handle Feedback (Vulnerable to XSS)
$feedback_file = 'feedback.txt';
if (isset($_POST['feedback'])) {
    // VULNERABLE: No sanitization
    $f = $_POST['feedback'] . "\n---\n";
    file_put_contents($feedback_file, $f, FILE_APPEND);
    $message = "Feedback submitted!";
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>CSRF Lab</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f4f9; padding: 20px; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        h1, h2, h3 { color: #333; }
        .section { margin-bottom: 30px; padding-bottom: 20px; border-bottom: 1px solid #eee; }
        input[type="text"], input[type="password"], input[type="email"], textarea { width: 100%; padding: 10px; margin: 5px 0 15px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        button { background-color: #28a745; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-size: 16px; }
        button.logout { background-color: #6c757d; }
        button:hover { opacity: 0.9; }
        .alert { padding: 10px; background-color: #d1ecf1; color: #0c5460; border-radius: 4px; margin-bottom: 20px; }
        .feedback-box { background: #fff3cd; padding: 15px; border: 1px solid #ffeeba; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Profile Manager (CSRF Target)</h1>
        
        <?php if ($message): ?>
            <div class="alert"><?php echo $message; ?></div>
        <?php endif; ?>

        <?php if (isset($_SESSION['user'])): ?>
            <div class="section">
                <p>Logged in as: <strong><?php echo htmlspecialchars($_SESSION['user']); ?></strong> | <a href="?logout">Logout</a></p>
                <p>Current Email: <strong><?php echo htmlspecialchars($users[$_SESSION['user']]['email']); ?></strong></p>
            </div>

            <div class="section">
                <h3>Update Profile</h3>
                <p><em>Vulnerable Form: No CSRF Token!</em></p>
                <!-- This is the target form for the CSRF attack -->
                <form method="post" action="index.php">
                    <label>New Email:</label>
                    <input type="email" name="email" placeholder="new@email.com" required>
                    <input type="hidden" name="update_email" value="1">
                    <button type="submit">Update Email</button>
                </form>
            </div>
        <?php else: ?>
            <div class="section">
                <h3>Login</h3>
                <p>Use <code>admin</code> / <code>admin</code></p>
                <form method="post">
                    <input type="text" name="username" placeholder="Username" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <button type="submit" name="login">Login</button>
                </form>
            </div>
        <?php endif; ?>

        <div class="section">
            <h3>Feedback (XSS Vector)</h3>
            <p>Post a comment here. If an admin views it, the stored XSS payload can trigger the Profile Update form above!</p>
            <form method="post">
                <textarea name="feedback" placeholder="Great site! <script>...</script>"></textarea>
                <button type="submit" style="background-color: #007bff;">Submit Feedback</button>
            </form>

            <div class="feedback-box">
                <h4>Recent Feedback:</h4>
                <?php 
                // VULNERABLE: Outputting directly
                if (file_exists($feedback_file)) {
                    // Removed nl2br to allow multi-line XSS payloads to work without breaking JS syntax
                    echo file_get_contents($feedback_file); 
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>
