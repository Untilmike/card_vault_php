<?php
require_once '../includes/session.php';
require_role(['admin','merchant']);
require_once '../config/db.php';
include '../includes/header.php';

$success = $error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mid  = $_SESSION['merchant_id'] ?? 1;
    $name = trim($_POST['full_name']);
    $email= trim($_POST['email']);
    $phone= trim($_POST['phone']);

    $stmt = $conn->prepare(
        "INSERT INTO customers (merchant_id,full_name,email,phone) VALUES (?,?,?,?)"
    );
    $stmt->bind_param("isss", $mid, $name, $email, $phone);
    if ($stmt->execute()) {
        log_action($conn, 'INSERT', 'customers', $conn->insert_id);
        $success = "Customer added successfully.";
    } else {
        $error = "Error: " . $stmt->error;
    }
}
?>
<h2>👤 Add New Customer</h2>
<?php if ($success): ?><div class="flash"><?= $success ?></div><?php endif; ?>
<?php if ($error):   ?><div class="flash-err"><?= $error ?></div><?php endif; ?>
<div class="form-box">
  <p style="font-size:13px;color:#888;margin-bottom:18px">
    Customer details are <strong>public information</strong> — stored without encryption.
  </p>
  <form method="POST">
    <label>Full Name</label>
    <input name="full_name" placeholder="e.g. Jane Doe" required>
    <label>Email Address</label>
    <input name="email" type="email" placeholder="e.g. jane@email.com" required>
    <label>Phone Number</label>
    <input name="phone" placeholder="e.g. 0712345678">
    <button type="submit" class="btn btn-g">Save Customer</button>
    <a href="customers.php" class="btn" style="margin-left:8px">Cancel</a>
  </form>
</div>
</div></body></html>