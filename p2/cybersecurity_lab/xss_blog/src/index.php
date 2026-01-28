<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['register'])) {
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->execute([$_POST['username'], password_hash($_POST['password'], PASSWORD_DEFAULT)]);
        $_SESSION['user_id'] = $pdo->lastInsertId();
        $_SESSION['username'] = $_POST['username'];
    } elseif (isset($_POST['login'])) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$_POST['username']]);
        $user = $stmt->fetch();
        if ($user && password_verify($_POST['password'], $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            // Set a dummy flag cookie to be stolen
            setcookie("FLAG", "CTF{xss_is_easy}", time() + 3600, "/");
        }
    } elseif (isset($_POST['comment']) && isset($_SESSION['user_id'])) {
        // VULNERABLE CODE: No sanitization
        $comment = $_POST['comment']; 
        $stmt = $pdo->prepare("INSERT INTO comments (user_id, content) VALUES (?, ?)");
        $stmt->execute([$_SESSION['user_id'], $comment]);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Mama's Recipes</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f4f9; color: #333; margin: 0; padding: 20px; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        h1 { color: #d63384; border-bottom: 2px solid #eee; padding-bottom: 10px; }
        p { line-height: 1.6; }
        input, textarea { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        textarea { height: 100px; font-family: inherit; }
        button { background-color: #d63384; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-size: 16px; margin-right: 5px; }
        button:hover { background-color: #a82365; }
        .comment-box { background-color: #fff0f6; border: 1px solid #ffdeeb; padding: 15px; margin-bottom: 15px; border-radius: 4px; }
        .comment-user { font-weight: bold; color: #a82365; display: block; margin-bottom: 5px; }
        hr { border: 0; border-top: 1px solid #eee; margin: 20px 0; }
        a { color: #d63384; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
    <h1>Mama's Best Chocolate Cake</h1>
    <p>Here is the secret recipe: 2 cups flour, 2 cups sugar, 3/4 cup cocoa powder...</p>
    <hr>
    
    <?php if (isset($_SESSION['user_id'])): ?>
        <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>! <a href="logout.php">Logout</a></p>
        <form method="post">
            <textarea name="comment" placeholder="Leave a comment..."></textarea><br>
            <button type="submit">Post Comment</button>
        </form>
    <?php else: ?>
        <h3>Login or Register to comment</h3>
        <form method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Login</button>
            <button type="submit" name="register">Register</button>
        </form>
    <?php endif; ?>

    <h3>Comments:</h3>
    <?php
    if (isset($pdo)) {
        $stmt = $pdo->query("SELECT users.username, comments.content FROM comments JOIN users ON comments.user_id = users.id ORDER BY comments.id DESC");
        while ($row = $stmt->fetch()) {
            echo "<div class='comment-box'>";
            echo "<span class='comment-user'>" . htmlspecialchars($row['username']) . ":</span>";
            // VULNERABLE: Outputting content directly
            echo $row['content']; 
            echo "</div>";
        }
    }
    ?>
    </div>
</body>
</html>
