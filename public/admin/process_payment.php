<?php
session_start();
$maNhanVien = $_SESSION['MaNhanVien'];
if (isset($_GET['id'])) {
    $contractId = $_GET['id'];
    $currentDate = date('Y-m-d'); // Ngày hiện tại
    $currentEmployeeId = $maNhanVien;

    $stmt = $dbh->prepare("UPDATE TT_ThuePhong SET NgayThanhToan = :ngayThanhToan, MaNhanVien = :maNhanVien WHERE MaHopDong = :maHopDong");
    $stmt->execute([
        ':ngayThanhToan' => $currentDate,
        ':maNhanVien' => $currentEmployeeId,
        ':maHopDong' => $contractId
    ]);

    header("Location: room_list.php?success=1");
    exit;
}
?>