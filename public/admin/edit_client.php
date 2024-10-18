<?php
// Khởi tạo phiên làm việc
if (!isset($_SESSION)) {
    session_start();
}
// Kiểm tra xem vai trò đã được lưu trong session hay chưa
if (isset($_SESSION['Role'])) {
    $role = $_SESSION['Role'];
    if ($role === 'user') {
        echo "<script>alert('Bạn không có quyền truy cập vào trang này.')
        window.location.href='../../user/dashboard.php';
        </script>";
        die();
    }
}
// nếu chưa đăng nhập thì chuyển hướng về trang đăng nhập
else {
    echo "<script>alert('Vui lòng đăng nhập.')
    window.location.href='./index.php';
    </script>";
    die();
}

// Bắt đầu nội dung trang
ob_start();
include_once __DIR__ . '/../../partials/header.php';
include_once __DIR__ . '/../../partials/heading.php';
require_once __DIR__ . '/../../config/dbadmin.php';
// Lấy giá trị client_id từ URL

if (empty($_GET['client_id'])) {
    echo "
    <script>alert('Không tìm thấy khách hàng')
    window.location.href='./manageclients.php';
    </script>
    ";
    die();
}
$clientID = $_GET['client_id'];
// Tiếp tục xử lý thông tin khách hàng và quá trình cập nhật
// Thực hiện truy vấn SQL để lấy thông tin khách hàng dựa trên $clientID
$query = $dbh->prepare("SELECT * FROM tblclient WHERE ClientID = :clientID");
$query->bindParam(':clientID', $clientID, PDO::PARAM_INT);
$query->execute();
$clientData = $query->fetch(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $clientName = $_POST['contactName'];
    $clientAddress = $_POST['address'];
    $clientPhone = $_POST['mobile'];
    $clientEmail = $_POST['email'];
    $notes = $_POST['w3review'];
    // Lấy ngày và giờ hiện tại
    $currentDateTime = date('Y-m-d H:i:s');
    // Thực hiện truy vấn SQL để cập nhật thông tin khách hàng và CreationDate
    $query = $dbh->prepare("UPDATE tblclient SET ContactName = :clientName, Address = :clientAddress, Cellphnumber = :clientPhone, Email = :clientEmail, Notes = :notes, CreationDate = :currentDateTime WHERE ClientID = :clientID");
    $query->bindParam(':clientName', $clientName, PDO::PARAM_STR);
    $query->bindParam(':clientAddress', $clientAddress, PDO::PARAM_STR);
    $query->bindParam(':clientPhone', $clientPhone, PDO::PARAM_STR);
    $query->bindParam(':clientEmail', $clientEmail, PDO::PARAM_STR);
    $query->bindParam(':notes', $notes, PDO::PARAM_STR);
    $query->bindParam(':currentDateTime', $currentDateTime, PDO::PARAM_STR);
    $query->bindParam(':clientID', $clientID, PDO::PARAM_INT);

    if ($query->execute()) {
        echo "<script>alert('Cập nhật thành công'); 
        window.location.href = './manageclients.php';</script>";
        die(); // Dừng chương trình sau khi xuất thông báo thành công
    } else {
        // Nếu có lỗi trong quá trình cập nhật
        echo "<script>alert('Cập nhật thất bại');</script>";
    }
}
?>

<body>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <?php
            include_once __DIR__ . '/sidebar.php';
            ?>
            <div class="col pr-0">

                <div class="p-4 pe-0">
                    <h5>
                        <a href="./dashboard.php" class="text-decoration-none"><small>Trang chủ</small></a>
                        <small>/ Cập nhật khách hàng</small>
                    </h5>
                </div>
                <div class="pb-3 pt-2 px-4 pe-0">
                    <h3>Cập nhật khách hàng</h3>
                </div>

                <div class="modal-body p-4 mb-5 border mx-4 rounded-2">
                    <form id="customerForm" method="POST">
                        <div class="mb-3">
                            <label for="clientName" class="form-label">Tên liên hệ</label>
                            <input type="text" class="form-control border rounded-1" id="clientName" name="contactName" value="<?php echo htmlspecialchars($clientData['ContactName']); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="clientAddress" class="form-label">Địa chỉ</label>
                            <textarea class="form-control border rounded-1 " id="clientAddress" name="address" rows="4"><?php echo htmlspecialchars($clientData['Address']); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="clientPhone" class="form-label">Số điện thoại di động</label>
                            <input type="text" class="form-control border rounded-1" id="clientPhone" name="mobile" value="<?php echo htmlspecialchars($clientData['Cellphnumber']); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="clientEmail" class="form-label">Địa chỉ email</label>
                            <input type="email" class="form-control border rounded-1" id="clientEmail" name="email" value="<?php echo htmlspecialchars($clientData['Email']); ?>">
                        </div>
                        <!-- Các trường thông tin khác -->
                        <div class="mb-3">
                            <label for="" class="form-label w-100">Ghi chú</label>
                            <textarea id="w3review" class="border w-100 rounded-1" name="w3review" rows="4" cols="50"><?php echo htmlspecialchars($clientData['Notes']); ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="" class="form-label ">Ngày tạo</label>
                            <input readonly='true' type="text" class="form-control border rounded-1" id="" value="<?php echo htmlspecialchars($clientData['CreationDate']); ?>" placeholder="">
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <span class="text-decoration-none text-white">Update</span>
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <a href="./manageclients.php" class="text-decoration-none text-white">Back</a>
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <?php
    include_once __DIR__ . '/../../partials/footer.php';
    ?>
    <script src="../assets/js/addclients.js"></script>
</body>

</html>