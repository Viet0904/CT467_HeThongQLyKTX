<?php
include_once __DIR__ . '/../../../config/dbadmin.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve data from POST request
    $maPhong = $_POST['maphong'] ?? ''; // Default to empty string if not set
    $maDay = $_POST['MaDay'];
    $tenPhong = $_POST['tenphong'];
    $loaiPhong = $_POST['loaiphong'];
    $dienTich = $_POST['dientich'];
    $soGiuong = $_POST['sogiuong'];
    $sucChua = $_POST['succhua'];
    $soChoThucTe = $_POST['sochothucte'];
    $daO = $_POST['dao'];
    $conTrong = $_POST['trong'];
    $giaThue = str_replace(',', '', $_POST['giathue']); // Remove commas for decimal
    $trangThai = $_POST['trangthai'];

    if (empty($maPhong)) {
        // Create new room
        $stmt = $dbh->prepare("INSERT INTO Phong (MaDay, TenPhong, LoaiPhong, DienTich, SoGiuong, SucChua, SoChoThucTe, DaO, ConTrong, GiaThue, TrangThaiSuDung) VALUES (:MaDay, :TenPhong, :LoaiPhong, :DienTich, :SoGiuong, :SucChua, :SoChoThucTe, :DaO, :ConTrong, :GiaThue, :TrangThaiSuDung)");
        $stmt->execute([
            ':MaDay' => $maDay,
            ':TenPhong' => $tenPhong,
            ':LoaiPhong' => $loaiPhong,
            ':DienTich' => $dienTich,
            ':SoGiuong' => $soGiuong,
            ':SucChua' => $sucChua,
            ':SoChoThucTe' => $soChoThucTe,
            ':DaO' => $daO,
            ':ConTrong' => $conTrong,
            ':GiaThue' => $giaThue,
            ':TrangThaiSuDung' => $trangThai,
        ]);
        $message = "Thêm phòng thành công.";
    } else {
        // Update existing room
        $stmt = $dbh->prepare("UPDATE Phong SET MaDay = :MaDay, TenPhong = :TenPhong, LoaiPhong = :LoaiPhong, DienTich = :DienTich, SoGiuong = :SoGiuong, SucChua = :SucChua, SoChoThucTe = :SoChoThucTe, DaO = :DaO, ConTrong = :ConTrong, GiaThue = :GiaThue, TrangThaiSuDung = :TrangThaiSuDung WHERE MaPhong = :MaPhong");
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
            ':ConTrong' => $conTrong,
            ':GiaThue' => $giaThue,
            ':TrangThaiSuDung' => $trangThai,
        ]);
        $message = "Cập nhật phòng thành công.";
    }

    echo "<script>
            alert('$message');
            window.location.href = './../room_list.php';
          </script>";
    exit();
} else {
    // If the form is not submitted properly, redirect to room list
    header("Location: ./../room_list.php");
    exit();
}
?>