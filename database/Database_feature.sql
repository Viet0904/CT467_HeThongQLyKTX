use htqlktx;
-- Trigger tự động tăng số luong DaO
DELIMITER //
CREATE TRIGGER after_insert_ThuePhong
AFTER INSERT ON ThuePhong
FOR EACH ROW
BEGIN
    UPDATE Phong
    SET DaO = DaO + 1
    WHERE MaPhong = NEW.MaPhong;
END //
DELIMITER ;
-- Trigger khi xoá 1 sv
DELIMITER //
CREATE TRIGGER trg_after_delete_sinhvien
AFTER DELETE ON SinhVien
FOR EACH ROW
BEGIN
    UPDATE Phong
    SET Dao = Dao - 1
    WHERE MaPhong = (SELECT MaPhong FROM ThuePhong WHERE MaSinhVien = OLD.MaSinhVien);
END//
DELIMITER ;

--  Trigger tự động cập nhật TongTien
-- Tạo trigger để cập nhật TongTien khi có thay đổi trong bảng DienNuoc
DELIMITER //
CREATE TRIGGER update_tongtien_update
BEFORE UPDATE ON DienNuoc
FOR EACH ROW
BEGIN
    SET NEW.TongTien = NEW.PhiDien + NEW.PhiNuoc;
END//
DELIMITER ;


-- Trigger xử lý khi thêm diennuoc
DELIMITER //
CREATE TRIGGER check_duplicate BEFORE INSERT ON DienNuoc
FOR EACH ROW
BEGIN
    DECLARE msg VARCHAR(255);
    IF EXISTS (SELECT 1 FROM DienNuoc WHERE maPhong = NEW.maPhong AND thang = NEW.thang AND namhoc = NEW.namhoc AND hocki = NEW.hocki) THEN
        SET msg = 'Không thể thêm trùng dữ liệu. Vui lòng thêm lại';
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = msg;
    END IF;
END//
DELIMITER ;




--  PROCEDURE GetDienNuoc
DELIMITER //
CREATE PROCEDURE GetDienNuoc(
    IN maPhong VARCHAR(50),
    IN thang INT,
    IN namhoc INT,
    IN hocki INT
)
BEGIN
    SELECT PhiDien, PhiNuoc 
    FROM diennuoc 
    WHERE MaPhong = maPhong 
      AND Thang = thang 
      AND NamHoc = namhoc 
      AND HocKi = hocki;
END//
DELIMITER ;