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
                    style="max-width: 1250px; margin-left: 273px; border: 1px solid #ddd; background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);">
                    <div style="padding: 2px; background-color: rgb(219, 48, 119); border-radius: 6px;"></div>
                    <div class="container-fluid py-3" style="padding: 20px;">
                        <!-- Phần header của List of Rooms -->
                        <div class="d-flex justify-content-between align-items-center">
                            <h5>Danh sách đăng ký KTX</h5>


                        </div>

                        <div class="table-responsive mt-3">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>
                                            STT
                                        </th>
                                        <th>Tên Sinh Viên</th>
                                        <th>Mã số sinh viên</th>
                                        <th>Giới Tính</th>
                                        <th>Phòng Đăng Ký</th>
                                        <th>Loại Phòng</th>
                                        <th>Đã ở</th>
                                        <th>Còn trống </th>
                                        <th>Hoạt động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Tổng số dòng mỗi trang
                                    $rowsPerPage = 10;

                                    // Xác định trang hiện tại, mặc định là trang 1 nếu không có
                                    $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                                    if ($currentPage < 1) {
                                        $currentPage = 1;
                                    }

                                    // Tính toán offset cho SQL
                                    $offset = ($currentPage - 1) * $rowsPerPage;

                                    // Lấy tổng số dòng
                                    $totalRowsSql = "SELECT COUNT(*) FROM SinhVien sv LEFT JOIN Phong p ON sv.MaPhongDangKy = p.MaPhong WHERE sv.MaPhongDangKy IS NOT NULL";
                                    $totalRowsStmt = $dbh->query($totalRowsSql);
                                    $totalRows = $totalRowsStmt->fetchColumn();

                                    // Tính tổng số trang
                                    $totalPages = ceil($totalRows / $rowsPerPage);

                                    // Chuẩn bị gọi procedure
                                    $stmt = $dbh->prepare("CALL GetSinhVienPhongDangKy(:limit, :offset)");
                                    $stmt->bindParam(':limit', $rowsPerPage, PDO::PARAM_INT);
                                    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

                                    // Thực thi procedure
                                    $stmt->execute();
                                    // Lấy kết quả và hiển thị
                                    $stt = $offset + 1;
                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                        echo '<tr>';
                                        echo '<td>' . $stt++ . '</td>';
                                        echo '<td>' . htmlspecialchars($row['TenSinhVien']) . '</td>';
                                        echo '<td>' . htmlspecialchars($row['MaSinhVien']) . '</td>';
                                        echo '<td>' . htmlspecialchars($row['GioiTinh']) . '</td>';
                                        echo '<td>' . htmlspecialchars($row['MaPhongDangKy']) . '</td>';
                                        echo '<td>' . htmlspecialchars($row['LoaiPhong']) . '</td>';
                                        echo '<td>' . htmlspecialchars($row['DaO']) . '</td>';
                                        echo '<td>' . htmlspecialchars($row['ConTrong']) . '</td>';
                                        echo '<td>
                                                <form method="POST" action="">
                                                    <input type="hidden" name="MaPhong" value="' . htmlspecialchars($row["MaPhongDangKy"]) . '">
                                                    <button type="submit" name="action" value="duyet" class="btn btn-primary">Duyệt</button>
                                                    <button type="submit" name="action" value="tu_choi" class="btn btn-danger">Từ Chối</button>
                                                </form>
                                            </td>';
                                        echo '</tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Phân trang -->
                        <!-- Pagination -->
                        <?php
                        if ($totalPages > 1) {
                            echo '<nav aria-label="..." class="d-flex">';
                            echo '<ul class="pagination mx-auto">';

                            // Trang đầu và trang trước
                            if ($currentPage > 1) {
                                echo '<li class="page-item"><a class="page-link" href="?page=1">Trang đầu</a></li>';
                                $prevPage = $currentPage - 1;
                                echo '<li class="page-item"><a class="page-link" href="?page=' . $prevPage . '">Previous</a></li>';
                            }

                            // Hiển thị các trang
                            for ($i = 1; $i <= $totalPages; $i++) {
                                if ($i === $currentPage) {
                                    echo '<li class="page-item active" aria-current="page"><span class="page-link">' . $i . '</span></li>';
                                } else {
                                    echo '<li class="page-item"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
                                }
                            }

                            // Trang tiếp và trang cuối
                            if ($currentPage < $totalPages) {
                                $nextPage = $currentPage + 1;
                                echo '<li class="page-item"><a class="page-link" href="?page=' . $nextPage . '">Next</a></li>';
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