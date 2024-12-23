<?php
include_once __DIR__ . '/../../config/dbadmin.php';
include_once __DIR__ . '/../../partials/header.php';
include_once __DIR__ . '/../../partials/heading.php';

// Thêm lớp mới
$message = '';

// Xử lý thêm lớp
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $maLop = $_POST['maLop'];
    $tenLop = $_POST['tenLop'];

    // Kiểm tra mã lớp đã tồn tại chưa
    $stmt = $dbh->prepare('SELECT COUNT(*) FROM Lop WHERE MaLop = :maLop');
    $stmt->execute([':maLop' => $maLop]);
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        $message = 'Mã lớp đã tồn tại';
    } else {
        // Thêm lớp
        $sql = "INSERT INTO Lop(MaLop, TenLop) VALUES (:maLop, :tenLop)";
        $stmt = $dbh->prepare($sql);
        $stmt->execute([':maLop' => $maLop, ':tenLop' => $tenLop]);

        // Hiển thị thông báo
        $message = "Thêm lớp thành công!";
        echo "<script>alert('$message');</script>";
        echo "<script>window.location.href='manage_class.php';</script>";
    }
}
?>

<body>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <?php include_once __DIR__ . '/sidebar.php'; ?>

            <div class="col px-0">
                <div class="my-2" style="margin-left: 260px;">
                    <div class="modal-header-1">
                        <h5 class="modal-title mt-2">Cập nhật thông tin lớp</h5>
                    </div>

                    <!-- Hiển thị thông báo -->
                    <?php if (!empty($message)): ?>
                        <div class="alert alert-info mt-3"><?php echo $message; ?></div>
                    <?php endif; ?>

                    <div class="modal-user">
                        <form action="new_class.php" method="POST">
                            <div class="row row-add mb-3">
                                <div class="col-md-4">
                                    <label for="maLop" class="form-label">Mã lớp</label>
                                    <input type="text" class="form-control mt-2" id="maLop" name="maLop" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="tenLop" class="form-label">Tên Lớp</label>
                                    <input type="text" class="form-control mt-2" id="tenLop" name="tenLop" required>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="text-end mt-2">
                                <button type="submit" class="btn btn-primary" style="background-color: #db3077;">Lưu</button>
                            </div>
                        </form>
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