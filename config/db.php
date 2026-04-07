<?php
define('AES_KEY', 'ExactlySixteen12');

$url   = getenv('mysql://root:DXRbaLjFAvfSFwGlhSuLuastUxNaJJyf@maglev.proxy.rlwy.net:59987/railway');
$parts = parse_url($url);

$host = $parts['host'];
$user = $parts['user'];
$pass = $parts['pass'];
$db   = ltrim($parts['path'], '/');
$port = $parts['port'] ?? 3306;

// Use mysqlnd directly via PDO with socket
if (!extension_loaded('pdo_mysql')) {
    // Manually register mysqlnd as pdo_mysql
    if (!dl('pdo_mysql.so')) {
        die("Cannot load pdo_mysql. Available: " . implode(', ', get_loaded_extensions()));
    }
}

try {
    $conn = new PDO(
        "mysql:host={$host};port={$port};dbname={$db};charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>