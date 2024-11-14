<?php
include_once __DIR__ . '/../../config/dbadmin.php'; // Kết nối cơ sở dữ liệu
include_once __DIR__ . '/../../partials/header.php'; // Tiêu đề trang
include_once __DIR__ . '/../../partials/heading.php'; // Đường dẫn
$maLop = isset($_GET['maLop']) ? $_GET['maLop'] : null;
if ($maLop) {
    $message = '';
    // Kiểm tra xem lớp có sinh viên không
    $checkStudentQuery = "SELECT COUNT(*) AS student_count FROM SinhVien WHERE MaLop = :maLop";
    $stmt = $dbh->prepare($checkStudentQuery);
    $stmt->bindParam(':maLop', $maLop, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result['student_count'] > 0) {
        // Nếu có sinh viên trong lớp
        $message = "Không thể xóa lớp vì vẫn còn sinh viên trong lớp này.";
    } else {
        // Nếu không có sinh viên, tiến hành xóa lớp
        $deleteClassQuery = "DELETE FROM Lop WHERE MaLop = :maLop";
        $stmt = $dbh->prepare($deleteClassQuery);
        $stmt->bindParam(':maLop', $maLop, PDO::PARAM_STR);
        $stmt->execute();
        $message = "Lớp đã được xóa khỏi hệ thống.";
    }
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