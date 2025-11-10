<?php
// includes/auth.php
// Start session nếu chưa start
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Thời gian timeout (giây) - ví dụ 30 phút
$timeout_duration = 1800;

// Kiểm tra timeout nếu có
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
    // Hết phiên -> hủy session và chuyển về login
    session_unset();
    session_destroy();
    header("Location: /thuvienonline/login.php");
    exit;
}
$_SESSION['LAST_ACTIVITY'] = time(); // cập nhật thời điểm hoạt động cuối

// Kiểm tra đã login chưa
if (!isset($_SESSION['user_id'])) {
    // Nếu là yêu cầu AJAX, trả về 401 để JS xử lý; còn bình thường redirect
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }
    header("Location: /thuvienonline/login.php");
    exit;
}
?>
