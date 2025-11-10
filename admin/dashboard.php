<?php
include '../includes/db_connect.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Trang Tổng Quan (Dashboard)</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    h2 {
      margin: 30px 0;
      text-align: center;
      font-weight: 700;
    }
    .card-box {
      text-align: center;
      border-radius: 15px;
      padding: 25px;
      transition: all 0.3s ease;
      cursor: pointer;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    .card-box:hover {
      transform: translateY(-5px);
      box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    .card-box i {
      font-size: 40px;
      margin-bottom: 10px;
    }
    #detail-box {
      display: none;
      margin-top: 30px;
    }
  </style>
</head>
<body>

<div class="container mt-5">
  <h2>📊 Trang Tổng Quan (Dashboard)</h2>

  <div class="row g-4 justify-content-center">
    <!-- Người dùng -->
    <div class="col-md-3">
      <div class="card-box border border-primary" onclick="loadDetail('users')">
        <i class="bi bi-person-circle text-primary"></i>
        <h5>Người dùng</h5>
        <h3>
          <?php
            $res = $conn->query("SELECT COUNT(*) AS total FROM users");
            $row = $res->fetch_assoc();
            echo $row['total'];
          ?>
        </h3>
      </div>
    </div>

    <!-- Sách -->
    <div class="col-md-3">
      <div class="card-box border border-success" onclick="loadDetail('sach')">
        <i class="bi bi-book text-success"></i>
        <h5>Sách</h5>
        <h3>
          <?php
            $res = $conn->query("SELECT COUNT(*) AS total FROM sach");
            $row = $res->fetch_assoc();
            echo $row['total'];
          ?>
        </h3>
      </div>
    </div>

    <!-- Thể loại -->
    <div class="col-md-3">
      <div class="card-box border border-warning" onclick="loadDetail('theloai')">
        <i class="bi bi-tags text-warning"></i>
        <h5>Thể loại</h5>
        <h3>
          <?php
            $res = $conn->query("SELECT COUNT(*) AS total FROM theloai");
            $row = $res->fetch_assoc();
            echo $row['total'];
          ?>
        </h3>
      </div>
    </div>

    <!-- Mượn trả -->
    <div class="col-md-3">
      <div class="card-box border border-danger" onclick="loadDetail('muontra')">
        <i class="bi bi-calendar2-check text-danger"></i>
        <h5>Mượn trả</h5>
        <h3>
          <?php
            $res = $conn->query("SELECT COUNT(*) AS total FROM muontra");
            $row = $res->fetch_assoc();
            echo $row['total'];
          ?>
        </h3>
      </div>
    </div>
  </div>

  <!-- Khung hiển thị chi tiết -->
  <div id="detail-box" class="mt-5">
    <h4 class="text-center" id="detail-title"></h4>
    <div id="filter-box" class="text-center mb-3"></div>
    <div id="detail-content" class="table-responsive"></div>
  </div>

</div>

<!-- JavaScript -->
<script>
function loadDetail(type) {
  document.getElementById('detail-box').style.display = 'block';
  document.getElementById('detail-title').innerText = '📋 Danh sách ' + type;

  // Gọi đến file load_data.php để lấy dữ liệu
  fetch('load_data.php?type=' + type)
    .then(response => response.text())
    .then(data => {
      document.getElementById('detail-content').innerHTML = data;
    });
}
</script>

</body>
</html>
