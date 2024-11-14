<?php
include_once __DIR__ . '/../../config/dbadmin.php';
include_once __DIR__ . '/../../partials/header.php';
include_once __DIR__ . '/../../partials/heading.php';

if (isset($_GET['success']) && $_GET['success'] == 1) {
    echo "<script>alert('Xóa phòng thành công.');</script>";
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu từ biểu mẫu
    $selectedGender = isset($_POST['gender']) ? $_POST['gender'] : '';
    $selectedArea = isset($_POST['area']) ? $_POST['area'] : '';
    $selectedBlock = isset($_POST['block']) ? $_POST['block'] : '';
    $selectedAvailability = isset($_POST['availability']) ? $_POST['availability'] : '';

    // Chuyển hướng với các tham số trong query string
    $query = http_build_query([
        'gender' => $selectedGender,
        'area' => $selectedArea,
        'block' => $selectedBlock,
        'available' => $selectedAvailability,
    ]);
    header("Location: room_list.php?$query");
    exit();
}

// Lấy dữ liệu từ biểu mẫu
$selectedGender = isset($_GET['gender']) ? $_GET['gender'] : '';
$selectedArea = isset($_GET['area']) ? $_GET['area'] : '';
$selectedBlock = isset($_GET['block']) ? $_GET['block'] : '';
$selectedAvailability = isset($_GET['availability']) ? $_GET['availability'] : '';





// Mảng điều kiện WHERE và tham số
$whereConditions = [];
$queryParams = [];

// Nếu người dùng chọn giới tính
if (!empty($selectedGender)) {
    $whereConditions[] = 'p.LoaiPhong = :gender';
    $queryParams[':gender'] = $selectedGender;
}

// Nếu người dùng chọn khu
if (!empty($selectedArea)) {
    $whereConditions[] = 'k.MaKhuKTX = :area';
    $queryParams[':area'] = $selectedArea;
}

// Nếu người dùng chọn dãy
if (!empty($selectedBlock)) {
    $whereConditions[] = 'd.MaDay = :block';
    $queryParams[':block'] = $selectedBlock;
}

// Nếu người dùng chọn phòng còn trống
if ($selectedAvailability !== '') {
    if ($selectedAvailability === '1') {
        $whereConditions[] = '(p.SoChoThucTe - p.DaO) > 0';
    } else {
        $whereConditions[] = '';
    }
}

// Kết hợp các điều kiện thành chuỗi
$whereClause = '';
if (!empty($whereConditions)) {
    $whereClause = 'WHERE ' . implode(' AND ', $whereConditions);
}
// Phân trang
$limit = 10; // Số bản ghi mỗi trang
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// Đếm tổng số bản ghi để phân trang
$countQuery = "
    SELECT COUNT(*) as total
    FROM Phong p
    INNER JOIN Day d ON p.MaDay = d.MaDay
    INNER JOIN KhuKTX k ON d.MaKhuKTX = k.MaKhuKTX
    $whereClause
";
$countStmt = $dbh->prepare($countQuery);
// Gán các tham số cho câu truy vấn đếm
foreach ($queryParams as $param => $value) {
    $countStmt->bindValue($param, $value);
}
$countStmt->execute();
$totalRows = $countStmt->fetchColumn();
$totalPages = ceil($totalRows / $limit);

// Câu truy vấn lấy dữ liệu phòng
$query = "
    SELECT p.*, d.TenDay, k.TenKhuKTX
    FROM Phong p
    INNER JOIN Day d ON p.MaDay = d.MaDay
    INNER JOIN KhuKTX k ON d.MaKhuKTX = k.MaKhuKTX
    $whereClause
    LIMIT :offset, :limit
";
$stmt = $dbh->prepare($query);
// Gán các tham số cho câu truy vấn dữ liệu
foreach ($queryParams as $param => $value) {
    $stmt->bindValue($param, $value);
}
// Gán các tham số phân trang
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->execute();
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                            <a href="./manage_room.php" class="btn text-white"
                                style="background-color: rgb(219, 48, 119);">
                                <i class="fas fa-plus me-1"></i>Thêm phòng mới
                            </a>
                        </div>
                        <?php
                        // Fetch Giới Tính options
                        $genderOptions = ['Nam', 'Nữ'];

                        // Fetch Khu options from KhuKTX table
                        $khuQuery = "SELECT MaKhuKTX, TenKhuKTX FROM KhuKTX";
                        $khuStmt = $dbh->prepare($khuQuery);
                        $khuStmt->execute();
                        $khuOptions = $khuStmt->fetchAll(PDO::FETCH_ASSOC);

                        // Fetch Dãy options from Day table
                        $dayQuery = "SELECT MaDay, TenDay FROM Day";
                        $dayStmt = $dbh->prepare($dayQuery);
                        $dayStmt->execute();
                        $dayOptions = $dayStmt->fetchAll(PDO::FETCH_ASSOC);
                        ?>

                        <form id="searchForm" method="GET" action="">
                            <div class="row g-3">
                                <div class="col-md-4 col-lg-2">
                                    <label for="availability" class="form-label">Phòng</label>
                                    <select class="form-select" id="availability" name="availability" aria-label="Select availability">
                                        <option value="">Tất cả</option>
                                        <option value="1">Còn Trống</option>
                                        <!-- <option value="occupied">Occupied</option> -->
                                    </select>
                                </div>
                                <div class="col-md-4 col-lg-2">
                                    <label for="gender" class="form-label">Giới Tính</label>
                                    <select class="form-select" id="gender" name="gender" aria-label="Select gender">
                                        <option value="">Tất cả</option>
                                        <?php foreach ($genderOptions as $gender): ?>
                                            <option value="<?php echo htmlspecialchars(strtolower($gender)); ?>"><?php echo htmlspecialchars($gender); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-4 col-lg-2">
                                    <label for="area" class="form-label">Khu</label>
                                    <select class="form-select" id="area" name="area" aria-label="Select area">
                                        <option value="">Tất cả</option>
                                        <?php foreach ($khuOptions as $khu): ?>
                                            <option value="<?php echo htmlspecialchars($khu['MaKhuKTX']); ?>"><?php echo htmlspecialchars($khu['TenKhuKTX']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-4 col-lg-2">
                                    <label for="block" class="form-label">Dãy</label>
                                    <select class="form-select" id="block" name="block" aria-label="Select block">
                                        <option value="">Tất cả </option>
                                        <?php foreach ($dayOptions as $day): ?>
                                            <option value="<?php echo htmlspecialchars($day['MaDay']); ?>"><?php echo htmlspecialchars($day['TenDay']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary" aria-label="Search rooms">
                                        <i class="bi bi-search me-2"></i>Tìm kiếm
                                    </button>
                                </div>
                            </div>
                        </form>
                        <?php
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            // Lấy dữ liệu từ biểu mẫu
                            $selectedGender = isset($_POST['gender']) ? $_POST['gender'] : '';
                            $selectedArea = isset($_POST['area']) ? $_POST['area'] : '';
                            $selectedBlock = isset($_POST['block']) ? $_POST['block'] : '';
                            $selectedAvailability = isset($_POST['availability']) ? $_POST['availability'] : '';

                            // Chuyển hướng với các tham số trong query string
                            $query = http_build_query([
                                'gender' => $selectedGender,
                                'area' => $selectedArea,
                                'block' => $selectedBlock,
                                'available' => $selectedAvailability,
                            ]);
                            header("Location: room_list.php?$query");
                            exit();
                        }
                        ?>

                        <div class="col-auto py-3">
                            <?php


                            // Lấy trang hiện tại từ query string, nếu không có thì mặc định là trang 1
                            $currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;
                            if ($currentPage < 1) {
                                $currentPage = 1;
                            } elseif ($currentPage > $totalPages) {
                                $currentPage = $totalPages;
                            }

                            // Tính chỉ số bắt đầu của dòng trên trang hiện tại
                            // $offset = ($currentPage - 1) * $rowsPerPage;

                            // // Truy vấn SQL với LIMIT và OFFSET
                            // $phong = "SELECT *, SoChoConLai(MaPhong) AS ConTrong 
                            // FROM Phong 
                            // LIMIT $rowsPerPage OFFSET $offset";
                            // $result = $dbh->query($phong);

                            if (count($rooms) > 0) {
                                echo '<table class="table table-bordered table-striped table-hover mt-3">';
                                echo '<thead class="table-primary">';
                                echo '<tr>';
                                echo '<th>STT</th>';
                                echo '<th>Mã phòng</th>';
                                echo '<th>Tên phòng</th>';
                                echo '<th>Mã dãy</th>';
                                echo '<th>Đơn giá (VND)</th>';
                                echo '<th>Phòng nam/nữ</th>';
                                echo '<th>Trạng thái sử dụng</th>';
                                echo '<th>Sức chứa</th>';
                                echo '<th>Số chỗ thực tế</th>';
                                echo '<th>Đã ở</th>';
                                echo '<th>Còn trống</th>';
                                echo '<th>Hoạt động</th>';
                                echo '</tr>';
                                echo '</thead>';
                                echo '<tbody>';

                                // Xuất dữ liệu của từng hàng
                                $stt = $offset + 1;
                                foreach ($rooms as $room) {
                                    // $ConTrong = $row["SoChoThucTe"] - $row["DaO"];
                                    echo '<tr>';
                                    echo '<td>' . $stt++ . '</td>';
                                    echo '<td>' . htmlspecialchars($room["MaPhong"]) . '</td>';
                                    echo '<td>' . htmlspecialchars($room["TenPhong"]) . '</td>';
                                    echo '<td>' . htmlspecialchars($room["MaDay"]) . '</td>';
                                    echo '<td>' . htmlspecialchars(number_format($room['GiaThue'], 2)) . '</td>';
                                    echo '<td>' . htmlspecialchars($room["LoaiPhong"]) . '</td>';
                                    echo '<td>' . htmlspecialchars($room["TrangThaiSuDung"]) . '</td>';
                                    echo '<td>' . htmlspecialchars($room["SucChua"]) . '</td>';
                                    echo '<td>' . htmlspecialchars($room["SoChoThucTe"]) . '</td>';
                                    echo '<td>' . htmlspecialchars($room["DaO"]) . '</td>';
                                    echo '<td>' . htmlspecialchars($room['SoChoThucTe'] - $room['DaO']) . '</td>';
                                    echo '<td>
                                    <div class="dropdown position-relative">
                                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" onclick="toggleActionDropdown(\'actionDropdownMenu' . htmlspecialchars($stt) . '\')">
                                            Hoạt động
                                        </button>
                                        <div id="actionDropdownMenu' . htmlspecialchars($stt) . '" class="dropdown-menu position-absolute p-0" style="display: none; min-width: 100px;">
                                            <a class="dropdown-item py-2" href="view_room.php?id=' . htmlspecialchars($room['MaPhong']) . '">Xem</a>
                                            <a class="dropdown-item py-2" href="manage_room.php?id=' . htmlspecialchars($room['MaPhong']) . '">Sửa</a>
                                            <a class="dropdown-item py-2" href="delete_room.php?id=' . htmlspecialchars($room['MaPhong']) . '">Xoá</a>
                                        </div>
                                    </div>
                                  </td>';
                                    echo '</tr>';
                                }

                                echo '</tbody>';
                                echo '</table>';
                            } else {
                                echo "0 kết quả";
                            }
                            ?>

                            <!-- Pagination -->
                            <?php
                            if ($totalPages > 1) {
                                echo '<nav aria-label="..." class="d-flex">';
                                echo '<ul class="pagination mx-auto">';
                                if ($currentPage > 1) {
                                    echo '<li class="page-item"><a class="page-link" href="?page=1">Trang đầu</a></li>';
                                    $prevPage = $currentPage - 1;
                                    echo '<li class="page-item"><a class="page-link" href="?page=' . htmlspecialchars($prevPage) . '">Trước</a></li>';
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
                                    echo '<li class="page-item"><a class="page-link" href="?page=' . htmlspecialchars($nextPage) . '">Sau</a></li>';
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