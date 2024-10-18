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
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <?php
            include_once __DIR__ . '/sidebar.php';
            ?>
            <div class="col pr-0">

                <div class="p-4 pe-0">
                    <h5>
                        <a href="./dashboard.php" class="text-decoration-none"><small>Trang chủ</small></a>
                        <small>/ Tìm hóa đơn</small>
                    </h5>
                </div>
                <div class="pb-3 pt-2 px-4 pe-0">
                    <h3>Tìm hóa đơn</h3>
                </div>

                <div class="modal-body p-4 mb-5 border mx-4 rounded-2">
                    <form id="customerForm" action="search-invoices.php" method="POST">
                        <div class="mb-4 pt-2">
                            <label for="" class="form-label">Tìm kiếm theo số hóa đơn</label>
                            <input name="searchinvoice" type="text" class="form-control border rounded-1" id="" aria-describedby="" placeholder="">
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <span href="" class="text-decoration-none text-white">Tìm kiếm</span>
                        </button>
                    </form>
                    <?php
                    if (isset($_POST['searchinvoice'])) {
                        $searchInvoiceNumber = $_POST['searchinvoice'];
                        $query = $dbh->prepare("SELECT tblinvoice.*, tblclient.ContactName FROM tblinvoice JOIN tblclient ON tblinvoice.ClientID = tblclient.ClientID WHERE InvoiceID = :invoiceID");
                        $query->bindParam(':invoiceID', $searchInvoiceNumber, PDO::PARAM_INT);
                        $query->execute();
                        $invoices = $query->fetchAll(PDO::FETCH_ASSOC);
                        // Kiểm tra xem có hóa đơn nào được tìm thấy hay không
                        if (count($invoices) > 0) {
                            echo '<h3 class="text-center"><b>Kết quả với #' . htmlspecialchars($searchInvoiceNumber) . '</b></h3>';
                            echo '<table id="quanly" class="table table-striped table-bordered">';
                            echo '<thead>';
                            echo '<tr>';
                            echo '<th scope="col">ID hóa đơn</th>';
                            echo '<th scope="col">Tên liên hệ</th>';
                            echo '<th scope="col">Ngày lập hóa đơn</th>';
                            echo '<th scope="col">Hành động</th>';
                            echo '</tr>';
                            echo '</thead>';
                            echo '<tbody>';
                            $stt = 1;
                            foreach ($invoices as $invoice) {
                                // Output each row, escaping the output to prevent XSS
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($invoice['InvoiceID']) . '</td>';
                                echo '<td>' . htmlspecialchars($invoice['ContactName']) . '</td>';
                                echo '<td>' . htmlspecialchars($invoice['PostingDate']) . '</td>';
                                echo '<td class="text-center">';
                                echo '<a href="./view-invoice.php?invoice_id=' . htmlspecialchars($invoice['InvoiceID']) . '" class="btn btn-xs btn-warning me-1">';
                                echo '<i alt="Edit" class="fa fa-pencil px-0 my-1"></i> Xem</a>';
                                echo '</td>';
                                echo '</tr>';
                            }

                            echo '</tbody>';
                            echo '</table>';
                        } else {
                            echo "<script>alert('Không tìm thấy hóa đơn')</script>";
                        }
                    }
                    ?>
                </div>

            </div>
        </div>
    </div>
    <?php
    include_once __DIR__ . '/../../partials/footer.php';
    ?>
    <script src="../assets/js/search_invoice.js"></script>
    <script>
    </script>
</body>

</html>