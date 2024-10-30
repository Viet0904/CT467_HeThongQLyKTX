use htqlktx;
-- Tìm phòng còn trống dựa theo giới tính 
DELIMITER //
CREATE FUNCTION SoChoConLai(MaPhongInput VARCHAR(20)) 
RETURNS INT
DETERMINISTIC
BEGIN
    DECLARE SoChoConLai INT;
    SELECT (SoChoThucTe - DaO) INTO SoChoConLai
    FROM Phong
    WHERE MaPhong = MaPhongInput;
    RETURN SoChoConLai;
END //
DELIMITER ;


-- Tính số chỗ còn trống của 1 phòng
DELIMITER //
CREATE PROCEDURE TimPhongConTrongGioiTinh (IN gioiTinhInput VARCHAR(10))
BEGIN
    SELECT *, (SoChoThucTe - DaO) AS ConTrong
    FROM Phong
    WHERE LoaiPhong = gioiTinhInput
      AND (SoChoThucTe - DaO) > 0;
END //
DELIMITER ;







--  Tính tổng tiền thuê phòng của một sinh viên
DELIMITER //
CREATE FUNCTION TongTienThuePhong(maSinhVien VARCHAR(10), soThangO INT) 
RETURNS DECIMAL(10,2)
DETERMINISTIC
BEGIN
    DECLARE giaThue DECIMAL(10,2);
    SELECT p.GiaThue INTO giaThue
    FROM Phong p
    JOIN SinhVien s ON s.MaDay = p.MaDay
    WHERE s.MaSinhVien = maSinhVien;
    RETURN giaThue * soThangO;
END //
DELIMITER ;
SELECT TongTienThuePhong('SV01', 6) AS TongTien;


-- Tự động cập nhật số chỗ trống khi thêm sinh viên vào phòng
DELIMITER //
CREATE TRIGGER CapNhatSoChoKhiThemSinhVien 
AFTER INSERT ON SinhVien
FOR EACH ROW
BEGIN
    UPDATE Phong
    SET DaO = DaO + 1,
        ConTrong = ConTrong - 1
    WHERE MaPhong = NEW.MaDay;
END //
DELIMITER ;





