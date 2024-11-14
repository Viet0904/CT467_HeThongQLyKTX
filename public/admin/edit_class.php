<?php
include_once __DIR__ . '/../../config/dbadmin.php';  // Kết nối với cơ sở dữ liệu
include_once __DIR__ . '/../../partials/header.php';  // Header của trang
include_once __DIR__ . '/../../partials/heading.php';  // Heading của trang

// Kiểm tra nếu có mã lớp trong URL
$maLop = isset($_GET['maLop']) ? $_GET['maLop'] : '';
if (empty($maLop)) {
    echo "Mã lớp không hợp lệ!";
    exit;
}

// Lấy thông tin lớp hiện tại từ cơ sở dữ liệu thông qua thủ tục lưu trữ
$query = $dbh->prepare("CALL layThongTinLop(:maLopInput)");
$query->bindParam(':maLopInput', $maLop, PDO::PARAM_STR);
$query->execute();
$currentLop = $query->fetch(PDO::FETCH_ASSOC);
$query->closeCursor(); // Đóng con trỏ để chuẩn bị cho truy vấn tiếp theo

if (!$currentLop) {
    echo "Không tìm thấy lớp với mã lớp này!";
    exit;
}

// Lấy danh sách tất cả các lớp thông qua thủ tục lưu trữ
$query = $dbh->query("CALL layDanhSachLop()");
$lopList = $query->fetchAll(PDO::FETCH_ASSOC);
$query->closeCursor(); // Đóng con trỏ

// Xử lý khi form được gửi đi
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy giá trị từ form
    $tenLop = $_POST['tenLop'];

    // Kiểm tra giá trị nhận được từ form
    if (empty($tenLop)) {
        $message = "Vui lòng chọn tên lớp.";
    } else {
        try {
            // Cập nhật thông tin lớp vào cơ sở dữ liệu thông qua thủ tục lưu trữ
            $updateStmt = $dbh->prepare("CALL capNhatLop(:maLopInput, :tenLopInput)");
            $updateStmt->bindParam(':maLopInput', $maLop, PDO::PARAM_STR);
            $updateStmt->bindParam(':tenLopInput', $tenLop, PDO::PARAM_STR);

            // Kiểm tra nếu update thành công
            if ($updateStmt->execute()) {
                $message = "Cập nhật thông tin lớp thành công!";
                echo "<script type='text/javascript'>alert('$message');</script>";
                echo "<script type='text/javascript'>window.location.href = 'manage_class.php';</script>";
                exit;
            } else {
                $errorInfo = $updateStmt->errorInfo();
                $message = "Lỗi khi cập nhật thông tin lớp. Chi tiết lỗi: " . implode(", ", $errorInfo);
            }
        } catch (PDOException $e) {
            $message = "Lỗi khi thực thi câu lệnh SQL: " . $e->getMessage();
        }
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
                        <form action="?maLop=<?php echo htmlspecialchars($maLop); ?>" method="POST">
                            <div class="row row-add mb-3">
                                <!-- Mã lớp: không cho phép sửa -->
                                <div class="col-md-4">
                                    <label for="maLop" class="form-label">Mã lớp</label>
                                    <input type="text" class="form-control mt-2" id="maLop" name="maLop" value="<?php echo htmlspecialchars($currentLop['MaLop']); ?>" readonly>
                                </div>

                                <!-- Tên lớp: cho phép chọn và sửa -->
                                <div class="col-md-4">
                                    <label for="tenLop" class="form-label">Tên Lớp</label>
                                    <select class="form-select mt-2" id="tenLop" name="tenLop" required>
                                        <?php foreach ($lopList as $lop): ?>
                                            <option value="<?php echo htmlspecialchars($lop['TenLop']); ?>"
                                                <?php echo ($lop['TenLop'] == $currentLop['TenLop']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($lop['TenLop']); ?>
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
</html>
