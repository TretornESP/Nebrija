<?php
$host = getenv('MYSQL_HOST') ?: 'xss_db';
$db   = getenv('MYSQL_DB') ?: 'xss_blog';
$user = getenv('MYSQL_USER') ?: 'root';
$pass = getenv('MYSQL_PASSWORD') ?: 'root';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // In a real scenario, don't show specific errors, but for a lab it helps debugging
    // echo $e->getMessage(); 
    // We might need to wait for DB to start, so suppressing error for now or retry logic in simple labs is handled by user refresh usually.
}
?>