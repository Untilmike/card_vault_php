<?php
define('DB_HOST', 'sql311.infinityfree.com');  // MySQL Host Name
define('DB_USER', 'if0_41594028');           // MySQL User Name
define('DB_PASS', 'Ruiru240');   // MySQL Password
define('DB_NAME', 'if0_41594028_epiz_123456_cardvault'); // MySQL DB Name
define('AES_KEY', 'ExactlySixteen12');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>