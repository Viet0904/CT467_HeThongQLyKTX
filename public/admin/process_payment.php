<?php
session_start();
include_once __DIR__ . '/../../config/dbadmin.php';

// Lấy thông tin nhân viên từ session
$maNhanVien = $_SESSION['MaNhanVien'] ?? null;

// Kiểm tra nếu không có thông tin nhân viên, yêu cầu đăng nhập lại
if (!$maNhanVien) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['MaHopDong'])) {
    $contractId = $_GET['MaHopDong'];
    $currentDate = date('Y-m-d'); // Ngày hiện tại
    $currentEmployeeId = $maNhanVien;

    // Cập nhật ngày thanh toán và nhân viên thanh toán
    $stmt = $dbh->prepare("CALL UpdateTT_ThuePhong(:maHopDong, :ngayThanhToan, :maNhanVien)");
    $stmt->execute([
        ':maHopDong' => $contractId,
        ':ngayThanhToan' => $currentDate,
        ':maNhanVien' => $currentEmployeeId
    ]);

    echo "<script>
        alert('Thanh toán hợp đồng thành công!');
        window.location.href = 'hopdong_detail.php?MaHopDong=" . $contractId . "';
        </script>";
    exit();

} else {
    // Nếu không có id hợp đồng, chuyển hướng về trang quản lý hợp đồng
    header("Location: view_qlthuephong.php");
    exit;
}
?>
