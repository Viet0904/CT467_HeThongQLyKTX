<?php
include_once __DIR__ . '/../../../config/dbadmin.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve data from POST request
    $maPhong = $_POST['maphong'] ?? '';
    $maDay = $_POST['MaDay'];
    $tenPhong = $_POST['tenphong'];
    $loaiPhong = $_POST['loaiphong'];
    $dienTich = $_POST['dientich'];
    $sucChua = $_POST['succhua'];
    $soChoThucTe = $_POST['sochothucte'];
    $daO = $_POST['dao'];
    $giaThue = str_replace(',', '', $_POST['giathue']);
    $trangThai = $_POST['trangthai'];

    // Kiểm tra mã phòng mới nếu đang cập nhật
    $oldMaPhong = $_POST['old_maphong'] ?? ''; // Mã phòng cũ được truyền từ form

    // Kiểm tra xem mã phòng cũ đã tồn tại hay chưa
    $stmt = $dbh->prepare("SELECT COUNT(*) FROM Phong WHERE MaPhong = :oldMaPhong");
    $stmt->execute([':oldMaPhong' => $oldMaPhong]); // Chú ý sửa đây
    $oldExists = $stmt->fetchColumn() > 0;

    // Kiểm tra xem mã phòng mới đã tồn tại hay chưa
    $stmt = $dbh->prepare("SELECT COUNT(*) FROM Phong WHERE MaPhong = :maPhong");
    $stmt->execute([':maPhong' => $maPhong]); // Chú ý sửa đây
    $newExists = $stmt->fetchColumn() > 0;

    if (!$oldExists){
            // Thêm mới
            $stmt = $dbh->prepare("INSERT INTO Phong (MaPhong, MaDay, TenPhong, LoaiPhong, DienTich, SucChua, SoChoThucTe, DaO, GiaThue, TrangThaiSuDung) VALUES (:MaPhong, :MaDay, :TenPhong, :LoaiPhong, :DienTich, :SucChua, :SoChoThucTe, :DaO, :GiaThue, :TrangThaiSuDung)");
            $stmt->execute([
                ':MaPhong' => $maPhong,
                ':MaDay' => $maDay,
                ':TenPhong' => $tenPhong,
                ':LoaiPhong' => $loaiPhong,
                ':DienTich' => $dienTich,
                ':SucChua' => $sucChua,
                ':SoChoThucTe' => $soChoThucTe,
                ':DaO' => $daO,
                ':GiaThue' => $giaThue,
                ':TrangThaiSuDung' => $trangThai,
            ]);
            echo "<script>alert('Thêm phòng thành công.'); window.location.href = './../room_list.php';</script>";
            exit();
    } else {
            // Nếu mã phòng tồn tại
            // Mã phòng mới khác mã phòng cũ
            if ($oldMaPhong !== $maPhong) {
                if ($newExists) {
                    // Nếu mã phòng mới đã tồn tại, thông báo cho người dùng
                    echo "<script>alert('Mã phòng mới đã tồn tại. Vui lòng nhập mã phòng khác.');</script>";
                }else {
                    // Nếu mã phòng mới chưa tồn tại, tiến hành cập nhật mã phòng
                    $stmt = $dbh->prepare("UPDATE Phong SET MaPhong = :MaPhong, MaDay = :MaDay, TenPhong = :TenPhong, LoaiPhong = :LoaiPhong, DienTich = :DienTich, SucChua = :SucChua, SoChoThucTe = :SoChoThucTe, DaO = :DaO, GiaThue = :GiaThue, TrangThaiSuDung = :TrangThaiSuDung WHERE MaPhong = :OldMaPhong");
                    $stmt->execute([
                        ':MaPhong' => $maPhong,
                        ':OldMaPhong' => $oldMaPhong,
                        ':MaDay' => $maDay,
                        ':TenPhong' => $tenPhong,
                        ':LoaiPhong' => $loaiPhong,
                        ':DienTich' => $dienTich,
                        ':SucChua' => $sucChua,
                        ':SoChoThucTe' => $soChoThucTe,
                        ':DaO' => $daO,
                        ':GiaThue' => $giaThue,
                        ':TrangThaiSuDung' => $trangThai,
                    ]);
                    echo "<script>alert('Cập nhật phòng thành công.'); window.location.href = './../room_list.php';</script>";
                    exit();
                }
            }else{
                // Cập nhật thông tin phòng hiện tại
                $stmt = $dbh->prepare("UPDATE Phong SET MaDay = :MaDay, TenPhong = :TenPhong, LoaiPhong = :LoaiPhong, DienTich = :DienTich, SucChua = :SucChua, SoChoThucTe = :SoChoThucTe, DaO = :DaO, GiaThue = :GiaThue, TrangThaiSuDung = :TrangThaiSuDung WHERE MaPhong = :OldMaPhong");
                $stmt->execute([
                    ':OldMaPhong' => $oldMaPhong,
                    ':MaDay' => $maDay,
                    ':TenPhong' => $tenPhong,
                    ':LoaiPhong' => $loaiPhong,
                    ':DienTich' => $dienTich,
                    ':SucChua' => $sucChua,
                    ':SoChoThucTe' => $soChoThucTe,
                    ':DaO' => $daO,
                    ':GiaThue' => $giaThue,
                    ':TrangThaiSuDung' => $trangThai,
                ]);
                echo "<script>alert('Cập nhật phòng thành công.'); window.location.href = './../room_list.php';</script>";
                exit();
            }
    }
} else {
    // If the form is not submitted properly, redirect to room list
    header("Location: ./../room_list.php");
    exit();
}

?>