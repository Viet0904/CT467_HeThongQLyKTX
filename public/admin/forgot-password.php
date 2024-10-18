<?php
include_once __DIR__ . '/../../partials/header.php';
include_once __DIR__ . '/../../partials/heading.php';
require_once __DIR__ . '/../../config/dbadmin.php';
?>

<body>
    <div class="container width-50 py-2">
        <div class="modal-dialog rounded shadow-lg p-2 m-4 bg-body rounded ">
            <div class="modal-content p-2">
                <div class="modal-header text-center d-block">
                    <h2 class="modal-title pt-3">
                        Quên mật khẩu
                    </h2>
                </div>

                <div id="login-form" class="modal-body">
                    <form method="POST" name="login" id="login">
                        <div class="form-group">
                            <label for="usernameInput" class="pt-2">
                                <i class="fas fa-user"></i> Email:
                            </label>
                            <input class="form-control mt-1 border rounded-1" placeholder="Nhập email" id="usernameInput" name="username"></input>
                        </div>

                        <div class="form-group">
                            <label for="passwordInput" class="pt-3">
                                <i class="fas fa-eye"></i> Số điện thoại:
                            </label>
                            <input type="password" class="form-control mt-1 border rounded-1" placeholder="Nhập số điện thoại" id="passwordInput" name="password"></input>
                        </div>

                        <div class="form-group">
                            <label for="passwordInput" class="pt-3">
                                <i class="fas fa-eye"></i> Mật khẩu mới:
                            </label>
                            <input type="password" class="form-control mt-1 border rounded-1" placeholder="Nhập mật khẩu mới" id="passwordInput" name="password"></input>
                        </div>

                        <div class="form-group">
                            <label for="passwordInput" class="pt-3">
                                <i class="fas fa-eye"></i> Cập nhật mật khẩu:
                            </label>
                            <input type="password" class="form-control mt-1 border rounded-1" placeholder="Cập nhật mật khẩu" id="passwordInput" name="password"></input>
                        </div>

                        <button type="submit" class="btn btn-primary mt-3 w-100" name="login">
                            <i class="fas fa-power-off"></i>
                            <span href="" class="text-decoration-none text-white">Cập nhật</span>
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