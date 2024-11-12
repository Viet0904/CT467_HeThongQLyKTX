<?php
include_once __DIR__ . '/../../config/dbadmin.php'; // Kết nối cơ sở dữ liệu
include_once __DIR__ . '/../../partials/header.php'; // Tiêu đề trang
include_once __DIR__ . '/../../partials/heading.php'; // Đường dẫn

$roomId = isset($_GET['id']) ? $_GET['id'] : null;
$roomData = [];

// Kiểm tra xem mã phòng có tồn tại
if ($roomId) {
    // Lấy thông tin phòng
    $stmt = $dbh->prepare("SELECT * FROM Phong WHERE MaPhong = :id");
    $stmt->execute([':id' => $roomId]);
    $roomData = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$roomData) {
        echo "<div class='alert alert-danger'>Phòng không tồn tại.</div>";
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Gọi stored procedure XoaPhongVaDuLieuLienQuan
    $stmt = $dbh->prepare("CALL XoaPhongVaDuLieuLienQuan(:MaPhong, @Message, @ErrorCode)");
    $stmt->bindParam(':MaPhong', $roomId, PDO::PARAM_STR);

    if ($stmt->execute()) {
        // Lấy kết quả thông báo và mã lỗi từ biến OUT của stored procedure
        $stmt = $dbh->query("SELECT @Message AS Message, @ErrorCode AS ErrorCode");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $message = $result['Message'];
        $errorCode = $result['ErrorCode'];

        if ($errorCode == 0) {
            echo "<script>alert('{$message}'); window.location.href='room_list.php';</script>";
        } else {
            echo "<script>alert('{$message}'); window.location.href='room_list.php';</script>";
        }
    } else {
        echo "<script>alert('Có lỗi xảy ra khi gọi stored procedure.'); window.location.href='room_list.php';</script>";
    }
}
?>

<body>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <?php include_once __DIR__ . '/sidebar.php'; ?>
            <div class="col px-0">
                <div class="my-2" style="margin-left: 260px;">
                    <div class="modal-header-1">
                        <h5 class="modal-title mt-2">Xoá phòng</h5>
                    </div>

                    <div class="modal-user mt-3">
                        <form action="" method="POST">
                            <div class="alert alert-warning">
                                <h6>Bạn có chắc chắn muốn xoá phòng <strong><?php echo htmlspecialchars($roomData['TenPhong']); ?></strong> (Mã phòng: <strong><?php echo htmlspecialchars($roomData['MaPhong']); ?></strong>)?</h6>
                            </div>
                            <div class="row-add d-flex justify-content-center align-items-center mt-2">
                                <div class="mx-2">
                                    <button type="submit" class="btn btn-danger">Xoá</button>
                                </div>
                                <div class="mx-2">
                                    <a href="room_list.php" class="btn btn-secondary">Trở về</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
