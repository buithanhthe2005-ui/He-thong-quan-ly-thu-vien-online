<?php
// admin_users.php
require_once __DIR__ . '/../includes/config.php';
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// xử lý tạo user
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_user'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $role = $_POST['role'];

    if ($username == '' || $password == '') $err = "Username và password bắt buộc.";
    else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (username, password, fullname, email, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $username, $hash, $fullname, $email, $role);
        if (!$stmt->execute()) {
            $err = "Lỗi: " . $stmt->error;
        }
        $stmt->close();
    }
}

// xử lý xóa user (id từ GET)
if (isset($_GET['delete'])) {
    $del_id = (int)$_GET['delete'];
    if ($del_id !== (int)$_SESSION['user_id']) { // tránh xóa chính mình
        $d = $conn->prepare("DELETE FROM users WHERE id = ?");
        $d->bind_param("i", $del_id);
        $d->execute();
        $d->close();
        header("Location: admin_users.php");
        exit;
    } else {
        $err = "Bạn không thể xóa chính mình.";
    }
}
// load danh sách user
$res = $conn->query("SELECT id, username, fullname, email, role FROM users ORDER BY id DESC");
?>
<!doctype html><html><head><meta charset="utf-8"><title>Admin - Users</title></head><body>
<h2>Quản lý người dùng</h2>
<?php if($err) echo "<p style='color:red'>$err</p>"; ?>

<h3>Tạo user mới</h3>
<form method="post">
  <input name="username" placeholder="username" required>
  <input name="password" placeholder="password" required>
  <input name="fullname" placeholder="Full name">
  <input name="email" placeholder="Email">
  <select name="role">
    <option value="docgia">docgia</option>
    <option value="thuthu">thuthu</option>
    <option value="admin">admin</option>
  </select>
  <button name="create_user" type="submit">Create</button>
</form>

<h3>Danh sách users</h3>
<table border="1" cellpadding="6" cellspacing="0">
<tr><th>ID</th><th>Username</th><th>Fullname</th><th>Email</th><th>Role</th><th>Action</th></tr>
<?php while($row = $res->fetch_assoc()): ?>
<tr>
  <td><?= $row['id'] ?></td>
  <td><?= htmlspecialchars($row['username']) ?></td>
  <td><?= htmlspecialchars($row['fullname']) ?></td>
  <td><?= htmlspecialchars($row['email']) ?></td>
  <td><?= $row['role'] ?></td>
  <td>
    <?php if($row['id'] != $_SESSION['user_id']): ?>
      <a href="admin_users.php?delete=<?= $row['id'] ?>" onclick="return confirm('Xác nhận xóa?')">Delete</a>
    <?php else: ?>
      (you)
    <?php endif; ?>
  </td>
</tr>
<?php endwhile; ?>
</table>

<p><a href="dashboard.php">Back</a> | <a href="logout.php">Logout</a></p>
</body></html>
