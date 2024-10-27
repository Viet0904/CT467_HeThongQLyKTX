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
                        <h5 class="modal-title mt-2">Thêm phòng mới</h5>
                    </div>

                    <div class="modal-user">
                        <form action="view_room.php" method="POST">
                            <div class="row row-add mb-3 mt-1">
                                <div class="col-md-4">
                                    <label for="maphong" class="form-label"> Mã phòng</label>
                                    <input type="text" class="form-control" id="maphong">
                                </div>
                                <div class="col-md-4">
                                    <label for="MaDay" class="form-label">Mã dãy</label>
                                    <input type="text" class="form-control" id="MaDay">
                                </div>
                                <div class="col-md-4">
                                    <label for="tenphong" class="form-label">Tên phòng</label>
                                    <input type="text" class="form-control" id="tenphong">
                                </div>
                            </div>

                            <div class="row row-add mb-3">
                                <div class="col-md-4">
                                    <label for="loaiphong" class="form-label">Loại phòng</label>
                                    <select class="form-select" id="loaiphong">
                                        <option selected>Nam</option>
                                        <option value="Female">Nữ</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="dientich" class="form-label">Diện tích</label>
                                    <input type="text" class="form-control" id="dientich">
                                </div>
                                <div class="col-md-4">
                                    <label for="sogiuong" class="form-label">Số giường</label>
                                    <input type="number" class="form-control" id="sogiuong">
                                </div>
                            </div>

                            <div class="row row-add mb-3">
                                <div class="col-md-3">
                                    <label for="succhua" class="form-label">Sức chứa</label>
                                    <input type="number" class="form-control" id="succhua">
                                </div>
                                <div class="col-md-3">
                                    <label for="socho" class="form-label">Số chỗ thực tế</label>
                                    <input type="number" class="form-control" id="socho">
                                </div>
                                <div class="col-md-3">
                                    <label for="dao" class="form-label">Đã ở</label>
                                    <input type="number" class="form-control" id="dao">
                                </div>
                                <div class="col-md-3">
                                    <label for="trong" class="form-label">Còn trống</label>
                                    <input type="number" class="form-control" id="trong">
                                </div>
                            </div>

                            <div class="row row-add mb-3">
                                <div class="col-md-12">
                                    <label for="giathue" class="form-label">Giá thuê</label>
                                    <input type="text" class="form-control" id="giathue">
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