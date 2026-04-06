<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: /card_vault_php/auth/login.php");
    exit();
}

// Check role access
function require_role(array $roles) {
    if (!in_array($_SESSION['role'], $roles)) {
        header("Location: /card_vault_php/pages/denied.php");
        exit();
    }
}

// Log user actions to audit table
function log_action($conn, $action, $table, $record_id = null) {
    if (!isset($_SESSION['user_id'])) return;
    $uid  = $_SESSION['user_id'];
    $ip   = $_SERVER['REMOTE_ADDR'];
    $rid  = $record_id ? intval($record_id) : 'NULL';
    $conn->query(
        "INSERT INTO audit_log (user_id, action, table_name, record_id, ip_address)
         VALUES ($uid, '$action', '$table', $rid, '$ip')"
    );
}
?>