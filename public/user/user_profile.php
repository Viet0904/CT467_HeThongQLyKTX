<?php
include_once __DIR__ . '/../../partials/header.php';
include_once __DIR__ . '/../../partials/navbar.php';
include_once __DIR__ . '/../../config/dbadmin.php';

// Kiểm tra mã nhân viên đã lưu trong session
if (!isset($_SESSION)) {
    session_start();
}

if (isset($_SESSION['MaSinhVien'])) {
    $maSinhVien = $_SESSION['MaSinhVien'];

    // Truy vấn thông tin nhân viên từ DB
    $query = "SELECT * FROM SinhVien WHERE MaSinhVien = ?";
    $stmt = $dbh->prepare($query);
    $stmt->bindValue(1, $maSinhVien);
    $stmt->execute();
    $sinhVien = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    echo "<script>alert('Vui lòng đăng nhập lại.'); window.location.href = '../index.php';</script>";
    exit();
}

// Xử lý khi người dùng cập nhật thông tin
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $khoaHoc = $_POST['khoaHoc'];
    $maLop = $_POST['maLop'];
    $maDay = $_POST['maDay'];
    $firstName = $_POST['firstName'];
    $ngaySinh = $_POST['ngaySinh'];
    $chucVu = $_POST['chucVu'];
    $gioiTinh = $_POST['gioiTinh'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $password = $_POST['password'];

    // Cập nhật thông tin nhân viên trong DB
    $updateQuery = "UPDATE SinhVien SET KhoaHoc = ?, MaLop = ?, MaDay = ?, HoTen = ?, NgaySinh = ?, ChucVu = ?, GioiTinh = ?, SDT = ?, Email = ?, DiaChi = ?, Password = ? WHERE MaSinhVien = ?";
    $stmt = $dbh->prepare($updateQuery);
    $stmt->bindValue(1, $khoaHoc);
    $stmt->bindValue(2, $maLop);
    $stmt->bindValue(3, $maDay);
    $stmt->bindValue(4, $firstName);
    $stmt->bindValue(5, $ngaySinh);
    $stmt->bindValue(6, $chucVu);
    $stmt->bindValue(7, $gioiTinh);
    $stmt->bindValue(8, $contact);
    $stmt->bindValue(9, $email);
    $stmt->bindValue(10, $address);
    $stmt->bindValue(11, $password);
    $stmt->bindValue(12, $maSinhVien); // MaSinhVien nên là tham số cuối cùng trong câu lệnh
    $stmt->execute();

    echo "<script>alert('Cập nhật thông tin thành công!'); window.location.href = './user_profile.php';</script>";
}
?>

<body>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <?php include_once __DIR__ . '/sidebar.php'; ?>

            <div class="col px-0">
                <!-- Nội dung chính -->
                <div class="my-2" style="margin-left: 260px;">
                    <div class="modal-header-1">
                        <h5 class="modal-title mt-2">Hồ sơ sinh viên</h5>
                    </div>

                    <div class="modal-user">
                        <form action="" method="POST">
                            <h5 class="mt-1"><b>Hồ sơ</b></h5>

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
                                        <option value="Nam" <?php echo (isset($sinhVien['GioiTinh']) && $sinhVien['GioiTinh'] === 'Nam') ? 'selected' : ''; ?>>Nam</option>
                                        <option value="Nữ" <?php echo (isset($sinhVien['GioiTinh']) && $sinhVien['GioiTinh'] === 'Nữ') ? 'selected' : ''; ?>>Nữ</option>
                                        <option value="Khác" <?php echo (isset($sinhVien['GioiTinh']) && $sinhVien['GioiTinh'] === 'Khác') ? 'selected' : ''; ?>>Khác</option>
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

                            <div class="text-end mt-3">
                                <button type="submit" class="btn btn-primary" style="background-color: #db3077;">Cập
                                    nhật</button>
                            </div>
                        </form>
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