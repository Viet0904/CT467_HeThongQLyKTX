<?php
include_once __DIR__ . '/../../config/dbadmin.php';
include_once __DIR__ . '/../../partials/header.php';
include_once __DIR__ . '/../../partials/heading.php';
// Xử lý form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mssv = $_POST['MSSV'] ?? '0';
    $maLop = $_POST['maLop'] ?? '0';

    $query = "SELECT SinhVien.*, Lop.TenLop, ThuePhong.MaPhong 
              FROM SinhVien 
              JOIN Lop ON SinhVien.MaLop = Lop.MaLop 
              LEFT JOIN ThuePhong ON SinhVien.MaSinhVien = ThuePhong.MaSinhVien";

    $conditions = [];
    $params = [];

    if ($mssv !== '0') {
        $conditions[] = "SinhVien.MaSinhVien = :mssv";
        $params[':mssv'] = $mssv;
    }

    if ($maLop !== '0') {
        $conditions[] = "SinhVien.MaLop = :maLop";
        $params[':maLop'] = $maLop;
    }

    if (!empty($conditions)) {
        $query .= " WHERE " . implode(' AND ', $conditions);
    }

    $totalRowsQuery = "SELECT COUNT(*) FROM ($query) AS total";
    $stmt = $dbh->prepare($totalRowsQuery);
    $stmt->execute($params);
    $totalRows = $stmt->fetchColumn();

    $rowsPerPage = 10;
    $totalPages = ceil($totalRows / $rowsPerPage);
    $currentPage = isset($_GET['page']) ? max(1, min($totalPages, (int)$_GET['page'])) : 1;
    $offset = ($currentPage - 1) * $rowsPerPage;

    $query .= " LIMIT $rowsPerPage OFFSET $offset";
    $stmt = $dbh->prepare($query);
    $stmt->execute($params);
    $result = $stmt;
} else {
    $rowsPerPage = 10;
    $totalRowsQuery = "SELECT COUNT(*) FROM SinhVien";
    $totalRows = $dbh->query($totalRowsQuery)->fetchColumn();
    $totalPages = ceil($totalRows / $rowsPerPage);
    $currentPage = isset($_GET['page']) ? max(1, min($totalPages, (int)$_GET['page'])) : 1;
    $offset = ($currentPage - 1) * $rowsPerPage;
    $query = "SELECT SinhVien.*, Lop.TenLop, ThuePhong.MaPhong 
              FROM SinhVien 
              JOIN Lop ON SinhVien.MaLop = Lop.MaLop 
              LEFT JOIN ThuePhong ON SinhVien.MaSinhVien = ThuePhong.MaSinhVien 
              LIMIT $rowsPerPage OFFSET $offset";
    $result = $dbh->query($query);
}
?>
?>

<body>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <?php include_once __DIR__ . '/sidebar.php'; ?>
            <div class="col px-0">
                <!-- Nội dung chính -->
                <div class="mt-4"
                    style="max-width: 1075px; margin-left: 273px; border: 1px solid #ddd; background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);">
                    <div style="padding: 2px; background-color: rgb(219, 48, 119); border-radius: 6px;"></div>
                    <div class="container-fluid py-3" style="padding: 20px;">
                        <!-- Phần header của List of Students -->
                        <div class="d-flex justify-content-between align-items-center">
                            <h5>Danh sách sinh viên</h5>
                            <a href="./manage_student.php" class="btn text-white" style="background-color: rgb(219, 48, 119);">
                                <i class="fas fa-plus me-1"></i>Thêm sinh viên
                            </a>
                        </div>

                        <!-- Form tìm kiếm -->
                        <form id="searchForm" method="POST" action="./student_list.php">
                            <div class="row g-3">
                                <div class="col-md-6 col-lg-3">
                                    <label for="MSSV" class="form-label">MSSV</label>
                                    <select class="form-select" id="MSSV" name="MSSV" onchange="toggleSelect('MSSV', 'maLop')">
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
                                    <label for="maLop" class="form-label">Mã Lớp</label>
                                    <select class="form-select" id="maLop" name="maLop" onchange="toggleSelect('maLop', 'MSSV')">
                                        <option value="0">Tất cả</option>
                                        <?php
                                        $classQuery = "SELECT DISTINCT MaLop FROM SinhVien";
                                        $classResult = $dbh->query($classQuery);
                                        while ($row = $classResult->fetch(PDO::FETCH_ASSOC)) {
                                            echo '<option value="' . htmlspecialchars($row['MaLop']) . '">' . htmlspecialchars($row['MaLop']) . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary" aria-label="Search students">
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

                        <div class="col-auto py-3">
                            <?php


                            if ($result->rowCount() > 0) {
                                echo '<table class="table table-bordered table-striped table-hover table-responsive mt-3">';
                                echo '<thead class="table-primary">';
                                echo '<tr><th>STT</th><th>Tên</th><th>MSSV</th><th>Giới tính</th><th>Mã lớp</th><th>Tên lớp</th><th>Hoạt động</th></tr>';
                                echo '</thead><tbody>';
                                $stt = $offset + 1;
                                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                    echo '<tr>';
                                    echo '<td>' . $stt++ . '</td>';
                                    echo '<td>' . htmlspecialchars($row["HoTen"]) . '</td>';
                                    echo '<td>' . htmlspecialchars($row["MaSinhVien"]) . '</td>';
                                    echo '<td>' . htmlspecialchars($row["GioiTinh"]) . '</td>';
                                    echo '<td>' . htmlspecialchars($row["MaLop"]) . '</td>';
                                    echo '<td>' . htmlspecialchars($row["TenLop"]) . '</td>';
                                    echo '<td>
                                        <div class="dropdown position-relative">
                                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" onclick="toggleActionDropdown(\'actionDropdownMenu' . $stt . '\')">
                                                Hoạt động
                                            </button>
                                            <div id="actionDropdownMenu' . $stt . '" class="dropdown-menu position-absolute p-0" style="display: none; min-width: 100px;">
                                                <a class="dropdown-item py-2" href="view_student.php?msv=' . htmlspecialchars($row['MaSinhVien']) . '">Xem</a>
                                                <a class="dropdown-item py-2" href="manage_student.php?msv=' . htmlspecialchars($row['MaSinhVien']) . '">Sửa</a>
                                                <a class="dropdown-item py-2" href="delete_student.php?msv=' . htmlspecialchars($row['MaSinhVien']) . '">Xoá</a>
                                            </div>
                                        </div>
                                      </td>';
                                    echo '</tr>';
                                }
                                echo '</tbody></table>';
                            } else {
                                echo "0 kết quả";
                            }
                            ?>

                            <!-- Pagination -->
                            <?php if ($totalPages > 1): ?>
                                <nav aria-label="Pagination" class="d-flex">
                                    <ul class="pagination mx-auto">
                                        <?php if ($currentPage > 1): ?>
                                            <li class="page-item"><a class="page-link" href="?page=1">Trang đầu</a></li>
                                            <li class="page-item"><a class="page-link" href="?page=<?= $currentPage - 1 ?>">Trước</a></li>
                                        <?php endif; ?>

                                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                            <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                                                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                                            </li>
                                        <?php endfor; ?>

                                        <?php if ($currentPage < $totalPages): ?>
                                            <li class="page-item"><a class="page-link" href="?page=<?= $currentPage + 1 ?>">Sau</a></li>
                                            <li class="page-item"><a class="page-link" href="?page=<?= $totalPages ?>">Trang cuối</a></li>
                                        <?php endif; ?>
                                    </ul>
                                </nav>
                            <?php endif; ?>
                            <p><strong>Tổng số:</strong> <?= $totalRows ?> dòng</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<!-- Bootstrap JS và Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gybBogGzPztE1M5rZG/8Xlqh8fATrSWJZDmmW4Ll48dWkOVbCH" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-shoIXUoVOFk60M7DuE4B54xkF5fUdOvBaj8oi2R/JFELyHgFf6lltwje5t5V5Hfp" crossorigin="anonymous"></script>

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