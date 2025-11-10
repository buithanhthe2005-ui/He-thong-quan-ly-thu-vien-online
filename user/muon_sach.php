<?php
include('../includes/db_connect.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_sach = $_POST['id_sach'];
    $ngay_muon = $_POST['ngay_muon'];
    $ngay_tra = $_POST['ngay_tra'];
    $id_nguoidung = $_SESSION['user_id'];

    $sql = "INSERT INTO muontra (id_sach, id_nguoidung, ngay_muon, ngay_tra, trangthai) 
            VALUES ('$id_sach', '$id_nguoidung', '$ngay_muon', '$ngay_tra', 'Đang mượn')";

    if ($conn->query($sql)) {
        echo "<script>alert('✅ Mượn sách thành công!'); window.location='user_dashboard.php';</script>";
    } else {
        echo "<script>alert('❌ Lỗi khi mượn sách!'); window.history.back();</script>";
    }
}
?>
