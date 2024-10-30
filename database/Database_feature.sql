-- Tìm phòng còn trống
DELIMITER //
CREATE PROCEDURE TimPhongConTrong (IN maDayInput VARCHAR(10))
BEGIN
    SELECT MaPhong, TenPhong, ConTrong
    FROM Phong
    WHERE MaDay = maDayInput AND ConTrong > 0;
END //
DELIMITER ;

CALL TimPhongConTrong('D01');

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

-- Đăng ký phòng cho sinh viên
START TRANSACTION;
-- Kiểm tra phòng còn chỗ không
SET @conTrong = (SELECT ConTrong FROM Phong WHERE MaPhong = 'P01');
IF @conTrong > 0 THEN
    -- Thêm sinh viên vào phòng
    INSERT INTO SinhVien (MaSinhVien, HoTen, MaDay)
    VALUES ('SV02', 'Nguyen Van B', 'P01');

    -- Cập nhật số chỗ còn trống của phòng
    UPDATE Phong
    SET DaO = DaO + 1,
        ConTrong = ConTrong - 1
    WHERE MaPhong = 'P01';

    COMMIT;
ELSE
    ROLLBACK;
END IF;



-- Tính tổng số tiền phải đóng của tất cả sinh viên trong phòng
DELIMITER //
CREATE FUNCTION TongTienCuaPhong(maPhongInput VARCHAR(10), soThangO INT)
RETURNS DECIMAL(10,2)
DETERMINISTIC
BEGIN
    DECLARE giaThue DECIMAL(10,2);
    SELECT GiaThue INTO giaThue
    FROM Phong
    WHERE MaPhong = maPhongInput;
    RETURN giaThue * soThangO * (SELECT COUNT(*) FROM SinhVien WHERE MaDay = maPhongInput);
END //
DELIMITER ;
SELECT TongTienCuaPhong('P01', 6) AS TongTien;




