<?php
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
                        <h5 class="modal-title mt-2">Đăng ký sinh viên mới</h5>
                    </div>

                    <div class="modal-user">
                        <form action="view_student.php" method="POST">
                            <!-- School Details Section -->
                            <h5 class="mt-1"><b>Chi tiết trường học</b></h5>
                            <div class="row row-add mb-3">
                                <div class="col-md-4">
                                    <label for="schoolID" class="form-label"> Mã Sinh viên</label>
                                    <input type="text" class="form-control" id="schoolID">
                                </div>
                                <div class="col-md-4">
                                    <label for="department" class="form-label">Khoá</label>
                                    <input type="text" class="form-control" id="department">
                                </div>
                                <div class="col-md-4">
                                    <label for="course" class="form-label">Lớp</label>
                                    <input type="text" class="form-control" id="course">
                                </div>
                            </div>
                            <div class="row row-add mb-3">
                                <div class="col-md-4">
                                    <label for="maDay" class="form-label">Mã dãy</label>
                                    <input type="text" class="form-control" id="maDay">
                                </div>
                                <div class="col-md-4">
                                    <label for="chucVu" class="form-label">Chức vụ</label>
                                    <input type="text" class="form-control" id="chucVu">
                                </div>
                            </div>

                            <div class="row row-add mb-3">
                            <div class="col-md-4">
                                    <label for="maDay" class="form-label">Mã dãy</label>
                                    <input type="text" class="form-control" id="maDay">
                                </div>
                                <div class="col-md-4">
                                    <label for="chucVu" class="form-label">Chức vụ</label>
                                    <input type="text" class="form-control" id="chucVu">
                                </div>
                            </div>

                            <!-- Personal Information Section -->
                            <h5><b>Thông tin cá nhân</b></h5>
                            <div class="row row-add mb-3">
                                <div class="col-md-4">
                                    <label for="firstName" class="form-label">Tên</label>
                                    <input type="text" class="form-control" id="firstName">
                                </div>
                                <div class="col-md-4">
                                    <label for="ngaySinh" class="form-label">Ngày sinh</label>
<<<<<<< HEAD
                                    <input type="date" id="filter-date" value="" class="form-control">
=======
                                    <input type="text" class="form-control" id="ngaySinh">
>>>>>>> 3bd600158415006c29d4ce2eeec815f740794177
                                </div>

                            </div>

                            <div class="row row-add mb-3">
                                <div class="col-md-4">
                                    <label for="gender" class="form-label">Giới tính</label>
                                    <select class="form-select" id="gender">
                                        <option selected>Nam</option>
                                        <option value="Female">Nữ</option>
                                        <option value="Other">Khác</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="contact" class="form-label">Liên hệ #</label>
                                    <input type="text" class="form-control" id="contact">
                                </div>
                                <div class="col-md-4">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email">
                                </div>
                            </div>

                            <div class="row row-add mb-3">
                                <div class="col-md-12">
                                    <label for="address" class="form-label">Địa chỉ</label>
                                    <input type="text" class="form-control" id="address">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label for="address" class="form-label">Trạng thái</label>
                                <select class="form-select width-status" id="statusSelect">
                                    <option selected>Hoạt động</option>
                                    <option value="Inactive">Không hoạt động</option>
                                </select>
                            </div>


                            <!-- Submit Button -->
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary"
                                    style="background-color: #db3077;">Lưu</button>
                            </div>
                        </form>
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