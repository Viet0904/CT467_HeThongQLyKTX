<?php
require_once __DIR__ . '/../../config/dbadmin.php';
if (empty($_GET['client_id'])) {
    echo "
    <script>
    alert('Không tìm thấy khách hàng')
    window.location.href='./manageclients.php';
    </script>
    ";
} elseif (isset($_GET['client_id']) && is_numeric($_GET['client_id'])) {
    $clientID = (int)$_GET['client_id'];
    // Xóa tất cả các hóa đơn liên quan đến khách hàng
    $queryDeleteInvoices = $dbh->prepare("DELETE FROM tblinvoice WHERE ClientID = :clientID");
    $queryDeleteInvoices->bindParam('clientID', $clientID, PDO::PARAM_INT);
    // Xóa tất cả các dòng trong bảng tblinvoices liên quan đến khách hàng
    if ($queryDeleteInvoices->execute()) {
        // Xóa khách hàng sau khi đã xóa hóa đơn
        $queryDeleteClient = $dbh->prepare("DELETE FROM tblclient WHERE ClientID = :clientID");
        $queryDeleteClient->bindParam('clientID', $clientID, PDO::PARAM_INT);
        if ($queryDeleteClient->execute()) {
            // Xóa thành công, chuyển hướng trở lại trang quản lý khách hàng
            echo "<script>alert('Xóa khách hàng thành công.')
            window.location.href = './manageclients.php'</script>";
        } else {
            // Xảy ra lỗi khi xóa khách hàng
            echo "<script>alert('Lỗi khi xóa khách hàng: ";
        }
    } else {
        // Xảy ra lỗi khi xóa hóa đơn
        echo "<script>alert('Lỗi khi xóa hóa đơn: </script>";
    }
} else {
    echo "ID khách hàng không hợp lệ.";
}
