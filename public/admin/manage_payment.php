<?php
include_once __DIR__ . '/../../config/dbadmin.php';
include_once __DIR__ . '/../../partials/header.php';

$paymentId = $_GET['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu từ biểu mẫu
    $thangNam = $_POST['thang_nam'];
    $soTien = $_POST['so_tien'];
    $ngayThanhToan = $_POST['ngay_thanh_toan'];
    $maNhanVien = $_POST['ma_nhan_vien'];

    // Cập nhật thông tin vào cơ sở dữ liệu
    $stmt = $dbh->prepare("UPDATE TT_ThuePhong SET ThangNam = :thang_nam, SoTien = :so_tien, NgayThanhToan = :ngay_thanh_toan, MaNhanVien = :ma_nhan_vien WHERE MaHopDong = :id");
    $stmt->execute([
        ':thang_nam' => $thangNam,
        ':so_tien' => $soTien,
        ':ngay_thanh_toan' => $ngayThanhToan,
        ':ma_nhan_vien' => $maNhanVien,
        ':id' => $paymentId
    ]);

    // Chuyển hướng về trang danh sách thanh toán sau khi cập nhật thành công
    header('Location: payment_details.php?MaHopDong=' . $paymentId);
    exit();
}

// Lấy thông tin hiện tại của khoản thanh toán
$stmt = $dbh->prepare("SELECT * FROM TT_ThuePhong WHERE MaHopDong = :id");
$stmt->execute([':id' => $paymentId]);
$payment = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<body>
    <div class="container">
        <h2>Sửa thông tin thanh toán</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="thang_nam" class="form-label">Tháng/Năm</label>
                <input type="text" class="form-control" name="thang_nam" value="<?php echo htmlspecialchars($payment['ThangNam']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="so_tien" class="form-label">Số Tiền</label>
                <input type="number" class="form-control" name="so_tien" value="<?php echo htmlspecialchars($payment['SoTien']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="ngay_thanh_toan" class="form-label">Ngày Thanh Toán</label>
                <input type="date" class="form-control" name="ngay_thanh_toan" value="<?php echo htmlspecialchars($payment['NgayThanhToan']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="ma_nhan_vien" class="form-label">Nhân Viên</label>
                <input type="text" class="form-control" name="ma_nhan_vien" value="<?php echo htmlspecialchars($payment['MaNhanVien']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Cập nhật</button>
        </form>
    </div>
</body>
</html>
