<?php
// If accessed directly (not included), redirect to admin
if (count(get_included_files()) === 1) {
    header('Location: ../');
    exit;
}

$conn = require __DIR__ . '/../../Config/database.php';

$sql = "SELECT id, username, email, isadmin FROM userdata";
$stmt = $conn->query($sql);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
.role-badge {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 4px 12px;
  border-radius: 3px;
  font-size: 13px;
  font-weight: 500;
}

.role-badge.admin {
  background: #4a9;
  color: white;
  border: 1px solid #3a8;
}

.role-badge.user {
  background: #e0e0e0;
  color: #666;
  border: 1px solid #ccc;
}

tr.admin-row {
  background: #f0fff4;
}

.admin-toggle-btn {
  padding: 4px 12px;
  font-size: 12px;
  cursor: pointer;
}

.admin-toggle-btn.promote {
  background: #4a9;
  color: white;
}

.admin-toggle-btn.demote {
  background: #e74c3c;
  color: white;
}

.admin-toggle-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}
</style>

<table class="table" style="width: 100%;">
  <thead>
    <tr>
      <th>Username</th>
      <th>Email</th>
      <th>Role</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($users as $user): ?>
      <tr class="<?php echo $user['isadmin'] == 1 ? 'admin-row' : ''; ?>">
        <td><?php echo htmlspecialchars($user['username']); ?></td>
        <td><?php echo htmlspecialchars($user['email']); ?></td>
        <td>
          <span class="role-badge <?php echo $user['isadmin'] == 1 ? 'admin' : 'user'; ?>">
            <?php echo $user['isadmin'] == 1 ? '👤 Admin' : '🧑 User'; ?>
          </span>
        </td>
        <td>
          <form action="users/toggle_admin.php" method="POST" style="display: inline;">
            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['id']); ?>">
            <button type="submit"
                    class="button admin-toggle-btn <?php echo $user['isadmin'] == 1 ? 'demote' : 'promote'; ?>"
                    <?php echo $user['id'] == $_SESSION['user_id'] ? 'disabled title="Cannot change your own role"' : ''; ?>>
              <?php echo $user['isadmin'] == 1 ? 'Demote' : 'Promote'; ?>
            </button>
          </form>
          <form action="permissions/" method="GET" style="display: inline; margin-left: 8px;">
            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['id']); ?>">
            <button type="submit" class="button">Manage Permissions</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
