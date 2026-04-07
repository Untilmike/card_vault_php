<?php
define('AES_KEY', 'ExactlySixteen12');

$host = getenv('MYSQLHOST')     ?: 'localhost';
$db   = getenv('MYSQLDATABASE') ?: '';
$user = getenv('MYSQLUSER')     ?: 'root';
$pass = getenv('MYSQLPASSWORD') ?: '';
$port = getenv('MYSQLPORT')     ?: '3306';

try {
    $dsn  = "mysql:host={$host};port={$port};dbname={$db};charset=utf8mb4";
    $conn = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    // Show connection details for debugging
    die("Connection failed: " . $e->getMessage() . 
        " | Host: $host | DB: $db | User: $user | Port: $port");
}
?>