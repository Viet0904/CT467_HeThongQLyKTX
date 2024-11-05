<?php
include_once __DIR__ . '/../../partials/header.php';
include_once __DIR__ . '/../../partials/heading.php';
require_once __DIR__ . '/../../config/dbadmin.php';
session_start();
// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['GioiTinh'], $_SESSION['MaSinhVien'])) {
    echo "<script>
        alert('Vui lòng đăng nhập để sử dụng chức năng này.');
        window.location.href = '../index.php';";
    exit();
}
$maSinhVien = $_SESSION['MaSinhVien'];
$studentGender = $_SESSION['GioiTinh'];


// Xử lý khi người dùng gửi yêu cầu đăng ký hoặc hủy đăng ký
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['MaPhong'])) {
    $maPhong = $_POST['MaPhong'];

    if (isset($_POST['dang_ky'])) {
        // Gọi thủ tục đăng ký phòng
        $sql = "CALL proc_dangkyphong(:maSinhVien, :maPhong)";
    } elseif (isset($_POST['huy_dang_ky'])) {
        // Gọi thủ tục hủy đăng ký phòng
        $sql = "CALL proc_huyDangKyPhong(:maSinhVien, :maPhong)";
    }

    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':maSinhVien', $maSinhVien);
    $stmt->bindParam(':maPhong', $maPhong);
    try {
        $stmt->execute();
        // Lấy thông báo từ stored procedure
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $message = $result['Message'] ?? 'Thao tác thành công.';
    } catch (PDOException $e) {
        $errorInfo = $e->errorInfo;
        $sqlstate = $errorInfo[0];
        $errorMessage = $errorInfo[2];
        // Sử dụng thông báo lỗi tùy chỉnh nếu có
        if ($sqlstate == '45000') {
            $message = $errorMessage;
        } else {
            $message = 'Đã xảy ra lỗi. Vui lòng thử lại.';
        }
    }

    $stmt->closeCursor();

    // Hiển thị thông báo và làm mới trang
    echo "<script>
        alert('" . $message . "');
        window.location.href = 'room_registration.php';
    </script>";
    exit();
}


?>

<body>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <div class="col-auto" style="width: 250px; overflow:auto;">
                <?php include_once __DIR__ . '/sidebar.php'; ?>
            </div>
            <div class="col-auto py-3">

                <?php

                // Kiểm tra xem $_SESSION['GioiTinh'] đã tồn tại chưa
                if (!isset($_SESSION['GioiTinh'])) {
                    echo "Lỗi: Không tìm thấy thông tin giới tính của sinh viên trong session.";
                    exit;
                }

                $studentGender = $_SESSION['GioiTinh']; // Lấy giới tính từ session

                // Số dòng trên mỗi trang
                $rowsPerPage = 10;

                // Tính tổng số dòng
                $totalRowsQuery = "SELECT COUNT(*) FROM Phong WHERE LoaiPhong = :studentGender AND (SoChoThucTe - DaO) > 0";

                // Prepare the statement
                $totalRowsStmt = $dbh->prepare($totalRowsQuery);

                // Bind the parameter
                $totalRowsStmt->bindParam(':studentGender', $studentGender, PDO::PARAM_STR);

                // Execute the query
                $totalRowsStmt->execute();

                // Fetch the total number of rows
                $totalRows = $totalRowsStmt->fetchColumn();


                // Tính tổng số trang
                $totalPages = ceil($totalRows / $rowsPerPage);

                // Lấy trang hiện tại từ query string, nếu không có thì mặc định là trang 1
                $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                if ($currentPage < 1) {
                    $currentPage = 1;
                } elseif ($currentPage > $totalPages) {
                    $currentPage = $totalPages;
                }

                // Tính chỉ số bắt đầu của dòng trên trang hiện tại
                $offset = ($currentPage - 1) * $rowsPerPage;

                // Gọi thủ tục TimPhongConTrongGioiTinh với giá trị LIMIT và OFFSET trực tiếp trong câu truy vấn
                // Gọi stored procedure với phân trang
                $query = "CALL TimPhongConTrongGioiTinh(:gioiTinh)";
                $stmt = $dbh->prepare($query);
                $stmt->bindParam(':gioiTinh', $_SESSION['GioiTinh'], PDO::PARAM_STR);
                $stmt->execute();
                // Lấy tất cả dữ liệu vào mảng
                $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
                // Đóng con trỏ để giải phóng kết nối cho các truy vấn tiếp theo
                $stmt->closeCursor();

                if (count($rooms) > 0) {
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
                    echo '<th>Trạng Thái</th>';
                    echo '<th>Hoạt động</th>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';

                    // Xuất dữ liệu của từng hàng
                    $stt = $offset + 1;
                    foreach ($rooms as $row) {
                        // Lấy `MaPhong` từ dòng hiện tại
                        $maPhong = $row["MaPhong"];

                        // Truy vấn bảng `dangKyPhong` với `MaPhong` và `MaSinhVien`
                        $sqlDangKy = "
                            SELECT *
                            FROM dangKyPhong
                            WHERE MaPhong = :maPhong AND MaSinhVien = :maSinhVien
                            ORDER BY NgayDangKy DESC
                            LIMIT 1
                        ";
                        $stmtDangKy = $dbh->prepare($sqlDangKy);
                        $stmtDangKy->bindParam(':maPhong', $maPhong, PDO::PARAM_STR);
                        $stmtDangKy->bindParam(':maSinhVien', $maSinhVien, PDO::PARAM_STR);
                        $stmtDangKy->execute();
                        $dangKy = $stmtDangKy->fetch(PDO::FETCH_ASSOC);
                        $stmtDangKy->closeCursor();
                        // Check điều kiện hiển thị
                        if ($row["ConTrong"] > 0 && $row["LoaiPhong"] == $studentGender) {
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
                            echo '<td>' . htmlspecialchars($row["ConTrong"]) . '</td>';
                            // Hiển thị trạng thái đăng ký
                            if ($dangKy) {
                                $trangThaiDangKy = $dangKy['TrangThaiDangKy'];
                            } else {
                                $trangThaiDangKy = '';
                            }
                            echo '<td>' . htmlspecialchars($trangThaiDangKy) . '</td>';

                            if (($dangKy && $dangKy['MaPhong'] != null) && ($dangKy && $dangKy['TrangThaiDangKy'] != 'Đã Huỷ')) {
                                // The student has registered for this room
                                echo '<td>
                                    <form method="POST" action="">
                                        <input type="hidden" name="MaPhong" value="' . htmlspecialchars($row["MaPhong"]) . '">
                                        <input type="hidden" name="huy_dang_ky" value="1">
                                        <button type="submit" class="btn btn-danger">Huỷ Đăng ký</button>
                                    </form>
                                </td>';
                            } else {
                                // The student has not registered for this room
                                echo '<td>
                                    <form method="POST" action="">
                                        <input type="hidden" name="MaPhong" value="' . htmlspecialchars($row["MaPhong"]) . '">
                                        <input type="hidden" name="dang_ky" value="0">
                                        <button type="submit" class="btn btn-success">Đăng ký</button>
                                    </form>
                                </td>';
                                echo '</tr>';
                            }
                        }
                    }
                    echo '</tbody>';
                    echo '</table>';
                } else {
                    echo "Không có kết quả";
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