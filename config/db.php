<?php
define('AES_KEY', 'ExactlySixteen12');

// Parse the public MySQL URL
$url = getenv('mysql://root:DXRbaLjFAvfSFwGlhSuLuastUxNaJJyf@maglev.proxy.rlwy.net:59987/railway');

if ($url) {
    $parts = parse_url($url);
    $host  = $parts['host'];
    $user  = $parts['user'];
    $pass  = $parts['pass'];
    $db    = ltrim($parts['path'], '/');
    $port  = $parts['port'] ?? 3306;
} else {
    $host  = getenv('MYSQLHOST')     ?: 'localhost';
    $user  = getenv('MYSQLUSER')     ?: 'root';
    $pass  = getenv('MYSQLPASSWORD') ?: '';
    $db    = getenv('MYSQLDATABASE') ?: '';
    $port  = getenv('MYSQLPORT')     ?: '3306';
}

try {
    $dsn  = "mysql:host={$host};port={$port};dbname={$db};charset=utf8mb4";
    $conn = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (Exception $e) {
    die("Connection failed: " . $e->getMessage());
}

// PDO helper to mimic mysqli insert_id
function last_insert_id($conn) {
    return $conn->lastInsertId();
}
?>