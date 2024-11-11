<?php
include_once __DIR__ . '/../../config/dbadmin.php';
// Gán giá trị từ $_GET
$maPhong = $_GET['id'] ?? '';
if ($maPhong) {
    // Chuẩn bị và thực thi câu lệnh SQL
    $stmt = $dbh->prepare("CALL DeleteDienNuocByID(?)");
    $stmt->bindValue(1, $maPhong, PDO::PARAM_INT);
    if ($stmt->execute()) {

        echo "<script>
            alert('Xóa thành công!');
            window.location.href='manage_diennuoc.php';
              </script>";
        exit();
    } else {
        $errorInfo = $stmt->errorInfo();
        echo "Lỗi: " . $errorInfo[2];
    }
    // Đóng kết nối
    $stmt->closeCursor();
} else {
    echo "ID không hợp lệ.";
}
