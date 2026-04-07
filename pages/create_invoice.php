<?php
require_once '../includes/session.php';
require_role(['admin','merchant']);
require_once '../config/db.php';
include '../includes/header.php';

$mid     = $_SESSION['merchant_id'] ?? 1;
$success = $error = '';
$cards   = [];

$stmt = $conn->prepare(
    "SELECT customer_id,full_name FROM customers WHERE merchant_id=?"
);
$stmt->execute([$mid]);
$customers = $stmt->fetchAll();

$cid = isset($_GET['customer_id']) ? intval($_GET['customer_id']) : null;
if ($cid) {
    $stmt = $conn->prepare(
        "SELECT card_id,card_type,last_four FROM cards
         JOIN customers c USING(customer_id)
         WHERE c.customer_id = ?"
    );
    $stmt->execute([$cid]);
    $cards = $stmt->fetchAll();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cid    = intval($_POST['customer_id']);
    $cardid = intval($_POST['card_id']);
    $amount = floatval($_POST['amount']);

    $stmt = $conn->prepare(
        "INSERT INTO invoices (merchant_id,customer_id,card_id,amount)
         VALUES (?,?,?,?)"
    );
    if ($stmt->execute([$mid, $cid, $cardid, $amount])) {
        log_action($conn, 'INSERT', 'invoices', $conn->lastInsertId());
        $success = "Invoice created successfully.";
    } else {
        $error = "Error creating invoice.";
    }
}
?>
<h2>🧾 Create Invoice</h2>
<?php if ($success): ?><div class="flash"><?= $success ?></div><?php endif; ?>
<?php if ($error):   ?><div class="flash-err"><?= $error ?></div><?php endif; ?>
<div class="form-box">
  <form method="GET" action="/pages/create_invoice.php">
    <label>Step 1 — Select Customer</label>
    <select name="customer_id" onchange="this.form.submit()">
      <option value="">-- Choose a customer --</option>
      <?php foreach ($customers as $c): ?>
      <option value="<?= $c['customer_id'] ?>"
        <?= $cid == $c['customer_id'] ? 'selected' : '' ?>>
        <?= htmlspecialchars($c['full_name']) ?>
      </option>
      <?php endforeach; ?>
    </select>
  </form>
  <?php if (!empty($cards)): ?>
  <form method="POST" action="/pages/create_invoice.php" style="margin-top:4px">
    <input type="hidden" name="customer_id" value="<?= $cid ?>">
    <label>Step 2 — Select Saved Card</label>
    <select name="card_id" required>
      <option value="">-- Choose a card --</option>
      <?php foreach ($cards as $card): ?>
      <option value="<?= $card['card_id'] ?>">
        <?= $card['card_type'] ?> ending in <?= $card['last_four'] ?>
      </option>
      <?php endforeach; ?>
    </select>
    <label>Step 3 — Amount (KES)</label>
    <input name="amount" type="number" step="0.01" min="1"
           placeholder="e.g. 1500.00" required>
    <button type="submit" class="btn btn-g">Create Invoice</button>
    <a href="/pages/invoices.php" class="btn" style="margin-left:8px">Cancel</a>
  </form>
  <?php elseif ($cid): ?>
  <p style="color:#e74c3c;margin-top:16px;font-size:13px">
    No cards for this customer.
    <a href="/pages/add_card.php?customer_id=<?= $cid ?>">Add one first →</a>
  </p>
  <?php endif; ?>
</div>
</div></body></html>