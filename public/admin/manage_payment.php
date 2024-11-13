<?php
session_start();
include_once __DIR__ . '/../../config/dbadmin.php';
include_once __DIR__ . '/../../partials/header.php';
include_once __DIR__ . '/../../partials/heading.php';

$contractId = $_GET['MaHopDong'] ?? null;
$maNhanVien = $_SESSION['MaNhanVien'] ?? null;
// Nếu không có hợp đồng, chuyển hướng về trang quản lý thanh toán
if (!$contractId) {
    header('Location: hopdong_detail.php');
    exit;
}

// Lấy thông tin hợp đồng để chỉnh sửa
$stmt = $dbh->prepare("SELECT TT.ThangNam, Phong.GiaThue AS SoTien, TT.NgayThanhToan, NV.Hoten 
                        FROM TT_ThuePhong TT 
                        LEFT JOIN ThuePhong TP ON TT.MaHopDong = TP.MaHopDong
                        LEFT JOIN Phong ON TP.MaPhong = Phong.MaPhong
                        LEFT JOIN NhanVien NV ON TT.MaNhanVien = NV.MaNhanVien 
                        WHERE TT.MaHopDong = :MaHopDong");
$stmt->execute([':MaHopDong' => $contractId]);
$payment = $stmt->fetch(PDO::FETCH_ASSOC);

// Nếu không có thông tin thanh toán, chuyển hướng về trang thanh toán
if (!$payment) {
    header('Location: hopdong_detail.php');
    exit;
}

// Xử lý cập nhật thông tin thanh toán
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ngayThanhToan = $_POST['NgayThanhToan'] ?? null;
    $nhanVien = $maNhanVien;

    $updateStmt = $dbh->prepare("UPDATE TT_ThuePhong 
                                SET NgayThanhToan = :NgayThanhToan, MaNhanVien = :MaNhanVien
                                WHERE MaHopDong = :MaHopDong");
    $updateStmt->execute([
        ':NgayThanhToan' => $ngayThanhToan,
        ':MaNhanVien' => $nhanVien,
        ':MaHopDong' => $contractId
    ]);

    header("Location: manage_payment.php?MaHopDong=" . $contractId . "&success=1");
    exit;
}

?>

<body>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <?php include_once __DIR__ . '/sidebar.php'; ?>

            <div class="col px-0">
                <div class="mt-4" style="max-width: 1075px; margin-left: 273px; border: 1px solid #ddd; background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);">
                    <div style="padding: 2px; background-color: rgb(219, 48, 119); border-radius: 6px;"></div>
                    <div class="container-fluid py-3" style="padding: 20px;">
                        <h5>Chỉnh sửa thanh toán hợp đồng <?php echo htmlspecialchars($contractId); ?></h5>

                        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
                            <script>
                                alert('Cập nhật thanh toán hợp đồng thành công!');
                                window.location.href = 'hopdong_detail.php?MaHopDong=<?php echo $contractId; ?>';
                            </script>
                            <?php exit(); ?>
                        <?php endif; ?>


                        <!-- Form cập nhật thanh toán -->
                        <form method="POST">
                            <div class="form-group mb-3">
                                <label for="ThangNam">Tháng/Năm:</label>
                                <input type="text" class="form-control" id="ThangNam" value="<?php echo htmlspecialchars($payment['ThangNam']); ?>" disabled>
                            </div>
                            <div class="form-group mb-3">
                                <label for="SoTien">Số Tiền:</label>
                                <input type="text" class="form-control" id="SoTien" value="<?php echo number_format($payment['SoTien'], 2); ?>" disabled>
                            </div>
                            <div class="form-group mb-3">
                                <label for="NgayThanhToan">Ngày Thanh Toán:</label>
                                <input type="date" class="form-control" id="NgayThanhToan" name="NgayThanhToan" value="<?php echo $payment['NgayThanhToan'] ? htmlspecialchars($payment['NgayThanhToan']) : ''; ?>">
                            </div>
                            <div class="form-group mb-3">
                                <label for="MaNhanVien">Nhân Viên:</label>
                                <input type="text" class="form-control" id="MaNhanVien" name="MaNhanVien" value="<?php echo htmlspecialchars($payment['Hoten']); ?>" disabled>
                            </div>
                            <div class="form-group mb-3">
                                <button type="submit" class="btn btn-primary">Cập nhật thanh toán</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
    // Hàm mở và đóng dropdown khi bấm tên admin
    function toggleDropdown(event) {
        event.stopPropagation(); // Ngăn chặn sự kiện click bên ngoài
        var dropdown = document.getElementById("dropdownMenu");
        dropdown.style.display = (dropdown.style.display === "block") ? "none" : "block"; // Toggle dropdown
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

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-oBqDVmMz4fnFO9gybBogGzPztE1M5rZG/8Xlqh8fATrSWJZDmmW4Ll48dWkOVbCH"
    crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
    integrity="sha384-shoIXUoVOFk60M7DuE4bfOY1pNIqcd9tPCSZrhTDQTXkNv8El+fEfXksqNhUNuUc"
    crossorigin="anonymous"></script>
</html>
