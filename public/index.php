<?php

// Khởi tạo phiên làm việc
if (!isset($_SESSION)) {
    session_start();
}
// Kiểm tra xem vai trò đã được lưu trong session hay chưa
// if (isset($_SESSION['Role'])) {
//     $role = $_SESSION['Role'];

//     if ($role === 'admin') {
//         header("Location: ./admin/index.php");
//         die();
//     } elseif ($role === 'user') {
//         header("Location: ./admin/index.php");
//         die();
//     }
// }
require_once __DIR__ . '/../config/dbadmin.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if username matches SinhVien pattern
    if (preg_match('/^[A-Za-z]\d{7}$/', $username)) {
        echo "<script>console.log('Vào được user');</script>";
        $query = "SELECT MaSinhVien, password, Email, HoTen,GioiTinh FROM SinhVien WHERE MaSinhVien = ?";
        $stmt = $dbh->prepare($query);
        $stmt->bindValue(1, $username);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result && password_verify($password, $result['password'])) {
            // Lưu thông tin tài khoản vào session
            $_SESSION['MaSinhVien'] = $result['MaSinhVien'];
            $_SESSION['Email'] = $result['Email'];
            $_SESSION['HoTen'] = $result['HoTen'];
            $_SESSION['GioiTinh'] = $result['GioiTinh'];
            $_SESSION['Role'] = 'user';
            echo "<script>alert('Đăng nhập thành công.')
                window.location.href='./user/dashboard.php';
                </script>";
            exit();
        } else {
            echo "<script>
            alert('Tài khoản hoặc mật khẩu không chính xác. Vui lòng nhập lại.');
        </script>";
        }
    }
    // Check if username matches NhanVien pattern
    elseif (preg_match('/^CB\d{6}$/', $username)) {
        echo "<script>console.log('Vào được admin');</script>";
        $query = "SELECT MaNhanVien, password, HoTen, Role FROM NhanVien WHERE MaNhanVien = ?";
        $stmt = $dbh->prepare($query);
        $stmt->bindValue(1, $username);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result && password_verify($password, $result['password'])) {
            // Lưu thông tin tài khoản vào session
            $_SESSION['MaNhanVien'] = $result['MaNhanVien'];
            $_SESSION['HoTen'] = $result['HoTen'];
            $_SESSION['Role'] =  $result['Role'];
            echo "<script>alert('Đăng nhập thành công.')
                window.location.href='./admin/dashboard.php';
                </script>";
            exit();
        } else {
            echo "<script>
            alert('Tài khoản hoặc mật khẩu không chính xác. Vui lòng nhập lại.');
            </script>";
        }
    } else {
        echo "<script>
            alert('Tài khoản hoặc mật khẩu không chính xác. Vui lòng nhập lại.');
        </script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">


<head>
<?php
    include_once __DIR__ . '/../partials/header.php';
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý ktx</title> 
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
        <div class="modal-dialog rounded shadow-lg p-2 m-4 bg-body rounded">
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
                                <i class="fas fa-user"></i> Tên Đăng Nhập:
                            </label>
                            <input class="form-control mt-1 border rounded-1" placeholder="Nhập tên Admin" id="usernameInput" name="username"></input>
                        </div>

                        <div class="form-group">
                            <label for="passwordInput" class="pt-4">
                                <i class="fas fa-eye"></i> Mật khẩu:
                            </label>
                            <input type="password" class="form-control mt-1 border rounded-1" placeholder="Nhập mật khẩu" id="passwordInput" name="password"></input>
                        </div>

                        <div class="form-group form-check pt-4 d-flex justify-content-between align-items-center">
                            <div>
                                <input type="checkbox" class="form-check-input" id="rememberMe">
                                <label class="form-check-label" for="rememberMe">Ghi nhớ tôi</label>
                            </div>
                            <a href="./register.php" class="text-decoration-none">Đăng ký</a>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3 w-100 mb-4" name="login">
                            <i class="fas fa-power-off"></i>
                            <span class="text-decoration-none text-white">Đăng nhập</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <a href="./admin/dashboard.php" class="text-decoration-none">admin</a>
    <a href="./user/dashboard.php" class="text-decoration-none">user</a>


    <!-- <script src="../assets/js/checklogin_admin.js"></script> -->
</body>

</html>