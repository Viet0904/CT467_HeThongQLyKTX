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
// Chuẩn bị câu truy vấn SQL sử dụng Prepared Statement
$query = $dbh->prepare("SELECT tblinvoice.*, tblclient.ContactName FROM tblinvoice 
JOIN tblclient ON tblinvoice.ClientID = tblclient.ClientID ORDER BY InvoiceID ASC");
// Thực thi truy vấn
$query->execute();
// Lấy dữ liệu hóa đơn
$invoices = $query->fetchAll(PDO::FETCH_ASSOC);
$currentpage = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Số hóa đơn hiển thị trên mỗi trang
$invoicesPerPage = 7;
$recordsPerPage = $invoicesPerPage;
// Tổng số hóa đơn
$totalInvoices = count($invoices);
// Xác định vị trí bắt đầu và kết thúc của mỗi trang
$start = ($currentpage - 1) * $invoicesPerPage;
$end = $start + $invoicesPerPage - 1;

// Lọc danh sách hóa đơn để chỉ hiển thị trên trang hiện tại
$displayedInvoices = array_slice($invoices, $start, $invoicesPerPage);

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
                                foreach ($displayedInvoices as $invoice) {
                                    echo '<tr>';
                                    echo '<td>' . htmlspecialchars($invoice['InvoiceID']) . '</td>';
                                    // Điều này giả định rằng bạn có trường ClientID trong bảng hóa đơn để tham chiếu đến khách hàng
                                    echo '<td>' . htmlspecialchars($invoice['ContactName']) . '</td>';
                                    echo '<td>' . htmlspecialchars($invoice['PostingDate']) . '</td>';
                                    echo '<td class="text-center">';
                                    echo '<a href="./view-invoice.php?invoice_id=' . htmlspecialchars($invoice['InvoiceID']) . '" class="btn btn-xs btn-warning me-1">';
                                    echo '<i alt="Edit" class="fa fa-pencil px-0 my-1"></i> Xem</a>';
                                    echo '</td>';
                                    echo '</tr>';
                                }
                                ?>
                            </tbody>

                        </div>
                    </table>
                    <nav aria-label="..." class="d-flex">
                        <ul class="pagination mx-auto">
                            <?php
                            if ($currentpage > 1) {
                                echo '<li class="page-item"><a class="page-link" href="?page=1">Trang đầu</a></li>';
                                $prevPage = $currentpage - 1;
                                echo '<li class="page-item"><a class="page-link" href="?page=' . htmlspecialchars($prevPage) . '">Previous</a></li>';
                            }

                            if ($totalInvoices > 0) {
                                $totalPages = ceil($totalInvoices / $recordsPerPage);
                                for ($i = 1; $i <= $totalPages; $i++) {
                                    if ($i === $currentpage) {
                                        echo '<li class="page-item active" aria-current="page"><span class="page-link">' . htmlspecialchars($i) . '</span></li>';
                                    } else {
                                        echo '<li class="page-item"><a class="page-link" href="?page=' . htmlspecialchars($i) . '">' . htmlspecialchars($i) . '</a></li>';
                                    }
                                }

                                if ($currentpage < $totalPages) {
                                    $nextPage = $currentpage + 1;
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