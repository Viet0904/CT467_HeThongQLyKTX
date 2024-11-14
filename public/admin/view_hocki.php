<?php
include_once __DIR__ . '/../../config/dbadmin.php'; // Kết nối cơ sở dữ liệu
include_once __DIR__ . '/../../partials/header.php'; // Tiêu đề trang
include_once __DIR__ . '/../../partials/heading.php'; // Đường dẫn

// Truy vấn tất cả các học kỳ từ cơ sở dữ liệu
$stmt = $dbh->prepare("SELECT * FROM HocKi ORDER BY NamHoc DESC, HocKi ASC");
$stmt->execute();
$terms = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<body>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <?php include_once __DIR__ . '/sidebar.php'; ?>

            <div class="col px-0">
                <div class="my-2" style="margin-left: 260px;">
                    <div class="modal-header-1">
                        <h5 class="modal-title mt-2">Danh sách học kỳ</h5>
                    </div>

                    <div class="modal-user mt-3">
                        <table class="table table-bordered">
                            <thead class="table-primary">
                                <tr>
                                    <th>Tên học kỳ</th>
                                    <th>Năm học</th>
                                    <th>Ngày bắt đầu</th>
                                    <th>Ngày kết thúc</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($terms as $term): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($term['HocKi']); ?></td>
                                        <td><?php echo htmlspecialchars($term['NamHoc']); ?></td>
                                        <td><?php echo htmlspecialchars($term['BatDau']); ?></td>
                                        <td><?php echo htmlspecialchars($term['KetThuc']); ?></td>
                                        <td>
                                            <a href="edit_hocki.php?HocKi=<?php echo htmlspecialchars($term['HocKi']); ?>&NamHoc=<?php echo htmlspecialchars($term['NamHoc']); ?>" class="btn btn-primary btn-sm">Sửa</a>
                                            <a href="delete_hocki.php?HocKi=<?php echo htmlspecialchars($term['HocKi']); ?>&NamHoc=<?php echo htmlspecialchars($term['NamHoc']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xoá học kỳ này?');">Xóa</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <div class="mt-3">
                            <a href="add_hocki.php" action="add" class="btn btn-success">Thêm học kỳ mới</a>
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