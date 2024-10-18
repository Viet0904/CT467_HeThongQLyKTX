<?php
// Khởi tạo phiên làm việc
if (!isset($_SESSION)) {
    session_start();
}
// Kiểm tra xem vai trò đã được lưu trong session hay chưa
if (isset($_SESSION['Role'])) {
    $role = $_SESSION['Role'];
    if ($role === 'user') {
        $sessionContactName = $_SESSION['ContactName'];
        $sessionId = $_SESSION['ClientID'];
        $sessionEmail = $_SESSION['Email'];
    } elseif ($role === 'admin') {
        echo "<script>alert('Bạn không có quyền truy cập vào trang này.')
        window.location.href='../../admin/dashboard.php';
        </script>";
        die();
    }
}

// Bắt đầu nội dung trang
ob_start();

?>

<div class="col-auto col-md-3 col-xl-2 px-0 bg-blue pt-2 shadow me-1 width-sidebar">
    <div class="d-flex flex-column align-items-center align-items-sm-start pt-2 text-white">
        <div class="row w-100">
            <div class="col-6">
                <a href="./dashboard.php" class="d-flex align-items-center pb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                    <span class="fs-5 d-none d-sm-inline ms-3">Menu</span>
                </a>
            </div>
        </div>

        <div class="down bg-white w-100">
            <div class="text-center pt-3 pb-2">
                <a href="./dashboard.php"><img src="../assets/images/user.jpg" height="70" width="70" class="rounded-circle display-none-icon"></a>
            </div>
            <div class="text-center">
                <a href="./dashboard.php" class="text-decoration-none"><span class=" name-caret text-info text-decoration-none">
                        <h5 class="display-none-icon"><b>
                                <?php echo htmlspecialchars($sessionContactName); ?>
                            </b></h5>
                    </span></a>
            </div>

            <ul class="text-center ps-0 d-inline-flex display-col">
                <li>
                    <a class="tooltips text-info text-decoration-none" href="./client-profile.php?clientid=<?php echo htmlspecialchars($sessionId) ?>">
                        <span class="tooltip-text ">Thông tin</span>
                        <i class="bi bi-person text-info mx-3 font-size-24"></i>
                    </a>
                </li>
                <li>
                    <a class="tooltips text-info text-decoration-none" href="./change-password.php?clientid=<?php echo htmlspecialchars($sessionId) ?>">
                        <span class="tooltip-text">Cài đặt</span>
                        <i class="bi bi-gear text-info mx-2 font-size-24"></i>
                    </a>
                </li>
                <li>
                    <a class="tooltips text-info text-decoration-none" href="./logout.php">
                        <span class="tooltip-text">Thoát</span>
                        <i class="bi bi-box-arrow-right text-info mx-3 font-size-24"></i>
                    </a>
                </li>
            </ul>

        </div>

        <ul class="nav nav-pills mb-sm-auto mb-0 align-items-center align-items-sm-start" id="menu">
            <li class="px-3 w-100 bg-lightblue">
                <a href="./dashboard.php" class="nav-link px-0 align-middle">
                    <i class="fs-4 bi-speedometer2 text-white"></i>
                    <span class="ms-1 d-none d-sm-inline text-white">Trang điều khiển</span> </a>
            </li>
            <li class="px-3 w-100 bg-lightblue">
                <a href="./invoice.php" class="nav-link px-0 align-middle">
                    <i class="fs-4 bi bi-receipt-cutoff text-white"></i> <span class="ms-1 d-none d-sm-inline text-white">Hóa
                        Đơn</span> </a>
            </li>
            <li class="px-3 w-100 bg-lightblue">
                <a href="./search-invoices.php" class="nav-link px-0 align-middle">
                    <i class="fs-4 bi bi-search text-white"></i> <span class="ms-1 d-none d-sm-inline text-white">Tìm hóa
                        đơn</span></a>
            </li>
        </ul>
        <hr>
    </div>
</div>
<script>
    const menu = document.querySelector('.sidebar-menu');
    menu.addEventListener('click', () => {
        menu.classList.toggle('collapsed');
    });
</script>