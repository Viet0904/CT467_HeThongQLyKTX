<?php
include_once __DIR__ . '/../../../config/dbadmin.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy dữ liệu từ form
    $maPhong = $_POST['maphong'] ?? '';
    $maDay = $_POST['MaDay'];
    $tenPhong = $_POST['tenphong'];
    $loaiPhong = $_POST['loaiphong'];
    $dienTich = $_POST['dientich'];
    $soGiuong = $_POST['sogiuong'];
    $sucChua = $_POST['succhua'];
    $soChoThucTe = $_POST['sochothucte'];
    $daO = $_POST['dao'];
    $giaThue = str_replace(',', '', $_POST['giathue']);
    $trangThai = $_POST['trangthai'];

    try {
        if (empty($maPhong)) {
            // Thêm phòng mới
            $stmt = $dbh->prepare("INSERT INTO Phong (MaDay, TenPhong, LoaiPhong, DienTich, SoGiuong, SucChua, SoChoThucTe, DaO, GiaThue, TrangThaiSuDung) VALUES (:MaDay, :TenPhong, :LoaiPhong, :DienTich, :SoGiuong, :SucChua, :SoChoThucTe, :DaO, :GiaThue, :TrangThaiSuDung)");
            $stmt->execute([
                ':MaDay' => $maDay,
                ':TenPhong' => $tenPhong,
                ':LoaiPhong' => $loaiPhong,
                ':DienTich' => $dienTich,
                ':SoGiuong' => $soGiuong,
                ':SucChua' => $sucChua,
                ':SoChoThucTe' => $soChoThucTe,
                ':DaO' => $daO,
                ':GiaThue' => $giaThue,
                ':TrangThaiSuDung' => $trangThai,
            ]);
            $message = "Thêm phòng thành công.";
        } else {
            // Cập nhật phòng hiện có
            $stmt = $dbh->prepare("UPDATE Phong SET MaDay = :MaDay, TenPhong = :TenPhong, LoaiPhong = :LoaiPhong, DienTich = :DienTich, SoGiuong = :SoGiuong, SucChua = :SucChua, SoChoThucTe = :SoChoThucTe, DaO = :DaO, GiaThue = :GiaThue, TrangThaiSuDung = :TrangThaiSuDung WHERE MaPhong = :MaPhong");
            $stmt->execute([
                ':MaPhong' => $maPhong,
                ':MaDay' => $maDay,
                ':TenPhong' => $tenPhong,
                ':LoaiPhong' => $loaiPhong,
                ':DienTich' => $dienTich,
                ':SoGiuong' => $soGiuong,
                ':SucChua' => $sucChua,
                ':SoChoThucTe' => $soChoThucTe,
                ':DaO' => $daO,
                ':GiaThue' => $giaThue,
                ':TrangThaiSuDung' => $trangThai,
            ]);
            $message = "Cập nhật phòng thành công.";
        }

        // Chuyển hướng với thông báo
        echo "<script>
                alert('$message');
                window.location.href = './../room_list.php';
              </script>";
    } catch (PDOException $e) {
        // Xử lý lỗi nếu có
        $errorMessage = "Có lỗi xảy ra: " . $e->getMessage();
        echo "<script>
                alert('$errorMessage');
                window.location.href = './../room_list.php';
              </script>";
    }
} else {
    // Nếu form không được gửi đúng cách, chuyển hướng đến danh sách phòng
    header("Location: ./../room_list.php");
    exit();
}
?>