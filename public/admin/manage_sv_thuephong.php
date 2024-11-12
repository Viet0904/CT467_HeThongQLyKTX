<?php
include_once __DIR__ . '/../../config/dbadmin.php';
include_once __DIR__ . '/../../partials/header.php';
include_once __DIR__ . '/../../partials/heading.php';

$message = '';
$maSinhVien = $_GET['msv'] ?? '';
$currentPhong = '';

// Kiểm tra sinh viên đã có phòng
$query = "SELECT MaPhong FROM ThuePhong WHERE MaSinhVien = :maSinhVien LIMIT 1";
$stmt = $dbh->prepare($query);
$stmt->execute([':maSinhVien' => $maSinhVien]);
$currentPhong = $stmt->fetchColumn();

// Xử lý form gửi đi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $maPhong = $_POST['maPhong'];
    $batDau = date('Y-m-d');
    $ketThuc = date('Y-m-d', strtotime('+4 months'));

    // Truy vấn để lấy giá thuê của phòng
    $query = "SELECT GiaThue FROM Phong WHERE MaPhong = :maPhong";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':maPhong', $maPhong, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Lấy giá thuê từ kết quả truy vấn
    $giaThueThucTe = $result['GiaThue'] ?? 0;

    if (!$currentPhong) {
        // Tạo mã hợp đồng mới
        $newMaHopDong = 'HD' . str_pad(substr($dbh->query("SELECT MaHopDong FROM ThuePhong ORDER BY MaHopDong DESC LIMIT 1")->fetchColumn() ?? 'HD000000', 2) + 1, 6, '0', STR_PAD_LEFT);

        // Thêm hợp đồng mới
        $query = "INSERT INTO ThuePhong (MaHopDong, MaSinhVien, MaPhong, BatDau, KetThuc, GiaThueThucTe)
                  VALUES (:maHopDong, :maSinhVien, :maPhong, :batDau, :ketThuc, :giaThueThucTe)";
        $params = [':maHopDong' => $newMaHopDong, ':maSinhVien' => $maSinhVien, ':maPhong' => $maPhong, ':batDau' => $batDau, ':ketThuc' => $ketThuc, ':giaThueThucTe' => $giaThueThucTe];
    } else {
        // Cập nhật hợp đồng hiện tại
        $query = "UPDATE ThuePhong SET MaPhong = :maPhong, BatDau = :batDau, KetThuc = :ketThuc, GiaThueThucTe = :giaThueThucTe WHERE MaSinhVien = :maSinhVien";
        $params = [':maPhong' => $maPhong, ':batDau' => $batDau, ':ketThuc' => $ketThuc, ':giaThueThucTe' => $giaThueThucTe, ':maSinhVien' => $maSinhVien];
    }

    $stmt = $dbh->prepare($query);
    $message = $stmt->execute($params) ? ($currentPhong ? "Cập nhật phòng thành công!" : "Đăng ký phòng thành công!") : "Đã xảy ra lỗi.";
    $currentPhong = $maPhong; // Cập nhật phòng hiện tại
}

// Lấy danh sách phòng
$phongList = $dbh->query("SELECT MaPhong FROM Phong")->fetchAll(PDO::FETCH_ASSOC);
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

                    <!-- Hiển thị thông báo -->
                    <?php if (!empty($message)): ?>
                        <div class="alert alert-info mt-3"><?php echo htmlspecialchars($message); ?></div>
                    <?php endif; ?>

                    <div class="modal-user">
                        <form action="manage_sv_thuephong.php?msv=<?php echo htmlspecialchars($maSinhVien); ?>" method="POST">
                            <input type="hidden" name="maSinhVien" value="<?php echo htmlspecialchars($maSinhVien); ?>">
                            <div class="row row-add mb-3">
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
    window.onclick = function (event) {
        var dropdownMenu = document.getElementById("dropdownMenu");

        // Đóng dropdown của tên admin nếu click bên ngoài
        if (!event.target.matches('#userDropdown') && !event.target.matches('.ms-1') && !dropdownMenu.contains(event.target)) {
            dropdownMenu.style.display = "none"; // Đảm bảo đóng dropdown
        }
    }
</script>
</html>
