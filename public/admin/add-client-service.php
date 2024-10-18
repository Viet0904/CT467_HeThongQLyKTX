<?php
// Khởi tạo phiên làm việc
if (!isset($_SESSION)) {
    session_start();
}
// Kiểm tra xem vai trò đã được lưu trong session hay chưa
if (isset($_SESSION['Role'])) {
    $role = $_SESSION['Role'];
    if ($role === 'admin') {
    } elseif ($role === 'user') {
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
// Truy vấn dữ liệu bằng Prepared Statement
if (empty($_GET['client_id'])) {
    echo "
    <script>
    alert('Không tìm thấy khách hàng')
    window.location.href='./manageclients.php';
    </script>
    ";
}
$query = $dbh->prepare("SELECT * FROM tblservices");
$query->execute();
$services = $query->fetchAll(PDO::FETCH_ASSOC);
// Duyệt qua các dịch vụ đã chọn
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['selected_services']) && is_array($_POST['selected_services']) && !empty($_POST['selected_services'])) {
        // Lấy giá trị client_id từ URL
        if (isset($_GET['client_id'])) {
            $clientID = $_GET['client_id'];
            // Tiếp tục xử lý thông tin khách hàng và quá trình cập nhật
        }
        foreach ($_POST['selected_services'] as $serviceID) {
            // Thực hiện truy vấn SQL để thêm dịch vụ vào bảng tblinvoice
            $query = $dbh->prepare("INSERT INTO tblinvoice (ClientID, ServiceId) VALUES (:clientID, :serviceID)");
            $query->bindParam(':clientID', $clientID, PDO::PARAM_INT);
            $query->bindParam(':serviceID', $serviceID, PDO::PARAM_INT);
            $query->execute();
        }
        echo "<script>alert('Thêm dịch vụ thành công.')
        window.location.href = './manageclients.php';</script>";
        die(); // Dừng chương trình sau khi xuất thông báo thành công
    } else {
        echo "<script>alert('Vui lòng chọn ít nhất một dịch vụ.');</script>";
    }
}

?>

<body>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <?php
            include_once __DIR__ . '/sidebar.php';
            ?>
            <div class="col">
                <div class="p-4">
                    <h5>
                        <a href="./dashboard.php" class="text-decoration-none"><small>Trang chủ</small></a>
                        <small>/ Chỉ định dịch vụ</small>
                    </h5>
                </div>
                <div class="pb-3 pt-2 px-4 pe-0">
                    <h3>Chỉ định dịch vụ</h3>
                </div>

                <div class="row px-5 border mx-3 rounded-2">
                    <form action="" method="POST">
                        <!-- Table Ends Here -->
                        <table id="quanly" class="table table-striped table-bordered">
                            <div class="p-3 ">
                                <thead>
                                    <tr>
                                        <th scope="col">Thứ tự</th>
                                        <th scope="col">Tên dịch vụ</th>
                                        <th scope="col">Giá dịch vụ</th>
                                        <th scope="col">Ngày tạo</th>
                                        <th scope="col">Chọn</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($services as $service) { ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($service['ID']); ?></td>
                                            <td><?php echo htmlspecialchars($service['ServiceName']); ?></td>
                                            <td><?php echo htmlspecialchars($service['ServicePrice']); ?></td>
                                            <td><?php echo htmlspecialchars($service['CreationDate']); ?></td>
                                            <td class="text-center">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="selected_services[]" value="<?php echo htmlspecialchars($service['ID']); ?>">
                                                </div>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>

                            </div>
                        </table>
                        <!-- Table Ends Here -->
                        <button type="submit" class="btn btn-primary width-30-input mb-3">
                            Save
                        </button>
                    </form>
                </div>


            </div>
        </div>
    </div>
    <?php
    include_once __DIR__ . '/../../partials/footer.php';
    ?>
</body>

</html>