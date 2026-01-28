<?php
require 'db.php';
session_start();

$message = "";

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // VULNERABLE: SQL Injection
    // Typical bypass: ' OR '1'='1
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    
    // Debugging (optional, helpful for students)
    // echo "Query: " . $sql . "<br>";

    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $_SESSION['user'] = $username;
        $row = $result->fetch_assoc();
        
        if ($row['username'] === 'admin') {
             $message = "Welcome Administrator! The flag is CTF{sql_injection_master}";
        } else {
             $message = "Welcome " . $row['username'];
        }
    } else {
        $message = "Invalid credentials";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Secure Login</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #212529; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .container { background: #343a40; padding: 2.5rem; border-radius: 8px; box-shadow: 0 10px 25px rgba(0,0,0,0.5); width: 100%; max-width: 350px; color: #fff; }
        h1 { text-align: center; margin-bottom: 30px; font-weight: 300; letter-spacing: 1px; }
        input[type="text"] { width: 100%; padding: 12px; margin: 8px 0 20px; background-color: #495057; border: 1px solid #6c757d; border-radius: 4px; color: white; box-sizing: border-box; }
        input[type="text"]:focus { outline: none; border-color: #adb5bd; }
        input[type="submit"] { background-color: #198754; color: white; border: none; padding: 12px; border-radius: 4px; cursor: pointer; font-size: 16px; width: 100%; font-weight: bold; letter-spacing: 0.5px; }
        input[type="submit"]:hover { background-color: #157347; }
        label { font-size: 0.9em; color: #adb5bd; }
        .error { color: #ff6b6b; text-align: center; margin-bottom: 15px; }
        .success { color: #51cf66; text-align: center; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="container">
    <h1>Login Portal</h1>
    <?php if ($message) echo "<div class='" . (strpos($message, 'Welcome') !== false ? 'success' : 'error') . "'>$message</div>"; ?>
    <form method="post">
        <label>Username</label>
        <input type="text" name="username">
        <label>Password</label>
        <input type="text" name="password"> <!-- Using text type for password to easily see payload -->
        <input type="submit" name="login" value="Sign In">
    </form>
    </div>
</body>
</html>