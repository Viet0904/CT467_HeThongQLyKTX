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
        'ChucVu' => '',
        'Password' => '',
        'Role' => ''
    ];
}

// Xử lý form

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($_POST['check'] == '0') {

        $maNhanVien = $_POST['maNhanVien'] ?? '';
        if ($_POST['Role'] == 'Nhân Viên') {
            $_POST['Role'] = 'NhanVien';
        }
        if ($_POST['Role'] == 'Quản trị viên') {
            $_POST['Role'] = 'Admin';
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
        try {
            $stmt = $dbh->prepare("CALL ThemNhanVien(:maNhanVien, :ten, :SDT, :ghiChu, :gioiTinh, :ngaySinh, :password, :chucVu, @p_Message, @p_ErrorCode)");
            $stmt->execute($data);

            // Lấy kết quả từ biến OUT
            $result = $dbh->query("SELECT @p_Message AS message, @p_ErrorCode AS errorCode")->fetch(PDO::FETCH_ASSOC);
            $message = $result['message'];
            $errorCode = $result['errorCode'];
            if ($errorCode != 0) {
                throw new Exception($message);
            }
        } catch (Exception $e) {
            $message = $e->getMessage();
        }
        echo '<script>alert("' . $message . '");window.location.href="employees_list.php";</script>';
    } else {
        $maNhanVien = $_POST['maNhanVien'] ?? '';
        if ($_POST['Role'] == 'Nhân Viên') {
            $_POST['Role'] = 'NhanVien';
        }
        if ($_POST['Role'] == 'Quản trị viên') {
            $_POST['Role'] = 'Admin';
        }
        // Dữ liệu cho Nhân viên
        $data = [
            ':oldMaNhanVien' => $maNhanVien,
            ':maNhanVien' => $_POST['MaNhanVien'],
            ':ten' => $_POST['HoTen'],
            ':SDT' => $_POST['SDT'],
            ':ngaySinh' => $_POST['NgaySinh'],
            ':ghiChu' => $_POST['GhiChu'],
            ':gioiTinh' => $_POST['GioiTinh'],
            ':chucVu' => $_POST['Role'],
            ':password' => password_hash($_POST['password'], PASSWORD_BCRYPT),
        ];
        try {
            $stmt = $dbh->prepare("CALL UpdateNhanVien(:oldMaNhanVien, :maNhanVien, :ten, :SDT, :ghiChu, :gioiTinh, :ngaySinh, :password, :chucVu, @p_Message, @p_ErrorCode)");
            $stmt->execute($data);

            // Lấy kết quả từ biến OUT
            $result = $dbh->query("SELECT @p_Message AS message, @p_ErrorCode AS errorCode")->fetch(PDO::FETCH_ASSOC);
            $message = $result['message'];
            $errorCode = $result['errorCode'];
            if ($errorCode != 0) {
                throw new Exception($message);
            }
        } catch (Exception $e) {
            $message = $e->getMessage();
        }
        echo '<script>alert("' . $message . '");window.location.href="employees_list.php";</script>';
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
                        <h5 class="modal-title mt-2"><?php echo $maNhanVien ? "Cập nhật nhân viên" : "Đăng ký nhân viên mới"; ?></h5>
                    </div>

                    <!-- Hiển thị thông báo -->
                    <?php if (!empty($message)): ?>
                        <div class="alert alert-info mt-3"><?php echo $message; ?></div>
                    <?php endif; ?>

                    <div class="modal-user mt-3">
                        <form action="manage_employees.php?mnv=<?php echo htmlspecialchars($maNhanVien); ?>" method="POST">
                            <!-- Send employee ID as a hidden field if updating -->
                            <input type="hidden" name="maNhanVien" value="<?php echo htmlspecialchars($NhanVien['MaNhanVien'] ?? $_POST['MaNhanVien'] ?? ''); ?>">

                            <div class="row row-add">
                                <div class="col-md-4">
                                    <h5 class="mt-1"><b>Thông tin cộng đồng</b></h5>
                                </div>

                                <div class="row row-add">
                                    <div class="col-md-3">
                                        <label for="schoolID"> <b>Mã nhân viên</b></label>
                                        <input type="text" class="form-control mb-2 mt-1 mx-3" name="MaNhanVien" value="<?php echo htmlspecialchars($NhanVien['MaNhanVien'] ?? $_POST['MaNhanVien'] ?? ''); ?>" required <?php echo $maNhanVien ? 'readonly' : ''; ?> pattern="CB\d{6}" title="Mã nhân viên phải bắt đầu bằng 'CB' và theo sau là 6 chữ số.">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="chucVu"><b>Chức vụ</b></label>
                                        <input type="text" class="form-control mt-1 mb-2 mx-3" name="Role"
                                            value="<?php echo htmlspecialchars($NhanVien['Role'] == 'Admin' ? 'Quản trị viên' : 'Nhân Viên'); ?>"
                                            required readonly>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="chucVu"><b>Ghi Chú</b></label>
                                        <input type="text" class="form-control mt-1 mb-2 mx-3" name="GhiChu"
                                            value="<?php echo htmlspecialchars($NhanVien['GhiChu'] ?? ''); ?>">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="password"><b>Passowrd</b></label>
                                        <input type="text" class="form-control mt-1 mb-2 mx-3" name="password"
                                            value="<?php echo htmlspecialchars($NhanVien['Password'] ?? ''); ?>">
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
                                        <?php if (!empty($NhanVien['GioiTinh'])): ?>
                                            <input type="text" class="form-control mb-2 mt-1 mx-3" name="GioiTinh" value="<?php echo htmlspecialchars($NhanVien['GioiTinh']); ?>" required>
                                        <?php else: ?>
                                            <select class="form-control mb-2 mt-1 mx-3" name="GioiTinh" required>
                                                <option value="">Chọn giới tính</option>
                                                <option value="Nam">Nam</option>
                                                <option value="Nữ">Nữ</option>
                                            </select>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="contact"><b>Liên hệ #</b></label>
                                        <input type="text" class="form-control mb-2 mt-1 mx-3" name="SDT" value="<?php echo htmlspecialchars($NhanVien['SDT'] ?? ''); ?>" required pattern="\d{10}" title="Số điện thoại phải là 10 chữ số.">
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="row-add d-flex justify-content-center align-items-center mt-2">
                                    <div class="mx-2">
                                        <button type="submit" class="btn" style="background-color: #db3077; color: white;">
                                            <input type="hidden" name="check" value="<?php echo $maNhanVien ? "1" : "0"; ?>">
                                            <?php echo $maNhanVien ? "Cập nhật" : "Đăng ký"; ?>
                                        </button>
                                    </div>
                                    <?php if ($maNhanVien): ?>
                                        <div class="mx-2">
                                            <a href="delete_employees.php?mnv=<?php echo htmlspecialchars($maNhanVien); ?>" class="btn btn-danger">Xoá</a>
                                        </div>
                                    <?php endif; ?>
                                    <div class="mx-2">
                                        <a href="employees_list.php" class="btn btn-secondary">Trở về</a>
                                    </div>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>