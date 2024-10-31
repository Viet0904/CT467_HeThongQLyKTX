<?php
include_once __DIR__ . '/../../config/dbadmin.php';
$sql = "SELECT SinhVien.MaSinhVien, SinhVien.HoTen, SinhVien.MaLop, SinhVien.KhoaHoc, Lop.TenLop, SinhVien.MaPhong
        FROM SinhVien
        LEFT JOIN Lop ON SinhVien.MaLop = Lop.MaLop";
$result = $dbh->query($sql);


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
                            <h5>Danh sách sinh viên</h5>
                            <a href="./manage_student.php" class="btn text-white"
                                style="background-color: rgb(219, 48, 119);">
                                <i class="fas fa-plus me-1"></i>Tạo mới
                            </a>

                        </div>

                        <div class="table-responsive mt-3">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Tên</th>
                                        <th>Mã số sinh viên</th>
                                        <th>Mã Lớp</th>
                                        <th>Tên lớp</th>
                                        <th>Khoá</th>
                                        <th>Phòng</th>
                                        <th>Hoạt động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($result->rowCount() > 0) {
                                        $i = 1;  // Counter for row numbers
                                        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<tr>
                                                <td>{$i}</td>
                                                <td>{$row['HoTen']}</td>
                                                <td>{$row['MaSinhVien']}</td>
                                                <td>{$row['MaLop']}</td>
                                                <td>{$row['TenLop']}</td>
                                                <td>{$row['KhoaHoc']}</td>
                                                <td>{$row['MaPhong']}</td>
                                                <td>
                                                    <div class='dropdown position-relative'>
                                                        <button class='btn btn-outline-secondary dropdown-toggle' type='button' onclick=\"toggleActionDropdown('actionDropdownMenu{$i}')\">
                                                            Hoạt động
                                                        </button>
                                                        <div id='actionDropdownMenu{$i}' class='dropdown-menu position-absolute p-0' style='display: none; min-width: 100px;'>
                                                            <a class='dropdown-item py-2' href='view_student.php?msv={$row['MaSinhVien']}'>Xem</a>
                                                            <a class='dropdown-item py-2' href='manage_student.php?msv={$row['MaSinhVien']}'>Sửa</a>
                                                            <a class='dropdown-item py-2' href='delete_student.php?msv={$row['MaSinhVien']}'>Xoá</a>
                                                        </div>
                                                    </div>
                                                </td>
                                              </tr>";
                                            $i++;
                                        }
                                    } else {
                                        echo "<tr><td colspan='8'>Không có dữ liệu</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Phân trang -->
                        <div class="d-flex justify-content-between align-items-center">
                            <p class="mb-0">Xem 1 đến 7 trong 7 trang</p>
                            <nav>
                                <ul class="pagination pagination-sm mb-0">
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#">Trước</a>
                                    </li>
                                    <li class="page-item active">
                                        <a class="page-link" href="#">1</a>
                                    </li>
                                    <li class="page-item">
                                        <a class="page-link" href="#">Sau</a>
                                    </li>
                                </ul>
                            </nav>
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