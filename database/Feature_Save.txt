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
CREATE FUNCTION GetMaPhongDangKy(maSinhVienInput VARCHAR(8))
RETURNS VARCHAR(10)
DETERMINISTIC
BEGIN
    DECLARE maPhongDangKy VARCHAR(10);
    SELECT MaPhong INTO maPhongDangKy
    FROM dangKyPhong
    WHERE MaSinhVien = maSinhVienInput;
    RETURN maPhongDangKy;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE GetDangKyPhongBySinhVien(
    IN p_MaSinhVien VARCHAR(8)
)
BEGIN
    SELECT *
    FROM dangKyPhong
    WHERE MaSinhVien = p_MaSinhVien;
END //
DELIMITER ;


DELIMITER //
CREATE PROCEDURE GetSinhVienPhongDangKy()
BEGIN
    SELECT 
        dk.*,
        sv.*,
        p.*
    FROM 
        dangKyPhong dk
    JOIN 
        SinhVien sv ON dk.MaSinhVien = sv.MaSinhVien
    JOIN 
        Phong p ON dk.MaPhong = p.MaPhong
    WHERE 
        dk.TrangThaiDangKy = 'Đang Chờ Duyệt'
    ORDER BY dk.NgayDangKy DESC;  
END //
DELIMITER ;



-- GetPhongDangKyInfo
DELIMITER //
CREATE PROCEDURE GetPhongDangKyInfo(
    IN p_MaSinhVien VARCHAR(8),
    IN p_GioiTinh VARCHAR(10)
)
BEGIN
    SELECT
        p.*,
        dkp.*
    FROM
        Phong p
	JOIN
        dangKyPhong dkp ON dkp.MaSinhVien = p_MaSinhVien
    WHERE
        p.LoaiPhong = p_GioiTinh AND (p.SoChoThucTe -  p.DaO) > 0;
END //
DELIMITER ;

call GetPhongDangKyInfo('b2111908','Nam')

--         IF(dkp.MaSinhVien IS NOT NULL, dkp.TrangThaiDangKy, '') AS TrangThaiDangKy
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

-- Cập nhật đăng ký phòng cho sinh viên
DELIMITER //
CREATE PROCEDURE proc_dangkyphong(
    IN p_MaSinhVien VARCHAR(8),
    IN p_MaPhong VARCHAR(10)
)
BEGIN
    DECLARE v_error VARCHAR(255);
    -- Kiểm tra xem sinh viên đã đăng ký phòng chưa
    IF EXISTS (
        SELECT 1 FROM dangKyPhong
        WHERE MaSinhVien = p_MaSinhVien
          AND TrangThaiDangKy IN ('Đang Chờ Duyệt', 'Đã Duyệt')
    ) THEN
        SET v_error = 'Sinh viên đã đăng ký phòng rồi. Hãy huỷ đăng ký trước khi đăng ký mới.';
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = v_error;
    END IF;
    -- Kiểm tra xem phòng có tồn tại không
    IF NOT EXISTS (
        SELECT 1 FROM Phong WHERE MaPhong = p_MaPhong
    ) THEN
        SET v_error = 'Phòng không tồn tại.';
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = v_error;
    END IF;
    -- Thêm bản ghi vào bảng dangKyPhong, sử dụng NOW() để lưu ngày và giờ
    INSERT INTO dangKyPhong (MaSinhVien, MaPhong, NgayDangKy)
    VALUES (p_MaSinhVien, p_MaPhong, NOW());
    -- Trả về thông báo thành công
    SELECT 'Đăng ký phòng thành công! Vui lòng chờ quản trị viên duyệt.' AS Message;
END //
DELIMITER ;

-- Huỷ Đăng Ký Phòng
DELIMITER //
CREATE PROCEDURE proc_huyDangKyPhong(
    IN p_MaSinhVien VARCHAR(8),
    IN p_MaPhong VARCHAR(10)
)
BEGIN
    DECLARE v_error VARCHAR(255);
    -- Kiểm tra xem sinh viên có đăng ký phòng này với trạng thái 'Đang Chờ Duyệt' không
    IF NOT EXISTS (
        SELECT 1 FROM dangKyPhong
        WHERE MaSinhVien = p_MaSinhVien
          AND MaPhong = p_MaPhong
          AND TrangThaiDangKy = 'Đang Chờ Duyệt'
    ) THEN
        SET v_error = 'Sinh viên không có đăng ký phòng đang chờ duyệt để hủy.';
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = v_error;
    ELSE
        -- Cập nhật trạng thái đăng ký thành 'Đã Huỷ'
        UPDATE dangKyPhong
        SET TrangThaiDangKy = 'Đã Huỷ'
        WHERE MaSinhVien = p_MaSinhVien
          AND MaPhong = p_MaPhong
          AND TrangThaiDangKy = 'Đang Chờ Duyệt';
        -- Trả về thông báo thành công
        SELECT 'Hủy đăng ký phòng thành công.' AS Message;
    END IF;
END //
DELIMITER ;

-- Khi Duyệt cấp nhật thông tin ThuePhong
DELIMITER //

CREATE PROCEDURE CreateRentalAgreement(
    IN p_MaSinhVien VARCHAR(10),
    IN p_MaPhong VARCHAR(10),
    IN p_BatDau DATE,
    IN p_KetThuc DATE,
    IN p_TienDatCoc DECIMAL(10, 2),
    IN p_GiaThueThucTe DECIMAL(10, 2),
    IN p_MaNhanVien VARCHAR(10)
)
BEGIN
    DECLARE v_MaHopDong VARCHAR(10);
    -- Tạo mã hợp đồng (tùy chọn, có thể tùy chỉnh)
    SET v_MaHopDong = CONCAT('HD', p_MaSinhVien, DATE_FORMAT(NOW(), '%Y%m%d%H%i%s'));
    -- Chèn vào bảng ThuePhong
    INSERT INTO ThuePhong (MaHopDong, MaSinhVien, MaPhong, BatDau, KetThuc, TienDatCoc, GiaThueThucTe)
    VALUES (v_MaHopDong, p_MaSinhVien, p_MaPhong, p_BatDau, p_KetThuc, p_TienDatCoc, p_GiaThueThucTe);

    -- Chèn thanh toán đầu tiên vào TT_ThuePhong cho tháng đầu tiên
    INSERT INTO TT_ThuePhong (MaHopDong, ThangNam, SoTien, NgayThanhToan, MaNhanVien)
    VALUES (v_MaHopDong, p_BatDau, p_GiaThueThucTe, NOW(), p_MaNhanVien);

END //
DELIMITER ;
 


--  Tạo Trigger Check Phòng
DELIMITER //
CREATE TRIGGER CheckPhongConstraints
BEFORE INSERT ON Phong
FOR EACH ROW
BEGIN
    -- Kiểm tra điều kiện SoChoThucTe <= SucChua
    IF NEW.SoChoThucTe > NEW.SucChua THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Số chỗ thực tế không thể lớn hơn sức chứa.';
    END IF;

    -- Kiểm tra điều kiện DaO <= SoChoThucTe
    IF NEW.DaO > NEW.SoChoThucTe THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Số người đã ở không thể lớn hơn số chỗ thực tế.';
    END IF;
END //
CREATE TRIGGER CheckPhongConstraintsUpdate
BEFORE UPDATE ON Phong
FOR EACH ROW
BEGIN
    -- Kiểm tra điều kiện SoChoThucTe <= SucChua
    IF NEW.SoChoThucTe > NEW.SucChua THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Số chỗ thực tế không thể lớn hơn sức chứa.';
    END IF;

    -- Kiểm tra điều kiện DaO <= SoChoThucTe
    IF NEW.DaO > NEW.SoChoThucTe THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Số người đã ở không thể lớn hơn số chỗ thực tế.';
    END IF;
END //
DELIMITER ;

