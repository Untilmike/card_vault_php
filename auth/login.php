<?php
session_start();
require_once '../config/db.php';

if (isset($_SESSION['user_id'])) {
    header("Location: /pages/dashboard.php");
    exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = hash('sha256', trim($_POST['password']));

    $stmt = $conn->prepare(
        "SELECT * FROM users WHERE username=? AND password_hash=?"
    );
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user) {
        $_SESSION['user_id']     = $user['user_id'];
        $_SESSION['username']    = $user['username'];
        $_SESSION['role']        = $user['role'];
        $_SESSION['merchant_id'] = $user['merchant_id'];

        $uid = $user['user_id'];
        $ip  = $_SERVER['REMOTE_ADDR'];
        $conn->query(
            "INSERT INTO audit_log (user_id,action,table_name,ip_address)
             VALUES ($uid,'LOGIN','users','$ip')"
        );
        header("Location: /pages/dashboard.php");
        exit();
    }
    $error = 'Invalid username or password.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login — Card Vault</title>
<style>
  *{box-sizing:border-box;margin:0;padding:0}
  body{font-family:Arial,sans-serif;background:#f4f6f9;
       display:flex;justify-content:center;align-items:center;height:100vh}
  .box{background:#fff;padding:36px;border-radius:8px;
       box-shadow:0 2px 10px rgba(0,0,0,.12);width:330px}
  h2{text-align:center;margin-bottom:6px;color:#4a235a}
  .sub{text-align:center;color:#888;font-size:13px;margin-bottom:22px}
  label{display:block;font-size:13px;font-weight:bold;margin-bottom:4px}
  input{width:100%;padding:10px;margin-bottom:14px;
        border:1px solid #ccc;border-radius:4px;font-size:14px}
  button{width:100%;padding:11px;background:#4a235a;color:#fff;
         border:none;border-radius:4px;cursor:pointer;font-size:15px}
  button:hover{background:#6c3483}
  .err{color:#e74c3c;text-align:center;margin-bottom:12px;font-size:13px}
</style>
</head>
<body>
<div class="box">
  <h2>🔐 Card Vault</h2>
  <p class="sub">Secure Merchant Payment System</p>
  <?php if ($error): ?>
    <p class="err"><?= $error ?></p>
  <?php endif; ?>
  <form method="POST">
    <label>Username</label>
    <input name="username" placeholder="Enter username" required>
    <label>Password</label>
    <input name="password" type="password" placeholder="Enter password" required>
    <button type="submit">Login</button>
  </form>
</div>
</body>
</html>