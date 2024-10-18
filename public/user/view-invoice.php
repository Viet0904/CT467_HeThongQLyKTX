<?php
// Khởi tạo phiên làm việc
if (!isset($_SESSION)) {
    session_start();
}
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

// kiểm tra có invoice_id không, nếu không thì chuyển về trang danh sách hóa đơn
if (empty($_GET['invoice_id'])) {
    echo "
    <script>
    alert('Không tìm thấy hóa đơn')
    window.location.href='./invoice.php';
    </script>
    ";
}

// Kiểm tra xem có invoice_id được truyền từ trang danh sách hóa đơn không
elseif (isset($_GET['invoice_id']) && is_numeric($_GET['invoice_id'])) {
    $invoiceID = (int) $_GET['invoice_id'];
    // Truy vấn CSDL để lấy thông tin chi tiết hóa đơn
    $query = $dbh->prepare("SELECT * FROM tblinvoice WHERE InvoiceID = :invoiceID");
    $query->bindParam('invoiceID', $invoiceID, PDO::PARAM_INT);
    $query->execute();
    // Lấy dữ liệu hóa đơn(chuyển thành mảng)
    $invoice = $query->fetch(PDO::FETCH_ASSOC);

    if ($invoice) {
        // Truy vấn thông tin khách hàng
        $queryClient = $dbh->prepare("SELECT ContactName, Cellphnumber, Email FROM tblclient WHERE ClientID = :clientID");
        $queryClient->bindParam('clientID', $invoice['ClientID'], PDO::PARAM_INT);
        $queryClient->execute();
        $clientInfo = $queryClient->fetch(PDO::FETCH_ASSOC);

        // Truy vấn CSDL để lấy thông tin dịch vụ liên quan đến hóa đơn
        $queryServices = $dbh->prepare("SELECT * FROM tblservices WHERE ID IN (SELECT ServiceId FROM tblinvoice WHERE InvoiceID = :invoiceID)");
        $queryServices->bindParam('invoiceID', $invoiceID, PDO::PARAM_INT);
        $queryServices->execute();
        $services = $queryServices->fetchAll(PDO::FETCH_ASSOC);

        if ($clientInfo) {
            // Tính tổng giá dịch vụ
            $totalPrice = 0;
            for ($i = 0; $i < count($services); $i++) {
                $totalPrice += $services[$i]['ServicePrice'];
            }
        } else {
            // Xử lý trường hợp không tìm thấy thông tin khách hàng
            echo "<script>alert('Không tìm thấy thông tin khách hàng.')</script>";
        }
    } else {
        // Xử lý trường hợp không tìm thấy hóa đơn
        echo "<script>alert('Không tìm thấy hóa đơn.')</script>";
    }
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
                        <small>/ Xem hóa đơn</small>
                    </h5>
                </div>
                <div class="pb-3 pt-2 px-4 pe-0">
                    <h3>Chi tiết hóa đơn</h3>
                </div>

                <div class="row px-5 mx-3 mb-5 border rounded-2">
                    <div class="my-3 text-center">
                        <h3>
                            <b>Hóa đơn#
                                <?php echo htmlspecialchars($invoice['InvoiceID']); ?>
                            </b>
                        </h3>
                    </div>

                    <!-- Table starts Here -->
                    <table id="quanly" class="table table-bordered border rounded-2 py-5">
                        <tbody>
                            <tr style="background-color: #f0f0f0;">
                                <th colspan="6">Chi tiết khách hàng</th>
                            </tr>
                            <tr>
                                <th>ID tài khoản</th>
                                <td>
                                    <?php echo htmlspecialchars($invoice['ClientID']); ?>
                                </td>
                                <th>Tên liên hệ</th>
                                <td>
                                    <?php echo htmlspecialchars($clientInfo['ContactName']); ?>
                                </td>
                                <th>Số điện thoại</th>
                                <td>
                                    <?php echo htmlspecialchars($clientInfo['Cellphnumber']); ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>
                                    <?php echo htmlspecialchars($clientInfo['Email']); ?>
                                </td>
                                <th>Ngày lập hóa đơn</th>
                                <td colspan="6">
                                    <?php echo htmlspecialchars($invoice['PostingDate']); ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <!-- Table Ends Here -->

                    <!-- Table service -->
                    <table id="quanly" class="table table-striped table-bordered mt-4 mb-5">
                        <thead>
                            <tr style="background-color: #f0f0f0;">
                                <th colspan="3">Chi tiết dịch vụ</th>
                            </tr>
                            <tr>
                                <th scope="col">Thứ tự</th>
                                <th scope="col">Tên dịch vụ</th>
                                <th scope="col">Giá dịch vụ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stt = 1;
                            for ($i = 0; $i < count($services); $i++) {
                                $service = $services[$i];
                                echo '<tr>';
                                echo '<td>' . $stt++ . '</td>';
                                echo '<td>' . htmlspecialchars($service['ServiceName']) . '</td>';
                                echo '<td>' . htmlspecialchars($service['ServicePrice']) . '</td>';
                                echo '</tr>';
                            }
                            ?>

                            <tr>
                                <th colspan="2 text-center">Tổng cộng</th>
                                <td>
                                    <?php echo htmlspecialchars($totalPrice); ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
    <?php
    include_once __DIR__ . '/../../partials/footer.php';
    ?>
</body>

</html>