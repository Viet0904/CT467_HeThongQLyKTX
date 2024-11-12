<?php
include_once __DIR__ . '/../../config/dbadmin.php'; // Kết nối cơ sở dữ liệu
include_once __DIR__ . '/../../partials/header.php'; // Tiêu đề trang
include_once __DIR__ . '/../../partials/heading.php'; // Đường dẫn

$maSinhVien = isset($_GET['msv']) ? $_GET['msv'] : null;
if ($maSinhVien) {
    $message = '';
    $sql = "CALL XoaSinhVienKhoiPhong(?, @p_Message); SELECT @p_Message AS message";

    if ($stmt = $dbh->prepare($sql)) {
        $stmt->bindParam(1, $maSinhVien, PDO::PARAM_STR);
        $stmt->execute();
        $stmt->nextRowset();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $message = $result['message'];
    }

    echo "<script>
        alert('$message');
        window.location.href = './dangkyphong_sv.php';
    </script>";
} else {
    echo "<script>
        alert('Mã sinh viên không hợp lệ');
        window.location.href = './index.php';
    </script>";
}



?>

<body>


</body>

</html>