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

                        <!-- Phần tìm kiếm và số lượng hiển thị -->
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label for="entries" class="form-label">Hiển thị</label>
                                <select class="form-select form-select-sm w-auto d-inline-block" id="entries"
                                    aria-label="Entries">
                                    <option selected>10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                                <span class="ms-2">trang</span>
                            </div>
                            <div class="col-md-6 text-end" style="margin-left: 527px">
                                <label for="search" class="form-label me-2">Tìm kiếm:</label>
                                <input type="search" class="form-control form-control-sm w-auto d-inline-block"
                                    id="search">
                            </div>
                        </div>

                        <!-- Bảng danh sách các phòng -->
                        <div class="table-responsive mt-3">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Khu</th>
                                        <th>Tên</th>
                                        <th>Loại phòng</th>
                                        <th>Số chỗ</th>
                                        <th>Đã ở</th>
                                        <th>Còn trống</th>
                                        <th>Giá</th>
                                        <th>Trạng thái</th>
                                        <th>Hoạt động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>A</td>
                                        <td>BB02101</td>
                                        <td>Nam</td>
                                        <td>4</td>
                                        <td>3</td>
                                        <td>1</td>
                                        <td>3,500.00</td>
                                        <td><span class="badge bg-success">Hoạt động</span></td>
                                        <td>
                                            <div class="dropdown position-relative">
                                                <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                                                    onclick="toggleActionDropdown('actionDropdownMenu1')">
                                                    Hoạt động
                                                </button>
                                                <div id="actionDropdownMenu1"
                                                    class="dropdown-menu position-absolute p-0"
                                                    style="display: none; min-width: 100px;">
                                                    <a class="dropdown-item py-2" href="./view_room.php">Xem</a>
                                                    <a class="dropdown-item py-2" href="./manage_room.php">Sửa</a>
                                                    <a class="dropdown-item py-2" href="javascript:void(0);"
                                                        onclick="openDeleteRoom()">Xoá</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
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