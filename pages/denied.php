<?php
require_once '../includes/session.php';
include '../includes/header.php';
?>
<div style="text-align:center;padding:60px 20px">
  <div style="font-size:48px">⛔</div>
  <h2 style="color:#e74c3c;margin:16px 0 8px">Access Denied</h2>
  <p style="color:#888;margin-bottom:24px">
    Your role <strong>(<?= $_SESSION['role'] ?>)</strong>
    does not have permission to view this page.
  </p>
  <a href="dashboard.php" class="btn">Back to Dashboard</a>
</div>
</div></body></html>