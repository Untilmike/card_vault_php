<?php
require_once '../includes/session.php';
require_role(['admin','merchant']);
require_once '../config/db.php';
include '../includes/header.php';

$mid = $_SESSION['merchant_id'] ?? 1;

if ($_SESSION['role'] === 'admin') {
    $stmt = $conn->query(
        "SELECT c.customer_id, c.full_name, c.email, c.phone,
                m.business_name, COUNT(cr.card_id) AS cards_on_file
         FROM customers c
         JOIN merchants m ON c.merchant_id = m.merchant_id
         LEFT JOIN cards cr ON c.customer_id = cr.customer_id
         GROUP BY c.customer_id, m.business_name"
    );
} else {
    $stmt = $conn->prepare(
        "SELECT c.customer_id, c.full_name, c.email, c.phone,
                m.business_name, COUNT(cr.card_id) AS cards_on_file
         FROM customers c
         JOIN merchants m ON c.merchant_id = m.merchant_id
         LEFT JOIN cards cr ON c.customer_id = cr.customer_id
         WHERE c.merchant_id = ?
         GROUP BY c.customer_id, m.business_name"
    );
    $stmt->execute([$mid]);
}
$customers = $stmt->fetchAll();
?>
<h2>👤 Customers</h2>
<div style="margin-bottom:16px">
  <a href="/pages/add_customer.php" class="btn btn-g">+ Add Customer</a>
</div>
<table>
  <tr><th>#</th><th>Name</th><th>Email</th><th>Phone</th><th>Cards</th><th>Action</th></tr>
  <?php foreach ($customers as $c): ?>
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
      <a href="/pages/add_card.php?customer_id=<?= $c['customer_id'] ?>"
         class="btn btn-b" style="font-size:12px;padding:5px 10px">+ Add Card</a>
    </td>
  </tr>
  <?php endforeach; ?>
  <?php if (empty($customers)): ?>
  <tr><td colspan="6" style="text-align:center;color:#999;padding:24px">
    No customers yet.
  </td></tr>
  <?php endif; ?>
</table>
</div></body></html>