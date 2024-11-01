<?php
include_once __DIR__ . '/../../config/dbadmin.php';

$paymentId = $_GET['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Thực hiện câu lệnh xóa
    $stmt = $dbh->prepare("DELETE FROM TT_ThuePhong WHERE MaHopDong = :id");
    $stmt->execute([':id' => $paymentId]);

    // Chuyển hướng về trang danh sách thanh toán sau khi xóa thành công
    header('Location: payment_list.php'); // Chỉnh sửa nếu cần chuyển đến trang khác
    exit();
}
?>

<body>
    <div class="container">
        <h2>Xóa khoản thanh toán</h2>
        <p>Bạn có chắc chắn muốn xóa khoản thanh toán này?</p>
        <form method="POST">
            <button type="submit" class="btn btn-danger">Xóa</button>
            <a href="payment_details.php?MaHopDong=<?php echo htmlspecialchars($paymentId); ?>" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
</body>
</html>
