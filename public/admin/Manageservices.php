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
// Thực hiện truy vấn SQL để lấy dữ liệu từ bảng tblservices
$query = $dbh->prepare("SELECT * FROM tblservices");
$query->execute();
// Lấy kết quả truy vấn
$services = $query->fetchAll(PDO::FETCH_ASSOC);
// Số lượng bản ghi trên mỗi trang
$recordsPerPage = 7;
// Tổng số bản ghi
$totalRecords = count($services);
// Số trang
$totalPages = ceil($totalRecords / $recordsPerPage);
// Xác định trang hiện tại
if (isset($_GET['page']) && is_numeric($_GET['page'])) {
    $currentPage = (int) $_GET['page'];
} else {
    $currentPage = 1;
}
// Xác định vị trí bắt đầu và kết thúc của dữ liệu trên trang hiện tại
$start = ($currentPage - 1) * $recordsPerPage;
$end = $start + $recordsPerPage;
// Lấy dữ liệu cho trang hiện tại
$currentPageData = array_slice($services, $start, $recordsPerPage);
?>

<body>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <?php
            include_once __DIR__ . '/sidebar.php';
            ?>
            <div class="col">
                <div class="p-4">
                    <h5>
                        <a href="./dashboard.php" class="text-decoration-none"><small>Trang chủ</small></a>
                        <small>/ Quản lý dịch vụ</small>
                    </h5>
                </div>
                <div class="pb-3 pt-2 px-4 pe-0">
                    <h3>Quản lý dịch vụ</h3>
                </div>

                <div class="row px-5 ">
                    <!-- Table Ends Here -->
                    <table id="quanly" class="table table-striped table-bordered">
                        <div class="px-4 ">
                            <thead>
                                <tr>
                                    <th scope="col-1">ID</th>
                                    <th scope="col-2">Tên dịch vụ</th>
                                    <th scope="col-2">Giá dịch vụ</th>
                                    <th scope="col-3">Ngày tạo</th>
                                    <th scope="col-4">Chỉnh sửa</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <?php
                                    // Duyệt qua dữ liệu từ truy vấn và hiển thị trên trang
                                    foreach ($currentPageData as $index => $service) {
                                        echo "<tr>";
                                        echo "<td>" . htmlspecialchars($service["ID"]) . "</td>";
                                        echo "<td>" . htmlspecialchars($service['ServiceName']) . "</td>"; // Escape HTML
                                        echo "<td>" . htmlspecialchars($service['ServicePrice']) . "</td>"; // Escape HTML
                                        echo "<td>" . htmlspecialchars($service['CreationDate']) . "</td>";
                                        echo '<td class="d-flex justify-content-center">';
                                        echo '<a href="./edit-services.php?service_id=' . htmlspecialchars((isset($service['ID']) ? $service['ID'] : '')) . '" class="btn btn-xs btn-warning me-3">';
                                        echo '<i alt="Edit" class="fa fa-pencil"></i> Sửa đổi</a>';
                                        echo '<a href="./delete_service.php?service_id=' . htmlspecialchars((isset($service['ID']) ? $service['ID'] : '')) . '" class="btn btn-xs btn-warning">';
                                        echo '<i alt="Edit" class="fa fa-pencil"></i> Xóa</a>';
                                        echo '</td>';
                                        echo "</tr>";
                                    }
                                    ?>
                                </tr>
                            </tbody>
                        </div>
                    </table>
                    <!-- Table Ends Here -->
                    <nav aria-label="..." class="d-flex">
                        <ul class="pagination mx-auto">
                            <?php
                            if ($currentPage > 1) {
                                echo '<li class="page-item"><a class="page-link" href="?page=1">Trang đầu</a></li>';
                                $prevPage = $currentPage - 1;
                                echo '<li class="page-item"><a class="page-link" href="?page=' . htmlspecialchars($prevPage) . '">Previous</a></li>';
                            }

                            for ($i = 1; $i <= $totalPages; $i++) {
                                if ($i === $currentPage) {
                                    echo '<li class="page-item active" aria-current="page"><span class="page-link">' . htmlspecialchars($i) . '</span></li>';
                                } else {
                                    echo '<li class="page-item"><a class="page-link" href="?page=' . htmlspecialchars($i) . '">' . htmlspecialchars($i) . '</a></li>';
                                }
                            }

                            if ($currentPage < $totalPages) {
                                $nextPage = $currentPage + 1;
                                echo '<li class="page-item"><a class="page-link" href="?page=' . htmlspecialchars($nextPage) . '">Next</a></li>';
                                echo '<li class="page-item"><a class="page-link" href="?page=' . htmlspecialchars($totalPages) . '">Trang cuối</a></li>';
                            }
                            ?>
                        </ul>
                    </nav>
                </div>


            </div>
        </div>
    </div>
    <?php
    include_once __DIR__ . '/../../partials/footer.php';
    ?>
</body>

</html>