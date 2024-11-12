use htqlktx;
-- Tính số chổ còn lại
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
CREATE TRIGGER check_and_prevent BEFORE INSERT ON DienNuoc
FOR EACH ROW
BEGIN
    DECLARE msg VARCHAR(255);
    -- Check for duplicate entries
    IF EXISTS (SELECT 1 FROM DienNuoc WHERE maPhong = NEW.maPhong AND thang = NEW.thang AND namhoc = NEW.namhoc AND hocki = NEW.hocki) THEN
        SET msg = 'Không thể thêm trùng dữ liệu. Vui lòng thêm lại';
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = msg;
    END IF;
    -- Prevent negative values for PhiDien and PhiNuoc
    IF NEW.PhiDien < 0 OR NEW.PhiNuoc < 0 THEN
        SET msg = 'Phí Điện và Phí Nước không thể âm';
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = msg;
    END IF;
END//
DELIMITER ;
-- Trigger to prevent negative values for PhiDien and PhiNuoc on update
DELIMITER //
CREATE TRIGGER prevent_negative_values_update
BEFORE UPDATE ON DienNuoc
FOR EACH ROW
BEGIN
    DECLARE msg VARCHAR(255);
    IF NEW.PhiDien < 0 OR NEW.PhiNuoc < 0 THEN
        SET msg = 'Phí Điện và Phí Nước không thể âm';
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

-- Function xoá dòng 
DELIMITER //
CREATE PROCEDURE DeleteDienNuocByID(IN p_ID INT)
BEGIN
    DELETE FROM DienNuoc WHERE ID = p_ID;
END //
DELIMITER ; 

-- Viết 1 PROCEDURE chứa Transction để thanh toán điện nước
DELIMITER //
CREATE PROCEDURE ThanhToanDienNuoc (
    IN p_MaPhong VARCHAR(10),
    IN p_Thang INT,
    IN p_NamHoc VARCHAR(50),
    IN p_HocKi ENUM('1', '2', '3'),
    OUT p_Message VARCHAR(255),
    OUT p_ErrorCode INT
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        -- Nếu có lỗi, rollback và trả về mã lỗi
        ROLLBACK;
        SET p_Message = 'Lỗi khi thanh toán điện nước';
        SET p_ErrorCode = 1;
    END;
    -- Bắt đầu giao dịch
    START TRANSACTION;
    -- Cập nhật ngày thanh toán và kiểm tra nếu thành công
    UPDATE DienNuoc
    SET NgayThanhToan = NOW(), TongTien = IF(ROW_COUNT() > 0, 0, TongTien)
    WHERE MaPhong = p_MaPhong
      AND Thang = p_Thang
      AND NamHoc = p_NamHoc
      AND HocKi = p_HocKi;

    -- Kiểm tra nếu cập nhật thành công
    IF ROW_COUNT() > 0 THEN
        COMMIT;
        SET p_Message = 'Thanh toán thành công';
        SET p_ErrorCode = 0;
    ELSE
        ROLLBACK;
        SET p_Message = 'Không tìm thấy bản ghi để cập nhật';
        SET p_ErrorCode = 2;
    END IF;
END //
DELIMITER ;


-- Hàm Đăng ký 
DELIMITER //
CREATE PROCEDURE DangKyPhong(
    IN p_MaSinhVien VARCHAR(10),
    IN p_MaPhong VARCHAR(10),
    IN p_BatDau DATE,
    IN p_KetThuc DATE,
    OUT p_Message VARCHAR(100)
)
BEGIN
    DECLARE v_GioiTinhSV VARCHAR(10);
    DECLARE v_LoaiPhong ENUM('Nam', 'Nữ');
    DECLARE v_SoChoConLai INT;
    DECLARE v_GiaThue DECIMAL(10, 2);
    DECLARE v_HopDongID INT;
    DECLARE v_Count INT;
    -- Kiểm tra sinh viên đã đăng ký phòng chưa
    SELECT COUNT(*) INTO v_Count 
    FROM ThuePhong 
    WHERE MaSinhVien = p_MaSinhVien;
    IF v_Count > 0 THEN
        SET p_Message = 'Sinh viên đã có phòng';
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = p_Message;
    END IF;
    -- Lấy thông tin giới tính của sinh viên
    SELECT GioiTinh INTO v_GioiTinhSV
    FROM SinhVien
    WHERE MaSinhVien = p_MaSinhVien;
    -- Lấy thông tin phòng
    SELECT LoaiPhong, (SucChua - DaO), GiaThue INTO v_LoaiPhong, v_SoChoConLai, v_GiaThue
    FROM Phong
    WHERE MaPhong = p_MaPhong;
    -- Kiểm tra giới tính phòng
    IF v_GioiTinhSV != v_LoaiPhong THEN
        SET p_Message = 'Giới tính không phù hợp với loại phòng';
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = p_Message;
    END IF;
    -- Kiểm tra còn chỗ không
    IF v_SoChoConLai <= 0 THEN
        SET p_Message = 'Phòng đã đầy';
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = p_Message;
    END IF;
    -- Bắt đầu transaction
    START TRANSACTION;
    -- Thêm vào bảng ThuePhong
    INSERT INTO ThuePhong (MaSinhVien, MaPhong, BatDau, KetThuc, GiaThueThucTe)
    VALUES (p_MaSinhVien, p_MaPhong, p_BatDau, p_KetThuc, v_GiaThue);
    -- Lấy ID của hợp đồng vừa thêm
    SET v_HopDongID = LAST_INSERT_ID();
    -- Thêm vào bảng TT_ThuePhong cho tháng đầu tiên
    INSERT INTO TT_ThuePhong (MaHopDong, ThangNam, SoTien)
    VALUES (v_HopDongID, p_BatDau, v_GiaThue);
    -- Cập nhật số lượng người ở trong phòng
    UPDATE Phong 
    SET DaO = DaO + 1
    WHERE MaPhong = p_MaPhong;
    COMMIT;
    SET p_Message = 'Đăng ký phòng thành công';
END //
DELIMITER ;

