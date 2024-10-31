<?php
include_once __DIR__ . '/../../config/dbadmin.php';
$message = '';

// Kiểm tra xem có mã sinh viên trong URL không (chế độ sửa)
$maSinhVien = isset($_GET['msv']) ? $_GET['msv'] : '';

// Nếu có mã sinh viên, truy vấn dữ liệu sinh viên từ cơ sở dữ liệu
if (!empty($maSinhVien)) {
    $query = "SELECT * FROM SinhVien WHERE MaSinhVien = :maSinhVien";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':maSinhVien', $maSinhVien);
    $stmt->execute();
    $sinhVien = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Xử lý khi form được submit (thêm mới hoặc cập nhật sinh viên)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy mã sinh viên từ POST
    $maSinhVien = $_POST['maSinhVien'] ?? '';
    $ten = $_POST['firstName'];
    $lienHe = $_POST['contact'];
    $email = $_POST['email'];
    $maLop = $_POST['maLop'];
    $diaChi = $_POST['address'];
    $gioiTinh = $_POST['gioiTinh'];
    $khoaHoc = $_POST['khoaHoc'];
    $ngaySinh = $_POST['ngaySinh'];
    $chucVu = $_POST['chucVu'];
    $maDay = $_POST['maDay'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Kiểm tra xem mã sinh viên có giá trị không
        if (!empty($maSinhVien)) {
            // Cập nhật sinh viên
            $sql = "UPDATE SinhVien SET HoTen = :ten, SDT = :lienHe, Email = :email, MaLop = :maLop, DiaChi = :diaChi, GioiTinh = :gioiTinh, 
                    KhoaHoc = :khoaHoc, NgaySinh = :ngaySinh, ChucVu = :chucVu, MaDay = :maDay, Password = :password WHERE MaSinhVien = :maSinhVien";
            $stmt = $dbh->prepare($sql);
            // Gán các tham số
            $stmt->bindParam(':maSinhVien', $maSinhVien);
        } else {
            // Thêm sinh viên mới
            $sql = "INSERT INTO SinhVien (MaSinhVien, HoTen, SDT, Email, MaLop, DiaChi, GioiTinh, KhoaHoc, NgaySinh, ChucVu, MaDay, Password) 
                    VALUES (:maSinhVien, :ten, :lienHe, :email, :maLop, :diaChi, :gioiTinh, :khoaHoc, :ngaySinh, :chucVu, :maDay, :password)";
            $stmt = $dbh->prepare($sql);
            // Gán các tham số
            $stmt->bindParam(':maSinhVien', $maSinhVien);
        }

        // Gán các tham số còn lại cho cả hai trường hợp
        $stmt->bindParam(':ten', $ten);
        $stmt->bindParam(':lienHe', $lienHe);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':maLop', $maLop);
        $stmt->bindParam(':diaChi', $diaChi);
        $stmt->bindParam(':gioiTinh', $gioiTinh);
        $stmt->bindParam(':khoaHoc', $khoaHoc);
        $stmt->bindParam(':ngaySinh', $ngaySinh);
        $stmt->bindParam(':chucVu', $chucVu);
        $stmt->bindParam(':maDay', $maDay);
        $stmt->bindParam(':password', $password);

        // Thực thi và kiểm tra kết quả
        try {
            if ($stmt->execute()) {
                $message = "Cập nhật thành công!";
            } else {
                $message = "Đã xảy ra lỗi.";
            }
        } catch (PDOException $e) {
            $message = "Lỗi: " . $e->getMessage();
        }
    }



include_once __DIR__ . '/../../partials/header.php';
include_once __DIR__ . '/../../partials/heading.php';
?>

<body>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <?php
            include_once __DIR__ . '/sidebar.php';
            ?>

            <div class="col px-0">
                <!-- Nội dung chính -->
                <div class="my-2" style="margin-left: 260px;">
                    <div class="modal-header-1">
                        <h5 class="modal-title mt-2">Đăng ký sinh viên mới</h5>
                    </div>

                    <!-- Hiển thị thông báo -->
                    <?php if (!empty($message)): ?>
                        <div class="alert alert-info mt-3"><?php echo $message; ?></div>
                    <?php endif; ?>

                    <div class="modal-user">
                        <form action="manage_student.php" method="POST">
                            
                            <!-- School Details Section -->
                            <h5 class="mt-1"><b>Chi tiết trường học</b></h5>
                            <div class="row row-add mb-3">
                                <div class="col-md-4">
                                    <label for="maSinhVien" class="form-label"> Mã Sinh viên</label>
                                    <input type="text" class="form-control" id="maSinhVien" name="maSinhVien"
                                        value="<?php echo htmlspecialchars($sinhVien['MaSinhVien'] ?? ''); ?>" <?php echo !empty($maSinhVien) ? 'readonly' : ''; ?> required>
                                </div>
                                <div class="col-md-4">
                                    <label for="khoaHoc" class="form-label">Khoá</label>
                                    <input type="text" class="form-control" id="khoaHoc" name="khoaHoc"
                                        value="<?php echo htmlspecialchars($sinhVien['KhoaHoc'] ?? ''); ?>">
                                </div>
                                <div class="col-md-4">
                                    <label for="maLop" class="form-label">Mã Lớp</label>
                                    <input type="text" class="form-control" id="maLop" name="maLop"
                                        value="<?php echo htmlspecialchars($sinhVien['MaLop'] ?? ''); ?>" required>
                                </div>
                            </div>
                            <div class="row row-add mb-3">

                                <div class="col-md-4">
                                    <label for="maDay" class="form-label">Mã Dãy</label>
                                    <input type="text" class="form-control" id="maDay" name="maDay"
                                        value="<?php echo htmlspecialchars($sinhVien['MaDay'] ?? ''); ?>" required>
                                </div>
                            </div>

                            <!-- Personal Information Section -->
                            <h5><b>Thông tin cá nhân</b></h5>
                            <div class="row row-add mb-3">
                                <div class="col-md-4">
                                    <label for="firstName" class="form-label">Tên</label>
                                    <input type="text" class="form-control" id="firstName" name="firstName"
                                        value="<?php echo htmlspecialchars($sinhVien['HoTen'] ?? ''); ?>" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="ngaySinh" class="form-label">Ngày sinh</label>
                                    <input type="text" class="form-control" id="ngaySinh" name="ngaySinh"
                                        value="<?php echo htmlspecialchars($sinhVien['NgaySinh'] ?? ''); ?>" required>
                                </div>
                                <div class="col-md-4">

                                    <label for="chucVu" class="form-label">Chức vụ</label>
                                    <input type="text" class="form-control" id="chucVu" name="chucVu"
                                        value="<?php echo htmlspecialchars($sinhVien['ChucVu'] ?? ''); ?>" required>
                                </div>
                            </div>

                            <div class="row row-add mb-3">
                                <div class="col-md-4">
                                    <label for="gioiTinh" class="form-label">Giới tính</label>
                                    <select class="form-select" id="gioiTinh" name="gioiTinh">
                                        <option value="Nam" <?php if ($sinhVien['GioiTinh'] === 'Nam')
                                            echo 'selected'; ?>>Nam</option>
                                        <option value="Nữ" <?php if ($sinhVien['GioiTinh'] === 'Nữ')
                                            echo 'selected'; ?>>
                                            Nữ</option>
                                        <option value="Khác" <?php if ($sinhVien['GioiTinh'] === 'Khác')
                                            echo 'selected'; ?>>Khác</option>
                                    </select>

                                </div>
                                <div class="col-md-4">
                                    <label for="contact" class="form-label">Liên hệ #</label>
                                    <input type="text" class="form-control" id="contact" name="contact"
                                        value="<?php echo htmlspecialchars($sinhVien['SDT'] ?? ''); ?>" required>

                                </div>
                                <div class="col-md-4">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="<?php echo htmlspecialchars($sinhVien['Email'] ?? ''); ?>" required>

                                </div>
                            </div>

                            <div class="row row-add mb-3">
                                <div class="col-md-12">
                                    <label for="address" class="form-label">Địa chỉ</label>
                                    <input type="text" class="form-control" id="address" name="address"
                                        value="<?php echo htmlspecialchars($sinhVien['DiaChi'] ?? ''); ?>" required>

                                </div>
                            </div>

                            <div class="col-md-12">
                                <label for="password" class="form-label">Password</label>
                                <input type="text" class="form-control" id="password" name="password"
                                    value="<?php echo htmlspecialchars($sinhVien['Password'] ?? ''); ?>" required>

                            </div>


                            <!-- Submit Button -->
                            <div class="text-end mt-2">
                                <button type="submit" class="btn btn-primary"
                                    style="background-color: #db3077;">Lưu</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
    </div>
</body>

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
    window.onclick = function (event) {
        var dropdownMenu = document.getElementById("dropdownMenu");

        // Đóng dropdown của tên admin nếu click bên ngoài
        if (!event.target.matches('#userDropdown') && !event.target.matches('.ms-1') && !dropdownMenu.contains(event.target)) {
            dropdownMenu.style.display = "none"; // Đảm bảo đóng dropdown
        }
    }
</script>

</html>