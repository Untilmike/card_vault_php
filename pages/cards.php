<?php
require_once '../includes/session.php';
require_role(['admin','merchant']);
require_once '../config/db.php';
include '../includes/header.php';

$mid  = $_SESSION['merchant_id'] ?? 1;
$key  = AES_KEY;
$role = $_SESSION['role'];

$card_number_sql = $role === 'admin'
    ? "CAST(AES_DECRYPT(cr.card_number_enc,'$key') AS CHAR) AS card_number"
    : "CONCAT('**** **** **** ', cr.last_four) AS card_number";

$stmt = $conn->prepare(
    "SELECT cr.card_id, c.full_name, cr.card_type, cr.last_four,
            CAST(AES_DECRYPT(cr.expiry_enc,'$key') AS CHAR)  AS expiry,
            CAST(AES_DECRYPT(cr.billing_enc,'$key') AS CHAR) AS billing,
            $card_number_sql,
            cr.card_token
     FROM cards cr
     JOIN customers c ON cr.customer_id = c.customer_id
     WHERE c.merchant_id = ?"
);
$stmt->execute([$mid]);
$cards = $stmt->fetchAll();
?>
<h2>💳 Card Vault
  <?php if ($role === 'admin'): ?>
    <span class="badge b-r" style="font-size:12px;margin-left:8px">Admin — decrypted view</span>
  <?php else: ?>
    <span class="badge b-y" style="font-size:12px;margin-left:8px">Merchant — masked view</span>
  <?php endif; ?>
</h2>
<div style="margin-bottom:16px">
  <a href="/pages/add_card.php" class="btn btn-r">+ Add Card</a>
</div>
<table>
  <tr><th>Customer</th><th>Type</th><th>Card Number</th>
      <th>Expiry</th><th>Billing</th><th>Token</th></tr>
  <?php foreach ($cards as $r): ?>
  <tr>
    <td><strong><?= htmlspecialchars($r['full_name']) ?></strong></td>
    <td><?= $r['card_type'] ?></td>
    <td><code><?= $r['card_number'] ?></code></td>
    <td><?= $r['expiry'] ?></td>
    <td><?= htmlspecialchars($r['billing']) ?></td>
    <td><small style="color:#888;font-family:monospace">
      <?= substr($r['card_token'], 0, 16) ?>…
    </small></td>
  </tr>
  <?php endforeach; ?>
  <?php if (empty($cards)): ?>
  <tr><td colspan="6" style="text-align:center;color:#999;padding:24px">
    No cards stored yet.
  </td></tr>
  <?php endif; ?>
</table>
</div></body></html>