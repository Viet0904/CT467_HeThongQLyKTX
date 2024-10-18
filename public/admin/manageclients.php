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

// Số lượng bản ghi trên mỗi trang
$recordsPerPage = 7;
// Xác định trang hiện tại
if (isset($_GET['page']) && is_numeric($_GET['page'])) {
    $currentPage = (int)$_GET['page'];
} else {
    $currentPage = 1;
}
// Tính vị trí bắt đầu của dòng dữ liệu
$startFrom = ($currentPage - 1) * $recordsPerPage;
// Chuẩn bị câu truy vấn sử dụng LIMIT
$query = "SELECT * FROM tblclient ORDER BY ClientID ASC LIMIT :startFrom, :recordsPerPage";
$stmt = $dbh->prepare($query);
$stmt->bindParam(':startFrom', $startFrom, PDO::PARAM_INT);
$stmt->bindParam(':recordsPerPage', $recordsPerPage, PDO::PARAM_INT);
$stmt->execute();
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
                        <small>/ Quản lý khách hàng</small>
                    </h5>
                </div>
                <div class="pb-3 pt-2 px-4 pe-0">
                    <h3>Quản lý khách hàng</h3>
                </div>

                <div class="row px-5 ">
                    <!-- Table Ends Here -->
                    <table id="quanly" class="table table-striped table-bordered">
                        <div class="px-4 ">
                            <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Tên liên lạc</th>
                                    <th scope="col">Số điện thoại</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Cài đặt</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <?php
                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                        echo '<tr>';
                                        echo '<td>' . htmlspecialchars($row['ClientID']) . '</td>';
                                        echo '<td>' . htmlspecialchars($row['ContactName'], ENT_QUOTES, 'UTF-8') . '</td>';
                                        echo '<td>' . htmlspecialchars($row['Cellphnumber']) . '</td>';
                                        echo '<td>' . htmlspecialchars($row['Email'], ENT_QUOTES, 'UTF-8') . '</td>';
                                        echo '<td class="text-center width-edit">';
                                        echo '<a href="./edit_client.php?client_id=' . htmlspecialchars($row['ClientID']) . '" class="btn btn-xs btn-warning me-2 margin-bottom-button">';
                                        echo '<i alt="Edit" class="fa fa-pencil px-0"></i> Sửa đổi</a>';
                                        echo '<a href="./add-client-service.php?client_id=' . htmlspecialchars($row['ClientID']) . '" class="btn btn-xs btn-warning me-2 margin-bottom-button">';
                                        echo '<i alt="Edit" class="fa fa-pencil px-0"></i>Thêm</a>';
                                        echo '<a href="./delete_client.php?client_id=' . htmlspecialchars($row['ClientID']) . '" class="btn btn-xs btn-warning">';
                                        echo '<i alt="Edit" class="fa fa-pencil px-0"></i> Xóa</a>';
                                        echo '</td>';
                                        echo '</tr>';
                                    }
                                    ?>
                                </tr>
                            </tbody>

                        </div>
                    </table>
                    <nav aria-label="..." class="d-flex">
                        <ul class="pagination mx-auto">
                            <?php
                            $totalRecords = $dbh->query('SELECT COUNT(*) FROM tblclient')->fetchColumn();
                            $totalPages = ceil($totalRecords / $recordsPerPage);
                            if ($totalPages > 1) {
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
                            }
                            ?>
                        </ul>
                    </nav>
                    <!-- Table Ends Here -->
                </div>
            </div>
        </div>
    </div>
    <?php
    include_once __DIR__ . '/../../partials/footer.php';
    ?>
</body>

</html>