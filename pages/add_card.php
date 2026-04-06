<?php
require_once '../includes/session.php';
require_role(['admin','merchant']);
require_once '../config/db.php';
include '../includes/header.php';

$mid      = $_SESSION['merchant_id'] ?? 1;
$key      = AES_KEY;
$success  = $error = '';

$customers = $conn->query(
    "SELECT customer_id,full_name FROM customers WHERE merchant_id=$mid"
);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cid     = intval($_POST['customer_id']);
    $type    = $conn->real_escape_string($_POST['card_type']);
    $cardnum = preg_replace('/\s+/', '', $_POST['card_number']);
    $last4   = substr($cardnum, -4);
    $expiry  = $conn->real_escape_string($_POST['expiry']);
    $cvv     = $conn->real_escape_string($_POST['cvv']);
    $billing = $conn->real_escape_string($_POST['billing']);
    $token   = bin2hex(random_bytes(16));

    $stmt = $conn->prepare(
        "INSERT INTO cards
         (customer_id,card_token,card_type,last_four,
          expiry_enc,card_number_enc,cvv_enc,billing_enc)
         VALUES (?,?,?,?,
                 AES_ENCRYPT(?,?),
                 AES_ENCRYPT(?,?),
                 AES_ENCRYPT(?,?),
                 AES_ENCRYPT(?,?))"
    );
    $stmt->bind_param(
        "isssssssssss",
        $cid, $token, $type, $last4,
        $expiry,  $key,
        $cardnum, $key,
        $cvv,     $key,
        $billing, $key
    );

    if ($stmt->execute()) {
        log_action($conn, 'INSERT', 'cards', $conn->insert_id);
        $success = "Card encrypted and saved. Token: <code>$token</code>";
    } else {
        $error = "Error: " . $stmt->error;
    }
}
?>
<h2>💳 Add Credit Card</h2>
<?php if ($success): ?><div class="flash"><?= $success ?></div><?php endif; ?>
<?php if ($error):   ?><div class="flash-err"><?= $error ?></div><?php endif; ?>
<div class="form-box">
  <p style="font-size:13px;color:#888;margin-bottom:18px">
    All fields below are <strong>AES encrypted</strong> before storing.
  </p>
  <form method="POST">
    <label>Customer</label>
    <select name="customer_id" required>
      <option value="">-- Select Customer --</option>
      <?php while ($c = $customers->fetch_assoc()): ?>
      <option value="<?= $c['customer_id'] ?>"
        <?= (isset($_GET['customer_id']) && $_GET['customer_id'] == $c['customer_id']) ? 'selected' : '' ?>>
        <?= htmlspecialchars($c['full_name']) ?>
      </option>
      <?php endwhile; ?>
    </select>
    <label>Card Type</label>
    <select name="card_type">
      <option>Visa</option><option>Mastercard</option><option>Amex</option>
    </select>
    <label>Card Number (sensitive)</label>
    <input name="card_number" maxlength="19" placeholder="1234 5678 9012 3456" required>
    <label>Expiry Date (confidential)</label>
    <input name="expiry" maxlength="5" placeholder="MM/YY" required>
    <label>CVV (sensitive)</label>
    <input name="cvv" maxlength="4" placeholder="123" required>
    <label>Billing Address (sensitive)</label>
    <input name="billing" placeholder="e.g. 123 Main St, Nairobi" required>
    <button type="submit" class="btn btn-r">🔒 Encrypt and Save Card</button>
    <a href="cards.php" class="btn" style="margin-left:8px">Cancel</a>
  </form>
</div>
</div></body></html>