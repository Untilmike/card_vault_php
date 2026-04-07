<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

define('AES_KEY', 'ExactlySixteen12');

try {
    $host = getenv('MYSQLHOST');
    $db   = getenv('MYSQLDATABASE');
    $user = getenv('MYSQLUSER');
    $pass = getenv('MYSQLPASSWORD');
    $port = getenv('MYSQLPORT') ?: '3306';

    $conn = new PDO(
        "mysql:host=$host;port=$port;dbname=$db;charset=utf8",
        $user,
        $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
         PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
    );
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>