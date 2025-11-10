<?php
// register.php
session_start();
require 'includes/config.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $fullname = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $role = 'docgia'; // mặc định người đăng ký là độc giả

    if ($username === '' || $password === '') {
        $errors[] = "Username và password không được trống.";
    }

    if (empty($errors)) {
        // kiểm tra username đã tồn tại?
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $errors[] = "Tên đăng nhập đã tồn tại.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $ins = $conn->prepare("INSERT INTO users (username, password, fullname, email, role) VALUES (?, ?, ?, ?, ?)");
            $ins->bind_param("sssss", $username, $hash, $fullname, $email, $role);
            if ($ins->execute()) {
                $_SESSION['flash'] = "Đăng ký thành công. Đăng nhập để tiếp tục.";
                header("Location: login.php");
                exit;
            } else {
                $errors[] = "Lỗi khi tạo tài khoản.";
            }
        }
        $stmt->close();
    }
}
?>
<!-- giao diện đăng ký đơn giản -->
<!doctype html>
<html><head><meta charset="utf-8"><title>Register</title></head><body>
<h2>Register</h2>
<?php foreach($errors as $e) echo "<p style='color:red'>$e</p>"; ?>
<form method="post">
  <input name="username" placeholder="Username" required><br>
  <input name="password" type="password" placeholder="Password" required><br>
  <input name="fullname" placeholder="Full name"><br>
  <input name="email" placeholder="Email"><br>
  <button type="submit">Register</button>
</form>
<p><a href="login.php">Login</a></p>
</body></html>
