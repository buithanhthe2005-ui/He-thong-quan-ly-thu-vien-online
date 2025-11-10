<?php
// login.php
session_start();
require_once(__DIR__ . '/includes/config.php');

$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $err = "Vui lòng nhập username và password.";
    } else {
        $stmt = $conn->prepare("SELECT id, password, role, fullname FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows === 1) {
            $stmt->bind_result($id, $hash, $role, $fullname);
            $stmt->fetch();
           if (password_verify($password, $hash)) {
                // login success
                session_regenerate_id(true);
                $_SESSION['user_id'] = $id;
                $_SESSION['username'] = $username;
                $_SESSION['role'] = $role;
                $_SESSION['fullname'] = $fullname;

                // redirect based on role
if ($role === 'admin') {
    header("Location: /thuvienonline/admin/admin_users.php");
    exit();
} else {
    header("Location: /thuvienonline/user/user_dashboard.php");
    exit();
}
            } else {
                $err = "Sai tài khoản hoặc mật khẩu.";
            }
        } else {
            $err = "Sai tài khoản hoặc mật khẩu.";
        }
        $stmt->close();
    }
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Login</title></head><body>
<h2>Login</h2>
<?php if(!empty($_SESSION['flash'])){ echo "<p style='color:green'>".$_SESSION['flash']."</p>"; unset($_SESSION['flash']); } ?>
<?php if($err) echo "<p style='color:red'>$err</p>"; ?>
<form method="post">
  <input name="username" placeholder="Username" required><br>
  <input name="password" type="password" placeholder="Password" required><br>
  <button type="submit">Login</button>
</form>
<p><a href="register.php">Register</a></p>
</body></html>
