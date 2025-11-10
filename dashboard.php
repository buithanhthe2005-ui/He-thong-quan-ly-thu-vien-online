<?php
include '../connect.php'; // hoặc require '../connect.php';
?>
<!doctype html><html><body>
<h2>Dashboard</h2>
<p>Xin chào, <?=htmlspecialchars($_SESSION['fullname'] ?? $_SESSION['username'])?></p>
<p><a href="logout.php">Đăng xuất</a></p>
</body></html>