<?php
include_once __DIR__ . '/../../partials/header.php';
include_once __DIR__ . '/../../partials/heading.php';
require_once __DIR__ . '/../../config/dbadmin.php';
?>

<body>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <?php
            include_once __DIR__ . '/sidebar.php';
            ?>

            <div class="col px-0">
                <!-- Nội dung chính của trang -->
                <div class="content">
                    <div class="dashboard-header">Chào mừng đến với Hệ Thống quản lý Ký túc xá!</div>

                    <!-- Dashboard cards - Hàng đầu tiên với 2 thẻ -->
                    <div style="margin-top: 30px; margin-left: 40px;">
                        <div class="row">
                            <div class="card">
                                <a href="room_list.php" style="text-decoration: none; color: black">
                                    <i class="fas fa-door-open"></i>
                                    <div class="card-title">Tổng số phòng</div>
                                    <div class="card-number">
                                        <?php
                                        $sqlPhong = "SELECT MaPhong FROM Phong";
                                        $resultPhong = $dbh->prepare($sqlPhong);
                                        $resultPhong->execute();
                                        $totalPhong = $resultPhong->rowCount();
                                        echo htmlspecialchars($totalPhong); // Hiển thị tổng số phòng
                                        ?>
                                    </div>
                                </a>
                            </div>
                            <div class="card">
                                <a href="student_list.php" style="text-decoration: none; color: black">
                                    <i class="fas fa-users"></i>
                                    <div class="card-title">Tổng sinh viên</div>
                                    <div class="card-number">
                                        <?php
                                        $sqlSinhVien = "SELECT MaSinhVien FROM SinhVien";
                                        $resultSinhVien = $dbh->prepare($sqlSinhVien);
                                        $resultSinhVien->execute();
                                        $totalSinhVien = $resultSinhVien->rowCount();
                                        echo htmlspecialchars($totalSinhVien); // Hiển thị tổng số sinh vien
                                        ?>
                                    </div>
                                </a>
                            </div>
                        </div>

                    </div>

                    <!-- Dashboard cards - Hàng thứ hai với 2 thẻ -->
                    <div style="margin-top: 30px; margin-left: 40px;">
                        <div class="row">
                            <div class="card">
                                <a href="report_price.php" style="text-decoration: none; color: black">
                                    <i class="fas fa-coins"></i>
                                    <div class="card-title">Tổng doanh thu</div>
                                    <div class="card-number">
                                        <?php
                                        // Truy vấn tổng tiền từ bảng DienNuoc
                                        $sqlDoanhThu = "SELECT SUM(TongTien) AS TongDoanhThu FROM DienNuoc";
                                        $resultDoanhThu = $dbh->prepare($sqlDoanhThu);
                                        $resultDoanhThu->execute();
                                        $tongDoanhThu = $resultDoanhThu->fetch(PDO::FETCH_ASSOC)['TongDoanhThu'];
                                        // Hiển thị tổng doanh thu đã format theo kiểu tiền tệ
                                        echo htmlspecialchars(number_format($tongDoanhThu, 2)) . " VND";
                                        ?>
                                    </div>
                                </a>
                            </div>
                            <div class="card">
                                <a href="employees_list.php" style="text-decoration: none; color: black">
                                    <i class="fas fa-cogs"></i>
                                    <div class="card-title">Tổng nhân viên</div>
                                    <div class="card-number">
                                        <?php
                                        $sqlNhanVien = "SELECT MaNhanVien FROM NhanVien";
                                        $resultNhanVien = $dbh->prepare($sqlNhanVien);
                                        $resultNhanVien->execute();
                                        $totalNhanVien = $resultNhanVien->rowCount();
                                        echo htmlspecialchars($totalNhanVien); // Hiển thị tổng số sinh vien
                                        ?>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <script>
        // Hàm mở và đóng dropdown
        function toggleDropdown() {
            var dropdown = document.getElementById("dropdownMenu");
            if (dropdown.style.display === "none" || dropdown.style.display === "") {
                dropdown.style.display = "block"; // Hiển thị dropdown
            } else {
                dropdown.style.display = "none"; // Ẩn dropdown
            }
        }

        // Đóng dropdown nếu click bên ngoài
        window.onclick = function(event) {
            if (!event.target.matches('#userDropdown') && !event.target.matches('.ms-1')) {
                var dropdown = document.getElementById("dropdownMenu");
                if (dropdown.style.display === "block") {
                    dropdown.style.display = "none";
                }
            }
        }
    </script>

</body>

</html>