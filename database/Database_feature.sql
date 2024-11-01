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
-- Lấy MaPhongDangKy của SinhVien
DELIMITER //
CREATE FUNCTION GetMaPhongDangKy(p_MaSinhVien VARCHAR(8))
RETURNS VARCHAR(10)
DETERMINISTIC
BEGIN
    DECLARE v_MaPhongDangKy VARCHAR(10);
    SELECT MaPhongDangKy INTO v_MaPhongDangKy
    FROM SinhVien
    WHERE MaSinhVien = p_MaSinhVien;
    RETURN v_MaPhongDangKy;
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



-- Cập nhật đăng ký phòng cho sinh viên
DELIMITER //
CREATE PROCEDURE proc_dangkyphong(
    IN p_MaSinhVien VARCHAR(8),
    IN p_MaPhong VARCHAR(10)
)
BEGIN
    DECLARE v_error VARCHAR(255);

    -- Check if the student already has a pending registration
    IF EXISTS (SELECT 1 FROM SinhVien WHERE MaSinhVien = p_MaSinhVien AND MaPhongDangKy IS NOT NULL) THEN
        SET v_error = 'Sinh viên đã đăng ký phòng rồi.';
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = v_error;
    END IF;

    -- Check if the room exists
    IF NOT EXISTS (SELECT 1 FROM Phong WHERE MaPhong = p_MaPhong) THEN
        SET v_error = 'Phòng không tồn tại.';
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = v_error;
    ELSE
        -- Update the student's MaPhongDangKy field
        UPDATE SinhVien
        SET MaPhongDangKy = p_MaPhong
        WHERE MaSinhVien = p_MaSinhVien;
    END IF;
END//
DELIMITER ;
-- Huỷ Đăng Ký Phòng
DELIMITER //
CREATE PROCEDURE proc_huyDangKyPhong(IN p_MaSinhVien VARCHAR(8))
BEGIN
    DECLARE v_MaPhongDangKy VARCHAR(10);
    DECLARE v_error VARCHAR(255);

    -- Check if the student exists and has a registered room
    SELECT MaPhongDangKy INTO v_MaPhongDangKy
    FROM SinhVien
    WHERE MaSinhVien = p_MaSinhVien;

    IF v_MaPhongDangKy IS NULL THEN
        SET v_error = 'Sinh viên không có phòng đăng ký để hủy.';
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = v_error;
    ELSE
        -- Update MaPhongDangKy to NULL
        UPDATE SinhVien
        SET MaPhongDangKy = NULL
        WHERE MaSinhVien = p_MaSinhVien;
        -- Return success message
        SELECT 'Hủy đăng ký phòng thành công.' AS Message;
    END IF;
END //
DELIMITER ;
