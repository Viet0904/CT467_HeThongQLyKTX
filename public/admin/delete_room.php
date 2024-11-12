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
    // Kiểm tra số lượng người ở trong bảng ThuePhong
    if ($roomData['DaO'] == 0) {
        // Nếu DaO = 0, thực hiện xóa phòng và dữ liệu liên quan trong bảng DienNuoc
        $dbh->beginTransaction();

        try {
            // Xóa dữ liệu phòng trong bảng DienNuoc
            $stmt = $dbh->prepare("DELETE FROM DienNuoc WHERE MaPhong = :id");
            $stmt->execute([':id' => $roomId]);

            // Xóa phòng trong bảng Phong
            $stmt = $dbh->prepare("DELETE FROM Phong WHERE MaPhong = :id");
            $stmt->execute([':id' => $roomId]);

            $dbh->commit();
            echo "<script>alert('Xóa phòng và dữ liệu liên quan thành công.')</script>";
            header("Location: room_list.php?success=1");
            exit;
        } catch (Exception $e) {
            $dbh->rollBack();
            echo "<script>alert('Có lỗi xảy ra khi xóa phòng hoặc dữ liệu liên quan.');window.location.href='room_list.php';</script>";
        }
    } else {
        echo "<script>alert('Không thể xóa phòng vì có người ở.');window.location.href='room_list.php';</script>";
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
