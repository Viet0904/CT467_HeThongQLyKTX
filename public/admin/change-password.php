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
if (empty($_GET['id'])) {
    // Nếu không có tham số 'id' trong URL, chuyển hướng về trang đăng nhập.
    echo "<script>alert('Không tìm thấy id admin')
    window.location.href='./index.php';
    </script>";
    die(); // Dừng chương trình
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $adminID = $_GET["id"];
    $oldPassword = $_POST["password"]; // Lấy mật khẩu hiện tại từ biểu mẫu
    $newPassword = $_POST["newpassword"]; // Lấy mật khẩu mới từ biểu mẫu

    // Lấy mật khẩu băm từ cơ sở dữ liệu
    $queryCheckPassword = $dbh->prepare("SELECT Password FROM tbladmin WHERE ID = :id");
    $queryCheckPassword->bindParam(':id', $adminID, PDO::PARAM_INT);
    $queryCheckPassword->execute();
    $result = $queryCheckPassword->fetch();
    $dbPassword = $result['Password'];

    // Kiểm tra mật khẩu hiện tại
    if (password_verify($oldPassword, $dbPassword)) {
        // Mật khẩu cũ chính xác, tiến hành cập nhật mật khẩu mới
        $hashedNewPassword = password_hash($newPassword, PASSWORD_BCRYPT); // Băm mật khẩu mới
        // Thực hiện cập nhật mật khẩu mới
        $queryUpdatePassword = $dbh->prepare("UPDATE tbladmin SET Password = :password WHERE ID = :id");
        $queryUpdatePassword->bindParam(':password', $hashedNewPassword, PDO::PARAM_STR);
        $queryUpdatePassword->bindParam(':id', $adminID, PDO::PARAM_INT);

        // Nếu cập nhật thành công, hủy phiên làm việc và chuyển hướng về trang đăng nhập
        if ($queryUpdatePassword->execute()) {
            session_destroy(); // Hủy phiên làm việc
            echo "<script>alert('Đổi mật khẩu thành công.')
            window.location.href = './index.php';</script>";
            die(); // Dừng chương trình sau khi xuất thông báo thành công
        }
    } else {
        echo "<script>alert('Mật khẩu không chính xác.');</script>";
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
                        <small>/ Thay đổi mật khẩu</small>
                    </h5>
                </div>
                <div class="pb-3 pt-2 px-4 pe-0">
                    <h3>Thay đổi mật khẩu</h3>
                </div>

                <div class="modal-body p-4 mb-5 border mx-4 rounded-2">
                    <form id="changepassword" method="POST">
                        <div class="mb-3">
                            <label for="">Mật khẩu hiện tại</label>
                            <input name="password" id="password" type="password" class="form-control border rounded-1" id="" placeholder="">
                        </div>
                        <div class="mb-3">
                            <label for="">Mật khẩu mới</label>
                            <input name="newpassword" id="newpassword" type="password" class="form-control border rounded-1" id="" placeholder="">
                        </div>
                        <div class="mb-3">
                            <label for="">Xác nhận mật khẩu</label>
                            <input name="confirmpassword" id="confirmpassword" type="password" class="form-control border rounded-1" id="" placeholder="">
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <span href="" class="text-decoration-none text-white">Thay đổi</span>
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <?php
    include_once __DIR__ . '/../../partials/footer.php';
    ?>
    <script src="../assets/js/change-password.js"></script>
</body>

</html>