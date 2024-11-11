<?php
include_once __DIR__ . '/../../config/dbadmin.php';
include_once __DIR__ . '/../../partials/header.php';
include_once __DIR__ . '/../../partials/heading.php';

$message = '';
$maSinhVien = $_GET['msv'] ?? ''; // Lấy mã sinh viên từ URL nếu có

// Lấy thông tin sinh viên nếu có mã sinh viên
if ($maSinhVien) {
    $stmt = $dbh->prepare("SELECT sv.*, sv.MaSinhVien AS MaSV_SinhVien, p.*, tp.*
    FROM SinhVien sv
    LEFT JOIN Lop ON sv.MaLop = Lop.MaLop
    LEFT JOIN ThuePhong tp ON sv.MaSinhVien = tp.MaSinhVien
    LEFT JOIN Phong p ON tp.MaPhong = p.MaPhong
    WHERE sv.MaSinhVien = :maSinhVien");
    $stmt->execute([':maSinhVien' => $maSinhVien]);
    $sinhVien = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Lấy danh sách mã phòng và mã dãy từ bảng Phong
$stmtPhong = $dbh->prepare("SELECT MaPhong, MaDay FROM Phong");
$stmtPhong->execute();
$phongList = $stmtPhong->fetchAll(PDO::FETCH_ASSOC);

// Truy vấn để lấy mã hợp đồng cuối cùng
$sql = "SELECT MaHopDong FROM ThuePhong ORDER BY MaHopDong DESC LIMIT 1";
$stmt = $dbh->prepare($sql);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result) {
    // Lấy số từ mã hợp đồng cuối cùng
    $lastId = intval(substr($result['MaHopDong'], 2)) + 1;
    // Tạo mã hợp đồng mới
    $newMaHopDong = 'HD' . str_pad($lastId, 6, '0', STR_PAD_LEFT);
} else {
    // Nếu chưa có hợp đồng nào, mã bắt đầu từ HD000001
    $newMaHopDong = 'HD000001';
}

// Bắt đầu transaction
$dbh->beginTransaction();

try {
    // Các truy vấn khác
    // Sau khi thêm hoặc cập nhật thông tin sinh viên
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $maSinhVien = $_POST['maSinhVien'] ?? $maSinhVien;

        // Lấy thông tin sinh viên từ CSDL
        $checkStmt = $dbh->prepare("SELECT * FROM SinhVien WHERE MaSinhVien = :maSinhVien");
        $checkStmt->execute([':maSinhVien' => $maSinhVien]);
        $sinhVien = $checkStmt->fetch(PDO::FETCH_ASSOC);

        // Sau khi lưu sinh viên, kiểm tra và cập nhật bảng thuê phòng
        $maPhong = $_POST['maPhong']; // Lấy mã phòng từ form

        // Truy vấn để lấy giá thuê của phòng
        $query = "SELECT GiaThue FROM Phong WHERE MaPhong = :maPhong";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':maPhong', $maPhong, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Lấy giá thuê từ kết quả truy vấn
        $giaThue = $result['GiaThue'];

        // Kiểm tra nếu sinh viên đã có hợp đồng thuê phòng
        $checkThuePhongStmt = $dbh->prepare("SELECT * FROM ThuePhong WHERE MaSinhVien = :maSinhVien");
        $checkThuePhongStmt->execute([':maSinhVien' => $maSinhVien]);
        $thuePhong = $checkThuePhongStmt->fetch(PDO::FETCH_ASSOC);

        // Dữ liệu thuê phòng
        $thuePhongData = [
            ':maSinhVien' => $maSinhVien,
            ':maHopDong' => $newMaHopDong,
            ':maPhong' => $maPhong,
            ':batDau' => date('Y-m-d'), 
            ':ketThuc' => date('Y-m-d', strtotime('+4 month')),
            ':giaThueThucTe' => $giaThue, 
        ];

        if ($thuePhong) {
            // Cập nhật thông tin thuê phòng
            $sqlThuePhong = "UPDATE ThuePhong SET MaHopDong = :maHopDong, MaPhong = :maPhong, BatDau = :batDau, KetThuc = :ketThuc, GiaThueThucTe = :giaThueThucTe WHERE MaSinhVien = :maSinhVien";
        } else {
            // Thêm thông tin thuê phòng mới
            $sqlThuePhong = "INSERT INTO ThuePhong (MaSinhVien, MaHopDong, MaPhong, BatDau, KetThuc, GiaThueThucTe) VALUES (:maSinhVien, :maHopDong, :maPhong, :batDau, :ketThuc, :giaThueThucTe)";
        }

        $stmtThuePhong = $dbh->prepare($sqlThuePhong);
        $stmtThuePhong->execute($thuePhongData);

        // Xác nhận transaction
        $dbh->commit();
        $message = $sinhVien ? "Cập nhật thành công!" : "Lưu thành công!";

        // Lấy lại thông tin sinh viên sau khi thêm hoặc cập nhật phòng
        $stmt = $dbh->prepare("SELECT sv.*, sv.MaSinhVien AS MaSV_SinhVien, p.MaPhong AS MaPhong, tp.*
        FROM SinhVien sv
        LEFT JOIN Lop ON sv.MaLop = Lop.MaLop
        LEFT JOIN ThuePhong tp ON sv.MaSinhVien = tp.MaSinhVien
        LEFT JOIN Phong p ON tp.MaPhong = p.MaPhong
        WHERE sv.MaSinhVien = :maSinhVien");
        $stmt->execute([':maSinhVien' => $maSinhVien]);
        $sinhVien = $stmt->fetch(PDO::FETCH_ASSOC);

        echo "<script>alert('$message');</script>";
        echo "<script>window.location.href='dangkyphong_sv.php';</script>";
        
        exit;
    }
} catch (PDOException $e) {
    $message = "Lỗi: " . $e->getMessage();
}
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
                        <h5 class="modal-title mt-2">Đăng ký phòng</h5>
                    </div>

                    <!-- Hiển thị thông báo -->
                    <?php if (!empty($message)): ?>
                        <div class="alert alert-info mt-3"><?php echo $message; ?></div>
                    <?php endif; ?>

                    <div class="modal-user">
                        <form action="manage_sv_thuephong.php" method="POST">
                            <div class="row row-add mb-3">
                                <div class="col-md-4">
                                    <label for="maPhong" class="form-label">Mã Phòng</label>
                                    <select class="form-control" id="maPhong" name="maPhong" required>
                                        <option value="">Chọn mã phòng</option>
                                        <?php foreach ($phongList as $phong): ?>
                                            <option value="<?= htmlspecialchars($phong['MaPhong']) ?>"
                                                <?php echo (isset($sinhVien['MaPhong']) && $sinhVien['MaPhong'] === $phong['MaPhong']) ? 'selected' : '' ?>>
                                                <?php echo htmlspecialchars($phong['MaPhong']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>

                                </div>
                                

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