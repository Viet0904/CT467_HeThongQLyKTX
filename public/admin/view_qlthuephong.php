<?php
include_once __DIR__ . '/../../config/dbadmin.php';
include_once __DIR__ . '/../../partials/header.php';
include_once __DIR__ . '/../../partials/heading.php';
$roomId = $_GET['MaPhong'] ?? null;
?>

<body>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <?php include_once __DIR__ . '/sidebar.php'; ?>

            <div class="col px-0">
                <div class="mt-4" style="max-width: 1075px; margin-left: 273px; border: 1px solid #ddd; background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);">
                    <div style="padding: 2px; background-color: rgb(219, 48, 119); border-radius: 6px;"></div>
                    <div class="container-fluid py-3" style="padding: 20px;">
                        <h5>Danh sách hợp đồng của phòng <?php echo htmlspecialchars($roomId); ?></h5>
                        <div class="table-responsive mt-3">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Mã Hợp Đồng</th>
                                        <th>Ngày Bắt Đầu</th>
                                        <th>Ngày Kết Thúc</th>
                                        <th>Giá Thuê</th>
                                        <th>Hành Động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $stmt = $dbh->prepare("
                                        SELECT ThuePhong.MaHopDong, HocKi.BatDau, HocKi.KetThuc, Phong.GiaThue
                                        FROM ThuePhong
                                        JOIN Phong ON ThuePhong.MaPhong = Phong.MaPhong
                                        JOIN HocKi ON ThuePhong.HocKi = HocKi.HocKi
                                        WHERE ThuePhong.MaPhong = :MaPhong
                                    ");
                                    $stmt->execute([':MaPhong' => $roomId]);
                                    while ($contract = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                        echo "<tr>
                                                <td>{$contract['MaHopDong']}</td>
                                                <td>{$contract['BatDau']}</td>
                                                <td>{$contract['KetThuc']}</td>
                                                <td>" . number_format($contract['GiaThue'], 2) . "</td>
                                                <td><a href='hopdong_detail.php?MaHopDong={$contract['MaHopDong']}' class='btn btn-outline-info'>Xem chi tiết</a></td>
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
</html>
