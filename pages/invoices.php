<?php
require_once '../includes/session.php';
require_role(['admin','merchant','cashier']);
require_once '../config/db.php';
include '../includes/header.php';

$mid  = $_SESSION['merchant_id'] ?? 1;
$role = $_SESSION['role'];

if ($role === 'admin') {
    $result = $conn->query(
        "SELECT * FROM vw_invoice_history ORDER BY created_at DESC"
    );
} else {
    $result = $conn->query(
        "SELECT * FROM vw_invoice_history
         WHERE business_name=(SELECT business_name FROM merchants WHERE merchant_id=$mid)
         ORDER BY created_at DESC"
    );
}
?>
<h2>🧾 Invoice History</h2>
<div style="margin-bottom:16px">
  <?php if (in_array($role, ['admin','merchant'])): ?>
  <a href="create_invoice.php" class="btn btn-g">+ New Invoice</a>
  <?php endif; ?>
</div>
<table>
  <tr>
    <th>#</th><th>Customer</th><th>Card</th>
    <th>Amount (KES)</th><th>Status</th><th>Date</th>
    <?php if (in_array($role, ['admin','merchant'])): ?><th>Action</th><?php endif; ?>
  </tr>
  <?php while ($inv = $result->fetch_assoc()):
    $bc = $inv['status'] === 'paid' ? 'b-g' : ($inv['status'] === 'pending' ? 'b-y' : 'b-r');
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
      <a href="update_invoice.php?id=<?= $inv['invoice_id'] ?>&status=paid"
         class="btn btn-g" style="font-size:12px;padding:4px 8px"
         onclick="return confirm('Mark as paid?')">Paid</a>
      <a href="update_invoice.php?id=<?= $inv['invoice_id'] ?>&status=failed"
         class="btn btn-r" style="font-size:12px;padding:4px 8px"
         onclick="return confirm('Mark as failed?')">Failed</a>
      <?php else: ?>—<?php endif; ?>
    </td>
    <?php endif; ?>
  </tr>
  <?php endwhile; ?>
</table>
</div></body></html>