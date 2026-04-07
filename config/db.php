<?php
define('AES_KEY', 'ExactlySixteen12');

// Parse public URL
$url   = getenv('mysql://root:DXRbaLjFAvfSFwGlhSuLuastUxNaJJyf@maglev.proxy.rlwy.net:59987/railway');
$parts = parse_url($url);

$host = $parts['host'];
$user = $parts['user'];
$pass = $parts['pass'];
$db   = ltrim($parts['path'], '/');
$port = $parts['port'] ?? 3306;

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    die("Connect failed: " . $conn->connect_error .
        " | Extensions: " . implode(', ', get_loaded_extensions()));
}
$conn->set_charset('utf8mb4');
?>