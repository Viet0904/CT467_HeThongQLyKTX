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
if (empty($_GET['service_id'])) {
    echo "<script>alert('Không tìm thấy dịch vụ.')
    window.location.href='../../admin/manageservices.php';
    </script>";
    die();
} elseif (isset($_GET['service_id']) && is_numeric($_GET['service_id'])) {
    $serviceID = (int)$_GET['service_id'];
    $query = $dbh->prepare("SELECT * FROM tblservices WHERE ID = :serviceID");
    $query->bindParam('serviceID', $serviceID, PDO::PARAM_INT);
    $query->execute();
    $serviceData = $query->fetch(PDO::FETCH_ASSOC);
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newServiceName = $_POST["contactName"];
    $newServicePrice = $_POST["servicesPrice"];
    $newCreationDate = date('Y-m-d H:i:s');
    // Thực hiện truy vấn SQL UPDATE
    $queryUpdate = $dbh->prepare("UPDATE tblservices SET ServiceName = :newServiceName, ServicePrice = :newServicePrice, CreationDate =:newCreationDate WHERE ID = :serviceID");
    $queryUpdate->bindParam(':newServiceName', $newServiceName, PDO::PARAM_STR);
    $queryUpdate->bindParam(':newServicePrice', $newServicePrice, PDO::PARAM_STR);
    $queryUpdate->bindParam(':newCreationDate', $newCreationDate, PDO::PARAM_STR);
    $queryUpdate->bindParam(':serviceID', $serviceID, PDO::PARAM_INT);
    if ($queryUpdate->execute()) {
        echo "<script> alert('Cập nhật thành công')
        window.location.href='../../admin/manageservices.php';
        </script>";
    } else {
        // Xảy ra lỗi khi cập nhật
        echo "Lỗi khi cập nhật dịch vụ: ";
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
                        <small>/ Cập nhật dịch vụ</small>
                    </h5>
                </div>
                <div class="pb-3 pt-2 px-4 pe-0">
                    <h3>Cập nhật dịch vụ</h3>
                </div>

                <div class="modal-body p-4 mb-5 border mx-4 rounded-2">
                    <form id="customerForm" method="POST">
                        <div class="mb-3">
                            <label for="contactName" class="form-label">Tên dịch vụ</label>
                            <input type="text" class="form-control border rounded-1" id="clientName" name="contactName" value="<?php echo htmlspecialchars($serviceData['ServiceName']); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="servicesPrice" class="form-label">Giá dịch vụ</label>
                            <input type="text" id="servicesPrice" name="servicesPrice" class="form-control border rounded-1" value="<?php echo htmlspecialchars($serviceData['ServicePrice']); ?>">
                        </div>

                        <div class="mb-3">
                            <label for="CreationDates" class="form-label">Ngày tạo</label>
                            <input type="text" class="form-control border rounded-1" value="<?php echo htmlspecialchars($serviceData['CreationDate']); ?>" readonly="True">
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <span href="" class="text-decoration-none text-white">Update</span>
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