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

<div class="sidebar">
    <div class="sidebar-header">
        <i class="fas fa-tachometer-alt"></i>
        Dashboard
    </div>
    <ul>
        <!-- Main Section -->
        <li class="section-header">Main</li>
        <li class="p-0 ms-1"><a href="#" class="py-2 ps-3"><i class=" fas fa-users"></i> Student List</a></li>
        <li class="p-0 ms-1"><a href="#" class="py-2 ps-3"><i class="fas fa-file-invoice-dollar"></i> Accounts</a></li>

        <!-- Reports Section -->
        <li class="section-header">Reports</li>
        <li class="p-0 ms-1"><a href="#" class="py-2 ps-3"><i class="fas fa-chart-line"></i> Monthly Collection Report</a></li>

        <!-- Master List Section -->
        <li class="section-header">Master List</li>
        <li class="p-0 ms-1"><a href="#" class="py-2 ps-3"><i class="fas fa-building"></i> Dorm List</a></li>
        <li class="p-0 ms-1"><a href="#" class="py-2 ps-3"><i class="fas fa-door-open"></i> List of Rooms</a></li>

        <!-- Maintenance Section -->
        <li class="section-header">Maintenance</li>
        <li class="p-0 ms-1"><a href="#" class="py-2 ps-3"><i class="fas fa-users-cog"></i> User List</a></li>
        <li class="p-0 ms-1"><a href="#" class="py-2 ps-3"><i class="fas fa-cogs"></i> Settings</a></li>
    </ul>
</div>

<script>
    const menu = document.querySelector('.sidebar-menu');
    menu.addEventListener('click', () => {
        menu.classList.toggle('collapsed');
    });
</script>