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
                        <h5 class="modal-title mt-2">Chi tiết trường học</h5>
                    </div>

                    <div class="modal-user">
                        <form action="" method="POST">
                            <!-- School Details Section -->
                            <div class="row row-add">
                                <div class="col-md-4">
                                    <h5 class="mt-1"><b>Chi tiết phòng</b></h5>
                                </div>
                                <div class="col-md-8">
                                    <div class="status-toggle">
                                        <span>Hoạt động</span>
                                        <input type="checkbox" checked disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row row-add mt-3">
                                <div class="col-md-4 ">
                                    <label for="maphong"> <b>Mã phòng</b></label>
                                    <p class="mb-2 mt-1 mx-3">B2111837</p>
                                </div>
                                <div class="col-md-4">
                                    <label for="MaDay"><b>Mã dãy</b></label>
                                    <p class="mb-2 mt-1 mx-3">Hg218732</p>
                                </div>
                                <div class="col-md-4">
                                    <label for="tenphong"><b>Tên phòng</b></label>
                                    <p class="mb-2 mt-1 mx-3">Hg218732</p>
                                </div>
                            </div>
                            <div class="row row-add mt-1">
                                <div class="col-md-4">
                                    <label for="loaiphong"><b>Loại phòng</b></label>
                                    <p class="mt-1 mb-2 mx-3">Nam</p>
                                </div>
                                <div class="col-md-4">
                                    <label for="dientich"><b>Diện tích</b></label>
                                    <p class="mt-1 mb-2 mx-3"> 11,450</p>
                                </div>
                                <div class="col-md-4">
                                    <label for="sogiuong"><b>Số giường</b></label>
                                    <p class="mt-1 mb-2 mx-3">4</p>
                                </div>
                            </div>

                            <div class="row row-add">
                                <div class="col-md-4">
                                    <label for="succhua"><b>Sức chứa</b></label>
                                    <p class="mb-2 mt-1 mx-3">4</p>
                                </div>
                                <div class="col-md-4">
                                    <label for="giathue"><b>Giá thuê</b></label>
                                    <p class="mb-2 mt-1 mx-3">14,0932</p>
                                </div>
                            </div>

                            <div class="row row-add">
                                <div class="col-md-4">
                                    <label for="socho"><b>Số chỗ thực tế</b></label>
                                    <p class="mb-2 mt-1 mx-3">4</p>
                                </div>
                                <div class="col-md-4">
                                    <label for="dao"><b>Đã ở</b></label>
                                    <p class="mb-2 mt-1 mx-3">3</p>
                                </div>
                                <div class="col-md-4">
                                    <label for="trong"><b>Còn trống</b></label>
                                    <p class="mb-2 mt-1 mx-3">1</p>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="row-add d-flex justify-content-center align-items-center mt-2">
                                <div class="mx-2">
                                    <a href="manage_room.php" class="btn" style="background-color: #db3077;">
                                        <p style="color: white" class="mb-0">Sửa</p>
                                    </a>
                                </div>
                                <div class="mx-2">
                                    <a href="javascript:void(0);" class="btn btn-danger"
                                        onclick="openDeleteRoom()">Xoá</a>
                                </div>
                                <div class="mx-2">
                                    <a href="room_list.php" class="btn btn-secondary">Trở về</a>
                                </div>
                            </div>



                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Modal để xác nhận xóa phòng -->
    <div id="deleteRoomModal" class="modal-overlay" style="display: none;">
        <div class="modal-content-1">
            <h5> <b>Bạn có chắc chắn muốn xoá phòng?</b></h5>
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