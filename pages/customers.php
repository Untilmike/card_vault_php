<?php
require_once '../includes/session.php';
require_role(['admin','merchant']);
require_once '../config/db.php';
include '../includes/header.php';

$mid = $_SESSION['merchant_id'] ?? 1;
$result = $conn->query(
    "SELECT * FROM vw_customer_summary
     WHERE business_name=(SELECT business_name FROM merchants WHERE merchant_id=$mid)"
);
?>
<h2>👤 Customers</h2>
<div style="margin-bottom:16px">
  <a href="add_customer.php" class="btn btn-g">+ Add Customer</a>
</div>
<table>
  <tr><th>#</th><th>Name</th><th>Email</th><th>Phone</th><th>Cards</th><th>Action</th></tr>
  <?php while ($c = $result->fetch_assoc()): ?>
  <tr>
    <td><?= $c['customer_id'] ?></td>
    <td><strong><?= htmlspecialchars($c['full_name']) ?></strong></td>
    <td><?= htmlspecialchars($c['email']) ?></td>
    <td><?= htmlspecialchars($c['phone'] ?? '—') ?></td>
    <td>
      <span class="badge <?= $c['cards_on_file'] > 0 ? 'b-g' : 'b-y' ?>">
        <?= $c['cards_on_file'] ?> card<?= $c['cards_on_file'] != 1 ? 's' : '' ?>
      </span>
    </td>
    <td>
      <a href="add_card.php?customer_id=<?= $c['customer_id'] ?>"
         class="btn btn-b" style="font-size:12px;padding:5px 10px">+ Add Card</a>
    </td>
  </tr>
  <?php endwhile; ?>
  <?php if ($result->num_rows === 0): ?>
  <tr><td colspan="6" style="text-align:center;color:#999;padding:24px">
    No customers yet.
  </td></tr>
  <?php endif; ?>
</table>
</div></body></html>