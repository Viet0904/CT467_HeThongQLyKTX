<?php
include_once __DIR__ . '/../../partials/header.php';
include_once __DIR__ . '/../../partials/user/heading.php';
require_once __DIR__ . '/../../config/dbadmin.php';
session_start();
$maSinhVien = $_SESSION['MaSinhVien'];

$query = "SELECT SinhVien.*, ThuePhong.MaPhong, ThuePhong.BatDau, ThuePhong.KetThuc, ThuePhong.GiaThueThucTe 
            FROM SinhVien 
            LEFT JOIN ThuePhong ON SinhVien.MaSinhVien = ThuePhong.MaSinhVien 
            WHERE SinhVien.MaSinhVien = ?";
$stmt = $dbh->prepare($query);
$stmt->bindValue(1, $maSinhVien);
$stmt->execute();
$sinhVien = $stmt->fetch(PDO::FETCH_ASSOC);
$maPhong = $sinhVien['MaPhong'];

$query = "SELECT 
            dn.*
          FROM DienNuoc dn
          JOIN Phong p ON dn.MaPhong = p.MaPhong
          WHERE p.MaPhong = ?";
$stmt = $dbh->prepare($query);
$stmt->bindValue(1, $maPhong);
$stmt->execute();
$diennuoc = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalRows = count($diennuoc);
$currentPage = $_GET['page'] ?? 1;
$perPage = 10;
$totalPages = ceil($totalRows / $perPage);
$offset = ($currentPage - 1) * $perPage;

$query .= " LIMIT $offset, $perPage";

$stmt = $dbh->prepare($query);
$stmt->bindValue(1, $maPhong);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
$updateSuccess = false;

if (isset($_POST['payButtonClicked']) && $_POST['payButtonClicked'] == 'true') {
    $thang = $_POST['Thang'];
    $maPhong = $_POST['MaPhong'];
    $hocKi = $_POST['MaPhong'];
    $hocKi = $_POST['HocKi'];
    $namHoc = $_POST['NamHoc'];
    try {
        $stmt = $dbh->prepare("CALL ThanhToanDienNuoc(?, ?, ?, ?, @p_Message, @p_ErrorCode)");
        $stmt->bindValue(1, $maPhong);
        $stmt->bindValue(2, $thang);
        $stmt->bindValue(3, $namHoc);
        $stmt->bindValue(4, $hocKi);
        $stmt->execute();

        $stmt = $dbh->query("SELECT @p_Message AS message, @p_ErrorCode AS errorCode");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $message = $result['message'];
        $errorCode = $result['errorCode'];

        if ($errorCode == 0) {
            echo "<script>
                alert('$message');
                window.location.href = window.location.href;
                  </script>";
        } else {
            echo "<script>alert('$message');</script>";
        }
    } catch (PDOException $e) {
        $errorMessage = 'Đã xảy ra lỗi: ' . $e->getMessage();
        echo "<script>alert('$errorMessage');</script>";
    }
}
?>

<body>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <div class="col-auto" style="width: 250px; overflow:auto;">
                <?php include_once __DIR__ . '/sidebar.php'; ?>
            </div>

            <div class="col px-0">
                <!-- Nội dung chính -->
                <div class=" mt-4"
                    style="max-width: 1275px; margin-left: 2px; border: 1px solid #ddd; background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);">
                    <div style="padding: 2px; background-color: rgb(219, 48, 119); border-radius: 6px;"></div>
                    <div class="container-fluid py-3 px-2" style="padding: 20px;">
                        <!-- Phần header của List of Rooms -->
                        <div class="d-flex justify-content-between align-items-center">
                            <h5>Danh sách phòng</h5>
                        </div>



                        <div class="col-auto py-3">
                            <!-- Hiển thị table  -->
                            <?php
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
                            echo '<th class="text-center" width="8%">Thanh Toán</th>';
                            echo '</tr>';
                            echo '</thead>';
                            echo '<tbody>';

                            $stt = 1;

                            // In dữ liệu
                            foreach ($diennuoc as $row) {
                                echo '<tr>';
                                echo '<td class="text-center fw-bold" rowspan="2">' . $stt . '</td>';
                                echo '<td class="text-center" rowspan="2">' . htmlspecialchars($row['Thang']) . '</td>';
                                echo '<td class="text-center" rowspan="2">' . htmlspecialchars($row['MaPhong']) . '</td>';
                                echo '<td>Đơn giá điện</td>';
                                echo '<td class="text-end">' . number_format($row['PhiDien'], 0, ',', '.') . '</td>';
                                echo '<td class="text-end">' . number_format($row['PhiDien'], 0, ',', '.') . '</td>';
                                echo '<td class="text-end" rowspan="2">' . number_format($row['TongTien'], 0, ',', '.') . '</td>';
                                echo '<td class="text-end" rowspan="2">' . number_format($row['TongTien'], 0, ',', '.') . '</td>';
                                echo '<td rowspan="2">' . htmlspecialchars($row['NgayThanhToan']) . '</td>';
                                echo '<td rowspan="2">' .
                                    htmlspecialchars($row['NamHoc']) . ', ' . htmlspecialchars($row['HocKi'])
                                    . '</td>';
                                echo '<td class="text-center" rowspan="2">';
                                if ($row['NgayThanhToan'] == null) {
                                    echo '
                                    <form method="post">
                                        <input type="hidden" name="payButtonClicked" value="true">
                                        <input type="hidden" name="Thang" value="' . htmlspecialchars($row['Thang']) . '">
                                        <input type="hidden" name="MaPhong" value="' . htmlspecialchars($row['MaPhong']) . '">
                                        <input type="hidden" name="NamHoc" value="' . htmlspecialchars($row['NamHoc']) . '">
                                        <input type="hidden" name="HocKi" value="' . $row['HocKi'] . '">
                                        <button class="btn btn-success" type="submit">Thanh toán</button>
                                    </form>';
                                } else {
                                    echo '
                                    <button class="btn btn-success" type="button" disabled>
                                            Đã thanh toán
                                        </button>';
                                }


                                echo '</td>';
                                echo '</tr>';

                                echo '<tr>';
                                echo '<td>Đơn giá nước</td>';
                                echo '<td class="text-end">' . number_format($row['PhiNuoc'], 0, ',', '.') . '</td>';
                                echo '<td class="text-end">' . number_format($row['PhiNuoc'], 0, ',', '.') . '</td>';
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