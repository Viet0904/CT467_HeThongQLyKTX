<?php
include_once __DIR__ . '/../../config/dbadmin.php';
include_once __DIR__ . '/../../partials/header.php';
include_once __DIR__ . '/../../partials/heading.php';

$message = '';
// Lấy mã lớp từ URL
$maLop = isset($_GET['maLop']) ? $_GET['maLop'] : '';

// Kiểm tra nếu mã lớp không tồn tại trong URL thì chuyển hướng hoặc hiển thị thông báo
if (empty($maLop)) {
    echo "<script>alert('Vui lòng chọn mã lớp để cập nhật');</script>";
    echo "<script>location.href = 'manage_class.php';</script>";
    exit;
}

// Lấy thông tin lớp hiện tại và danh sách tất cả lớp
$stmtLop = $dbh->prepare("SELECT MaLop, TenLop FROM Lop");
$stmtLop->execute();
$lopList = $stmtLop->fetchAll(PDO::FETCH_ASSOC);

// Lấy thông tin lớp hiện tại
$currentLop = null;
foreach ($lopList as $lop) {
    if ($lop['MaLop'] === $maLop) {
        $currentLop = $lop;
        break;
    }
}

// Kiểm tra nếu lớp không tồn tại
if (!$currentLop) {
    echo "<script>alert('Lớp không tồn tại');</script>";
    echo "<script>location.href = 'manage_class.php';</script>";
    exit;
}

try {
    // Xử lý cập nhật lớp
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $newMaLop = $_POST['maLop'];
        $tenLop = $_POST['tenLop'] ?? '';

        // Cập nhật lớp
        $sql = "UPDATE Lop SET TenLop = :tenLop WHERE MaLop = :maLop";
        $stmt = $dbh->prepare($sql);
        $stmt->execute([':tenLop' => $tenLop, ':maLop' => $newMaLop]);

        // Hiển thị thông báo
        $message = "Cập nhật thành công!";
        echo "<script>alert('$message');</script>";
        echo "<script>location.href = 'manage_class.php';</script>";
    }
} catch (Exception $e) {
    $message = "Có lỗi xảy ra: " . $e->getMessage();
    echo "<script>alert('$message');</script>";
    echo "<script>location.href = 'manage_class.php';</script>";
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
                        <form action="manage_class.php?maLop=<?php echo $maLop; ?>" method="POST">
                            <div class="row row-add mb-3">
                                <div class="col-md-4">
                                    <label for="maLop" class="form-label">Mã lớp</label>
                                    <select class="form-select mt-2" id="maLop" name="maLop" required>
                                        <?php foreach ($lopList as $lop): ?>
                                            <option value="<?php echo $lop['MaLop']; ?>"
                                                <?php echo ($lop['MaLop'] == $currentLop['MaLop']) ? 'selected' : ''; ?>>
                                                <?php echo $lop['MaLop']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="tenLop" class="form-label">Tên Lớp</label>
                                    <select class="form-select mt-2" id="tenLop" name="tenLop" required>
                                        <?php foreach ($lopList as $lop): ?>
                                            <option value="<?php echo $lop['TenLop']; ?>"
                                                <?php echo ($lop['TenLop'] == $currentLop['TenLop']) ? 'selected' : ''; ?>>
                                                <?php echo $lop['TenLop']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
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