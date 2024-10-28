<!-- Phần Header trên cùng -->
<nav class="navbar navbar-expand-lg navbar-light bg-light py-1"
    style="border-bottom: 1px solid #dee2e6; margin-left: 250px;">
    <div class="container-fluid">

        <!-- Tiêu đề hệ thống -->
        <a class="navbar-brand ms-3 fs-6" href="#">Hệ thống quản lý Ký túc xá trường học</a>

        <!-- Phần thông tin tài khoản người dùng -->
        <div class="d-flex align-items-center position-relative me-3">
            <img src="../public/images/user.jpg" class="rounded-circle" alt="User Avatar" width="40" height="40">
            <span class="me-2 ms-2" id="userDropdown" style="cursor: pointer;" onclick="toggleDropdown(event)">Quản trị
                viên</span>
            <span style="cursor: pointer; font-size: 12px;" onclick="toggleDropdown(event)">▼</span>

            <!-- Khung dropdown -->
            <div id="dropdownMenu" class="dropdown-menu position-absolute p-0 ms-1" style="display: none;">
                <a class="dropdown-item py-2" href="./admin_profile.php">Thông tin</a>
                <!-- Đường phân cách -->
                <hr style="border: none; border-top: 1px solid #a9a9a9; margin: 1px 0;">
                <a class="dropdown-item py-2" href="#">Thoát</a>
            </div>
        </div>
    </div>
</nav>


