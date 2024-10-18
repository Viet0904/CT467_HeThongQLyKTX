<?php
// Khởi tạo phiên làm việc
if (!isset($_SESSION)) {
    session_start();
}
// Kiểm tra xem vai trò đã được lưu trong session hay chưa
if (isset($_SESSION['Role'])) {
    $role = $_SESSION['Role'];

    if ($role === 'admin') {
        header("Location: ./dashboard.php");
        die();
    } elseif ($role === 'user') {
        echo "<script>alert('Bạn không có quyền truy cập vào trang này.')
        window.location.href='../../user/dashboard.php';
        </script>";
        die();
    }
}

// Bắt đầu nội dung trang
ob_start();
include_once __DIR__ . '/../../partials/header.php';
include_once __DIR__ . '/../../partials/heading.php';
require_once __DIR__ . '/../../config/dbadmin.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    // Tìm tài khoản trong database
    $query = "SELECT * FROM tbladmin WHERE UserName = :username";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();
    // Nếu có tồn tại UserName trong database
    if ($stmt->rowCount() > 0) {
        // Lấy thông tin tài khoản
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        // Lấy mật khẩu đã được mã hóa trong database
        $hashedPassword = $user['Password'];
        // Sử dụng password_verify để kiểm tra mật khẩu
        if (password_verify($password, $hashedPassword)) {
            // Lưu thông tin tài khoản vào session
            $_SESSION['ID'] = $user['ID'];
            $_SESSION['Email'] = $user['Email'];
            $_SESSION['username'] = $username;
            $_SESSION['Role'] = $user['Role'];
            echo "<script>alert('Đăng nhập thành công.')
                window.location.href='./dashboard.php';
                </script>";
            exit();
        } else {
            echo "<script>alert('Tài khoản hoặc mật khẩu không chính xác. Vui lòng nhập lại.');</script>";
        }
    } else {
        echo "<script>alert('Tài khoản hoặc mật khẩu không chính xác. Vui lòng nhập lại.');</script>";
    }
}
?>

<body>
    <div class="container width-50 py-2">
        <div class="modal-dialog rounded shadow-lg p-2 m-4 bg-body rounded ">
            <div class="modal-content p-2">
                <div class="modal-header text-center d-block">
                    <h2 class="modal-title pt-3">
                        Đăng nhập
                    </h2>
                </div>

                <div id="login-form" class="modal-body">
                    <form method="POST" name="login" id="login">
                        <div class="form-group">
                            <label for="usernameInput" class="pt-2">
                                <i class="fas fa-user"></i> Tên admin:
                            </label>
                            <input class="form-control mt-1 border rounded-1" placeholder="Nhập tên Admin" id="usernameInput" name="username"></input>
                        </div>

                        <div class="form-group">
                            <label for="passwordInput" class="pt-4">
                                <i class="fas fa-eye"></i> Mật khẩu:
                            </label>
                            <input type="password" class="form-control mt-1 border rounded-1" placeholder="Nhập mật khẩu" id="passwordInput" name="password"></input>
                        </div>

                        <div class="form-group form-check pt-4">
                            <input type="checkbox" class="form-check-input">
                            <Label class="form-check-Label">Ghi nhớ tôi</Label>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3 w-100 mb-4" name="login">
                            <i class="fas fa-power-off"></i>
                            <span href="" class="text-decoration-none text-white">Đăng nhập</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <?php
    include_once __DIR__ . '/../../partials/footer.php';
    ?>
    <script src="../assets/js/checklogin_admin.js"></script>
</body>

</html>