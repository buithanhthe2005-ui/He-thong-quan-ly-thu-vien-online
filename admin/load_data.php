<?php
include('../includes/db_connect.php');

$type = $_GET['type'] ?? '';

switch ($type) {
  case 'users':
    $res = $conn->query("SELECT id, username, fullname, email, role FROM users");
    echo "<table class='table table-bordered table-striped'>
            <tr><th>ID</th><th>Tên đăng nhập</th><th>Họ tên</th><th>Email</th><th>Vai trò</th></tr>";
    while($row = $res->fetch_assoc()) {
      echo "<tr>
              <td>{$row['id']}</td>
              <td>{$row['username']}</td>
              <td>{$row['fullname']}</td>
              <td>{$row['email']}</td>
              <td>{$row['role']}</td>
            </tr>";
    }
    echo "</table>";
    break;

case 'sach':
    $res = $conn->query("SELECT id, ten_sach, tacgia, id_theloai, soluong, ngaynhap FROM sach");
    echo "<table class='table table-bordered table-striped'>
            <tr><th>ID</th><th>Tên sách</th><th>Tác giả</th><th>Thể loại</th></tr><th>soluong<th><th>ngaynhap<th>";
    while ($row = $res->fetch_assoc()) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['ten_sach']}</td>
                <td>{$row['tacgia']}</td>
                <td>{$row['id_theloai']}</td>
                <td>{$row['soluong']}</td>
                <td>{$row['ngaynhap']}</td>
              </tr>";
    }
    echo "</table>";
    break;

  case 'theloai':
    $res = $conn->query("SELECT id, ten_theloai FROM theloai");
    echo "<table class='table table-bordered table-striped'>
            <tr><th>ID</th><th>Tên thể loại</th><th>Mô tả</th></tr>";
    while($row = $res->fetch_assoc()) {
      echo "<tr>
              <td>{$row['id']}</td>
              <td>{$row['ten_theloai']}</td>
            </tr>";
    }
    echo "</table>";
    break;

  case 'muontra':
    $res = $conn->query("SELECT id, id_nguoidung, id_sach, ngay_muon, ngay_tra, trangthai FROM muontra");

    echo "<table class='table table-bordered table-striped'>
            <tr>
                <th>ID</th>
                <th>Người mượn</th>
                <th>Sách</th>
                <th>Ngày mượn</th>
                <th>Ngày trả</th>
                <th>Trạng thái</th>
            </tr>";

    while($row = $res->fetch_assoc()) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['id_nguoidung']}</td>
                <td>{$row['id_sach']}</td>
                <td>{$row['ngay_muon']}</td>
                <td>{$row['ngay_tra']}</td>
                <td>{$row['trangthai']}</td>
            </tr>";
    }

    echo "</table>";
    break;
  default:
    echo "Không có dữ liệu.";
}
?>
