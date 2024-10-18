<?php
// Khởi tạo phiên làm việc
if (!isset($_SESSION)) {
    session_start();
}
// Kiểm tra xem vai trò đã được lưu trong session hay chưa
if (isset($_SESSION['Role'])) {
    $role = $_SESSION['Role'];
    if ($role === 'user') {
    } elseif ($role === 'admin') {
        echo "<script>alert('Bạn không có quyền truy cập vào trang này.')
        window.location.href='../../admin/dashboard.php';
        </script>";
        die();
    }
} else {
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

if (empty($_GET['clientid'])) {
    echo "
    <script>
    alert('Không tìm thấy tài khoản')
    window.location.href='./dashboard.php';
    </script>
    ";
    die();
} else {
    $ClientID = $_GET['clientid'];
    $query = "SELECT * FROM tblclient WHERE ClientID = :clientid";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':clientid', $ClientID, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $contactName = $_POST['contactName'];
    $address = $_POST['address'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $notes = $_POST['w3review'];
    $query = $dbh->prepare("UPDATE tblclient SET ContactName = :contactname, Address = :address, Cellphnumber = :cellphnumber, Email = :email, Notes = :notes WHERE ClientID = :clientid");
    $query->bindParam(':contactname', $contactName, PDO::PARAM_STR);
    $query->bindParam(':address', $address, PDO::PARAM_STR);
    $query->bindParam(':cellphnumber', $mobile, PDO::PARAM_STR);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':notes', $notes, PDO::PARAM_STR);
    $query->bindParam(':clientid', $ClientID, PDO::PARAM_INT);
    $query->bindParam(':clientid', $ClientID, PDO::PARAM_INT);
    if ($query->execute()) {
        echo "<script>alert('Cập nhật thành công.')
        window.location.href = './dashboard.php';
        </script>";
    } else {
        echo "<script>alert('Cập nhật thất bại.');</script>";
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
                        <small>/ Hồ sơ</small>
                    </h5>
                </div>
                <div class="pb-3 pt-2 px-4 pe-0">
                    <h3>Hồ sơ khách hàng</h3>
                </div>

                <div class="modal-body p-4 mb-5 border mx-4 rounded-2">
                    <form method="POST" id="client_profile">
                        <div class="mb-3">
                            <label for="">Tên khách hàng</label>
                            <input type="text" class="form-control border rounded-1" id="contactName" name="contactName" placeholder="" value="<?php echo htmlspecialchars($result['ContactName']) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="">Địa chỉ</label>
                            <textarea class="border w-100 rounded-1" name="address" id="address" rows="4" cols="50"><?php echo htmlspecialchars($result['Address']) ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="">Số điện thoại</label>
                            <input type="text" class="form-control border rounded-1" id="mobile" name="mobile" placeholder="" value="<?php echo htmlspecialchars($result['Cellphnumber']) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="">Email</label>
                            <input type="email" class="form-control border rounded-1" id="email" name="email" placeholder="" value="<?php echo htmlspecialchars($result['Email']) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="">Ghi chú</label>
                            <textarea id="w3review" class="border w-100 rounded-1" name="w3review" rows="4" cols="50"><?php echo htmlspecialchars($result['Notes']) ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <span class="text-decoration-none text-white">Update</span>
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <?php
    include_once __DIR__ . '/../../partials/footer.php';
    ?>
    <script src="../assets/js/client_profile.js"></script>
</body>

</html>