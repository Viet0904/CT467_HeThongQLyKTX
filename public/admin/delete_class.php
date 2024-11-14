<?php
include_once __DIR__ . '/../../config/dbadmin.php'; // Kết nối cơ sở dữ liệu
include_once __DIR__ . '/../../partials/header.php'; // Tiêu đề trang
include_once __DIR__ . '/../../partials/heading.php'; // Đường dẫn

$maLop = isset($_GET['maLop']) ? $_GET['maLop'] : null;

if ($maLop) {
    // Gọi stored procedure và nhận thông báo từ OUT parameter
    $stmt = $dbh->prepare("CALL DeleteLopIfNoStudents(:maLopParam, @resultMessage)");
    $stmt->bindParam(':maLopParam', $maLop, PDO::PARAM_STR);
    $stmt->execute();

    // Lấy thông báo từ OUT parameter
    $resultMessageStmt = $dbh->query("SELECT @resultMessage AS message");
    $result = $resultMessageStmt->fetch(PDO::FETCH_ASSOC);

    // Đặt thông báo
    $message = $result['message'];
    echo "<script>
        alert('$message');
        window.location.href = './manage_class.php';
    </script>";
} else {
    echo "<script>
        alert('Mã lớp không hợp lệ');
        window.location.href = './index.php';
    </script>";
}
?>

<body>
</body>

</html>
