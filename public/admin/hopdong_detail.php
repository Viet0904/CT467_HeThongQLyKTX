<?php
include_once __DIR__ . '/../../config/dbadmin.php';
include_once __DIR__ . '/../../partials/header.php';
include_once __DIR__ . '/../../partials/heading.php';
$contractId = $_GET['MaHopDong'] ?? null;
?>

<body>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <?php include_once __DIR__ . '/sidebar.php'; ?>

            <div class="col px-0">
                <div class="mt-4" style="max-width: 1075px; margin-left: 273px; border: 1px solid #ddd; background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);">
                    <div style="padding: 2px; background-color: rgb(219, 48, 119); border-radius: 6px;"></div>
                    <div class="container-fluid py-3" style="padding: 20px;">
                        <h5>Chi tiết thanh toán hợp đồng <?php echo htmlspecialchars($contractId); ?></h5>
                        <div class="table-responsive mt-3">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tháng/Năm</th>
                                        <th>Số Tiền</th>
                                        <th>Ngày Thanh Toán</th>
                                        <th>Nhân Viên</th>
                                        <th>Hành Động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $stmt = $dbh->prepare("
                                        SELECT TT.ThangNam, Phong.GiaThue AS SoTien, TT.NgayThanhToan, NV.Hoten 
                                        FROM TT_ThuePhong TT 
                                        LEFT JOIN ThuePhong TP ON TT.MaHopDong = TP.MaHopDong
                                        LEFT JOIN Phong ON TP.MaPhong = Phong.MaPhong
                                        LEFT JOIN NhanVien NV ON TT.MaNhanVien = NV.MaNhanVien 
                                        WHERE TT.MaHopDong = :MaHopDong
                                    ");
                                    $stmt->execute([':MaHopDong' => $contractId]);
                                    while ($payment = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                        echo "<tr>
                                                <td>{$payment['ThangNam']}</td>
                                                <td>" . number_format($payment['SoTien'], 2) . "</td>
                                                <td>{$payment['NgayThanhToan']}</td>
                                                <td>{$payment['Hoten']}</td>
                                                <td>
                                                    <div class='dropdown'>
                                                        <button class='btn btn-secondary dropdown-toggle' type='button' id='actionDropdownMenu{$contractId}' data-bs-toggle='dropdown' aria-expanded='false'>
                                                            Hành động
                                                        </button>
                                                        <ul class='dropdown-menu' aria-labelledby='actionDropdownMenu{$contractId}'>
                                                            <li><a class='dropdown-item' href='manage_payment.php?id={$contractId}'>Sửa</a></li>
                                                            <li><a class='dropdown-item' href='delete_payment.php?id={$contractId}' onclick='return confirm(\"Bạn có chắc chắn muốn xóa khoản thanh toán này?\");'>Xoá</a></li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-oBqDVmMz4fnFO9gybBogGzPztE1M5rZG/8Xlqh8fATrSWJZDmmW4Ll48dWkOVbCH"
    crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
    integrity="sha384-shoIXUoVOFk60M7DuE4bfOY1pNIqcd9tPCSZrhTDQTXkNv8El+fEfXksqNhUNuUc"
    crossorigin="anonymous"></script>
<script>
    // Đóng tất cả các dropdown nếu click bên ngoài
    window.onclick = function(event) {
        // Kiểm tra nếu click bên ngoài dropdown
        if (!event.target.matches('.dropdown-toggle')) {
            var dropdowns = document.querySelectorAll('.dropdown-menu');
            dropdowns.forEach(function(dropdown) {
                dropdown.classList.remove('show');
            });
        }
    };
</script>
</html>
