<?php
session_start();
?>

<div class="sidebar px-0">
    <div class="sidebar-header">
        <i class="fas fa-tachometer-alt"></i>
        Menu
    </div>
    <ul>
        <!-- Main Section -->
        <li class="section-header">Main</li>
        <li class="p-0 ms-1"><a href="./dashboard.php" class="py-2 ps-3"><i class="fas fa-tachometer-alt"></i> Trang điều khiển</a></li>
        <li class="p-0 ms-1"><a href="./student_list.php" class="py-2 ps-3"><i class="fas fa-users"></i> Danh sách sinh viên</a></li>
        <li class="p-0 ms-1"><a href="./dangkyphong_sv.php" class="py-2 ps-3"><i class="fas fa-key"></i> Danh sách thuê phòng</a></li>
        <!-- Master List Section -->
        <li class="section-header">Danh sách chính</li>

        <li class="p-0 ms-1"><a href="./thuephong.php" class="py-2 ps-3"><i class="fas fa-users"></i>Đăng ký phòng cho sinh viên</a></li>

        <li class="p-0 ms-1"><a href="./room_list.php" class="py-2 ps-3"><i class="fas fa-door-open"></i> Danh sách phòng</a></li>
        <li class="p-0 ms-1"><a href="./manage_diennuoc.php" class="py-2 ps-3"><i class="fas fa-lightbulb me-3"></i> Quản lý điện nước</a></li>
        <li class="p-0 ms-1"><a href="./ql_thuephong.php" class="py-2 ps-3"><i class="fas fa-bed"></i> Quản lý thuê phòng</a></li>
        <!-- Additional Options for Admin -->
        <?php if ($_SESSION['Role'] === 'Admin'): ?>
            <li class="p-0 ms-1"><a href="./employees_list.php" class="py-2 ps-3"><i class="fas fa-user-shield"></i> Quản lý nhân viên</a></li>
            <li class="p-0 ms-1"><a href="./manage_khu.php" class="py-2 ps-3"><i class="fas fa-map-marker-alt"></i> Quản lý khu</a></li>
            <li class="p-0 ms-1"><a href="./manage_day.php" class="py-2 ps-3"><i class="fas fa-columns"></i> Quản lý dãy</a></li>
            <li class="p-0 ms-1"><a href="./manage_class.php" class="py-2 ps-3"><i class="fas fa-graduation-cap"></i> Quản lý lớp</a></li>
        <?php endif; ?>

        <!-- Reports Section -->
        <li class="section-header">Báo cáo</li>
        <li class="p-0 ms-1"><a href="report_price.php" class="py-2 ps-3"><i class="fas fa-chart-line"></i> Doanh thu hàng tháng</a></li>
    </ul>
</div>