<?php
include_once __DIR__ . '/../../config/dbadmin.php';
include_once __DIR__ . '/../../partials/header.php';
include_once __DIR__ . '/../../partials/heading.php';
session_start();
$room_id = $_POST['room_id'] ?? '0';
$month = $_POST['month'] ?? 0;

$query = "SELECT SQL_CALC_FOUND_ROWS 
            dn.*
          FROM DienNuoc dn
          JOIN Phong p ON dn.MaPhong = p.MaPhong
          WHERE 1=1 ";

$params = [];
if ($room_id !== '0') {
    $query .= " AND dn.MaPhong = :room_id";
    $params[':room_id'] = $room_id;
}

if ($month != 0) {
    $query .= " AND dn.Thang = :month";
    $params[':month'] = $month;
}

$currentPage = $_GET['page'] ?? 1;
$perPage = 10;
$offset = ($currentPage - 1) * $perPage;

$query .= " LIMIT :offset, :perPage";

$stmt = $dbh->prepare($query);
foreach ($params as $key => &$val) {
    $stmt->bindParam($key, $val);
}
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->bindParam(':perPage', $perPage, PDO::PARAM_INT);
$stmt->execute();
$diennuoc = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalRows = $dbh->query("SELECT FOUND_ROWS()")->fetchColumn();
$totalPages = ceil($totalRows / $perPage);

?>

<body>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <?php
            include_once __DIR__ . '/sidebar.php';
            ?>

            <div class="col px-0">
                <!-- Nội dung chính -->
                <div class=" mt-4"
                    style="max-width: 1275px; margin-left: 253px; border: 1px solid #ddd; background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);">
                    <div style="padding: 2px; background-color: rgb(219, 48, 119); border-radius: 6px;"></div>
                    <div class="container-fluid py-3 px-2" style="padding: 20px;">
                        <!-- Phần header của List of Rooms -->
                        <div class="d-flex justify-content-between align-items-center">
                            <h5>Danh sách phòng</h5>
                        </div>

                        <form id="searchForm" method="post" action="manage_diennuoc.php">
                            <div class="row g-3">
                                <div class="col-md-6 col-lg-3">
                                    <label for="room_id" class="form-label">Mã Phòng</label>
                                    <select class="form-select" id="availability" name="room_id" aria-label="Select availability">
                                        <option value="0">Tất cả</option>
                                        <?php
                                        $phongQuery = "SELECT MaPhong FROM Phong";
                                        $phongStmt = $dbh->prepare($phongQuery);
                                        $phongStmt->execute();
                                        $phongOptions = $phongStmt->fetchAll(PDO::FETCH_ASSOC);
                                        foreach ($phongOptions as $option) {
                                            echo '<option value="' . htmlspecialchars($option['MaPhong']) . '">' . htmlspecialchars($option['MaPhong']) . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <?php
                                if (!isset($_POST['search'])) {
                                    $defaultMaPhong = $phongOptions[0]['MaPhong'] ?? '';
                                    echo '<input type="hidden" name="room_id" value="' . htmlspecialchars($defaultMaPhong) . '">';
                                }
                                ?>
                                <div class="col-md-6 col-lg-3">
                                    <label for="month" class="form-label">Tháng</label>
                                    <select class="form-select" id="month" name="month" aria-label="Select availability">
                                        <option value="0">Tất cả</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                        <!-- <option value="occupied">Occupied</option> -->
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-12">
                                    <button type="submit" name="search" class="btn btn-primary" aria-label="Search rooms">
                                        <i class="bi bi-search me-2"></i>Search
                                    </button>
                                </div>
                            </div>
                        </form>

                        <div class="col-auto py-3">
                            <!-- Hiển thị table  -->
                            <?php
                            if ($totalRows === 0) {
                                echo '<div class="alert alert-warning" role="alert">Không tìm thấy dữ liệu</div>';
                            } else {

                                echo '<table class="table table-bordered table-striped">';
                                echo '<thead class="table-primary">';
                                echo '<tr>';
                                echo '<th class="text-center" width="3%">STT</th>';
                                echo '<th class="text-center" width="7%">Tháng</th>';
                                echo '<th class="text-center" width="7%">Mã phòng</th>';
                                echo '<th class="text-center" width="10%">Loại</th>';
                                echo '<th class="text-center" width="10%">Phí sử dụng của phòng (VNĐ)</th>';
                                echo '<th class="text-center" width="10%">Số tiền phải đóng của sinh viên (VNĐ)</th>';
                                echo '<th class="text-center" width="14%">Tổng số tiền phải đóng của sinh viên (VNĐ)</th>';
                                echo '<th class="text-center" width="14%">Tổng số tiền còn lại phải đóng của phòng (VNĐ)</th>';
                                echo '<th class="text-center" width="12%">Ngày đóng</th>';
                                echo '<th class="text-center" width="12%">Năm học, học kỳ</th>';
                                echo '<th class="text-center" width="8%">Hoạt Động</th>';
                                echo '</tr>';
                                echo '</thead>';
                                echo '<tbody>';
                            }
                            $stt = ($currentPage - 1) * $perPage + 1;

                            // In dữ liệu
                            foreach ($diennuoc as $row) {
                                $maPhong = $row['MaPhong'];
                                $query = "SELECT COUNT(*) as TongsoSV FROM ThuePhong WHERE MaPhong = ?";
                                $stmt = $dbh->prepare($query);
                                $stmt->bindValue(1, $maPhong);
                                $stmt->execute();
                                $tongSoSV = $stmt->fetch(PDO::FETCH_ASSOC)['TongsoSV'];
                                if ($tongSoSV <= 0) {
                                    $tongSoSV = 1;
                                }
                                $phiDienTungSV = $row['PhiDien'] / $tongSoSV;
                                $phiNuocTungSV = $row['PhiNuoc'] / $tongSoSV;
                                $tongPhiSV = $phiDienTungSV + $phiNuocTungSV;
                                echo '<tr>';
                                echo '<td class="text-center fw-bold" rowspan="2">' . $stt . '</td>';
                                echo '<td class="text-center" rowspan="2">' . htmlspecialchars($row['Thang']) . '</td>';
                                echo '<td class="text-center" rowspan="2">' . htmlspecialchars($row['MaPhong']) . '</td>';
                                echo '<td>Đơn giá điện</td>';
                                echo '<td class="text-end">' . number_format($row['PhiDien'], 0, ',', '.') . '</td>';
                                echo '<td class="text-end">' . number_format($phiDienTungSV, 0, ',', '.') . '</td>';
                                echo '<td class="text-end" rowspan="2">' . number_format($tongPhiSV, 0, ',', '.') . '</td>';
                                echo '<td class="text-end" rowspan="2">' . number_format($row['TongTien'], 0, ',', '.') . '</td>';
                                echo '<td rowspan="2">' . htmlspecialchars($row['NgayThanhToan']) . '</td>';
                                echo '<td rowspan="2">' .
                                    htmlspecialchars($row['NamHoc']) . ', ' . htmlspecialchars($row['HocKi'])
                                    . '</td>';
                                echo '<td class="text-center" rowspan="2">';
                                echo '
                                    <div class="dropdown position-relative">
                                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" onclick="toggleActionDropdown(\'actionDropdownMenu' . htmlspecialchars($stt) . '\')">
                                            Hoạt động
                                        </button>
                                        <div id="actionDropdownMenu' . htmlspecialchars($stt) . '" class="dropdown-menu position-absolute p-0" style="display: none; min-width: 100px;">
                                            <a class="dropdown-item py-2" href="add_diennuoc.php?maphong=' . htmlspecialchars($row['MaPhong']) . '">Thêm</a>
                                            <a class="dropdown-item py-2" href="edit_diennuoc.php?maphong=' . htmlspecialchars($row['MaPhong']) . '&thang=' . htmlspecialchars($row['Thang']) . '&namhoc=' . htmlspecialchars($row['NamHoc']) . '&hocki=' . htmlspecialchars($row['HocKi']) . '">Sửa</a>
                                            <a class="dropdown-item py-2" href="delete_diennuoc.php?id=' . htmlspecialchars($row['ID']) . '">Xoá</a>
                                        </div>
                                    </div>';

                                echo '</td>';
                                echo '</tr>';

                                echo '<tr>';
                                echo '<td>Đơn giá nước</td>';
                                echo '<td class="text-end">' . number_format($row['PhiNuoc'], 0, ',', '.') . '</td>';
                                echo '<td class="text-end">' . number_format($phiNuocTungSV, 0, ',', '.') . '</td>';
                                echo '</tr>';

                                $stt++;
                            }

                            echo '</tbody>';
                            echo '</table>';
                            ?>

                            <!-- Pagination -->
                            <?php
                            if ($totalPages > 1) {
                                echo '<nav aria-label="..." class="d-flex">';
                                echo '<ul class="pagination mx-auto">';
                                if ($currentPage > 1) {
                                    echo '<li class="page-item"><a class="page-link" href="?page=1">Trang đầu</a></li>';
                                    $prevPage = $currentPage - 1;
                                    echo '<li class="page-item"><a class="page-link" href="?page=' . htmlspecialchars($prevPage) . '">Previous</a></li>';
                                }

                                for ($i = 1; $i <= $totalPages; $i++) {
                                    if ($i === $currentPage) {
                                        echo '<li class="page-item active" aria-current="page"><span class="page-link">' . htmlspecialchars($i) . '</span></li>';
                                    } else {
                                        echo '<li class="page-item"><a class="page-link" href="?page=' . htmlspecialchars($i) . '">' . htmlspecialchars($i) . '</a></li>';
                                    }
                                }

                                if ($currentPage < $totalPages) {
                                    $nextPage = $currentPage + 1;
                                    echo '<li class="page-item"><a class="page-link" href="?page=' . htmlspecialchars($nextPage) . '">Next</a></li>';
                                    echo '<li class="page-item"><a class="page-link" href="?page=' . htmlspecialchars($totalPages) . '">Trang cuối</a></li>';
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
    window.onclick = function(event) {
        var dropdownMenu = document.getElementById("dropdownMenu");

        // Đóng dropdown của tên admin nếu click bên ngoài
        if (!event.target.matches('#userDropdown') && !event.target.matches('.ms-1') && !dropdownMenu.contains(event.target)) {
            dropdownMenu.style.display = "none"; // Đảm bảo đóng dropdown
        }
    }
</script>

</html>

<!-- echo '<table class="table table-bordered table-striped table-hover mt-3">';
    echo '<thead class="table-primary">';
        echo '<tr>';
            echo '<th>STT</th>';
            echo '<th>Mã phòng</th>';
            echo '<th>Tháng</th>';
            echo '<th>Loại</th>';
            echo '<th>Phí sử dụng của phòng (VNĐ)</th>';
            echo '<th>Số tiền phải đóng của sinh viên (VNĐ)</th>';
            echo '<th>Tổng số tiền phải đóng của sinh </th>';
            echo '<th>Tổng số tiền còn lại phải đóng của phòng (VNĐ)</th>';
            echo '<th>Ngày đóng</th>';
            echo '<th>Năm học</th>';
            echo '<th>học kỳ </th>';
            echo '<th>Trạng thái thánh toán</th>';
            echo '</tr>';
        echo '</thead>';
    echo '<tbody>'; -->