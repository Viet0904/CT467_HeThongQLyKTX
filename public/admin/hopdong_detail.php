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
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $stmt = $dbh->prepare("SELECT TT.*, NV.Hoten FROM TT_ThuePhong TT LEFT JOIN NhanVien NV ON TT.MaNhanVien = NV.MaNhanVien WHERE TT.MaHopDong = :MaHopDong");
                                    $stmt->execute([':MaHopDong' => $contractId]);
                                    while ($payment = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                        echo "<tr>
                                                <td>{$payment['ThangNam']}</td>
                                                <td>{$payment['SoTien']}</td>
                                                <td>{$payment['NgayThanhToan']}</td>
                                                <td>{$payment['Hoten']}</td>
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
</html>
