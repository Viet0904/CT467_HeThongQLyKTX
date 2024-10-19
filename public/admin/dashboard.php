<?php
// Khởi tạo phiên làm việc
if (!isset($_SESSION)) {
    session_start();
}
// Kiểm tra xem vai trò đã được lưu trong session hay chưa
if (isset($_SESSION['Role'])) {
    $role = $_SESSION['Role'];
    if ($role === 'user') {
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
include_once __DIR__ . '/../../partials/header.php';
include_once __DIR__ . '/../../partials/heading.php';
require_once __DIR__ . '/../../config/dbadmin.php';
?>

<body>
    <!-- Nội dung trang dashboard -->
    <!-- Phần Header trên cùng -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light" style="border-bottom: 1px solid #dee2e6; margin-left: 250px;">
        <div class="container-fluid">
    
            <!-- Tiêu đề hệ thống -->
            <a class="navbar-brand ms-3 fs-6" href="#">School Dormitory Management System - Admin</a>
    
            <!-- Phần thông tin tài khoản người dùng -->
            <div class="d-flex align-items-center position-relative me-3">
                <img src="../public/images/user.jpg" class="rounded-circle" alt="User Avatar" width="40" height="40">
                <span class="me-2 ms-2" id="userDropdown" style="cursor: pointer;" onclick="toggleDropdown()">Administrator Admin</span>
                <span style="cursor: pointer; font-size: 12px;" onclick="toggleDropdown()">▼</span>
    
                <!-- Khung dropdown -->
                <div id="dropdownMenu" class="dropdown-menu position-absolute p-0 ms-1" style="display: none;">
                    <a class="dropdown-item py-2" href="#">Log Out</a>
                </div>
            </div>
        </div>
    </nav>
    
    
    <!-- Nội dung chính của trang -->
    <div class="content">
        <div class="dashboard-header">Welcome, admin!</div>
    
        <!-- Dashboard cards - Hàng đầu tiên với 3 thẻ -->
        <div style="margin-top: 30px; margin-left: 40px;">
            <div class="row">
                <div class="card">
                    <i class="fas fa-building"></i>
                    <div class="card-title">Total Dorms</div>
                    <div class="card-number">4</div>
                </div>
                <div class="card">
                    <i class="fas fa-door-open"></i>
                    <div class="card-title">Total Rooms</div>
                    <div class="card-number">6</div>
                </div>
                <div class="card">
                    <i class="fas fa-users"></i>
                    <div class="card-title">Registered Students</div>
                    <div class="card-number">2</div>
                </div>
            </div>
        
        </div> 
        
        <!-- Dashboard cards - Hàng thứ hai với 2 thẻ -->
        <div style="margin-top: 30px; margin-left: 40px;">
            <div class="row">
                <div class="card">
                    <i class="fas fa-coins"></i>
                    <div class="card-title">This Month Total Collection</div>
                    <div class="card-number">$8,500.00</div>
                </div>
                <div class="card">
                    <i class="fas fa-cogs"></i>
                    <div class="card-title">Totals active Accounts</div>
                    <div class="card-number">2</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Hàm mở và đóng dropdown
        function toggleDropdown() {
            var dropdown = document.getElementById("dropdownMenu");
            if (dropdown.style.display === "none" || dropdown.style.display === "") {
                dropdown.style.display = "block"; // Hiển thị dropdown
            } else {
                dropdown.style.display = "none"; // Ẩn dropdown
            }
        }
    
        // Đóng dropdown nếu click bên ngoài
        window.onclick = function(event) {
            if (!event.target.matches('#userDropdown') && !event.target.matches('.ms-1')) {
                var dropdown = document.getElementById("dropdownMenu");
                if (dropdown.style.display === "block") {
                    dropdown.style.display = "none";
                }
            }
        }
    </script>
</body>

</html>