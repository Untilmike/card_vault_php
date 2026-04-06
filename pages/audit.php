<?php
require_once '../includes/session.php';
require_role(['admin','auditor']);
require_once '../config/db.php';
include '../includes/header.php';

$result = $conn->query("SELECT * FROM vw_audit_trail LIMIT 200");
?>
<h2>🔍 Audit Trail</h2>
<p style="font-size:13px;color:#888;margin-bottom:16px">
  Every login, insert and update is recorded here.
</p>
<table>
  <tr><th>#</th><th>User</th><th>Role</th><th>Action</th>
      <th>Table</th><th>Record ID</th><th>IP</th><th>Time</th></tr>
  <?php while ($log = $result->fetch_assoc()): ?>
  <tr>
    <td><?= $log['log_id'] ?></td>
    <td><strong><?= htmlspecialchars($log['username']) ?></strong></td>
    <td><span class="badge b-b"><?= $log['role'] ?></span></td>
    <td><?= $log['action'] ?></td>
    <td><?= $log['table_name'] ?></td>
    <td><?= $log['record_id'] ?? '—' ?></td>
    <td><code style="font-size:12px"><?= $log['ip_address'] ?></code></td>
    <td><?= date('d M Y H:i', strtotime($log['logged_at'])) ?></td>
  </tr>
  <?php endwhile; ?>
</table>
</div></body></html>