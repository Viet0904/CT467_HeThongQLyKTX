<?php
// Kết nối với cơ sở dữ liệu
include_once __DIR__ . '/../../config/dbadmin.php';

// Kiểm tra xem có mã sinh viên trong query string không
if (isset($_GET['msv'])) {
    // Lấy mã sinh viên từ query string
    $maSinhVien = $_GET['msv'];

    try {
        // Chuẩn bị câu truy vấn DELETE
        $query = "DELETE FROM SinhVien WHERE MaSinhVien = :maSinhVien";
        $stmt = $dbh->prepare($query);
        
        // Gắn giá trị cho tham số :maSinhVien
        $stmt->bindParam(':maSinhVien', $maSinhVien, PDO::PARAM_STR);

        // Thực thi câu truy vấn
        if ($stmt->execute()) {
            // Nếu xoá thành công, chuyển hướng về trang danh sách sinh viên
            header("Location: manage_student.php");
            exit();
        } else {
            echo "Xoá không thành công.";
        }
    } catch (PDOException $e) {
        // Xử lý lỗi nếu có
        echo "Lỗi: " . $e->getMessage();
    }
} else {
    echo "Không tìm thấy mã sinh viên.";
}
?>
