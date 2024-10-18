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
?>

<!-- Nội dung trang dashboard -->

<body>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <?php
            include_once __DIR__ . '/sidebar.php';
            ?>
            <div class="col py-5">
                <h3 class="text-center">
                    <b>Welcome to MyEitik !!
                        <?php echo htmlspecialchars($sessionContactName) ?>
                        Programming Blog</b>
                </h3>
            </div>
        </div>
    </div>

    <?php
    include_once __DIR__ . '/../../partials/footer.php';
    ?>
</body>

</html>