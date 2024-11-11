<?php
include_once __DIR__ . '/../../config/dbadmin.php'; // Kết nối cơ sở dữ liệu
include_once __DIR__ . '/../../partials/header.php'; // Tiêu đề trang
include_once __DIR__ . '/../../partials/heading.php'; // Đường dẫn

$maSinhVien = isset($_GET['msv']) ? $_GET['msv'] : null;
$sinhVien = [];

// Kiểm tra xem mã sinh viên có tồn tại
if ($maSinhVien) {
    // Lấy thông tin sinh viên
    $stmt = $dbh->prepare("SELECT * FROM SinhVien WHERE MaSinhVien = :maSinhVien");
    $stmt->execute([':maSinhVien' => $maSinhVien]);
    $sinhVien = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$sinhVien) {
        echo "<div class='alert alert-danger'>Sinh viên không tồn tại.</div>";
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Xóa các bản ghi liên quan trong bảng ThuePhong
    $stmt = $dbh->prepare("DELETE FROM ThuePhong WHERE MaSinhVien = :maSinhVien");
    $stmt->execute([':maSinhVien' => $maSinhVien]);

    // Xử lý việc xóa sinh viên
    $stmt = $dbh->prepare("DELETE FROM SinhVien WHERE MaSinhVien = :maSinhVien");
    if ($stmt->execute([':maSinhVien' => $maSinhVien])) {
        echo "<script>
            alert('Xóa sinh viên thành công.');
            window.location.href = 'student_list.php?success=1';
        </script>";
        exit;
    } else {
        echo "<div class='alert alert-danger'>Có lỗi xảy ra khi xóa sinh viên.</div>";
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
                        <h5 class="modal-title mt-2">Xoá sinh viên</h5>
                    </div>

                    <div class="modal-user mt-3">
                        <form action="" method="POST">
                            <div class="alert alert-warning">
                                <h6>Bạn có chắc chắn muốn xoá sinh viên
                                    <strong><?php echo htmlspecialchars($sinhVien['HoTen']); ?></strong> (Mã sinh viên:
                                    <strong><?php echo htmlspecialchars($sinhVien['MaSinhVien']); ?></strong>)?</h6>
                            </div>
                            <div class="row-add d-flex justify-content-center align-items-center mt-2">
                                <div class="mx-2">
                                    <button type="submit" class="btn btn-danger">Xoá</button>
                                </div>
                                <div class="mx-2">
                                    <a href="student_list.php" class="btn btn-secondary">Trở về</a>
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