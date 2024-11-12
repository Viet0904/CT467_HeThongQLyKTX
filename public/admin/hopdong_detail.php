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
                                        // Kiểm tra và hiển thị "Chưa thanh toán" nếu NgayThanhToan là null
                                        $ngayThanhToan = $payment['NgayThanhToan'] ?? "Chưa thanh toán";
                                        $hoten = $payment['NgayThanhToan'] ? $payment['Hoten'] : ""; // Để trống nếu chưa thanh toán
                                    
                                        echo "<tr>
                                                <td>{$payment['ThangNam']}</td>
                                                <td>" . number_format($payment['SoTien'], 2) . "</td>
                                                <td>{$ngayThanhToan}</td>
                                                <td>{$hoten}</td>";
                                        
                                        echo "<td>
                                                <div class='dropdown position-relative'>
                                                    <button class='btn btn-outline-secondary dropdown-toggle' type='button' onclick='toggleActionDropdown(\"actionDropdownMenu" . htmlspecialchars($payment['ThangNam']) . "\")'>
                                                        Hoạt động
                                                    </button>
                                                    <div id='actionDropdownMenu" . htmlspecialchars($payment['ThangNam']) . "' class='dropdown-menu position-absolute' style='display: none; min-width: 100px; top: 100%; left: 0;'>
                                                    <a class='dropdown-item py-2' href='./process_payment.php?MaHopDong=" . htmlspecialchars($contractId) . "'>Thanh Toán</a>
                                                    <a class='dropdown-item py-2' href='./manage_payment.php?MaHopDong=" . htmlspecialchars($contractId) . "'>Sửa</a>
                                                </div>
                                                </div>
                                            </td>";
                                        echo '</tr>';
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
