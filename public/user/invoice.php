<?php
// Khởi tạo phiên làm việc
if (!isset($_SESSION)) {
    session_start();
}
// Kiểm tra xem vai trò đã được lưu trong session hay chưa
if (isset($_SESSION['Role'])) {
    $role = $_SESSION['Role'];
    if ($role === 'user') {
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

// Đảm bảo rằng đã đăng nhập vào tài khoản người dùng và lưu thông tin người dùng vào session
if (isset($_SESSION['ClientID'])) {
    $clientID = $_SESSION['ClientID'];

    // Tính tổng số hóa đơn
    $query = "SELECT COUNT(*) as total FROM tblclient
              JOIN tblinvoice ON tblclient.ClientID = tblinvoice.ClientID
              WHERE tblclient.ClientID = :ClientID";
    $query = $dbh->prepare($query);
    $query->bindParam(':ClientID', $clientID, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);
    $totalRecords = $result['total'];

    // Số hóa đơn trên mỗi trang
    $recordsPerPage = 7;
    // Tính số trang hiện tại
    $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
    $totalPages = ceil($totalRecords / $recordsPerPage);

    // Chuẩn bị câu truy vấn SQL sử dụng Prepared Statement
    $query = "SELECT tblinvoice.*, tblclient.ContactName FROM tblclient
              JOIN tblinvoice ON tblclient.ClientID = tblinvoice.ClientID
              WHERE tblclient.ClientID = :ClientID
              ORDER BY tblinvoice.PostingDate
              LIMIT :offset, :recordsPerPage";

    $offset = ($currentPage - 1) * $recordsPerPage;
    $query = $dbh->prepare($query);
    $query->bindParam(':ClientID', $clientID, PDO::PARAM_INT);
    $query->bindParam(':offset', $offset, PDO::PARAM_INT);
    $query->bindParam(':recordsPerPage', $recordsPerPage, PDO::PARAM_INT);
    // Thực thi truy vấn
    $query->execute();
    // Lấy dữ liệu hóa đơn
    $invoices = $query->fetchAll(PDO::FETCH_ASSOC);
}
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
                        <small>/ Hóa đơn</small>
                    </h5>
                </div>
                <div class="pb-3 pt-2 px-4 pe-0">
                    <h3>Hóa đơn</h3>
                </div>

                <div class="row px-5 ">
                    <!-- Table Ends Here -->
                    <table id="quanly" class="table table-striped table-bordered">
                        <div class="px-4 ">
                            <thead>
                                <tr>
                                    <th scope="col">ID hóa đơn</th>
                                    <th scope="col">Tên liên hệ</th>
                                    <th scope="col">Ngày lập hóa đơn</th>
                                    <th scope="col">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                for ($i = 0; $i < count($invoices); $i++) {
                                    $invoice = $invoices[$i];
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($invoice['InvoiceID']) . "</td>";
                                    echo "<td>" . htmlspecialchars($invoice['ContactName']) . "</td>";
                                    echo "<td>" . htmlspecialchars($invoice['PostingDate']) . "</td>";
                                    echo '<td class="text-center">';
                                    echo '<a href="./view-invoice.php?invoice_id=' . $invoice['InvoiceID'] . '" class="btn btn-xs btn-warning me-1">';
                                    echo '<i alt="Edit" class="fa fa-pencil px-0 my-1"></i> Xem</a>';
                                    echo '</td>';
                                    echo "</tr>";
                                }
                                ?>

                            </tbody>

                        </div>
                    </table>
                    <!-- Table Ends Here -->
                    <?php
                    if ($totalPages > 1) {
                    ?>
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
                    <?php
                    }
                    ?>
                </div>


            </div>
        </div>
    </div>
    <?php
    include_once __DIR__ . '/../../partials/footer.php';
    ?>
</body>

</html>