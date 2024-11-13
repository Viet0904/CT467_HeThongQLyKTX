<?php
include_once __DIR__ . '/../../config/dbadmin.php';
include_once __DIR__ . '/../../partials/header.php';
include_once __DIR__ . '/../../partials/heading.php';

// Số hàng mỗi trang
$rowsPerPage = 10;

// Xác định trang hiện tại (nếu không có, mặc định là trang 1)
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $rowsPerPage;

// Xử lý tìm kiếm
$maPhong = isset($_POST['maPhong']) && $_POST['maPhong'] != '0' ? $_POST['maPhong'] : null;

// Đếm tổng số hàng
$query = "SELECT COUNT(*) FROM Phong";
if ($maPhong) {
    $query .= " WHERE MaPhong = :maPhong";
}
$totalRowsStmt = $dbh->prepare($query);
if ($maPhong) {
    $totalRowsStmt->bindParam(':maPhong', $maPhong, PDO::PARAM_STR);
}
$totalRowsStmt->execute();
$totalRows = $totalRowsStmt->fetchColumn();
$totalPages = ceil($totalRows / $rowsPerPage);

// Truy vấn với LIMIT và OFFSET
$stmt = $dbh->prepare("SELECT * FROM Phong" . ($maPhong ? " WHERE MaPhong = :maPhong" : "") . " LIMIT :limit OFFSET :offset");
if ($maPhong) {
    $stmt->bindParam(':maPhong', $maPhong, PDO::PARAM_STR);
}
$stmt->bindParam(':limit', $rowsPerPage, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<body>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <?php include_once __DIR__ . '/sidebar.php'; ?>

            <div class="col px-0">
                <div class="mt-4" style="max-width: 1075px; margin-left: 273px; border: 1px solid #ddd; background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);">
                    <div style="padding: 2px; background-color: rgb(219, 48, 119); border-radius: 6px;"></div>
                    <div class="container-fluid py-3" style="padding: 20px;">
                        <h5>Hợp đồng của phòng</h5>

                        <!-- Tìm kiếm -->
                        <form id="searchForm" method="POST" action="">
                            <div class="row g-3">
                                <div class="col-md-6 col-lg-3">
                                    <label for="maPhong" class="form-label">Mã Phòng</label>
                                    <select class="form-select" id="maPhong" name="maPhong" aria-label="Select area" onchange="toggleSelect('maPhong', 'MSSV')">
                                        <option value="0">Tất cả</option>
                                        <?php
                                        $phongQuery = "SELECT MaPhong FROM Phong";
                                        $phongResult = $dbh->query($phongQuery);
                                        while ($row = $phongResult->fetch(PDO::FETCH_ASSOC)) {
                                            echo '<option value="' . htmlspecialchars($row['MaPhong']) . '" ' . ($maPhong == $row['MaPhong'] ? 'selected' : '') . '>' . htmlspecialchars($row['MaPhong']) . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary" aria-label="Search rooms">
                                        <i class="bi bi-search me-2"></i>Search
                                    </button>
                                </div>
                            </div>
                            <script>
                                function toggleSelect(selectedId, otherId) {
                                    var selected = document.getElementById(selectedId);
                                    var other = document.getElementById(otherId);
                                    if (selected.value !== '0') {
                                        other.disabled = true;
                                    } else {
                                        other.disabled = false;
                                    }
                                }
                            </script>
                        </form>

                        <div class="table-responsive mt-3">
                            <table class="table table-bordered table-hover">
                                <thead class="table-primary">
                                    <tr>
                                        <th>STT</th>
                                        <th>Tên phòng</th>
                                        <th>Mã phòng</th>
                                        <th>Hoạt động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1 + $offset;
                                    foreach ($rooms as $room) {
                                        echo "<tr>
                                                <td>{$i}</td>
                                                <td>{$room['TenPhong']}</td>
                                                <td>{$room['MaPhong']}</td>
                                                <td><a href='view_qlthuephong.php?MaPhong={$room['MaPhong']}' class='btn btn-outline-primary'>Xem hợp đồng</a></td>
                                              </tr>";
                                        $i++;
                                    }
                                    ?>
                                </tbody>
                            </table>

                            <!-- Phân trang -->
                            <nav>
                                <ul class="pagination justify-content-center">
                                    <?php if ($page > 1): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?page=1">Trang đầu</a>
                                        </li>
                                        <li class="page-item">
                                            <a class="page-link" href="?page=<?php echo $page - 1; ?>">Trước</a>
                                        </li>
                                    <?php endif; ?>

                                    <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                                        <li class="page-item <?php if ($p == $page) echo 'active'; ?>">
                                            <a class="page-link" href="?page=<?php echo $p; ?>"><?php echo $p; ?></a>
                                        </li>
                                    <?php endfor; ?>

                                    <?php if ($page < $totalPages): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?page=<?php echo $page + 1; ?>">Sau</a>
                                        </li>
                                        <li class="page-item">
                                            <a class="page-link" href="?page=<?php echo $totalPages; ?>">Trang cuối</a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </nav>

                            <p><b>Tổng số:</b> <?php echo $totalRows; ?> dòng</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
