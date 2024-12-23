<?php
include_once __DIR__ . '/../../config/dbadmin.php';
include_once __DIR__ . '/../../partials/header.php';
include_once __DIR__ . '/../../partials/heading.php';

?>

<body>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <?php
            include_once __DIR__ . '/sidebar.php';
            ?>

            <div class="col px-0">
                <!-- Nội dung chính -->
                <div class="mt-4"
                    style="max-width: 1075px; margin-left: 273px; border: 1px solid #ddd; background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);">
                    <div style="padding: 2px; background-color: rgb(219, 48, 119); border-radius: 6px;"></div>
                    <div class="container-fluid py-3" style="padding: 20px;">
                        <!-- Phần header của List of Rooms -->
                        <div class="d-flex justify-content-between align-items-center">
                            <h5>Danh sách lớp</h5>
                            <a href="./new_class.php" class="btn text-white"
                                style="background-color: rgb(219, 48, 119);">
                                <i class="fas fa-plus me-1"></i>Thêm lớp
                            </a>
                        </div>

                        <!-- Form tìm kiếm -->
                        <form action="" method="GET" class="mt-2 mb-0">
                            <div class="row">
                                <div class="col-auto">
                                    <select name="maLop" class="form-select">
                                        <option value="">Tất cả</option>
                                        <?php
                                        // Lấy danh sách mã lớp từ cơ sở dữ liệu
                                        $query = "SELECT MaLop FROM Lop";
                                        $stmt = $dbh->query($query);
                                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                            $selected = isset($_GET['maLop']) && $_GET['maLop'] === $row['MaLop'] ? 'selected' : '';
                                            echo '<option value="' . htmlspecialchars($row['MaLop']) . '" ' . $selected . '>' . htmlspecialchars($row['MaLop']) . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                                </div>
                            </div>
                        </form>

                        <div class="col-auto py-3 ">

                            <?php
                            // Số dòng trên mỗi trang
                            $rowsPerPage = 10;
                            // Tính tổng số dòng
                            $totalRowsQuery = "SELECT COUNT(*) FROM Lop";
                            
                            // Nếu có tìm kiếm theo mã lớp, cần điều chỉnh câu truy vấn
                            if (isset($_GET['maLop']) && $_GET['maLop'] != '') {
                                $totalRowsQuery .= " WHERE MaLop = :maLop";
                            }

                            $totalRowsResult = $dbh->prepare($totalRowsQuery);

                            // Nếu có mã lớp, bind tham số
                            if (isset($_GET['maLop']) && $_GET['maLop'] != '') {
                                $totalRowsResult->bindParam(':maLop', $_GET['maLop'], PDO::PARAM_STR);
                            }

                            $totalRowsResult->execute();
                            $totalRows = $totalRowsResult->fetchColumn();

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
                            $offset = ($currentPage - 1) * $rowsPerPage;

                            // Truy vấn SQL với LIMIT và OFFSET, nếu có tìm kiếm theo mã lớp thì thêm điều kiện WHERE
                            $lopQuery = "SELECT Lop.* FROM Lop";
                            if (isset($_GET['maLop']) && $_GET['maLop'] != '') {
                                $lopQuery .= " WHERE MaLop = :maLop";
                            }
                            $lopQuery .= " LIMIT $rowsPerPage OFFSET $offset";

                            $lopStmt = $dbh->prepare($lopQuery);

                            // Nếu có mã lớp, bind tham số
                            if (isset($_GET['maLop']) && $_GET['maLop'] != '') {
                                $lopStmt->bindParam(':maLop', $_GET['maLop'], PDO::PARAM_STR);
                            }

                            $lopStmt->execute();

                            if ($lopStmt->rowCount() > 0) {
                                echo '<table class="table table-bordered table-striped table-hover mt-3">';
                                echo '<thead class="table-primary">';
                                echo '<tr>';
                                echo '<th>STT</th>';
                                echo '<th>Mã lớp</th>';
                                echo '<th>Tên lớp</th>';
                                echo '<th>Hoạt động</th>';
                                echo '</tr>';
                                echo '</thead>';
                                echo '<tbody>';

                                // Xuất dữ liệu của từng hàng
                                $stt = $offset + 1;
                                while ($row = $lopStmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo '<tr>';
                                    echo '<td>' . $stt++ . '</td>';
                                    echo '<td>' . htmlspecialchars($row["MaLop"]) . '</td>';
                                    echo '<td>' . htmlspecialchars($row["TenLop"]) . '</td>';
                                    echo '<td>
                                    <div class="dropdown position-relative text-end">
                                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" onclick="toggleActionDropdown(\'actionDropdownMenu' . htmlspecialchars($stt) . '\')">
                                            Hoạt động
                                        </button>
                                        <div id="actionDropdownMenu' . htmlspecialchars($stt) . '" class="dropdown-menu position-absolute p-0" style="display: none; min-width: 100px;">
                                            <a class="dropdown-item py-2" href="edit_class.php?maLop=' . htmlspecialchars($row['MaLop']) . '">Sửa</a>
                                            <a class="dropdown-item py-2" href="delete_class.php?maLop=' . htmlspecialchars($row['MaLop']) . '">Xoá</a>
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
    window.onclick = function (event) {
        var dropdownMenu = document.getElementById("dropdownMenu");

        // Đóng dropdown của tên admin nếu click bên ngoài
        if (!event.target.matches('#userDropdown') && !event.target.matches('.ms-1') && !dropdownMenu.contains(event.target)) {
            dropdownMenu.style.display = "none"; // Đảm bảo đóng dropdown
        }
    }

</script>

</html>