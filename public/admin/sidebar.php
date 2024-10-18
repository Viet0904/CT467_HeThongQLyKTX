<?php
// Khởi tạo phiên làm việc
if (!isset($_SESSION)) {
    session_start();
}
// Kiểm tra xem vai trò đã được lưu trong session hay chưa
if (isset($_SESSION['Role'])) {
    $role = $_SESSION['Role'];
    if ($role === 'admin') {
        $sessionUsername = $_SESSION['username'];
        $sessionId = $_SESSION['ID'];
    } elseif ($role === 'user') {
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
?>

<div class="col-auto col-md-3 col-xl-2 px-0 bg-blue shadow me-1 width-sidebar">
    <div class="d-flex flex-column align-items-center align-items-sm-start text-white">
        <div class="row w-100 pt-3">
            <div class="col-6">
                <a href="./dashboard.php" class="d-flex align-items-center pb-3 mb-md-0 me-md-auto text-white text-decoration-none display-none-icon">
                    <span class="fs-5 d-none d-sm-inline ms-3">Menu</span>
                </a>
            </div>
        </div>

        <div class="down bg-white w-100">
            <div class="text-center pt-3 pb-2">
                <a href="./dashboard.php">
                    <img src="../assets/images/admin.jpg" height="70" width="70" class="display-none-icon">
                </a>
            </div>
            <div class="text-center">
                <a href="./dashboard.php" class="text-decoration-none">
                    <span class=" name-caret text-info text-decoration-none">
                        <h5 class="display-none-icon"><b>
                                <?php echo htmlspecialchars($sessionUsername); ?>
                            </b></h5>
                    </span>
                </a>
            </div>

            <ul class="text-center ps-0 d-inline-flex display-col">
                <li>
                    <a class="tooltips text-info text-decoration-none" href="./admin-profile.php?id=<?php echo htmlspecialchars($sessionId);  ?>">
                        <span class="tooltip-text ">Thông tin</span>
                        <i class="bi bi-person text-info mx-3 font-size-24"></i>
                    </a>
                </li>
                <li>
                    <a class="tooltips text-info text-decoration-none" href="./change-password.php?id=<?php echo htmlspecialchars($sessionId); ?>">
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
            <li class="w-100 bg-lightblue">
                <a href="./dashboard.php" class="nav-link px-3 align-middle">
                    <i class="fs-4 bi-speedometer2 text-white"></i>
                    <span class="ms-1 d-none d-sm-inline text-white">Trang điều khiển</span> </a>
            </li>
            <li class="w-100">
                <a href="#submenu2" data-bs-toggle="collapse" class="px-3 nav-link align-middle bg-lightblue">
                    <i class="fs-4 bi-table text-white"></i>
                    <span class="text-white ms-1 d-none d-sm-inline">Dịch vụ</span>
                    <i class="bi bi-chevron-down text-white pt-2 float-end display-none-icon"></i>
                </a>
                <ul class="collapse nav flex-column ms-1" id="submenu2" data-bs-parent="#menu">
                    <li class="w-100 bg-lightblue">
                        <a href="./add-services.php" class="nav-link px-3"> <span class="d-none d-sm-inline text-white ps-4">Thêm
                                dịch vụ</span>
                        </a>
                    </li>
                    <li class="w-100 bg-lightblue">
                        <a href="./manageservices.php" class="nav-link px-3"> <span class="d-none d-sm-inline text-white ps-4 ">Quản
                                lý dịch vụ</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="w-100 bg-lightblue">
                <a href="./addclients.php" class="nav-link px-3 align-middle">
                    <i class="fs-4 bi-people text-white"></i> <span class="ms-1 d-none d-sm-inline text-white">Thêm
                        khách hàng</span>
                </a>
            </li>
            <li class="-100 bg-lightblue">
                <a href="./manageclients.php" class="nav-link px-3 align-middle">
                    <i class="fs-4 bi-table text-white"></i> <span class="ms-1 d-none d-sm-inline text-white">Danh sách
                        khách hàng</span></a>
            </li>
            <li class="w-100 bg-lightblue">
                <a href="./invoice.php" class="nav-link px-3 align-middle">
                    <i class="fs-4 bi bi-receipt-cutoff text-white"></i> <span class="ms-1 d-none d-sm-inline text-white">Hóa
                        Đơn</span> </a>
            </li>
            <li class="w-100 bg-lightblue">
                <a href="./search-invoices.php" class="nav-link px-3 align-middle">
                    <i class="fs-4 bi bi-search text-white"></i> <span class="ms-1 d-none d-sm-inline text-white">Tìm
                        hóa
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