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
                    <div class="dashboard-header">Welcome!</div>

                    
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