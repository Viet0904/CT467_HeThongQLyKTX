<?php
// Khởi tạo phiên làm việc
if (!isset($_SESSION)) {
    session_start();
}
// Kiểm tra xem vai trò đã được lưu trong session hay chưa
if (isset($_SESSION['Role'])) {
    $role = $_SESSION['Role'];
    if ($role === 'user') {
        // Bạn có thể thực hiện xử lý cho vai trò 'user' ở đây
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
include_once __DIR__ . '/../../partials/header.php';
include_once __DIR__ . '/../../partials/heading.php';
require_once __DIR__ . '/../../config/dbadmin.php';

if (isset($_POST['invoiceid'])) {
    $invoiceid = $_POST['invoiceid'];
    $Clientid = $_SESSION['ClientID'];
    // Use a prepared statement to avoid SQL injection
    $query = $dbh->prepare("SELECT tblinvoice.*,tblclient.ContactName FROM tblinvoice JOIN tblclient ON tblinvoice.ClientID = tblclient.ClientID WHERE InvoiceID = :invoice AND tblclient.ClientID = :clientid");
    $query->bindParam(":invoice", $invoiceid, PDO::PARAM_INT);
    $query->bindParam(":clientid", $Clientid, PDO::PARAM_INT);
    $query->execute();

    if ($query->rowCount() > 0) {
        $invoices = $query->fetchAll(PDO::FETCH_ASSOC);
    } else {
        echo "<script>alert('Không tìm thấy hóa đơn này.')</script>";
    }
}
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
                    <form id="customerForm" method="POST">
                        <div class="mb-4 pt-2">
                            <label for="" class="form-label">Tìm kiếm theo số hóa đơn</label>
                            <input name="invoiceid" type="text" class="form-control border rounded-1" id="" aria-describedby="" placeholder="">
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <span class="text-decoration-none text-white">Tìm kiếm</span>
                        </button>
                    </form>

                    <!-- bấm vào tìm kiếm sẽ hiện ra bảng hóa đơn này-->
                    <?php
                    if (isset($invoices) && count($invoices) > 0) {
                        echo '<div class="mt-5">';
                        echo '<h3 class="text-center mb-3"><b>Kết quả với# ' . htmlspecialchars($invoiceid) . '</b></h3>';
                        echo '<table id="quanly" class="table table-striped table-bordered">';
                        echo '<div class="px-4">';
                        echo '<thead>';
                        echo '<tr>';
                        echo '<th scope="col">ID hóa đơn</th>';
                        echo '<th scope="col">Tên liên hệ</th>';
                        echo '<th scope="col">Ngày lập hóa đơn</th>';
                        echo '<th scope="col">Hành động</th>';
                        echo '</tr>';
                        echo '</thead>';
                        echo '<tbody>';

                        for ($i = 0; $i < count($invoices); $i++) {
                            $invoice = $invoices[$i];
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($invoice['InvoiceID']) . "</td>";
                            echo "<td>" . htmlspecialchars($invoice['ContactName']) . "</td>";
                            echo "<td>" . htmlspecialchars($invoice['PostingDate']) .  "</td>";
                            echo '<td class="text-center">';
                            echo '<a href="./view-invoice.php?invoice_id=' . $invoice['InvoiceID'] . '" class="btn btn-xs btn-warning me-1">';
                            echo '<i alt="Edit" class="fa fa-pencil px-0 my-1"></i> Xem</a>';
                            echo '</td>';
                            echo "</tr>";
                        }
                        echo '</tbody>';
                        echo '</div>';
                        echo '</table>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php
    include_once __DIR__ . '/../../partials/footer.php';
    ?>
    <script>
        $(document).ready(function() {
            $("#customerForm").validate({
                rules: {
                    invoiceid: {
                        required: true,
                        number: true,
                    },
                },
                messages: {
                    invoiceid: {
                        required: "Vui lòng nhập số hóa đơn",
                        number: "Vui lòng nhập số hóa đơn",
                    },
                },
                errorElement: "div",
                errorPlacement: function(error, element) {
                    error.addClass("invalid-feedback");
                    error.insertAfter(element);
                },
                highlight: function(element) {
                    $(element).addClass("is-invalid").removeClass("is-valid");
                },
                unhighlight: function(element) {
                    $(element).addClass("is-valid").removeClass("is-invalid");
                },
            });
        });
    </script>
</body>

</html>