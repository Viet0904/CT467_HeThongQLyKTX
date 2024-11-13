<?php
require_once __DIR__ . '/../config/dbadmin.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['MSSV'];
    $username = $_POST['username'];
    $email = $_POST['Email'];
    $gender = $_POST['gender'];
    $password = $_POST['password'] ?? '';

    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    // Check if student_id exists in the SinhVien table (case-insensitive)
    $sql = "SELECT password FROM SinhVien WHERE LOWER(MaSinhVien) = LOWER(?) AND LOWER(Email) = LOWER(?) AND LOWER(HoTen) = LOWER(?) AND GioiTinh = ?";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([strtolower($student_id), strtolower($email), strtolower($username), $gender]);

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        echo "<script>alert('Thông tin không chính xác');</script>";
    } else {
        // Check if the password is correct
        $row = $result;
        if (password_verify($_POST['password'], $row['password'])) {
            echo "<script>alert('Đăng ký thành công');</script>";
        } else {
            echo "<script>alert('Mật khẩu không đúng');</script>";
        }
    }
}






?>
<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý khách hàng</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0-alpha1/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/responsive.css">
    <!-- Option 1: Include in HTML -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <style>
        body {
            background: url('./assets/images/anhktx.jpg') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
        }

        #page-title {
            text-shadow: 6px 4px 7px black;
            font-size: 3.5em;
            color: #fff4f4 !important;
            background: #8080801c;
        }
    </style>
</head>

<body>
    <h1 class="text-center text-white px-4 py-5" id="page-title">Hệ thống quản lí Ký túc xá<b></b></h1>
    <div class="container w-50 py-2">
        <div class="modal-dialog rounded shadow-lg p-2 m-4 bg-body rounded ">
            <div class="modal-content p-2">
                <div class="modal-header text-center d-block">
                    <h2 class="modal-title pt-3">
                        Đăng ký
                    </h2>
                </div>

                <div id="login-form" class="modal-body">
                    <form action="./register.php" method="POST" name="login" id="login">
                        <div class="form-group">
                            <label for="masinhvien" name="masinhvien" class="pt-2">
                                <i class="fas fa-user"></i> Mã Sinh Viên:
                            </label>
                            <input class="form-control mt-1 border rounded-1" placeholder="Nhập Mã Sinh Viên" id="MSSV" name="MSSV"></input>
                        </div>
                        <div class="form-group">
                            <label for="usernameInput" class="pt-2">
                                <i class="fas fa-user"></i> Họ Tên:
                            </label>
                            <input class="form-control mt-1 border rounded-1" placeholder="Nhập Họ và Tên" id="usernameInput" name="username"></input>
                        </div>
                        <div class="form-group">
                            <label for="usernameInput" class="pt-2">
                                <i class="fas fa-user"></i> Email:
                            </label>
                            <input class="form-control mt-1 border rounded-1" placeholder="Nhập Email" id="Email" name="Email"></input>
                        </div>

                        <div class="form-group">
                            <label for="gender" class="pt-2">
                                <i class="fas fa-venus-mars"></i> Giới tính:
                            </label>
                            <select class="form-control mt-1 border rounded-1" id="gender" name="gender">
                                <option value="Nam">Nam</option>
                                <option value="Nữ">Nữ</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="passwordInput" class="pt-4">
                                <i class="fas fa-eye"></i> Mật khẩu:
                            </label>
                            <input type="password" class="form-control mt-1 border rounded-1" placeholder="Nhập mật khẩu" id="passwordInput" name="password"></input>
                        </div>

                        <div class="form-group form-check pt-4 d-flex justify-content-between align-items-center">
                            <div>
                                <input type="checkbox" class="d-none form-check-input none" id="rememberMe">
                                <label class="d-none form-check-label " for="rememberMe">Ghi nhớ tôi</label>
                            </div>
                            <a href="./index.php" class="text-decoration-none">Đăng nhập</a>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3 w-100 mb-4" name="login">
                            <i class="fas fa-power-off"></i>
                            <span href="" class="text-decoration-none text-white">Đăng ký</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <!-- <script src="../assets/js/checklogin_admin.js"></script> -->
</body>

</html>