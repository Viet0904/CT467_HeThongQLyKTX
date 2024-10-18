<?php
require_once __DIR__ . '/../../config/dbadmin.php';
if (empty($_GET['service_id'])) {
    echo "
    <script>
    alert('Không tìm thấy dịch vụ')
    window.location.href='./Manageservices.php';
    </script>
    ";
} elseif (isset($_GET['service_id']) && is_numeric($_GET['service_id'])) {
    $serviceID = (int)$_GET['service_id'];
    // xóa các dòng từ bảng tblinvoice
    $query = $dbh->prepare("DELETE FROM tblinvoice WHERE ServiceId = :serviceID");
    $query->bindParam(':serviceID', $serviceID, PDO::PARAM_INT);
    // Xóa dịch vụ từ bảng tblservices
    if ($query->execute()) {
        //  xóa dịch vụ từ bảng tblservices
        $query = $dbh->prepare("DELETE FROM tblservices WHERE ID = :serviceID");
        $query->bindParam(':serviceID', $serviceID, PDO::PARAM_INT);

        if ($query->execute()) {
            header('Location: ./Manageservices.php'); // Chuyển hướng về trang quản lý dịch vụ
            exit();
        } else {
            // Xử lý khi có lỗi xảy ra trong quá trình xóa dịch vụ
            echo "<script>alert('Có lỗi xảy ra khi xóa dịch vụ.')</script>";
        }
    } else {
        // Xử lý khi có lỗi xảy ra trong quá trình xóa các dòng từ bảng tblinvoice
        echo "<script>alert('Có lỗi xảy ra khi xóa các dòng từ bảng tblinvoice.')</script>";
    }
} else {
    // Xử lý khi không có ID hoặc ID không hợp lệ
    echo "<script>alert('ID dịch vụ không hợp lệ.')</script>";
}
