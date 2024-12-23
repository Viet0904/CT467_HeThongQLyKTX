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
-- Tính Tổng Số SV trong Phòng
DELIMITER //
CREATE FUNCTION TongSoSinhVienTrongPhong(MaPhongInput VARCHAR(10))
RETURNS INT
DETERMINISTIC
BEGIN
    DECLARE TongSoSV VARCHAR(10);
    SELECT COUNT(*) INTO TongSoSV
    FROM ThuePhong 
    WHERE MaPhong = MaPhongInput;
    -- Nếu không có sinh viên nào thì trả về 1
    IF (TongSoSV <= 0) THEN
        SET TongSoSV = 1;
    END IF;
    RETURN TongSoSV;
END //
DELIMITER ;

-- Tính Phí Diện Nước từng sinh viên
DELIMITER //
CREATE FUNCTION TongPhiMoiSinhVien(MaPhong VARCHAR(10), PhiDien DECIMAL(10, 2), PhiNuoc DECIMAL(10, 2))
RETURNS DECIMAL(10, 2)
DETERMINISTIC
BEGIN
    DECLARE TongSoSV VARCHAR(10);
    DECLARE TongPhiSV DECIMAL(10, 2);
    SELECT COUNT(*) INTO TongSoSV
    FROM ThuePhong
    WHERE MaPhong = MaPhong;
    IF TongSoSV <= 0 THEN
        SET TongSoSV = 1;
    END IF;
    SET TongPhiSV = (PhiDien / TongSoSV) + (PhiNuoc / TongSoSV);
    RETURN TongPhiSV;
END//
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


-- Trigger tự động tính TongTien khi insert vào bảng DienNuoc
DELIMITER //
CREATE TRIGGER update_tongtien_insert
BEFORE INSERT ON DienNuoc
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

-- PROCEDURE GetDienNuoc
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
    SET NgayThanhToan = NOW(), TongTien = 0
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
    IN p_HocKi ENUM('1', '2', '3'),
    IN p_NamHoc VARCHAR(50),
    OUT p_Message VARCHAR(100)
)
BEGIN
    DECLARE v_GioiTinhSV VARCHAR(10);
    DECLARE v_LoaiPhong ENUM('Nam', 'Nữ');
    DECLARE v_SoChoConLai INT;
    DECLARE v_GiaThue DECIMAL(10, 2);
    DECLARE v_HopDongID INT;
    DECLARE v_Count INT;
    -- Kiểm tra sinh viên đã đăng ký phòng cho học kỳ và năm học cụ thể chưa
    SELECT COUNT(*) INTO v_Count 
    FROM ThuePhong 
    WHERE MaSinhVien = p_MaSinhVien
      AND HocKi = p_HocKi
      AND NamHoc = p_NamHoc;
    IF v_Count > 0 THEN
        SET p_Message = 'Sinh viên đã có phòng trong học kỳ này';
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
    INSERT INTO ThuePhong (MaSinhVien, MaPhong, HocKi, NamHoc, GiaThueThucTe)
    VALUES (p_MaSinhVien, p_MaPhong, p_HocKi, p_NamHoc, v_GiaThue);
    -- Lấy ID của hợp đồng vừa thêm
    SET v_HopDongID = LAST_INSERT_ID();
    -- Thêm vào bảng TT_ThuePhong cho tháng đầu tiên (tháng mặc định hoặc tháng đầu của học kỳ)
    INSERT INTO TT_ThuePhong (MaHopDong, ThangNam, SoTien)
    VALUES (v_HopDongID, CURDATE(), v_GiaThue);
    COMMIT;
    -- Thông báo đăng ký thành công
    SET p_Message = 'Đăng ký phòng thành công';
END //
DELIMITER ;

-- Hàm Đăng ký 
DELIMITER //
CREATE PROCEDURE ChuyenPhong(
    IN p_MaSinhVien VARCHAR(10),
    IN p_MaPhong VARCHAR(10),
    IN p_HocKi ENUM('1', '2', '3'),
    IN p_NamHoc VARCHAR(50),
    OUT p_Message VARCHAR(100)
)
BEGIN
    DECLARE v_GioiTinhSV VARCHAR(10);
    DECLARE v_LoaiPhong ENUM('Nam', 'Nữ');
    DECLARE v_SoChoConLai INT;
    DECLARE v_GiaThue DECIMAL(10, 2);
    DECLARE v_HopDongID INT;
    DECLARE v_Count INT;
    DECLARE v_OldMaPhong VARCHAR(10);
    DECLARE v_OldHopDongID INT;
    -- Kiểm tra sinh viên đã đăng ký phòng cho học kỳ và năm học cụ thể chưa
    SELECT COUNT(*) INTO v_Count 
    FROM ThuePhong 
    WHERE MaSinhVien = p_MaSinhVien
      AND HocKi = p_HocKi
      AND NamHoc = p_NamHoc;
    IF v_Count > 0 THEN
        -- Lấy thông tin phòng cũ và hợp đồng cũ
        SELECT MaPhong, MaHopDong INTO v_OldMaPhong, v_OldHopDongID
        FROM ThuePhong
        WHERE MaSinhVien = p_MaSinhVien
          AND HocKi = p_HocKi
          AND NamHoc = p_NamHoc;
    END IF;
    -- Lấy thông tin giới tính của sinh viên
    SELECT GioiTinh INTO v_GioiTinhSV
    FROM SinhVien
    WHERE MaSinhVien = p_MaSinhVien;
    -- Lấy thông tin phòng mới
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
    -- Nếu sinh viên đã có phòng, xóa phòng cũ và hợp đồng cũ
    IF v_Count > 0 THEN
        DELETE FROM TT_ThuePhong WHERE MaHopDong = v_OldHopDongID;
        DELETE FROM ThuePhong WHERE MaHopDong = v_OldHopDongID;
    END IF;
    -- Thêm vào bảng ThuePhong
    INSERT INTO ThuePhong (MaSinhVien, MaPhong, HocKi, NamHoc, GiaThueThucTe)
    VALUES (p_MaSinhVien, p_MaPhong, p_HocKi, p_NamHoc, v_GiaThue);
    -- Lấy ID của hợp đồng vừa thêm
    SET v_HopDongID = LAST_INSERT_ID();
    -- Thêm vào bảng TT_ThuePhong cho tháng đầu tiên (tháng mặc định hoặc tháng đầu của học kỳ)
    INSERT INTO TT_ThuePhong (MaHopDong, ThangNam, SoTien)
    VALUES (v_HopDongID, CURDATE(), v_GiaThue);
    COMMIT;
    -- Thông báo đăng ký phòng thành công
    SET p_Message = 'Đăng ký phòng thành công';
END //
DELIMITER ;

-- Hàm Xoá Sinh Viên khỏi phòng
DELIMITER //
CREATE PROCEDURE XoaSinhVienKhoiPhong(
    IN p_MaSinhVien VARCHAR(10),
    OUT p_Message VARCHAR(100)
)
BEGIN
    DECLARE v_HopDongID INT;
    DECLARE v_Count INT;
    -- Kiểm tra sinh viên có tồn tại trong ThuePhong không
    SELECT COUNT(*) INTO v_Count 
    FROM ThuePhong 
    WHERE MaSinhVien = p_MaSinhVien;
    IF v_Count = 0 THEN
        SET p_Message = 'Sinh viên không tồn tại trong ThuePhong';
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = p_Message;
    END IF;
    -- Lấy ID của hợp đồng thuê phòng
    SELECT MaHopDong INTO v_HopDongID
    FROM ThuePhong
    WHERE MaSinhVien = p_MaSinhVien;
    -- Bắt đầu transaction
    START TRANSACTION;
    -- Xoá các bản ghi trong TT_ThuePhong
    DELETE FROM TT_ThuePhong WHERE MaHopDong = v_HopDongID;
    -- Xoá bản ghi trong ThuePhong
    DELETE FROM ThuePhong WHERE MaHopDong = v_HopDongID;
    COMMIT;
    -- Thông báo xoá thành công
    SET p_Message = 'Xoá sinh viên khỏi phòng thành công';
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE XoaPhongVaDuLieuLienQuan(
    IN p_MaPhong VARCHAR(10),
    OUT p_Message VARCHAR(255),
    OUT p_ErrorCode INT
)
BEGIN
    DECLARE v_DaO INT;
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        -- Nếu có lỗi, rollback và trả về mã lỗi
        ROLLBACK;
        SET p_Message = 'Có lỗi xảy ra khi xóa phòng hoặc dữ liệu liên quan';
        SET p_ErrorCode = 1;
    END;
    -- Kiểm tra số lượng người ở trong bảng ThuePhong
    SELECT DaO INTO v_DaO
    FROM Phong
    WHERE MaPhong = p_MaPhong;
    IF v_DaO = 0 THEN
        -- Bắt đầu transaction
        START TRANSACTION;
        -- Xóa dữ liệu phòng trong bảng DienNuoc
        DELETE FROM DienNuoc WHERE MaPhong = p_MaPhong;
        -- Xóa phòng trong bảng Phong
        DELETE FROM Phong WHERE MaPhong = p_MaPhong;
        COMMIT;
        SET p_Message = 'Xóa phòng và dữ liệu liên quan thành công';
        SET p_ErrorCode = 0;
    ELSE
        SET p_Message = 'Không thể xóa phòng vì còn người ở';
        SET p_ErrorCode = 2;
    END IF;
END //
DELIMITER ;

-- Hàm Xoá Sinh Viên đang có phòng khỏi bảng SinhVien
DELIMITER //
CREATE PROCEDURE XoaSinhVienDangCoPhong(
    IN p_MaSinhVien VARCHAR(10),
    OUT p_Message VARCHAR(100)
)
BEGIN
    DECLARE v_HopDongID INT;
    DECLARE v_Count INT;
    -- Kiểm tra sinh viên có tồn tại trong ThuePhong không
    SELECT COUNT(*) INTO v_Count 
    FROM ThuePhong 
    WHERE MaSinhVien = p_MaSinhVien;
    IF v_Count = 0 THEN
        SET p_Message = 'Sinh viên không tồn tại trong ThuePhong';
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = p_Message;
    END IF;
    -- Lấy ID của hợp đồng thuê phòng
    SELECT MaHopDong INTO v_HopDongID
    FROM ThuePhong
    WHERE MaSinhVien = p_MaSinhVien;
    -- Bắt đầu transaction
    START TRANSACTION;
    -- Xoá các bản ghi trong TT_ThuePhong
    DELETE FROM TT_ThuePhong WHERE MaHopDong = v_HopDongID;
    -- Xoá bản ghi trong ThuePhong
    DELETE FROM ThuePhong WHERE MaHopDong = v_HopDongID;
    -- Xoá bản ghi trong SinhVien
    DELETE FROM SinhVien WHERE MaSinhVien = p_MaSinhVien;
    COMMIT;
    -- Thông báo xoá thành công
    SET p_Message = 'Xoá sinh viên khỏi phòng và hệ thống thành công';
END //
DELIMITER ;

-- Hàm Thêm Khu KTX
DELIMITER //
CREATE PROCEDURE ThemKhuKTX(
    IN p_MaKhu VARCHAR(10),
    IN p_TenKhu VARCHAR(50),
    OUT p_Message VARCHAR(255),
    OUT p_ErrorCode INT
)
BEGIN
    DECLARE v_Count INT;
    -- Kiểm tra mã khu đã tồn tại chưa
    SELECT COUNT(*) INTO v_Count
    FROM KhuKTX
    WHERE MaKhuKTX = p_MaKhu;
    IF v_Count > 0 THEN
        SET p_Message = 'Mã khu đã tồn tại';
        SET p_ErrorCode = 1;
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = p_Message;
    ELSE
        -- Bắt đầu transaction
        START TRANSACTION;
        -- Thêm khu vào bảng KhuKTX
        INSERT INTO KhuKTX (MaKhuKTX, TenKhuKTX)
        VALUES (p_MaKhu, p_TenKhu);
        COMMIT;
        SET p_Message = 'Thêm khu KTX thành công';
        SET p_ErrorCode = 0;
    END IF;
END //
DELIMITER ;
-- Hàm Sửa Khu KTX
DELIMITER //
CREATE PROCEDURE SuaKhuKTX(
    IN p_MaKhu VARCHAR(10),
    IN p_TenKhu VARCHAR(50),
    OUT p_Message VARCHAR(255),
    OUT p_ErrorCode INT
)
BEGIN
    DECLARE v_Count INT;
    -- Kiểm tra mã khu có tồn tại không
    SELECT COUNT(*) INTO v_Count
    FROM KhuKTX
    WHERE MaKhuKTX = p_MaKhu;

    IF v_Count > 0 THEN
        -- Nếu MaKhuKTX đã tồn tại, thực hiện cập nhật TenKhuKTX
        START TRANSACTION;
        UPDATE KhuKTX
        SET TenKhuKTX = p_TenKhu
        WHERE MaKhuKTX = p_MaKhu;
        COMMIT;

        SET p_Message = 'Cập nhật tên khu KTX thành công';
        SET p_ErrorCode = 0;
    ELSE
        -- Nếu MaKhuKTX không tồn tại
        SET p_Message = 'Mã Khu KTX không tồn tại';
        SET p_ErrorCode = 1;
    END IF;
END //
DELIMITER ;



-- Hàm xoá KTX với kiểm tra khóa ngoại
DELIMITER //
CREATE PROCEDURE XoaKhuKTX(
    IN p_MaKhu VARCHAR(10),
    OUT p_Message VARCHAR(255),
    OUT p_ErrorCode INT
)
BEGIN
    DECLARE v_Count INT;
    DECLARE v_DayCount INT;
    -- Kiểm tra mã khu có tồn tại không
    SELECT COUNT(*) INTO v_Count
    FROM KhuKTX
    WHERE MaKhuKTX = p_MaKhu;
    IF v_Count = 0 THEN
        SET p_Message = 'Mã khu không tồn tại';
        SET p_ErrorCode = 1;
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = p_Message;
    END IF;
    -- Kiểm tra xem có dãy nào đang là khóa ngoại của KTX không
    SELECT COUNT(*) INTO v_DayCount
    FROM Day
    WHERE MaKhuKTX = p_MaKhu;
    IF v_DayCount > 0 THEN
        SET p_Message = 'Không thể xóa khu vì có dãy đang là khóa ngoại của KTX';
        SET p_ErrorCode = 2;
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = p_Message;
    END IF;
    -- Bắt đầu transaction
    START TRANSACTION;
    -- Xoá khu trong bảng KhuKTX
    DELETE FROM KhuKTX WHERE MaKhuKTX = p_MaKhu;
    COMMIT;
    SET p_Message = 'Xoá khu KTX thành công';
    SET p_ErrorCode = 0;
END //
DELIMITER ;




-- Thêm Dãy
DELIMITER //
CREATE PROCEDURE ThemDay(
    IN p_MaDay VARCHAR(10),
    IN p_TenDay VARCHAR(50),
    IN p_MaKhuKTX VARCHAR(10),
    OUT p_Message VARCHAR(255),
    OUT p_ErrorCode INT
)
BEGIN
    DECLARE v_Count INT;
    -- Kiểm tra mã dãy đã tồn tại chưa
    SELECT COUNT(*) INTO v_Count
    FROM Day
    WHERE MaDay = p_MaDay;
    IF v_Count > 0 THEN
        SET p_Message = 'Mã dãy đã tồn tại';
        SET p_ErrorCode = 1;
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = p_Message;
    ELSE
        -- Bắt đầu transaction
        START TRANSACTION;
        -- Thêm dãy vào bảng Day
        INSERT INTO Day (MaDay, TenDay, MaKhuKTX)
        VALUES (p_MaDay, p_TenDay, p_MaKhuKTX);
        COMMIT;
        SET p_Message = 'Thêm dãy thành công';
        SET p_ErrorCode = 0;
    END IF;
END //
DELIMITER ;
-- Sửa Dãy

DELIMITER //
CREATE PROCEDURE SuaDay(
    IN p_MaDay VARCHAR(10),
    IN p_TenDay VARCHAR(50),
    IN p_MaKhuKTX VARCHAR(10),
    OUT p_Message VARCHAR(255),
    OUT p_ErrorCode INT
)
BEGIN
    DECLARE v_Count INT;
    -- Kiểm tra mã dãy có tồn tại không
    SELECT COUNT(*) INTO v_Count
    FROM Day
    WHERE MaDay = p_MaDay;

    IF v_Count > 0 THEN
        -- Nếu MaDay đã tồn tại, thực hiện cập nhật TenDay và MaKhuKTX
        START TRANSACTION;
        UPDATE Day
        SET TenDay = p_TenDay,
            MaKhuKTX = p_MaKhuKTX
        WHERE MaDay = p_MaDay;
        COMMIT;
        SET p_Message = 'Cập nhật dãy thành công';
        SET p_ErrorCode = 0;
    ELSE
        -- Nếu MaDay không tồn tại
        SET p_Message = 'Mã Dãy không tồn tại';
        SET p_ErrorCode = 1;
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = p_Message;
    END IF;
END //
DELIMITER ;


-- Xoá Dãy
DELIMITER //
CREATE PROCEDURE XoaDay(
    IN p_MaDay VARCHAR(10),
    OUT p_Message VARCHAR(255),
    OUT p_ErrorCode INT
)
BEGIN
    DECLARE v_Count INT;
    DECLARE v_PhongCount INT;
    -- Kiểm tra mã dãy có tồn tại không
    SELECT COUNT(*) INTO v_Count
    FROM Day
    WHERE MaDay = p_MaDay;
    IF v_Count = 0 THEN
        SET p_Message = 'Mã dãy không tồn tại';
        SET p_ErrorCode = 1;
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = p_Message;
    ELSE
        -- Kiểm tra xem có phòng nào đang là khóa ngoại của dãy không
        SELECT COUNT(*) INTO v_PhongCount
        FROM Phong
        WHERE MaDay = p_MaDay;
        IF v_PhongCount > 0 THEN
            SET p_Message = 'Không thể xóa dãy vì có phòng đang là khóa ngoại của dãy';
            SET p_ErrorCode = 2;
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = p_Message;
        ELSE
            -- Bắt đầu transaction
            START TRANSACTION;
            -- Xóa dãy trong bảng Day
            DELETE FROM Day WHERE MaDay = p_MaDay;
            COMMIT;
            SET p_Message = 'Xóa dãy thành công';
            SET p_ErrorCode = 0;
        END IF;
    END IF;
END //
DELIMITER ;

-- Hàm Thêm HocKi
DELIMITER //
CREATE PROCEDURE ThemHocKi(
    IN p_HocKi ENUM('1', '2', '3'),
    IN p_NamHoc VARCHAR(50),
    IN p_BatDau DATE,
    IN p_KetThuc DATE,
    OUT p_Message VARCHAR(255),
    OUT p_ErrorCode INT
)
BEGIN
    DECLARE v_Count INT;
    DECLARE v_DateOverlap INT;
    -- Kiểm tra mã học kỳ và năm học đã tồn tại chưa
    SELECT COUNT(*) INTO v_Count
    FROM HocKi
    WHERE HocKi = p_HocKi AND NamHoc = p_NamHoc;
    IF v_Count > 0 THEN
        SET p_Message = 'Học kỳ và năm học đã tồn tại';
        SET p_ErrorCode = 1;
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = p_Message;
    ELSE
        SELECT COUNT(*) INTO v_DateOverlap
        FROM HocKi
        WHERE NamHoc = p_NamHoc
          AND ((p_BatDau BETWEEN BatDau AND KetThuc) OR (p_KetThuc BETWEEN BatDau AND KetThuc)
          OR (BatDau BETWEEN p_BatDau AND p_KetThuc) OR (KetThuc BETWEEN p_BatDau AND p_KetThuc));
        IF v_DateOverlap > 0 THEN
            SET p_Message = 'Ngày bắt đầu và kết thúc bị trùng với kỳ khác trong cùng năm học';
            SET p_ErrorCode = 2;
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = p_Message;
        ELSE
            -- Bắt đầu transaction
            START TRANSACTION;
            -- Thêm học kỳ vào bảng HocKi
            INSERT INTO HocKi (HocKi, NamHoc, BatDau, KetThuc)
            VALUES (p_HocKi, p_NamHoc, p_BatDau, p_KetThuc);
            COMMIT;
            SET p_Message = 'Thêm học kỳ thành công';
            SET p_ErrorCode = 0;
        END IF;
    END IF;
END //
DELIMITER ;
drop PROCEDURE ThemHocKi;
-- Hàm Sửa HocKi
DELIMITER //
CREATE PROCEDURE SuaHocKi(
    IN p_HocKi ENUM('1', '2', '3'),
    IN p_NamHoc VARCHAR(50),
    IN p_BatDau DATE,
    IN p_KetThuc DATE,
    OUT p_Message VARCHAR(255),
    OUT p_ErrorCode INT
)
BEGIN
    DECLARE v_Count INT;
    DECLARE v_DateOverlap INT;
    DECLARE v_DienNuocCount INT;
    -- Kiểm tra mã học kỳ và năm học có tồn tại không
    SELECT COUNT(*) INTO v_Count
    FROM HocKi
    WHERE HocKi = p_HocKi AND NamHoc = p_NamHoc;
    IF v_Count = 0 THEN
        SET p_Message = 'Học kỳ và năm học không tồn tại';
        SET p_ErrorCode = 1;
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = p_Message;
    ELSE
        -- Kiểm tra trùng ngày bắt đầu và kết thúc với các kỳ khác trong cùng năm học
        SELECT COUNT(*) INTO v_DateOverlap
        FROM HocKi
        WHERE NamHoc = p_NamHoc
          AND HocKi != p_HocKi
          AND ((p_BatDau BETWEEN BatDau AND KetThuc) OR (p_KetThuc BETWEEN BatDau AND KetThuc)
          OR (BatDau BETWEEN p_BatDau AND p_KetThuc) OR (KetThuc BETWEEN p_BatDau AND p_KetThuc));
        IF v_DateOverlap > 0 THEN
            SET p_Message = 'Ngày bắt đầu và kết thúc bị trùng với kỳ khác trong cùng năm học';
            SET p_ErrorCode = 2;
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = p_Message;
        ELSE
            -- Kiểm tra xem có DienNuoc nào có tháng nằm trong khoảng ngày bắt đầu và kết thúc không
            SELECT COUNT(*) INTO v_DienNuocCount
            FROM DienNuoc
            WHERE HocKi = p_HocKi AND NamHoc = p_NamHoc
              AND (DATE(CONCAT(NamHoc, '-', Thang, '-01')) NOT BETWEEN p_BatDau AND p_KetThuc);
            IF v_DienNuocCount > 0 THEN
                SET p_Message = 'Có dữ liệu điện nước không nằm trong khoảng ngày bắt đầu và kết thúc';
                SET p_ErrorCode = 3;
                SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = p_Message;
            ELSE
                -- Bắt đầu transaction
                START TRANSACTION;
                -- Cập nhật ngày bắt đầu và kết thúc của học kỳ
                UPDATE HocKi
                SET BatDau = p_BatDau, KetThuc = p_KetThuc
                WHERE HocKi = p_HocKi AND NamHoc = p_NamHoc;
                COMMIT;
                SET p_Message = 'Cập nhật học kỳ thành công';
                SET p_ErrorCode = 0;
            END IF;
        END IF;
    END IF;
END //
DELIMITER ;

-- Hàm Xoá HocKi với kiểm tra ràng buộc DienNuoc
DELIMITER //
CREATE PROCEDURE XoaHocKi(
    IN p_HocKi ENUM('1', '2', '3'),
    IN p_NamHoc VARCHAR(50),
    OUT p_Message VARCHAR(255),
    OUT p_ErrorCode INT
)
BEGIN
    DECLARE v_Count INT;
    DECLARE v_ThuePhongCount INT;
    DECLARE v_DienNuocCount INT;
    -- Kiểm tra mã học kỳ và năm học có tồn tại không
    SELECT COUNT(*) INTO v_Count
    FROM HocKi
    WHERE HocKi = p_HocKi AND NamHoc = p_NamHoc;
    IF v_Count = 0 THEN
        SET p_Message = 'Học kỳ và năm học không tồn tại';
        SET p_ErrorCode = 1;
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = p_Message;
    ELSE
        -- Kiểm tra xem có phòng nào đang thuê trong học kỳ không
        SELECT COUNT(*) INTO v_ThuePhongCount
        FROM ThuePhong
        WHERE HocKi = p_HocKi AND NamHoc = p_NamHoc;
        IF v_ThuePhongCount > 0 THEN
            SET p_Message = 'Không thể xóa học kỳ vì có phòng đang thuê trong học kỳ';
            SET p_ErrorCode = 2;
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = p_Message;
        ELSE
            -- Kiểm tra xem có DienNuoc nào đang ràng buộc HocKi không
            SELECT COUNT(*) INTO v_DienNuocCount
            FROM DienNuoc
            WHERE HocKi = p_HocKi AND NamHoc = p_NamHoc;
            IF v_DienNuocCount > 0 THEN
                SET p_Message = 'Không thể xóa học kỳ vì có dữ liệu điện nước ràng buộc';
                SET p_ErrorCode = 3;
                SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = p_Message;
            ELSE
                -- Bắt đầu transaction
                START TRANSACTION;
                -- Xóa học kỳ trong bảng HocKi
                DELETE FROM HocKi WHERE HocKi = p_HocKi AND NamHoc = p_NamHoc;
                COMMIT;
                SET p_Message = 'Xóa học kỳ thành công';
                SET p_ErrorCode = 0;
            END IF;
        END IF;
    END IF;
END //
DELIMITER ;



-- Nhân Viên
DELIMITER //
CREATE PROCEDURE UpdateNhanVien (
    IN p_OldMaNhanVien VARCHAR(50),
    IN p_HoTen VARCHAR(255),
    IN p_Role VARCHAR(50),
    IN p_SDT VARCHAR(15),
    IN p_GhiChu TEXT,
    IN p_NgaySinh DATE,
    IN p_GioiTinh VARCHAR(10)
)
BEGIN
    UPDATE NhanVien 
    SET HoTen = p_HoTen, 
        Role = p_Role, 
        SDT = p_SDT, 
        GhiChu = p_GhiChu, 
        NgaySinh = p_NgaySinh, 
        GioiTinh = p_GioiTinh
    WHERE MaNhanVien = p_OldMaNhanVien;
END //
DELIMITER ; 


-- Thêm Nhân Viên với kiểm tra tồn tại
DELIMITER //
CREATE PROCEDURE ThemNhanVien (
    IN p_MaNhanVien VARCHAR(8),
    IN p_HoTen VARCHAR(50),
    IN p_SDT VARCHAR(10),
    IN p_GhiChu TEXT,
    IN p_GioiTinh VARCHAR(10),
    IN p_NgaySinh DATE,
    IN p_Password VARCHAR(255),
    IN p_Role ENUM('Admin', 'NhanVien'),
    OUT p_Message VARCHAR(255),
    OUT p_ErrorCode INT
)
BEGIN
    DECLARE v_Count INT;
    -- Kiểm tra mã nhân viên đã tồn tại chưa
    SELECT COUNT(*) INTO v_Count
    FROM NhanVien
    WHERE MaNhanVien = p_MaNhanVien;
    IF v_Count > 0 THEN
        SET p_Message = 'Mã nhân viên đã tồn tại';
        SET p_ErrorCode = 1;
    ELSE
        -- Bắt đầu transaction
        START TRANSACTION;
        -- Thêm nhân viên vào bảng NhanVien
        INSERT INTO NhanVien (MaNhanVien, HoTen, SDT, GhiChu, GioiTinh, NgaySinh, Password, Role)
        VALUES (p_MaNhanVien, p_HoTen, p_SDT, p_GhiChu, p_GioiTinh, p_NgaySinh, p_Password, p_Role);
        COMMIT;
        SET p_Message = 'Thêm nhân viên thành công';
        SET p_ErrorCode = 0;
    END IF;
END //
DELIMITER ;

-- Update NhanVien
DELIMITER //
CREATE PROCEDURE UpdateNhanVien (
    IN p_OldMaNhanVien VARCHAR(8),
    IN p_MaNhanVien VARCHAR(8),
    IN p_HoTen VARCHAR(50),
    IN p_SDT VARCHAR(10),
    IN p_GhiChu TEXT,
    IN p_GioiTinh VARCHAR(10),
    IN p_NgaySinh DATE,
    IN p_Password VARCHAR(255),
    IN p_Role ENUM('Admin', 'NhanVien'),
    OUT p_Message VARCHAR(255),
    OUT p_ErrorCode INT
)
BEGIN
    -- Bắt đầu transaction
    START TRANSACTION;
    -- Cập nhật nhân viên trong bảng NhanVien
    UPDATE NhanVien
    SET MaNhanVien = p_MaNhanVien,
        HoTen = p_HoTen,
        SDT = p_SDT,
        GhiChu = p_GhiChu,
        GioiTinh = p_GioiTinh,
        NgaySinh = p_NgaySinh,
        Password = p_Password,
        Role = p_Role
    WHERE MaNhanVien = p_OldMaNhanVien;
    COMMIT;
    SET p_Message = 'Cập nhật nhân viên thành công';
    SET p_ErrorCode = 0;
END //
DELIMITER ;
