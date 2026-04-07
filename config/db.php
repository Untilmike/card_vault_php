<?php
define('AES_KEY', 'ExactlySixteen12');

// Show exactly what variables we have
$url        = getenv('MYSQL_PUBLIC_URL');
$mysqlhost  = getenv('MYSQLHOST');
$mysqluser  = getenv('MYSQLUSER');
$mysqlpass  = getenv('MYSQLPASSWORD');
$mysqldb    = getenv('MYSQLDATABASE');
$mysqlport  = getenv('MYSQLPORT');

echo "<pre>";
echo "MYSQL_PUBLIC_URL: " . ($url ? substr($url, 0, 30) . "..." : "NOT SET") . "\n";
echo "MYSQLHOST: " . ($mysqlhost ?: "NOT SET") . "\n";
echo "MYSQLUSER: " . ($mysqluser ?: "NOT SET") . "\n";
echo "MYSQLDB: "   . ($mysqldb   ?: "NOT SET") . "\n";
echo "MYSQLPORT: " . ($mysqlport ?: "NOT SET") . "\n";
echo "Extensions: " . implode(', ', get_loaded_extensions()) . "\n";
echo "</pre>";
die();
?>