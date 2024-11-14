<?php
include_once __DIR__ . '/../../config/dbadmin.php'; // Kết nối cơ sở dữ liệu
include_once __DIR__ . '/../../partials/header.php'; // Tiêu đề trang
include_once __DIR__ . '/../../partials/heading.php'; // Đường dẫn

$maNhanVien = isset($_GET['mnv']) ? $_GET['mnv'] : null;
$employeeData = [];

// Kiểm tra mã nhân viên có tồn tại trong cơ sở dữ liệu không
if ($maNhanVien) {
    // Lấy thông tin nhân viên
    $stmt = $dbh->prepare("SELECT * FROM NhanVien WHERE MaNhanVien = :mnv");
    $stmt->execute([':mnv' => $maNhanVien]);
    $employeeData = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$employeeData) {
        echo "<div class='alert alert-danger'>Nhân viên không tồn tại.</div>";
        exit;
    }
}

// Kiểm tra xem mã nhân viên có trong bảng tt_thuephong không
if ($maNhanVien) {
    $stmt = $dbh->prepare("SELECT COUNT(*) FROM tt_thuephong WHERE MaNhanVien = :mnv");
    $stmt->execute([':mnv' => $maNhanVien]);
    $count = $stmt->fetchColumn();

    // Nếu có hợp đồng thuê phòng, không cho phép xóa
    if ($count > 0) {
        echo "<script>alert('Không thể xóa nhân viên vì nhân viên tồn tại trong hợp đồng thuê phòng.'); window.location.href='employees_list.php';</script>";
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Xóa nhân viên khỏi cơ sở dữ liệu
        $stmt = $dbh->prepare("DELETE FROM NhanVien WHERE MaNhanVien = :MaNhanVien");
        $stmt->bindParam(':MaNhanVien', $maNhanVien, PDO::PARAM_STR);
        
        if ($stmt->execute()) {
            echo "<script>alert('Nhân viên đã được xóa thành công.'); window.location.href='employees_list.php';</script>";
        } else {
            echo "<script>alert('Có lỗi xảy ra khi xóa nhân viên.'); window.location.href='employees_list.php';</script>";
        }
    } catch (PDOException $e) {
        // Log error
        error_log("Error deleting employee: " . $e->getMessage());
        echo "<script>alert('Có lỗi xảy ra khi xóa nhân viên.'); window.location.href='employees_list.php';</script>";
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
                        <h5 class="modal-title mt-2">Xoá nhân viên</h5>
                    </div>

                    <div class="modal-user mt-3">
                        <form action="" method="POST">
                            <div class="alert alert-warning">
                                <h6>Bạn có chắc chắn muốn xoá nhân viên <strong><?php echo htmlspecialchars($employeeData['HoTen']); ?></strong> (Mã nhân viên: <strong><?php echo htmlspecialchars($employeeData['MaNhanVien']); ?></strong>)?</h6>
                            </div>
                            <div class="row-add d-flex justify-content-center align-items-center mt-2">
                                <div class="mx-2">
                                    <button type="submit" class="btn btn-danger">Xoá</button>
                                </div>
                                <div class="mx-2">
                                    <a href="employee_list.php" class="btn btn-secondary">Trở về</a>
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
