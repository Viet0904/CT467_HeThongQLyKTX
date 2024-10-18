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
    echo "<script>alert('Không tìm thấy tài khoản')
    window.location.href='./dashboard.php';
    </script>";
    die();
} else {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $ClientID = $_GET['clientid'];
        $oldPassword = $_POST["password"]; // Lấy mật khẩu hiện tại từ biểu mẫu
        $newPassword = $_POST["newpassword"]; // Lấy mật khẩu mới từ biểu mẫu

        // Băm mật khẩu hiện tại và mật khẩu mới dạng bcrypt
        $hashedOldPassword = password_hash($oldPassword, PASSWORD_BCRYPT);
        $hashedNewPassword = password_hash($newPassword, PASSWORD_BCRYPT);

        // Lấy mật khẩu băm từ cơ sở dữ liệu
        $queryCheckPassword = $dbh->prepare("SELECT * FROM tblclient WHERE ClientID = :clientid");
        $queryCheckPassword->bindParam(':clientid', $ClientID, PDO::PARAM_INT);
        $queryCheckPassword->execute();

        // Gọi hàm fetch() để lấy về kết quả
        $result = $queryCheckPassword->fetch();
        $dbPassword = $result['Password'];

        // Kiểm tra mật khẩu cũ có khớp với mật khẩu trong cơ sở dữ liệu hay không
        if (password_verify($oldPassword, $dbPassword)) {
            $queryUpdatePassword = $dbh->prepare("UPDATE tblclient SET Password = :password WHERE ClientID = :clientid");
            $queryUpdatePassword->bindParam(":password", $hashedNewPassword, PDO::PARAM_STR);
            $queryUpdatePassword->bindParam(":clientid", $ClientID, PDO::PARAM_INT);

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
                    <form method="POST" id="changepassword">
                        <div class="mb-3">
                            <label for="">Mật khẩu hiện tại</label>
                            <input type="password" name="password" id="password" class="form-control border rounded-1" id="" placeholder="">
                        </div>
                        <div class="mb-3">
                            <label for="">Mật khẩu mới</label>
                            <input type="password" name="newpassword" id="newpassword" class="form-control border rounded-1" id="" placeholder="">
                        </div>
                        <div class="mb-3">
                            <label for="">Xác nhận mật khẩu</label>
                            <input type="password" name="confirmpassword" id="confirmpassword" class="form-control border rounded-1" id="" placeholder="">
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