<?php
include_once __DIR__ . '/../../../config/dbadmin.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy dữ liệu từ POST request
    $maNhanVien = $_POST['MaNhanVien'] ?? '';
    $hoTen = $_POST['HoTen'] ?? '';
    $chucVu = $_POST['Role'] ?? '';
    $sdt = $_POST['SDT'] ?? '';
    $gioiTinh = $_POST['GioiTinh'] ?? '';
    $ghiChu = $_POST['GhiChu'] ?? ''; // Lấy ghi chú từ form
    $ngaySinh = $_POST['NgaySinh'] ?? ''; // Lấy ngày sinh từ form

    // Mã nhân viên cũ được truyền từ form (không cần kiểm tra nữa vì mã nhân viên không thay đổi)
    $oldMaNhanVien = $_POST['old_manhanvien'] ?? '';

    // Cập nhật thông tin nhân viên
    try {
        $stmt = $dbh->prepare("UPDATE NhanVien 
                               SET HoTen = :HoTen, Role = :Role, SDT = :SDT, 
                                   GhiChu = :GhiChu, NgaySinh = :NgaySinh, GioiTinh = :GioiTinh 
                               WHERE MaNhanVien = :OldMaNhanVien");
        $stmt->execute([
            ':OldMaNhanVien' => $oldMaNhanVien,  
            ':HoTen' => $hoTen,
            ':Role' => $chucVu,
            ':SDT' => $sdt,
            ':GhiChu' => $ghiChu,    
            ':NgaySinh' => $ngaySinh, 
            ':GioiTinh' => $gioiTinh,
        ]);

        echo "<script>alert('Cập nhật nhân viên thành công.'); window.location.href = './../employees_list.php';</script>";
        exit();
    } catch (PDOException $e) {
        echo "<script>alert('Có lỗi xảy ra khi cập nhật dữ liệu. Vui lòng thử lại.'); window.location.href = './../employees_list.php';</script>";
        exit();
    }
} else {
    // Nếu form không được gửi đúng, chuyển hướng về danh sách nhân viên và thông báo lỗi
    echo "<script>alert('Dữ liệu không hợp lệ. Vui lòng thử lại.'); window.location.href = './../employees_list.php';</script>";
    exit();
}
?>
