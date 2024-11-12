<?php
include_once __DIR__ . '/../../config/dbadmin.php';
include_once __DIR__ . '/../../partials/header.php';
include_once __DIR__ . '/../../partials/heading.php';

$message = '';
$maSinhVien = $_GET['msv'] ?? '';

$currentPhong = '';


// Xử lý form gửi đi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $maPhong = $_POST['maPhong'];


    $stmt = $dbh->prepare("SELECT HocKi, NamHoc FROM HocKi WHERE NamHoc = YEAR(CURRENT_DATE) AND CURRENT_DATE BETWEEN BatDau AND KetThuc LIMIT 1");

    $stmt = $dbh->prepare($currentHocKiQuery);
    $stmt->execute();
    $stmt->execute([
        ':currentDate' => $currentDate,
        ':namHoc' => $namHoc,
    ]);

    try {
        $stmt = $dbh->prepare("CALL DangKyPhong(:maSinhVien, :maPhong, :hocKi, :namHoc, @message)");
        $stmt->execute([
            ':maSinhVien' => $maSinhVien,
            ':maPhong' => $maPhong,
            ':hocKi' => $hocKi,
            ':namHoc' => $namHoc,
        ]);

        $message = $dbh->query("SELECT @message AS message")->fetch(PDO::FETCH_ASSOC)['message'];
    } catch (PDOException $e) {
        $message = $e->getMessage();
    }
    if (!empty($message)) {
        echo "<script>alert('" . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . "');</script>";
    }
}
//  Lấy Danh Sách Sinh Viên
$sinhVienList = $dbh->query("SELECT MaSinhVien FROM SinhVien")->fetchAll(PDO::FETCH_ASSOC);
// Lấy danh sách phòng
$phongList = $dbh->query("SELECT MaPhong FROM Phong")->fetchAll(PDO::FETCH_ASSOC);
// Lấy Danh sách Học Ki và năm học
$hocKiList = $dbh->query("SELECT HocKi, NamHoc FROM HocKi")->fetchAll(PDO::FETCH_ASSOC);
?>

<body>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <?php include_once __DIR__ . '/sidebar.php'; ?>
            <div class="col px-0">
                <div class="my-2" style="margin-left: 260px;">
                    <div class="modal-header-1">
                        <h5 class="modal-title mt-2">Đăng ký phòng</h5>
                    </div>

                    <div class="modal-user">
                        <form action="manage_sv_thuephong.php?msv=<?php echo htmlspecialchars($maSinhVien); ?>" method="POST">
                            <input type="hidden" name="maSinhVien" value="<?php echo htmlspecialchars($maSinhVien); ?>">
                            <div class="row row-add mb-3">
                                <div class="col-md-4">
                                    <label for="maPhong" class="form-label">Mã Sinh Viên</label>
                                    <select class="form-control" id="maPhong" name="maPhong" required>
                                        <option value="">Chọn mã phòng</option>
                                        <?php foreach ($sinhVienList as $SinhVien): ?>
                                            <option value="<?= htmlspecialchars($SinhVien['MaSinhVien']) ?>"
                                                <?php echo ($currentPhong === $SinhVien['MaSinhVien']) ? 'selected' : ''; ?>>
                                                <?= htmlspecialchars($SinhVien['MaSinhVien']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="maPhong" class="form-label">Mã Phòng</label>
                                    <select class="form-control" id="maPhong" name="maPhong" required>
                                        <option value="">Chọn mã phòng</option>
                                        <?php foreach ($phongList as $phong): ?>
                                            <option value="<?= htmlspecialchars($phong['MaPhong']) ?>"
                                                <?php echo ($currentPhong === $phong['MaPhong']) ? 'selected' : ''; ?>>
                                                <?= htmlspecialchars($phong['MaPhong']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>



                                <div class="text-end mt-2">
                                    <button type="submit" class="btn btn-primary" style="background-color: #db3077;">Lưu</button>
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
    window.onclick = function(event) {
        var dropdownMenu = document.getElementById("dropdownMenu");

        // Đóng dropdown của tên admin nếu click bên ngoài
        if (!event.target.matches('#userDropdown') && !event.target.matches('.ms-1') && !dropdownMenu.contains(event.target)) {
            dropdownMenu.style.display = "none"; // Đảm bảo đóng dropdown
        }
    }
</script>

</html>