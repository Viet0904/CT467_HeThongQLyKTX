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
                <div class=" mt-4"
                    style="max-width: 1075px; margin-left: 273px; border: 1px solid #ddd; background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);">
                    <div style="padding: 2px; background-color: rgb(219, 48, 119); border-radius: 6px;"></div>
                    <div class="container-fluid py-3" style="padding: 20px;">
                        <!-- Phần header của List of Rooms -->
                        <div class="d-flex justify-content-between align-items-center">
                            <h5>Danh sách phòng</h5>
                            <a href="./manage_room.php" class="btn text-white"
                                style="background-color: rgb(219, 48, 119);">
                                <i class="fas fa-plus me-1"></i>Tạo mới
                            </a>

                        </div>

                        <div class="col-auto py-3">

                            <?php
                            // Số dòng trên mỗi trang
                            $rowsPerPage = 10;
                            // Tính tổng số dòng
                            $totalRowsQuery = "SELECT COUNT(*) FROM Phong";
                            $totalRowsResult = $dbh->query($totalRowsQuery);
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

                            // Truy vấn SQL với LIMIT và OFFSET
                            $phong = "SELECT *, SoChoConLai(MaPhong) AS ConTrong 
                            FROM Phong 
                            LIMIT $rowsPerPage OFFSET $offset";
                            $result = $dbh->query($phong);

                            if ($result->rowCount() > 0) {
                                echo '<table class="table table-bordered table-striped table-hover mt-3">';
                                echo '<thead class="table-primary">';
                                echo '<tr>';
                                echo '<th>Stt</th>';
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
                                echo '</tr>';
                                echo '</thead>';
                                echo '<tbody>';

                                // Xuất dữ liệu của từng hàng
                                $stt = $offset + 1;
                                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                    // $ConTrong = $row["SoChoThucTe"] - $row["DaO"];
                                    echo '<tr>';
                                    echo '<td>' . $stt++ . '</td>';
                                    echo '<td>' . htmlspecialchars($row["MaPhong"]) . '</td>';
                                    echo '<td>' . htmlspecialchars($row["TenPhong"]) . '</td>';
                                    echo '<td>' . htmlspecialchars($row["MaDay"]) . '</td>';
                                    echo '<td>' . htmlspecialchars($row["GiaThue"]) . '</td>';
                                    echo '<td>' . htmlspecialchars($row["LoaiPhong"]) . '</td>';
                                    echo '<td>' . htmlspecialchars($row["TrangThaiSuDung"]) . '</td>';
                                    echo '<td>' . htmlspecialchars($row["SucChua"]) . '</td>';
                                    echo '<td>' . htmlspecialchars($row["SoChoThucTe"]) . '</td>';
                                    echo '<td>' . htmlspecialchars($row["DaO"]) . '</td>';
                                    echo '<td>' . htmlspecialchars($row['ConTrong']) . '</td>';
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
    window.onclick = function (event) {
        var dropdownMenu = document.getElementById("dropdownMenu");

        // Đóng dropdown của tên admin nếu click bên ngoài
        if (!event.target.matches('#userDropdown') && !event.target.matches('.ms-1') && !dropdownMenu.contains(event.target)) {
            dropdownMenu.style.display = "none"; // Đảm bảo đóng dropdown
        }
    }

</script>

</html>