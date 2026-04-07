<?php
require_once '../includes/session.php';
require_role(['admin','auditor']);
require_once '../config/db.php';
include '../includes/header.php';

$stmt = $conn->query(
    "SELECT al.log_id, u.username, u.role, al.action,
            al.table_name, al.record_id, al.ip_address, al.logged_at
     FROM audit_log al
     JOIN users u ON al.user_id = u.user_id
     ORDER BY al.logged_at DESC
     LIMIT 200"
);
$logs = $stmt->fetchAll();
?>
<h2>🔍 Audit Trail</h2>
<p style="font-size:13px;color:#888;margin-bottom:16px">
  Every login, insert and update is recorded here.
</p>
<table>
  <tr><th>#</th><th>User</th><th>Role</th><th>Action</th>
      <th>Table</th><th>Record ID</th><th>IP</th><th>Time</th></tr>
  <?php foreach ($logs as $log): ?>
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
  <?php endforeach; ?>
  <?php if (empty($logs)): ?>
  <tr><td colspan="8" style="text-align:center;color:#999;padding:24px">
    No activity logged yet.
  </td></tr>
  <?php endif; ?>
</table>
</div></body></html>