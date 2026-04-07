<?php
require_once '../includes/session.php';
require_role(['admin','merchant','cashier']);
require_once '../config/db.php';
include '../includes/header.php';

$mid  = $_SESSION['merchant_id'] ?? 1;
$role = $_SESSION['role'];

$base = "SELECT i.invoice_id, m.business_name, c.full_name AS customer,
                cr.card_type, cr.last_four, i.amount, i.status, i.created_at
         FROM invoices i
         JOIN merchants m ON i.merchant_id = m.merchant_id
         JOIN customers c ON i.customer_id = c.customer_id
         JOIN cards cr ON i.card_id = cr.card_id";

if ($role === 'admin') {
    $stmt = $conn->query($base . " ORDER BY i.created_at DESC");
} else {
    $stmt = $conn->prepare($base . " WHERE i.merchant_id = ? ORDER BY i.created_at DESC");
    $stmt->execute([$mid]);
}
$invoices = $stmt->fetchAll();
?>
<h2>🧾 Invoice History</h2>
<div style="margin-bottom:16px">
  <?php if (in_array($role, ['admin','merchant'])): ?>
  <a href="/pages/create_invoice.php" class="btn btn-g">+ New Invoice</a>
  <?php endif; ?>
</div>
<table>
  <tr>
    <th>#</th><th>Customer</th><th>Card</th>
    <th>Amount (KES)</th><th>Status</th><th>Date</th>
    <?php if (in_array($role, ['admin','merchant'])): ?><th>Action</th><?php endif; ?>
  </tr>
  <?php foreach ($invoices as $inv):
    $bc = $inv['status'] === 'paid' ? 'b-g' :
          ($inv['status'] === 'pending' ? 'b-y' : 'b-r');
  ?>
  <tr>
    <td><strong>#<?= $inv['invoice_id'] ?></strong></td>
    <td><?= htmlspecialchars($inv['customer']) ?></td>
    <td><?= $inv['card_type'] ?> &bull;&bull;&bull;&bull; <?= $inv['last_four'] ?></td>
    <td><strong><?= number_format($inv['amount'], 2) ?></strong></td>
    <td><span class="badge <?= $bc ?>"><?= $inv['status'] ?></span></td>
    <td><?= date('d M Y', strtotime($inv['created_at'])) ?></td>
    <?php if (in_array($role, ['admin','merchant'])): ?>
    <td>
      <?php if ($inv['status'] === 'pending'): ?>
      <a href="/pages/update_invoice.php?id=<?= $inv['invoice_id'] ?>&status=paid"
         class="btn btn-g" style="font-size:12px;padding:4px 8px"
         onclick="return confirm('Mark as paid?')">Paid</a>
      <a href="/pages/update_invoice.php?id=<?= $inv['invoice_id'] ?>&status=failed"
         class="btn btn-r" style="font-size:12px;padding:4px 8px"
         onclick="return confirm('Mark as failed?')">Failed</a>
      <?php else: ?>—<?php endif; ?>
    </td>
    <?php endif; ?>
  </tr>
  <?php endforeach; ?>
  <?php if (empty($invoices)): ?>
  <tr><td colspan="7" style="text-align:center;color:#999;padding:24px">
    No invoices yet.
  </td></tr>
  <?php endif; ?>
</table>
</div></body></html>
