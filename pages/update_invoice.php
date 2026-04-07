<?php
require_once '../includes/session.php';
require_role(['admin','merchant']);
require_once '../config/db.php';

$id     = intval($_GET['id']);
$status = in_array($_GET['status'], ['paid','failed','pending'])
          ? $_GET['status'] : '';

if ($status) {
    $stmt = $conn->prepare(
        "UPDATE invoices SET status=? WHERE invoice_id=?"
    );
    $stmt->execute([$status, $id]);
    log_action($conn, 'UPDATE-' . $status, 'invoices', $id);
}
header("Location: /pages/invoices.php");
exit();
?>