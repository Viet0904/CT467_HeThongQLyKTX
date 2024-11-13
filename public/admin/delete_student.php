<?php
include_once __DIR__ . '/../../config/dbadmin.php'; // Kết nối cơ sở dữ liệu
include_once __DIR__ . '/../../partials/header.php'; // Tiêu đề trang
include_once __DIR__ . '/../../partials/heading.php'; // Đường dẫn

$maSinhVien = isset($_GET['msv']) ? $_GET['msv'] : null;

if ($maSinhVien) {
    $message = '';

    // Kiểm tra xem sinh viên có phòng không
    $checkRoomQuery = "SELECT MaPhong FROM ThuePhong WHERE MaSinhVien = :maSinhVien";
    $stmt = $dbh->prepare($checkRoomQuery);
    $stmt->bindParam(':maSinhVien', $maSinhVien, PDO::PARAM_STR);
    $stmt->execute();
    $room = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($room) {
        // Nếu sinh viên có phòng, gọi thủ tục Xóa sinh viên khỏi phòng
        $sql = "CALL XoaSinhVienDangCoPhong(?, @p_Message); SELECT @p_Message AS message";
        if ($stmt = $dbh->prepare($sql)) {
            $stmt->bindParam(1, $maSinhVien, PDO::PARAM_STR);
            $stmt->execute();
            $stmt->nextRowset();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $message = $result['message'];
        }
    } else {
        // Nếu sinh viên không có phòng, chỉ cần xóa sinh viên khỏi bảng SinhVien
        $deleteStudentQuery = "DELETE FROM SinhVien WHERE MaSinhVien = :maSinhVien";
        $stmt = $dbh->prepare($deleteStudentQuery);
        $stmt->bindParam(':maSinhVien', $maSinhVien, PDO::PARAM_STR);
        $stmt->execute();
        $message = "Sinh viên đã được xóa khỏi hệ thống";
    }

    echo "<script>
        alert('$message');
        window.location.href = './student_list.php';
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
