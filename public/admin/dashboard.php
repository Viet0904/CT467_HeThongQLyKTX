<?php
include_once __DIR__ . '/../../partials/header.php';
include_once __DIR__ . '/../../partials/heading.php';
?>

<body>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <?php
            include_once __DIR__ . '/sidebar.php';
            ?>

            <div class="col px-0">
                <!-- Nội dung chính của trang -->
                <div class="content">
                    <div class="dashboard-header">Welcome, admin!</div>

                    <!-- Dashboard cards - Hàng đầu tiên với 2 thẻ -->
                    <div style="margin-top: 30px; margin-left: 40px;">
                        <div class="row">
                            <div class="card">
                                <i class="fas fa-door-open"></i>
                                <div class="card-title">Tổng số phòng</div>
                                <div class="card-number">6</div>
                            </div>
                            <div class="card">
                                <i class="fas fa-users"></i>
                                <div class="card-title">Tổng sinh viên</div>
                                <div class="card-number">2</div>
                            </div>
                        </div>
                    
                    </div> 
                    
                    <!-- Dashboard cards - Hàng thứ hai với 2 thẻ -->
                    <div style="margin-top: 30px; margin-left: 40px;">
                        <div class="row">
                            <div class="card">
                                <i class="fas fa-coins"></i>
                                <div class="card-title">Tổng doanh thu</div>
                                <div class="card-number">$8,500.00</div>
                            </div>
                            <div class="card">
                                <i class="fas fa-cogs"></i>
                                <div class="card-title">Tổng tài khoản hoạt động</div>
                                <div class="card-number">2</div>
                            </div>
                        </div>
                    </div>
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