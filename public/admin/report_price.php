<?php
include_once __DIR__ . '/../../config/dbadmin.php';
include_once __DIR__ . '/../../partials/header.php';
include_once __DIR__ . '/../../partials/heading.php';

// Default values for month and year filters
$searchMonth = isset($_GET['month']) ? $_GET['month'] : '';
$searchHocKi = isset($_GET['hocKi']) ? $_GET['hocKi'] : '';
$searchYear = isset($_GET['year']) ? $_GET['year'] : '';

// Số dòng trên mỗi trang
$rowsPerPage = 10;

// Điều kiện WHERE cho truy vấn SQL nếu có nhập tháng và năm
$whereClause = '';
if (!empty($searchMonth)) {
    $whereClause .= " AND Thang = :month";
}
if (!empty($searchHocKi)) {
    $whereClause .= " AND HocKi = :hocKi";
}
if (!empty($searchYear)) {
    $whereClause .= " AND NamHoc = :year";
}

// Tính tổng số dòng với điều kiện tìm kiếm
$totalRowsQuery = "SELECT COUNT(*) FROM DienNuoc WHERE 1=1 $whereClause";
$totalRowsStmt = $dbh->prepare($totalRowsQuery);
if (!empty($searchMonth))
    $totalRowsStmt->bindParam(':month', $searchMonth);
if (!empty($searchHocKi))
    $totalRowsStmt->bindParam(':hocKi', $searchHocKi);
if (!empty($searchYear))
    $totalRowsStmt->bindParam(':year', $searchYear);
$totalRowsStmt->execute();
$totalRows = $totalRowsStmt->fetchColumn();

// Tính tổng số trang
$totalPages = ceil($totalRows / $rowsPerPage);

// Lấy trang hiện tại từ query string, nếu không có thì mặc định là trang 1
$currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$currentPage = max(1, min($currentPage, $totalPages));

// Tính chỉ số bắt đầu của dòng trên trang hiện tại
$offset = ($currentPage - 1) * $rowsPerPage;

// Truy vấn SQL với điều kiện và giới hạn phân trang
$diennuocQuery = "SELECT Thang, NamHoc, HocKi, PhiDien, PhiNuoc, TongTien, MaPhong FROM DienNuoc WHERE 1=1 $whereClause LIMIT $rowsPerPage OFFSET $offset";
$diennuocStmt = $dbh->prepare($diennuocQuery);
if (!empty($searchMonth))
    $diennuocStmt->bindParam(':month', $searchMonth);
if (!empty($searchHocKi))
    $diennuocStmt->bindParam(':hocKi', $searchHocKi);
if (!empty($searchYear))
    $diennuocStmt->bindParam(':year', $searchYear);
$diennuocStmt->execute();

// Tính tổng tiền của tất cả dòng (không phân trang) với điều kiện tìm kiếm
$totalAmountQuery = "SELECT SUM(TongTien) AS totalAmount FROM DienNuoc WHERE 1=1 $whereClause";
$totalAmountStmt = $dbh->prepare($totalAmountQuery);
if (!empty($searchMonth))
    $totalAmountStmt->bindParam(':month', $searchMonth);
if (!empty($searchHocKi))
    $totalAmountStmt->bindParam(':hocKi', $searchHocKi);
if (!empty($searchYear))
    $totalAmountStmt->bindParam(':year', $searchYear);
$totalAmountStmt->execute();
$totalAmountAllPages = $totalAmountStmt->fetch(PDO::FETCH_ASSOC)['totalAmount'];

?>

<body>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <?php include_once __DIR__ . '/sidebar.php'; ?>
            <div class="col px-0">
                <div class="mt-4"
                    style="max-width: 1075px; margin-left: 273px; border: 1px solid #ddd; background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);">
                    <div style="padding: 2px; background-color: rgb(219, 48, 119); border-radius: 6px;"></div>
                    <div class="container-fluid py-3" style="padding: 20px;">
                        <h5>Báo cáo tháng</h5>
                        <hr style="border: none; border-top: 1px solid #282827; margin: 1px 0;">

                        <!-- Search Form -->
                            <div class="filter-container pt-3">
                                <form method="GET" action="">
                                    <label for="month">Chọn tháng:</label>
                                    <input type="number" id="month" name="month" min="1" max="12"
                                        value="<?php echo htmlspecialchars($searchMonth); ?>" style="width: 150px">
                                    <label for="hocKi">Chọn học kỳ:</label>
                                    <input type="number" id="hocKi" name="hocKi" min="1" max="3"
                                        value="<?php echo htmlspecialchars($searchHocKi); ?>" style="width: 150px">
                                    <label for="year">Chọn năm:</label>
                                    <input type="number" id="year" name="year" min="2000"
                                        value="<?php echo htmlspecialchars($searchYear)?: 2024; ?>" style="width: 150px">
                                    <button type="submit" class="filter-btn mx-2">Tìm kiếm</button>
                                </form>
                        </div>

                        <!-- Bảng danh sách các chi phí điện nước -->
                        <div class="table-responsive mt-3">
                            <table class="table table-bordered table-striped table-hover mt-3">
                                <thead class="table-primary">
                                    <tr>
                                        <th>STT</th>
                                        <th>Phòng</th>
                                        <th>Tháng</th>
                                        <th>Học Kỳ</th>
                                        <th>Năm học</th>
                                        <th>Tiền điện</th>
                                        <th>Tiền nước</th>
                                        <th>Tổng tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $count = $offset + 1;
                                    while ($row = $diennuocStmt->fetch(PDO::FETCH_ASSOC)) {
                                        echo "<tr>";
                                        echo "<td>" . $count++ . "</td>";
                                        echo "<td>" . htmlspecialchars($row['MaPhong']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['Thang']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['HocKi']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['NamHoc']) . "</td>";
                                        echo "<td>" . number_format($row['PhiDien'], 2) . "</td>";
                                        echo "<td>" . number_format($row['PhiNuoc'], 2) . "</td>";
                                        echo "<td>" . number_format($row['TongTien'], 2) . "</td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                    <!-- Dòng tổng cộng -->
                                    <tr>
                                        <td colspan="7" class="text-center"><strong>Tổng tiền</strong></td>
                                        <td><strong><?php echo number_format($totalAmountAllPages, 2); ?></strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Phân trang -->
                        <?php
                        if ($totalPages > 1) {
                            echo '<nav aria-label="Page navigation" class="d-flex">';
                            echo '<ul class="pagination mx-auto">';
                            if ($currentPage > 1) {
                                echo '<li class="page-item"><a class="page-link" href="?page=1">Trang đầu</a></li>';
                                echo '<li class="page-item"><a class="page-link" href="?page=' . ($currentPage - 1) . '">Previous</a></li>';
                            }
                            for ($i = 1; $i <= $totalPages; $i++) {
                                if ($i == $currentPage) {
                                    echo '<li class="page-item active" aria-current="page"><span class="page-link">' . $i . '</span></li>';
                                } else {
                                    echo '<li class="page-item"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
                                }
                            }
                            if ($currentPage < $totalPages) {
                                echo '<li class="page-item"><a class="page-link" href="?page=' . ($currentPage + 1) . '">Next</a></li>';
                                echo '<li class="page-item"><a class="page-link" href="?page=' . $totalPages . '">Trang cuối</a></li>';
                            }
                            echo '</ul>';
                            echo '</nav>';
                        }
                        ?>
                        <p><strong>Tổng số:</strong> <?php echo $totalRows; ?> dòng</p>
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

</html>