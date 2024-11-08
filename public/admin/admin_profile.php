<?php
include_once __DIR__ . '/../../partials/header.php';
include_once __DIR__ . '/../../partials/heading.php';
include_once __DIR__ . '/../../config/dbadmin.php';

// Kiểm tra mã nhân viên đã lưu trong session
if (!isset($_SESSION)) {
    session_start();
}

if (isset($_SESSION['MaNhanVien'])) {
    $maNhanVien = $_SESSION['MaNhanVien'];

    // Truy vấn thông tin nhân viên từ DB
    $query = "SELECT MaNhanVien, HoTen, SDT, GioiTinh, NgaySinh, Password FROM NhanVien WHERE MaNhanVien = ?";
    $stmt = $dbh->prepare($query);
    $stmt->bindValue(1, $maNhanVien);
    $stmt->execute();
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    echo "<script>alert('Vui lòng đăng nhập lại.'); window.location.href = '../index.php';</script>";
    exit();
}

// Xử lý khi người dùng cập nhật thông tin
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tenQt = $_POST['tenQt'];
    $SDT = $_POST['SDT'];
    $gender = $_POST['gender'];
    $ngaySinh = $_POST['ngaySinh'];
    $matKhau = $_POST['matKhau'];


    // Cập nhật thông tin nhân viên trong DB
    $updateQuery = "UPDATE NhanVien SET HoTen = ?, SDT = ?, GioiTinh = ?, NgaySinh = ?, Password = ? WHERE MaNhanVien = ?";
    $stmt = $dbh->prepare($updateQuery);
    $stmt->bindValue(1, $tenQt);
    $stmt->bindValue(2, $SDT);
    $stmt->bindValue(3, $gender);
    $stmt->bindValue(4, $ngaySinh);
    $stmt->bindValue(5, $matKhau);
    $stmt->bindValue(6, $maNhanVien); // MaNhanVien nên là tham số cuối cùng trong câu lệnh
    $stmt->execute();


    echo "<script>alert('Cập nhật thông tin thành công!'); window.location.href = './admin_profile.php';</script>";
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
                        <h5 class="modal-title mt-2">Hồ sơ quản trị viên</h5>
                    </div>

                    <div class="modal-user">
                        <form action="" method="POST">
                            <h5 class="mt-1"><b>Hồ sơ</b></h5>

                            <div class="mb-3">
                                <label for="tenQt" class="form-label">Tên quản trị viên</label>
                                <input type="text" class="form-control" id="tenQt" name="tenQt"
                                    value="<?= htmlspecialchars($admin['HoTen'] ?? '') ?>">
                            </div>

                            <div class="mb-3">
                                <label for="maQt" class="form-label">Mã quản trị viên</label>
                                <input type="text" class="form-control" id="maQt" name="maQt"
                                    value="<?= htmlspecialchars($admin['MaNhanVien'] ?? '') ?>" readonly required>
                            </div>


                            <div class="mb-3">
                                <label for="SDT" class="form-label">Số liên lạc</label>
                                <input type="text" class="form-control" id="SDT" name="SDT"
                                    value="<?= htmlspecialchars($admin['SDT'] ?? '') ?>">
                            </div>

                            <div class="row row-add">
                            <div class="col-md-6">
                                    <label for="gioiTinh" class="form-label">Giới tính</label>
                                    <select class="form-select" id="gioiTinh" name="gioiTinh">
                                        <option value="Nam" <?php echo (isset($admin['GioiTinh']) && $admin['GioiTinh'] === 'Nam') ? 'selected' : ''; ?>>Nam</option>
                                        <option value="Nữ" <?php echo (isset($admin['GioiTinh']) && $admin['GioiTinh'] === 'Nữ') ? 'selected' : ''; ?>>Nữ</option>
                                        <option value="Khác" <?php echo (isset($admin['GioiTinh']) && $admin['GioiTinh'] === 'Khác') ? 'selected' : ''; ?>>Khác</option>
                                    </select>


                                </div>
                                <div class="col-md-6">
                                    <label for="ngaySinh" class="form-label">Ngày sinh</label>
                                    <input type="date" class="form-control" id="ngaySinh" name="ngaySinh"
                                        value="<?= htmlspecialchars($admin['NgaySinh'] ?? '') ?>">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="matKhau" class="form-label">Mật khẩu</label>
                                <input type="text" class="form-control" id="matKhau" name="matKhau"
                                    value="<?= htmlspecialchars($admin['Password'] ?? '') ?>">
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