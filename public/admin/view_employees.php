<?php
include_once __DIR__ . '/../../config/dbadmin.php';
$mnv = $_GET['mnv'];
$sql = "SELECT NhanVien.*
        FROM NhanVien
        WHERE NhanVien.MaNhanVien = :mnv";
$stmt = $dbh->prepare($sql);
$stmt->execute(['mnv' => $mnv]);
$employees = $stmt->fetch(PDO::FETCH_ASSOC);

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
                <div class="my-2" style="margin-left: 260px;">
                    <div class="modal-header-1">
                        <h5 class="modal-title mt-2">Chi tiết thông tin nhân viên</h5>
                    </div>

                    <div class="modal-user mt-3">
                        <form action="" method="POST">
                            <!-- School Details Section -->
                            <div class="row row-add">
                                <div class="col-md-4">
                                    <h5 class="mt-1"><b>Thông tin cộng đồng</b></h5>
                                </div>
                            </div>
                            <div class="row row-add">
                                <div class="col-md-4 ">
                                    <label for="schoolID"> <b>Mã nhân viên</b></label>
                                    <p class="mb-2 mt-1 mx-3"><?php echo $employees['MaNhanVien']; ?></p>
                                </div>
                                <div class="col-md-4">
                                    <label for="chucVu"><b>Chức vụ</b></label>
                                    <p class="mt-1 mb-2 mx-3">
                                        <?php echo htmlspecialchars($employees['Role'] == 'Admin' ? 'Quản trị viên' : 'Nhân viên văn phòng'); ?>
                                    </p>
                                </div>
                                <div class="col-md-4">
                                    <label for="chucVu"><b>Ghi Chú</b></label>
                                    <p class="mt-1 mb-2 mx-3"><?php echo $employees['GhiChu']; ?></p>
                                </div>
                            </div>

                            <!-- Đường phân cách -->
                            <hr style="border: none; border-top: 1px solid #a9a9a9; margin: 1px 0;">

                            <!-- Personal Information Section -->
                            <h5 class="mt-3"><b>Thông tin cá nhân</b></h5>
                            <div class="row row-add">
                                <div class="col-md-3">
                                    <label for="firstName"><b>Tên</b></label>
                                    <p class="mb-2 mt-1 mx-3"><?php echo $employees['HoTen']; ?></p>
                                </div>
                                <div class="col-md-3">
                                    <label for="firstName"><b>Ngày sinh</b></label>
                                    <p class="mb-2 mt-1 mx-3"><?php echo $employees['NgaySinh']; ?></p>
                                </div>
                                <div class="col-md-3">
                                    <label for="gender"><b>Giới tính</b></label>
                                    <p class="mb-2 mt-1 mx-3"><?php echo $employees['GioiTinh']; ?></p>
                                </div>
                                <div class="col-md-3">
                                    <label for="contact"><b>Liên hệ #</b></label>
                                    <p class="mb-2 mt-1 mx-3"><?php echo $employees['SDT']; ?></p>
                                </div>
                            </div>
                            <!-- Submit Button -->
                            <div class="row-add d-flex justify-content-center align-items-center mt-2">
                                <div class="mx-2">
                                    <a href="manage_employees.php?mnv=<?php echo htmlspecialchars($mnv); ?>" class="btn" style="background-color: #db3077;">
                                        <p style="color: white" class="mb-0">Sửa</p>
                                    </a>
                                </div>
                                <div class="mx-2">
                                    <a href="delete_employees.php?mnv=<?php echo htmlspecialchars($mnv); ?>" class="btn btn-danger">Xoá</a>
                                </div>
                                <div class="mx-2">
                                    <a href="employees_list.php" class="btn btn-secondary">Trở về</a>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>

    </div>
    </div>

    <!-- Modal để xác nhận xóa phòng -->
    <div id="deleteRoomModal" class="modal-overlay" style="display: none;">
        <div class="modal-content-1">
            <h5> <b>Bạn có chắc chắn muốn xoá Nhân viên?</b></h5>
            <!-- Đường phân cách -->
            <hr style="border: none; border-top: 1px solid #a9a9a9; margin: 10px 0;">
            <div class="modal-footer">
                <button type="button" class="btn btn-danger">Xoá</button>
                <button type="button" class="btn btn-secondary" onclick="closeDeleteRoom()">Trở về</button>
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
    // Mở modal xác nhận xóa phòng
    function openDeleteRoom() {
        document.getElementById("deleteRoomModal").style.display = "flex";
    }

    // Đóng modal xác nhận xóa phòng
    function closeDeleteRoom() {
        document.getElementById("deleteRoomModal").style.display = "none";
    }
</script>

</html>