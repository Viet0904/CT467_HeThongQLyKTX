<?php
include_once __DIR__ . '/../../config/dbadmin.php';
include_once __DIR__ . '/../../partials/header.php';
include_once __DIR__ . '/../../partials/heading.php';

$message = '';
$maNhanVien = $_GET['mnv'] ?? ''; // Lấy mã Nhan viên từ URL

// Lấy thông tin Nhan viên nếu có mã Nhan viên
if ($maNhanVien) {
    $stmt = $dbh->prepare("SELECT * FROM NhanVien WHERE MaNhanVien = :maNhanVien");
    $stmt->execute([':maNhanVien' => $maNhanVien]);
    $NhanVien = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    // Nếu không có mã nhân viên, khởi tạo mảng trống
    $NhanVien = [
        'MaNhanVien' => '',
        'HoTen' => '',
        'SDT' => '',
        'NgaySinh' => '',
        'GhiChu' => '',
        'GioiTinh' => '',
        'ChucVu' => ''
    ];
}

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $maNhanVien = $_POST['maNhanVien'] ?? $maNhanVien;

        // Kiểm tra mã nhân viên trùng lặp chỉ khi đăng ký mới
        if (empty($maNhanVien)) {
            $checkStmt = $dbh->prepare("SELECT * FROM NhanVien WHERE MaNhanVien = :maNhanVien");
            $checkStmt->execute([':maNhanVien' => $_POST['MaNhanVien']]);
            $existingEmployee = $checkStmt->fetch(PDO::FETCH_ASSOC);

            if ($existingEmployee) {
                $message = "Mã Nhân viên đã tồn tại. Vui lòng sử dụng mã khác.";
            }
        }

        // Dữ liệu cho Nhân viên
        $data = [
            ':maNhanVien' => $_POST['MaNhanVien'],
            ':ten' => $_POST['HoTen'],
            ':SDT' => $_POST['SDT'],
            ':ngaySinh' => $_POST['NgaySinh'],
            ':ghiChu' => $_POST['GhiChu'],
            ':gioiTinh' => $_POST['GioiTinh'],
            ':chucVu' => $_POST['Role'],
            ':password' => password_hash($_POST['password'], PASSWORD_BCRYPT),
        ];

        // Tạo câu truy vấn SQL thêm vào hoặc cập nhật Nhân viên
        if ($maNhanVien) {
            $sql = "UPDATE NhanVien SET HoTen = :ten, SDT = :SDT, NgaySinh = :ngaySinh, GhiChu = :ghiChu, GioiTinh = :gioiTinh, Role = :chucVu, Password = :password WHERE MaNhanVien = :maNhanVien";
        } else {
            $sql = "INSERT INTO NhanVien (MaNhanVien, HoTen, SDT, NgaySinh, GhiChu, GioiTinh, Role, Password) 
                    VALUES (:maNhanVien, :ten, :SDT, :ngaySinh, :ghiChu, :gioiTinh, :chucVu, :password)";
        }

        $stmt = $dbh->prepare($sql);
        $stmt->execute($data);

        $message = $maNhanVien ? "Cập nhật thành công!" : "Đăng ký thành công!";
        echo "<script>alert('$message');</script>";
        echo "<script>window.location.href='view_employees.php?mnv=" . ($_POST['MaNhanVien'] ?? $maNhanVien) . "';</script>"; // Redirect to the employee details page
        exit;
    }
} catch (PDOException $e) {
    $message = "Lỗi: " . $e->getMessage();
}
?>

<body>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <?php include_once __DIR__ . '/sidebar.php'; ?>

            <div class="col px-0">
                <div class="my-2" style="margin-left: 260px;">
                    <div class="modal-header-1">
                        <h5 class="modal-title mt-2"><?php echo $maNhanVien ? "Cập nhật nhân viên" : "Đăng ký nhân viên mới"; ?></h5>
                    </div>

                    <!-- Hiển thị thông báo -->
                    <?php if (!empty($message)): ?>
                        <div class="alert alert-info mt-3"><?php echo $message; ?></div>
                    <?php endif; ?>

                    <div class="modal-user mt-3">
                        <form action="./action/manage_employees_action.php?mnv=<?php echo htmlspecialchars($maNhanVien); ?>" method="POST">
                            <!-- Send employee ID as a hidden field if updating -->
                            <input type="hidden" name="maNhanVien" value="<?php echo htmlspecialchars($NhanVien['MaNhanVien'] ?? $_POST['MaNhanVien'] ?? ''); ?>">

                            <div class="row row-add">
                                <div class="col-md-4">
                                    <h5 class="mt-1"><b>Thông tin cộng đồng</b></h5>
                            </div>

                            <div class="row row-add">
                                <div class="col-md-4">
                                    <label for="schoolID"> <b>Mã nhân viên</b></label>
                                    <input type="text" class="form-control mb-2 mt-1 mx-3" name="MaNhanVien" value="<?php echo htmlspecialchars($NhanVien['MaNhanVien'] ?? $_POST['MaNhanVien'] ?? ''); ?>" required <?php echo $maNhanVien ? 'readonly' : ''; ?>>
                                </div>
                                <div class="col-md-4">
                                    <label for="chucVu"><b>Chức vụ</b></label>
                                    <input type="text" class="form-control mt-1 mb-2 mx-3" name="Role" 
                                        value="<?php echo htmlspecialchars($NhanVien['Role'] == 'Admin' ? 'Quản trị viên' : 'Nhân viên văn phòng'); ?>" 
                                        required>
                                </div>
                                <div class="col-md-4">
                                    <label for="chucVu"><b>Ghi Chú</b></label>
                                    <input type="text" class="form-control mt-1 mb-2 mx-3" name="GhiChu" 
                                        value="<?php echo htmlspecialchars($NhanVien['GhiChu'] ?? ''); ?>">
                                </div>

                            </div>

                            <!-- Personal Information Section -->
                            <h5 class="mt-3"><b>Thông tin cá nhân</b></h5>
                            <div class="row row-add">
                                <div class="col-md-3">
                                    <label for="firstName"><b>Tên</b></label>
                                    <input type="text" class="form-control mb-2 mt-1 mx-3" name="HoTen" value="<?php echo htmlspecialchars($NhanVien['HoTen'] ?? ''); ?>" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="dob"><b>Ngày sinh</b></label>
                                    <input type="date" class="form-control mb-2 mt-1 mx-3" name="NgaySinh" value="<?php echo htmlspecialchars($NhanVien['NgaySinh'] ?? ''); ?>" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="gender"><b>Giới tính</b></label>
                                    <input type="text" class="form-control mb-2 mt-1 mx-3" name="GioiTinh" value="<?php echo htmlspecialchars($NhanVien['GioiTinh'] ?? ''); ?>" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="contact"><b>Liên hệ #</b></label>
                                    <input type="text" class="form-control mb-2 mt-1 mx-3" name="SDT" value="<?php echo htmlspecialchars($NhanVien['SDT'] ?? ''); ?>" required>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="row-add d-flex justify-content-center align-items-center mt-2">
                                <div class="mx-2">
                                    <button type="submit" class="btn" style="background-color: #db3077; color: white;">
                                        <?php echo $maNhanVien ? "Cập nhật" : "Đăng ký"; ?>
                                    </button>
                                </div>
                                <?php if ($maNhanVien): ?>
                                    <div class="mx-2">
                                        <a href="delete_employees.php?mnv=<?php echo htmlspecialchars($maNhanVien); ?>" class="btn btn-danger">Xoá</a>
                                    </div>
                                <?php endif; ?>
                                <div class="mx-2">
                                    <a href="view_employees.php?mnv=<?php echo htmlspecialchars($maNhanVien);?>" class="btn btn-secondary">Trở về</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
