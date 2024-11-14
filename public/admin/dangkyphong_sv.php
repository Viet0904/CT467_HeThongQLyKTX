<?php
include_once __DIR__ . '/../../config/dbadmin.php';
include_once __DIR__ . '/../../partials/header.php';
include_once __DIR__ . '/../../partials/heading.php';
if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && (isset($_POST['MSSV']) && $_POST['MSSV'] !== '0' || isset($_POST['maPhong']) && $_POST['maPhong'] !== '0')
) {
    $MSSV = $_POST['MSSV'] ?? '0';

    $maPhong = $_POST['maPhong'] ?? '0';
    // lấy ra HocKi
    $currentHocKiQuery = "SELECT HocKi, NamHoc FROM HocKi WHERE NamHoc = YEAR(CURRENT_DATE) AND CURRENT_DATE BETWEEN BatDau AND KetThuc LIMIT 1";
    $currentHocKiStmt = $dbh->prepare($currentHocKiQuery);
    $currentHocKiStmt->execute();
    $currentHocKi = $currentHocKiStmt->fetch(PDO::FETCH_ASSOC);
    if (!$currentHocKi) {
        die("Không tìm thấy học kỳ hiện tại.");
    }

    $query = "SELECT SinhVien.*, Lop.TenLop, ThuePhong.MaPhong
              FROM SinhVien 
              JOIN Lop ON SinhVien.MaLop = Lop.MaLop 
              LEFT JOIN ThuePhong ON SinhVien.MaSinhVien = ThuePhong.MaSinhVien 
              WHERE ThuePhong.HocKi = :hocKi AND ThuePhong.NamHoc = :namHoc
              ";

    if ($MSSV !== '0') {
        $query .= " AND SinhVien.MaSinhVien = :MSSV";
    } else {

        $query .= " AND ThuePhong.MaPhong = :maPhong";
    }
    $rowsPerPage = 10;
    // Tính tổng số dòng cho truy vấn có điều kiện
    $countQuery = "SELECT COUNT(*) FROM ($query) AS total";
    $countStmt = $dbh->prepare($countQuery);
    $countStmt->bindParam(':hocKi', $currentHocKi['HocKi'], PDO::PARAM_STR);
    $countStmt->bindParam(':namHoc', $currentHocKi['NamHoc'], PDO::PARAM_STR);

    if ($MSSV !== '0') {
        $countStmt->bindParam(':MSSV', $MSSV, PDO::PARAM_STR);
    } else {
        $countStmt->bindParam(':maPhong', $maPhong, PDO::PARAM_STR);
    }



    $countStmt->execute();
    $totalRows = $countStmt->fetchColumn();

    // Tính tổng số trang
    $totalPages = ceil($totalRows / $rowsPerPage);

    // Lấy trang hiện tại từ query string, nếu không có thì mặc định là trang 1
    $currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;
    if ($currentPage < 1) {
        $currentPage = 1;
    } elseif ($currentPage > $totalPages) {
        $currentPage = $totalPages;
    }


    // Tính chỉ số bắt đầu của dòng trên trang hiện tại
    $offset = max(0, ($currentPage - 1) * $rowsPerPage);

    $query .= " LIMIT $rowsPerPage OFFSET $offset";

    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':hocKi', $currentHocKi['HocKi'], PDO::PARAM_STR);
    $stmt->bindParam(':namHoc', $currentHocKi['NamHoc'], PDO::PARAM_STR);

    if ($MSSV !== '0') {
        $stmt->bindParam(':MSSV', $MSSV, PDO::PARAM_STR);
    }

    if ($maPhong !== '0') {
        $stmt->bindParam(':maPhong', $maPhong, PDO::PARAM_STR);
    }

    $stmt->execute();
    $result = $stmt;
} else {
    // lấy ra HocKi
    $currentHocKiQuery = "SELECT HocKi, NamHoc FROM HocKi WHERE NamHoc = YEAR(CURRENT_DATE) AND CURRENT_DATE BETWEEN BatDau AND KetThuc LIMIT 1";
    $currentHocKiStmt = $dbh->prepare($currentHocKiQuery);
    $currentHocKiStmt->execute();
    $currentHocKi = $currentHocKiStmt->fetch(PDO::FETCH_ASSOC);
    if (!$currentHocKi) {
        die("Không tìm thấy học kỳ hiện tại.");
    }

    // Tính tổng số dòng
    $rowsPerPage = 10;
    $totalRowsQuery = "SELECT COUNT(*) FROM SinhVien 
    JOIN ThuePhong ON SinhVien.MaSinhVien = ThuePhong.MaSinhVien 
    WHERE ThuePhong.HocKi = :hocKi AND ThuePhong.NamHoc = :namHoc";
    $totalRowsStmt = $dbh->prepare($totalRowsQuery);
    $totalRowsStmt->execute([
        ':hocKi' =>  $currentHocKi['HocKi'],
        ':namHoc' =>  $currentHocKi['NamHoc'],
    ]);
    $totalRows = $totalRowsStmt->fetchColumn();
    // Tính tổng số trang
    $totalPages = ceil($totalRows / $rowsPerPage);

    // Lấy trang hiện tại từ query string, nếu không có thì mặc định là trang 1
    $currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;
    if ($currentPage < 1) {
        $currentPage = 1;
    } elseif ($currentPage > $totalPages) {
        $currentPage = $totalPages;
    }
    $hocKi = $currentHocKi['HocKi'];
    $namHoc = $currentHocKi['NamHoc'];
    $offset = max(0, ($currentPage - 1) * $rowsPerPage);
    // Truy vấn SQL với LIMIT và OFFSET và điều kiện học kỳ hiện tại
    $sinhvien = "SELECT SinhVien.*, Lop.TenLop, ThuePhong.MaPhong
                    FROM SinhVien 
                    JOIN Lop ON SinhVien.MaLop = Lop.MaLop 
                    LEFT JOIN ThuePhong ON SinhVien.MaSinhVien = ThuePhong.MaSinhVien 
                    WHERE ThuePhong.HocKi = :hocKi AND ThuePhong.NamHoc = :namHoc
                    LIMIT :rowsPerPage OFFSET :offset";

    $stmt = $dbh->prepare($sinhvien);
    $stmt->bindParam(':hocKi', $hocKi, PDO::PARAM_STR);
    $stmt->bindParam(':namHoc', $namHoc, PDO::PARAM_STR);
    $stmt->bindValue(':rowsPerPage', (int)$rowsPerPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

    try {
        $stmt->execute();
        $result = $stmt;
    } catch (PDOException $e) {
        echo "Xảy ra lỗi: " . $e->getMessage();
        $result = null;
    }
}
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
                    style="max-width: 1075px; margin-left: 273px; border: 1px solid #ddd; background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);">
                    <div style="padding: 2px; background-color: rgb(219, 48, 119); border-radius: 6px;"></div>
                    <div class="container-fluid py-3" style="padding: 20px;">
                        <!-- Phần header của List of Rooms -->
                        <div class="d-flex justify-content-between align-items-center">
                            <h5>Danh sách thuê phòng</h5>
                            <a href="./thuephong.php" class="btn text-white"
                                style="background-color: rgb(219, 48, 119);">
                                <i class="fas fa-plus me-1"></i>Đăng ký thuê phòng
                            </a>
                        </div>
                        <form id="searchForm" method="POST" action="">
                            <div class="row g-3">
                                <div class="col-md-6 col-lg-3">
                                    <label for="MSSV" class="form-label">MSSV</label>
                                    <select class="form-select" id="MSSV" name="MSSV" aria-label="Select MSSV" onchange="toggleSelect('MSSV', 'maPhong')">
                                        <option value="0">Tất cả</option>
                                        <?php
                                        $sinhvienQuery = "SELECT MaSinhVien FROM SinhVien";
                                        $sinhvienResult = $dbh->query($sinhvienQuery);
                                        while ($row = $sinhvienResult->fetch(PDO::FETCH_ASSOC)) {
                                            echo '<option value="' . htmlspecialchars($row['MaSinhVien']) . '">' . htmlspecialchars($row['MaSinhVien']) . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="col-md-6 col-lg-3">
                                    <label for="maPhong" class="form-label">Mã Phòng</label>
                                    <select class="form-select" id="maPhong" name="maPhong" aria-label="Select Room" onchange="toggleSelect('maPhong', 'MSSV')">
                                        <option value=" 0">Tất cả</option>
                                        <?php
                                        $phongQuery = "SELECT MaPhong FROM Phong";
                                        $phongResult = $dbh->query($phongQuery);
                                        while ($row = $phongResult->fetch(PDO::FETCH_ASSOC)) {
                                            echo '<option value="' . htmlspecialchars($row['MaPhong']) . '">' . htmlspecialchars($row['MaPhong']) . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-search me-2"></i>Tìm kiếm
                                    </button>
                                </div>
                            </div>
                        </form>
                        <script>
                            function toggleSelect(selectedId, otherId) {
                                var selected = document.getElementById(selectedId);
                                var other = document.getElementById(otherId);
                                other.disabled = selected.value !== '0';
                            }
                        </script>
                        <div class="col-auto py-3 ">
                            <?php
                            $selectedMSSV = isset($_POST['MSSV']) ? $_POST['MSSV'] : '0';
                            $selectedMaPhong = isset($_POST['maPhong']) ? $_POST['maPhong'] : '0';

                            // Base query
                            $sinhvien = "SELECT SinhVien.*, Lop.TenLop, ThuePhong.MaPhong 
                                        FROM SinhVien 
                                        JOIN Lop ON SinhVien.MaLop = Lop.MaLop 
                                        LEFT JOIN ThuePhong ON SinhVien.MaSinhVien = ThuePhong.MaSinhVien";

                            // Add search conditions
                            $conditions = [];
                            if ($selectedMSSV !== '0') {
                                $conditions[] = "SinhVien.MaSinhVien = :selectedMSSV";
                            }
                            if ($selectedMaPhong !== '0') {
                                $conditions[] = "ThuePhong.MaPhong = :selectedMaPhong";
                            }

                            // Append conditions to the query
                            if (!empty($conditions)) {
                                $sinhvien .= " WHERE " . implode(" AND ", $conditions);
                            }

                            // Add pagination
                            $sinhvien .= " LIMIT :rowsPerPage OFFSET :offset";

                            // Prepare and bind parameters
                            $stmt = $dbh->prepare($sinhvien);
                            if ($selectedMSSV !== '0') {
                                $stmt->bindParam(':selectedMSSV', $selectedMSSV, PDO::PARAM_STR);
                            }
                            if ($selectedMaPhong !== '0') {
                                $stmt->bindParam(':selectedMaPhong', $selectedMaPhong, PDO::PARAM_STR);
                            }
                            $stmt->bindParam(':rowsPerPage', $rowsPerPage, PDO::PARAM_INT);
                            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

                            $stmt->execute();
                            // Display results
                            if ($stmt->rowCount() > 0) {
                                echo '<table class="table table-bordered table-striped table-hover mt-3">';
                                echo '<thead class="table-primary">';
                                echo '<tr>';
                                echo '<th>STT</th>';
                                echo '<th>Tên</th>';
                                echo '<th>MSSV</th>';
                                echo '<th>Giới tính</th>';
                                echo '<th>Mã Phòng</th>';
                                echo '<th>Hoạt động</th>';
                                echo '</tr>';
                                echo '</thead>';
                                echo '<tbody>';

                                $stt = $offset + 1;
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo '<tr>';
                                    echo '<td>' . $stt++ . '</td>';
                                    echo '<td>' . htmlspecialchars($row["HoTen"]) . '</td>';
                                    echo '<td>' . htmlspecialchars($row["MaSinhVien"]) . '</td>';
                                    echo '<td>' . htmlspecialchars($row["GioiTinh"]) . '</td>';
                                    echo '<td>' . htmlspecialchars($row["MaPhong"]) . '</td>';
                                    echo '<td>
                                    <div class="dropdown position-relative">
                                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" onclick="toggleActionDropdown(\'actionDropdownMenu' . htmlspecialchars($stt) . '\')">
                                            Hoạt động
                                        </button>
                                        <div id="actionDropdownMenu' . htmlspecialchars($stt) . '" class="dropdown-menu position-absolute p-0" style="display: none; min-width: 100px;">
                                            <a class="dropdown-item py-2" href="view_thuephong.php?msv=' . htmlspecialchars($row['MaSinhVien']) . '">Xem</a>
                                            <a class="dropdown-item py-2" href="manage_sv_thuephong.php?msv=' . htmlspecialchars($row['MaSinhVien']) . '">Sửa</a>
                                        </div>
                                    </div>
                                </td>';
                                    echo '</tr>';
                                }

                                echo '</tbody>';
                                echo '</table>';
                            } else {
                                echo "Không có kết quả nào";
                            }

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