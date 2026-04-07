<?php
define('AES_KEY', 'ExactlySixteen12');

$host = getenv('MYSQLHOST')     ?: 'localhost';
$db   = getenv('MYSQLDATABASE') ?: '';
$user = getenv('MYSQLUSER')     ?: 'root';
$pass = getenv('MYSQLPASSWORD') ?: '';
$port = getenv('MYSQLPORT')     ?: '3306';

// Show all loaded extensions for debugging
$loaded = get_loaded_extensions();
$mysql_exts = array_filter($loaded, function($e) {
    return stripos($e, 'mysql') !== false || stripos($e, 'pdo') !== false;
});

if (empty($mysql_exts)) {
    die("No MySQL extensions found. All extensions: " . implode(', ', $loaded));
}

try {
    $dsn  = "mysql:host={$host};port={$port};dbname={$db};charset=utf8mb4";
    $conn = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage() .
        " | MySQL extensions: " . implode(', ', $mysql_exts));
}
?>