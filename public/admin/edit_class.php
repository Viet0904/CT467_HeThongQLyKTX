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

// Lấy thông tin lớp hiện tại từ cơ sở dữ liệu
$query = $dbh->prepare("SELECT * FROM Lop WHERE MaLop = :maLop");
$query->bindParam(':maLop', $maLop, PDO::PARAM_STR);
$query->execute();
$currentLop = $query->fetch(PDO::FETCH_ASSOC);

if (!$currentLop) {
    echo "Không tìm thấy lớp với mã lớp này!";
    exit;
}

// Lấy danh sách tất cả các lớp
$lopList = $dbh->query("SELECT * FROM Lop")->fetchAll(PDO::FETCH_ASSOC);

// Xử lý khi form được gửi đi
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Debug: Kiểm tra dữ liệu nhận được từ form
    var_dump($_POST);  // Kiểm tra các giá trị đã gửi
    die();  // Dừng mã tại đây để kiểm tra

    // Lấy giá trị từ form
    $maLop = $_POST['maLop'];  // Mã lớp sẽ không thay đổi
    $tenLop = $_POST['tenLop'];

    // Kiểm tra giá trị nhận được từ form
    if (empty($tenLop)) {
        $message = "Vui lòng chọn tên lớp.";
    } else {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Lấy giá trị từ form
            $maLop = $_POST['maLop'];  // Mã lớp sẽ không thay đổi
            $tenLop = $_POST['tenLop'];
        
            if (empty($tenLop)) {
                $message = "Vui lòng chọn tên lớp.";
            } else {
                try {
                    // Thực thi stored procedure để cập nhật thông tin lớp
                    $stmt = $dbh->prepare("CALL UpdateClassInfo(:maLop, :tenLop, @resultMessage)");
                
                    // Liên kết tham số vào câu lệnh
                    $stmt->bindParam(':maLop', $maLop, PDO::PARAM_STR);
                    $stmt->bindParam(':tenLop', $tenLop, PDO::PARAM_STR);
                
                    // Thực thi câu lệnh
                    $stmt->execute();
                
                    // Lấy thông báo kết quả từ OUT parameter
                    $resultMessageStmt = $dbh->query("SELECT @resultMessage AS message");
                    $result = $resultMessageStmt->fetch(PDO::FETCH_ASSOC);
                
                    // Đặt thông báo cho người dùng
                    $message = $result['message'];
                
                    // Nếu cập nhật thành công, chuyển hướng về trang quản lý lớp
                    if ($message == 'Cập nhật thông tin lớp thành công.') {
                        header("Location: manage_class.php");
                        exit;
                    }
                } catch (PDOException $e) {
                    // Xử lý lỗi nếu có
                    $message = "Lỗi khi thực thi câu lệnh SQL: " . $e->getMessage();
                }
            }
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

                    <div class="modal-user mt-3">
                        <form action="manage_class.php?maLop=<?php echo $maLop; ?>" method="POST">
                            <div class="row row-add mb-3">
                                <!-- Mã lớp: không cho phép sửa -->
                                <div class="col-md-4">
                                    <label for="maLop" class="form-label">Mã lớp</label>
                                    <input type="text" class="form-control mt-2" id="maLop" name="maLop" value="<?php echo $currentLop['MaLop']; ?>" readonly>
                                </div>

                                <!-- Tên lớp: cho phép chọn và sửa -->
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
</html>
