<?php
require_once '../includes/session.php';
require_once '../config/db.php';
include '../includes/header.php';
?>
<h2>Dashboard</h2>
<p style="color:#666;margin-bottom:24px">
  Welcome back, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>.
  Logged in as <strong><?= $_SESSION['role'] ?></strong>.
</p>

<div style="background:#fff;padding:20px 28px;border-radius:8px;
            box-shadow:0 2px 6px rgba(0,0,0,.08);margin-bottom:24px">
  <h3 style="color:#4a235a;margin-bottom:10px">Data Classification</h3>
  <table>
    <tr><th>Classification</th><th>Fields</th><th>Protection</th></tr>
    <tr><td>Public</td><td>Customer name, email, phone</td><td>No encryption</td></tr>
    <tr><td>Confidential</td><td>Card type, last 4 digits, expiry</td><td>AES Encrypted</td></tr>
    <tr><td>Sensitive</td><td>Full card number, CVV, billing address</td><td>AES Encrypted</td></tr>
  </table>
</div>

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:16px">
  <?php if (in_array($_SESSION['role'], ['admin','merchant'])): ?>
  <a href="customers.php" style="text-decoration:none">
    <div style="background:#fff;padding:24px;border-radius:8px;
                box-shadow:0 2px 6px rgba(0,0,0,.08);text-align:center">
      <div style="font-size:30px">👤</div>
      <div style="margin-top:8px;font-weight:bold;color:#4a235a">Customers</div>
      <div style="font-size:12px;color:#888;margin-top:4px">Public info</div>
    </div></a>
  <a href="cards.php" style="text-decoration:none">
    <div style="background:#fff;padding:24px;border-radius:8px;
                box-shadow:0 2px 6px rgba(0,0,0,.08);text-align:center">
      <div style="font-size:30px">💳</div>
      <div style="margin-top:8px;font-weight:bold;color:#4a235a">Cards</div>
      <div style="font-size:12px;color:#888;margin-top:4px">Encrypted vault</div>
    </div></a>
  <?php endif; ?>
  <?php if (in_array($_SESSION['role'], ['admin','merchant','cashier'])): ?>
  <a href="invoices.php" style="text-decoration:none">
    <div style="background:#fff;padding:24px;border-radius:8px;
                box-shadow:0 2px 6px rgba(0,0,0,.08);text-align:center">
      <div style="font-size:30px">🧾</div>
      <div style="margin-top:8px;font-weight:bold;color:#4a235a">Invoices</div>
      <div style="font-size:12px;color:#888;margin-top:4px">Billing records</div>
    </div></a>
  <?php endif; ?>
  <?php if (in_array($_SESSION['role'], ['admin','auditor'])): ?>
  <a href="audit.php" style="text-decoration:none">
    <div style="background:#fff;padding:24px;border-radius:8px;
                box-shadow:0 2px 6px rgba(0,0,0,.08);text-align:center">
      <div style="font-size:30px">🔍</div>
      <div style="margin-top:8px;font-weight:bold;color:#4a235a">Audit Log</div>
      <div style="font-size:12px;color:#888;margin-top:4px">All activity</div>
    </div></a>
  <?php endif; ?>
</div>
</div></body></html>