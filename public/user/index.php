<?php
// Khởi tạo phiên làm việc
if (!isset($_SESSION)) {
    session_start();
}

// Kiểm tra xem vai trò đã được lưu trong session hay chưa
if (isset($_SESSION['Role'])) {
    $role = $_SESSION['Role'];
    if ($role === 'user') {
        header("Location: ./dashboard.php");
        die();
    } elseif ($role === 'admin') {
        echo "<script>alert('Bạn không có quyền truy cập vào trang này.')
        window.location.href='../../admin/dashboard.php';
        </script>";
        die();
    }
}

// Bắt đầu nội dung trang
ob_start();
include_once __DIR__ . '/../../partials/header.php';
include_once __DIR__ . '/../../partials/heading.php';
require __DIR__ . '/../../config/dbadmin.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['EmailInput'];
    $password = $_POST['password'];
    try {
        $query = "SELECT * FROM tblclient WHERE Email = :email";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $hashedPassword = $user['Password'];

            // Sử dụng password_verify để kiểm tra mật khẩu
            if (password_verify($password, $hashedPassword)) {
                $_SESSION['ClientID'] = $user['ClientID'];
                $_SESSION['Email'] = $user['Email'];
                $_SESSION['ContactName'] = $user['ContactName'];
                $_SESSION['Role'] = $user['Role'];

                echo "<script>alert('Đăng nhập thành công')
                window.location.href = './dashboard.php';
                </script>";

                exit();
            } else {
                echo "<script>alert('Tài khoản hoặc mật khẩu không chính xác. Vui lòng nhập lại.');</script>";
            }
        } else {
            echo "<script>alert('Tài khoản hoặc mật khẩu không chính xác. Vui lòng nhập lại.');</script>";
        }
    } catch (PDOException $e) {
        header('Location: /../../views/errors/404.php');
        echo "Lỗi kết nối cơ sở dữ liệu: " . $e->getMessage();
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

                <div class="modal-body">
                    <form method="POST" name="login" id="login">
                        <div class="form-group">
                            <label for="EmailInput" class="pt-2">
                                <i class="fa-solid fa-envelope"></i> Email:
                            </label>
                            <input class="form-control mt-1 border rounded-1" placeholder="Nhập email của bạn" id="EmailInput" name="EmailInput"></input>
                            <span id="emailError" style="color: red;"></span>
                        </div>

                        <div class="form-group">
                            <label for="passwordInput" class="pt-4">
                                <i class="fas fa-eye"></i> Mật khẩu:
                            </label>
                            <input class="form-control mt-1 border rounded-1" placeholder="Nhập mật khẩu" id="passwordInput" name="password" type="password"></input>
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
    <script src="../assets/js/checklogin_user.js"></script>
</body>

</html>