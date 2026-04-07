<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$role = $_SESSION['role'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Card Vault</title>
<style>
  *{box-sizing:border-box;margin:0;padding:0}
  body{font-family:Arial,sans-serif;background:#f4f6f9;color:#333}
  nav{background:#4a235a;padding:14px 28px;display:flex;
      justify-content:space-between;align-items:center}
  nav a{color:#ccc;text-decoration:none;margin-left:16px;font-size:14px}
  nav a:hover{color:#fff}
  .brand{color:#fff;font-weight:bold;font-size:17px}
  .container{padding:28px;max-width:1000px;margin:auto}
  h2{margin-bottom:20px;color:#4a235a}
  table{width:100%;border-collapse:collapse;background:#fff;
        border-radius:8px;overflow:hidden;
        box-shadow:0 2px 6px rgba(0,0,0,.08)}
  th{background:#4a235a;color:#fff;padding:11px 14px;
     text-align:left;font-size:13px}
  td{padding:10px 14px;border-bottom:1px solid #eee;font-size:13px}
  tr:last-child td{border-bottom:none}
  tr:hover td{background:#f9f9f9}
  .btn{display:inline-block;padding:8px 16px;background:#4a235a;
       color:#fff;border:none;border-radius:4px;cursor:pointer;
       text-decoration:none;font-size:13px}
  .btn:hover{opacity:.88}
  .btn-g{background:#b7950b}
  .btn-r{background:#e74c3c}
  .btn-b{background:#6c3483}
  label{display:block;font-size:13px;font-weight:bold;margin-bottom:4px}
  input,select{width:100%;padding:9px;margin-bottom:14px;
               border:1px solid #ccc;border-radius:4px;font-size:14px}
  .flash{background:#d4edda;color:#155724;padding:10px 14px;
         border-radius:4px;margin-bottom:16px}
  .flash-err{background:#f8d7da;color:#721c24;padding:10px 14px;
             border-radius:4px;margin-bottom:16px}
  .badge{padding:3px 9px;border-radius:10px;font-size:11px;font-weight:bold}
  .b-g{background:#d4edda;color:#155724}
  .b-y{background:#fff3cd;color:#856404}
  .b-r{background:#f8d7da;color:#721c24}
  .b-b{background:#cce5ff;color:#004085}
  .form-box{background:#fff;padding:28px;border-radius:8px;
            box-shadow:0 2px 6px rgba(0,0,0,.08);max-width:460px}
</style>
</head>
<body>
<nav>
  <span class="brand">🏦 Card Vault</span>
  <div>
    <?php if (in_array($role, ['admin','merchant'])): ?>
      <a href="/pages/customers.php">Customers</a>
      <a href="/pages/cards.php">Cards</a>
      <a href="/pages/invoices.php">Invoices</a>
    <?php endif; ?>
    <?php if ($role === 'cashier'): ?>
      <a href="/pages/invoices.php">Invoices</a>
    <?php endif; ?>
    <?php if (in_array($role, ['admin','auditor'])): ?>
      <a href="/pages/audit.php">Audit Log</a>
    <?php endif; ?>
    <a href="/auth/logout.php" style="color:#e74c3c">Logout</a>
  </div>
  <small style="color:#aaa">
    <?= htmlspecialchars($_SESSION['username'] ?? '') ?>
    &middot; <?= $role ?>
  </small>
</nav>
<div class="container"></div>