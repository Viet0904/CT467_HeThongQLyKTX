<!DOCTYPE html>
<html lang="en">

<head>
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
            background: url('./assets/images/cover.png') no-repeat center center fixed;
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
    <h1 class="text-center text-white px-4 py-5" id="page-title">Hệ thống quản lí ký túc xá<b></b></h1>
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

                        <div class="form-group form-check pt-4 d-flex justify-content-between align-items-center">
                            <div>
                                <input type="checkbox" class="form-check-input" id="rememberMe">
                                <label class="form-check-label" for="rememberMe">Ghi nhớ tôi</label>
                            </div>
                            <a href="./register.php" class="text-decoration-none">Đăng ký</a>
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

    <a href="./admin/dashboard.php" class="text-decoration-none">Đăng ký</a>

    <!-- <script src="../assets/js/checklogin_admin.js"></script> -->
</body>

</html>