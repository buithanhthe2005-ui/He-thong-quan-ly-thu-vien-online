<?php
include('../includes/connect.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_sach = $_POST['id_sach'];
    $id_nguoidung = $_SESSION['user_id'];

    $sql = "UPDATE muontra SET trang_thai='Đã trả'
            WHERE id_sach='$id_sach' AND id_nguoidung='$id_nguoidung' AND trang_thai='Đang mượn'";

    if ($conn->query($sql)) {
        echo "<script>alert('📗 Trả sách thành công!'); window.location='user_dashboard.php';</script>";
    } else {
        echo "<script>alert('❌ Lỗi khi trả sách!'); window.history.back();</script>";
    }
}
?>
