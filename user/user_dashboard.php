<?php
include('../includes/db_connect.php');
session_start();

// Giả lập người dùng đăng nhập (nếu bạn có login thì lấy từ session)
$_SESSION['user_id'] = 1; // ví dụ người dùng có id = 1
$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thư viện Online - Người dùng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body { background-color: #f5f7fa; font-family: 'Segoe UI', sans-serif; }
        .navbar { background-color: #0056b3; }
        .navbar-brand { color: white !important; font-weight: bold; }
        .footer { background-color: #004080; color: white; padding: 20px 0; text-align: center; margin-top: 40px; }
        .btn-filter { background-color: #007bff; color: white; }
        .btn-filter:hover { background-color: #0056b3; }
        .table th { background-color: #e8f0fe; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="#">📚 HỆ THỐNG QUẢN LÝ THƯ VIỆN ONLINE TRƯỜNG ĐẠI HỌC HẢI PHÒNG</a>
    </div>
</nav>

<div class="container mt-5">
    <h3 class="text-primary mb-4 text-center">Danh mục thư viện</h3>

    <!-- Bộ lọc -->
    <form method="GET" class="row mb-4">
        <div class="col-md-4">
            <label>Chọn thể loại:</label>
            <select name="theloai" class="form-select">
                <option value="">Tất cả</option>
                <?php
                $theloai = $conn->query("SELECT * FROM theloai");
                while($row = $theloai->fetch_assoc()){
                    $selected = (isset($_GET['theloai']) && $_GET['theloai'] == $row['id']) ? "selected" : "";
                    echo "<option value='{$row['id']}' $selected>{$row['ten_theloai']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="col-md-4">
            <label>Chọn nhà xuất bản:</label>
            <select name="nxb" class="form-select">
                <option value="">Tất cả</option>
                <?php
                $nxb = $conn->query("SELECT * FROM nxb");
                while($row = $nxb->fetch_assoc()){
                    $selected = (isset($_GET['nxb']) && $_GET['nxb'] == $row['id']) ? "selected" : "";
                    echo "<option value='{$row['id']}' $selected>{$row['ten_nxb']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-filter w-100">Lọc kết quả</button>
        </div>
    </form>

    <!-- Bảng sách -->
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Tên sách</th>
                <th>Thể loại</th>
                <th>Nhà xuất bản</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $sql = "SELECT s.id, s.ten_sach, t.ten_theloai, n.ten_nxb 
                FROM sach s 
                JOIN theloai t ON s.id_theloai = t.id 
                JOIN nxb n ON s.id_nxb = n.id";

        $conditions = [];
        if(!empty($_GET['theloai'])) $conditions[] = "s.id_theloai = " . intval($_GET['theloai']);
        if(!empty($_GET['nxb'])) $conditions[] = "s.id_nxb = " . intval($_GET['nxb']);
        if($conditions) $sql .= " WHERE " . implode(" AND ", $conditions);

        $books = $conn->query($sql);
        if ($books->num_rows > 0) {
            while($row = $books->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['ten_sach']}</td>
                        <td>{$row['ten_theloai']}</td>
                        <td>{$row['ten_nxb']}</td>
                        <td>
                            <button class='btn btn-sm btn-primary' data-bs-toggle='modal' data-bs-target='#muonModal' 
                                data-idsach='{$row['id']}' data-tensach='{$row['ten_sach']}'>
                                Mượn
                            </button>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='4' class='text-center text-muted'>Không tìm thấy sách phù hợp</td></tr>";
        }
        ?>
        </tbody>
    </table>
</div>

<!-- Modal mượn sách -->
<div class="modal fade" id="muonModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="muon_sach.php">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">📘 Mượn sách</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id_sach" id="id_sach">
          <div class="mb-3">
            <label class="form-label">Tên sách:</label>
            <input type="text" id="ten_sach" class="form-control" readonly>
          </div>
          <div class="mb-3">
            <label>Ngày mượn:</label>
            <input type="date" name="ngay_muon" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Ngày trả:</label>
            <input type="date" name="ngay_tra" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Xác nhận mượn</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
var muonModal = document.getElementById('muonModal');
muonModal.addEventListener('show.bs.modal', function (event) {
  var button = event.relatedTarget;
  var idsach = button.getAttribute('data-idsach');
  var tensach = button.getAttribute('data-tensach');
  document.getElementById('id_sach').value = idsach;
  document.getElementById('ten_sach').value = tensach;
});
</script>

<div class="footer">
    <p>📍 Địa chỉ: 171 Phan Đăng Lưu, Kiến An, Hải Phòng</p>          
    <p>☎️ Số điện thoại (0123)456789
    <p>🏫 Trường Đại học Hải Phòng</p>
</div>

</body>
</html>
