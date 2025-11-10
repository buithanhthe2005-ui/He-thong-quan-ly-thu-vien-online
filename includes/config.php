<?php
$servername = "localhost";
$username = "root";
$password = ""; // nếu bạn có đặt mật khẩu MySQL thì điền vào đây
$dbname = "thuvienonline"; // tên CSDL bạn đã tạo trong phpMyAdmin

// Kết nối tới MySQL
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
?>
