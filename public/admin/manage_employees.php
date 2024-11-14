<?php
include_once __DIR__ . '/../../config/dbadmin.php';
include_once __DIR__ . '/../../partials/header.php';
include_once __DIR__ . '/../../partials/heading.php';

$message = '';
$maNhanVien = $_GET['mnv'] ?? ''; // Lấy mã Nhân viên từ URL

// Lấy thông tin Nhân viên nếu có mã Nhân viên
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
        // Lấy dữ liệu từ form
        $maNhanVien = $_POST['MaNhanVien'] ?? $maNhanVien;

        // Kiểm tra mã nhân viên trùng lặp chỉ khi đăng ký mới
        if (empty($maNhanVien)) {
            $checkStmt = $dbh->prepare("SELECT * FROM NhanVien WHERE MaNhanVien = :maNhanVien");
            $checkStmt->execute([':maNhanVien' => $_POST['MaNhanVien']]);
            $existingEmployee = $checkStmt->fetch(PDO::FETCH_ASSOC);

            if ($existingEmployee) {
                $message = "Mã Nhân viên đã tồn tại. Vui lòng sử dụng mã khác.";
                echo "<script>alert('$message');</script>";
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

        // Thực hiện gọi thủ tục lưu trữ (Procedure) cho Insert hoặc Update
        if ($maNhanVien) {
            // Gọi thủ tục cập nhật
            $stmt = $dbh->prepare("CALL UpdateNhanVien(:maNhanVien, :ten, :SDT, :ngaySinh, :ghiChu, :gioiTinh, :chucVu)");
        } else {
            // Gọi thủ tục chèn mới
            $stmt = $dbh->prepare("CALL InsertNhanVien(:maNhanVien, :ten, :SDT, :ngaySinh, :ghiChu, :gioiTinh, :chucVu)");
        }

        // Thực thi câu lệnh và kiểm tra kết quả
        $result = $stmt->execute($data);

        if ($result) {
            $message = $maNhanVien ? "Cập nhật thành công!" : "Đăng ký thành công!";
            echo "<script>alert('$message'); window.location.href = 'view_employees.php?mnv=" . ($_POST['MaNhanVien'] ?? $maNhanVien) . "';</script>";
            exit; // Đảm bảo rằng không có đoạn mã nào được thực thi sau đó
        } else {
            $message = "Lỗi khi lưu dữ liệu. Vui lòng thử lại.";
            echo "<script>alert('$message');</script>";
        }
        
    }
} catch (PDOException $e) {
    $message = "Lỗi: " . $e->getMessage();
    echo "<script>alert('$message');</script>";
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

                    <div class="modal-user mt-3">
                        <form action="./action/manage_employees_action.php?mnv=<?php echo htmlspecialchars($maNhanVien); ?>" method="POST">
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
                                            value="<?php echo htmlspecialchars(isset($NhanVien['Role']) && $NhanVien['Role'] == 'Admin' ? 'Quản trị viên' : (isset($NhanVien['Role']) ? 'Nhân viên văn phòng' : '')); ?>" 
                                            required>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="chucVu"><b>Ghi Chú</b></label>
                                        <input type="text" class="form-control mt-1 mb-2 mx-3" name="GhiChu" 
                                            value="<?php echo htmlspecialchars($NhanVien['GhiChu'] ?? ''); ?>">
                                    </div>

                                </div>

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

                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Bootstrap JS và Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-oBqDVmMz4fnFO9gybBogGzPztE1M5rZG/8Xlqh8fATrSWJZDmmW4Ll48dWkOVbCH"
    crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
    integrity="sha384-shoIXUoVOFk60M7DuE4bfOY1pNIqcd9tPCSZrhTDQTXkNv8El+fEfXksqNhUNuUc"
    crossorigin="anonymous"></script>

<script>
    // Hàm mở và đóng dropdown khi bấm tên admin
    function toggleDropdown(event) {
        event.stopPropagation(); // Ngăn chặn sự kiện click bên ngoài
        var dropdown = document.getElementById("dropdownMenu");
        dropdown.style.display = (dropdown.style.display === "block") ? "none" : "block"; // Toggle dropdown
    }

    // Hàm mở và đóng dropdown khi bấm vào nút Action
    function toggleActionDropdown(id) {
        var dropdown = document.getElementById(id);
        if (dropdown.style.display === "none" || dropdown.style.display === "") {
            dropdown.style.display = "block"; // Hiển thị dropdown
        } else {
            dropdown.style.display = "none"; // Ẩn dropdown
        }
    }

    // Đóng tất cả các dropdown nếu click bên ngoài
    window.onclick = function(event) {
        var dropdownMenu = document.getElementById("dropdownMenu");

        // Đóng dropdown của tên admin nếu click bên ngoài
        if (!event.target.matches('#userDropdown') && !event.target.matches('.ms-1') && !dropdownMenu.contains(event.target)) {
            dropdownMenu.style.display = "none"; // Đảm bảo đóng dropdown
        }
    }
</script>
</body>
