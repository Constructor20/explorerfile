<?php
$conn = require __DIR__ . '/../../Config/database.php';

$sql = "SELECT id, username, email, isadmin FROM userdata";
$stmt = $conn->query($sql);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<table class="table" style="width: 100%;">
  <thead>
    <tr>
      <th>Username</th>
      <th>Email</th>
      <th>Admin</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($users as $user): ?>
      <tr>
        <td><?php echo htmlspecialchars($user['username']); ?></td>
        <td><?php echo htmlspecialchars($user['email']); ?></td>
        <td><?php echo $user['isadmin'] == 1 ? 'Yes' : 'No'; ?></td>
        <td>
          <form action="permissions/" method="GET" style="display: inline;">
            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['id']); ?>">
            <button type="submit" class="button">Manage Permissions</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
