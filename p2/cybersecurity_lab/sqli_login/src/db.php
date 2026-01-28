<?php
$host = getenv('MYSQL_HOST') ?: 'sqli_db';
$db   = getenv('MYSQL_DB') ?: 'sqli_login';
$user = getenv('MYSQL_USER') ?: 'root';
$pass = getenv('MYSQL_PASSWORD') ?: 'root';
$charset = 'utf8mb4';

// Use mysqli for easier procedural SQLi demonstration or just raw PDO queries
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>