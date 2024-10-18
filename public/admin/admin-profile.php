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
// nếu không có tham số 'id' trong URL
if (empty($_GET['id'])) {
    // Nếu không có tham số 'id' trong URL, chuyển hướng về trang đăng nhập.
    echo "<script>alert('Không tìm thấy id admin')
    window.location.href='./index.php';
    </script>";
    die(); // Dừng chương trình
}
// nếu có tham số 'id' trong URL 
else {
    $adminID = $_GET["id"];
    $query = $dbh->prepare("SELECT * FROM tbladmin WHERE ID = :id");
    $query->bindParam(':id', $adminID, PDO::PARAM_INT);
    // nếu thực thi thành công
    if ($query->execute()) {
        // lấy dữ liệu
        $data = $query->fetch(PDO::FETCH_ASSOC);
    } else {
        echo "<script>alert('Lỗi truy vấn dữ liệu');</script>";
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $AdminName = $_POST["contactName"];
    $UserName = $_POST["username"];
    $MobileNumber = $_POST["mobile"];
    $Email = $_POST["email"];
    $queryUpdate = $dbh->prepare("UPDATE tbladmin SET AdminName = :adminName, UserName = :userName,
    MobileNumber = :mobileNumber, Email = :email WHERE ID = :id");
    $queryUpdate->bindParam(":adminName", $AdminName, PDO::PARAM_STR);
    $queryUpdate->bindParam(":userName", $UserName, PDO::PARAM_STR);
    $queryUpdate->bindParam(":mobileNumber", $MobileNumber, PDO::PARAM_STR);
    $queryUpdate->bindParam(":email", $Email, PDO::PARAM_STR);
    $queryUpdate->bindParam(":id", $adminID, PDO::PARAM_INT);
    if ($queryUpdate->execute()) {
        echo "<script>alert('Cập nhật thành công'); 
        window.location.href = './dashboard.php';</script>";
        die(); // Dừng chương trình sau khi xuất thông báo thành công
    } else {
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
                        <small>/ Hồ sơ</small>
                    </h5>
                </div>
                <div class="pb-3 pt-2 px-4 pe-0">
                    <h3>Hồ sơ quản trị viên</h3>
                </div>

                <div class="modal-body p-4 mb-5 border mx-4 rounded-2">
                    <form method="POST" id="admin_profile">
                        <div class="mb-3">
                            <label for="">Tên quản trị viên</label>
                            <input type="text" class="form-control border rounded-1" id="contactName" name="contactName" placeholder="" value="<?php echo htmlspecialchars($data['AdminName']) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="">Tên tài khoản</label>
                            <input type="text" class="form-control border rounded-1" id="username" name="username" placeholder="" value="<?php echo htmlspecialchars($data['UserName']) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="">Số liên lạc</label>
                            <input type="text" id="mobile" name="mobile" class="form-control border rounded-1" id="" placeholder="" value="<?php echo htmlspecialchars($data['MobileNumber']) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="">Địa chỉ email</label>
                            <input type="email" name="email" id="email" class="form-control border rounded-1" id="" placeholder="" value="<?php echo htmlspecialchars($data['Email']) ?>">
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
    <script src="../assets/js/admin-profile.js"></script>

</body>

</html>