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
                        <h5 class="modal-title mt-2">Tạo tài khoản mới</h5>
                    </div>

                    <div class="modal-user">
                        <form action="view_student.php" method="POST">
                            <!-- School Details Section -->
                            <h6 class="mt-1"><b>Tài khoản</b></h6>
                            <div class="row row-add mb-3">
                                <div class="col-md-6">
                                    <label for="schoolID" class="form-label"> Sinh viên</label>
                                    <input type="text" class="form-control" id="schoolID" placeholder="Pham Gia Khang">
                                </div>
                                <div class="col-md-6">
                                    <label for="department" class="form-label">Phòng</label>
                                    <input type="text" class="form-control" id="department" placeholder="BB03211">
                                </div>
                            </div>

                            <div class="row row-add mb-3">
                                <div class="col-md-6">
                                    <label for="schoolID" class="form-label"> Giá</label>
                                    <input type="text" class="form-control" id="schoolID" placeholder="0">
                                </div>
                                <div class="col-md-6">
                                    <label for="address" class="form-label">Trạng thái</label>
                                    <select class="form-select" id="statusSelect">
                                        <option selected>Hoạt động</option>
                                        <option value="Inactive">Không hoạt động</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="text-end">
                                <a href="view_account.php" class="btn btn-primary" style="background-color: #db3077;">
                                    Lưu
                                </a>
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