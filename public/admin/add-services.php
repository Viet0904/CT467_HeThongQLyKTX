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
// Kiểm tra xem dữ liệu đã được gửi từ form POST chưa
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $serviceName = $_POST["serviceName"];
    $servicePrice = $_POST["servicePrice"];
    // Kiểm tra nếu dịch vụ đã tồn tại
    $checkQuery = $dbh->prepare("SELECT * FROM tblservices WHERE ServiceName = :serviceName");
    $checkQuery->bindParam(":serviceName", $serviceName, PDO::PARAM_STR);
    $checkQuery->execute();
    // Nếu dịch vụ đã tồn tại thì xuất thông báo
    if ($checkQuery->rowCount() > 0) {
        echo "<script>alert('Dịch vụ này đã tồn tại!');</script>";
    } else {
        // Thêm dịch vụ vào cơ sở dữ liệu
        $insertQuery = $dbh->prepare("INSERT INTO tblservices (ServiceName, ServicePrice) VALUES (:serviceName, :servicePrice)");
        $insertQuery->bindParam(":serviceName", $serviceName);
        $insertQuery->bindParam(":servicePrice", $servicePrice);
        if ($insertQuery->execute()) {
            echo "<script>alert('Thêm dịch vụ thành công!');</script>";
        } else {
            echo "<script>alert('Lỗi khi thêm dịch vụ.');</script>";
        }
    }
} ?>

<body>
    <div class="container-fluid">
        <div class="row flex-nowrap height-dashboard">
            <?php
            include_once __DIR__ . '/sidebar.php';
            ?>
            <div class="col ">

                <div class="p-4">
                    <h5>
                        <a href="./dashboard.php" class="text-decoration-none"><small>Trang chủ</small></a>
                        <small>/ Thêm dịch vụ</small>
                    </h5>
                </div>
                <div class="pb-3 pt-2 px-4 pe-0">
                    <h3>Thêm dịch vụ</h3>
                </div>

                <div class="modal-body p-4 border w-75 mx-4 rounded-2">
                    <form id="serviceForm" method="POST">
                        <div class="mb-3">
                            <label for="" class="form-label">Tên dịch vụ</label>
                            <input type="text" class="form-control border rounded-1" id="serviceName" name="serviceName" aria-describedby="" placeholder="Nhập tên dịch vụ">
                        </div>
                        <div class="mb-3 ">
                            <label for="" class="form-label">Giá dịch vụ</label>
                            <input type="number" class="form-control border rounded-1" id="servicePrice" name="servicePrice" placeholder="Nhập giá dịch vụ">
                        </div>
                        <button id="addService" type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php
    include_once __DIR__ . '/../../partials/footer.php';
    ?>
    <script src="../assets/js/addservices.js"></script>
</body>

</html>