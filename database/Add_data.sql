-- Chèn dữ liệu mẫu vào bảng KhuKTX
INSERT INTO KhuKTX (MaKhuKTX, TenKhuKTX)
VALUES
('A', 'Khu Ký Túc Xá A'),
('B', 'Khu Ký Túc Xá B');
('C', 'Khu Ký Túc Xá C');

-- Chèn dữ liệu mẫu vào bảng Day
INSERT INTO Day (MaDay, TenDay, MaKhuKTX)
VALUES
('C1', 'Dãy C1', 'A'),
('A1', 'Dãy A1', 'A'),
('B1', 'Dãy B1', 'B');

-- Chèn dữ liệu mẫu vào bảng Phong
INSERT INTO Phong (MaPhong, TenPhong, DienTich, SoGiuong, GiaThue, MaDay, TrangThaiSuDung, SucChua, SoChoThucTe, DaO, ConTrong, GhiChu)
VALUES
('AC05004', 'Phòng 004', 25.5, 4, 245000.00, 'C5', 'Đang sử dụng', 8, 4, 4, 0, sạch sẽ'),
('AA01211', 'Phòng 211', 30.0, 4, 200000.00, 'A1', 'Đang sử dụng', 8, 5, 4, 1, thoáng mát');

-- Chèn dữ liệu mẫu vào bảng SinhVien
INSERT INTO SinhVien (MaSinhVien, HoTen, SDT, MaLop, DiaChi, GioiTinh, NganhHoc,KhoaHoc, NgaySinh, NgayDangKy, ChucVu,MaDay, Password)
VALUES
('B2111908', 'Nguyễn Quốc Việt', '0123456789', 'HG21V7A1', 'An Giang', 'Nam', 'CNTT', 47, '9/4/2003', '2000-01-01' 'Thành Viên ANXK', 'C5', 'password123'),
('B2111893', 'Trương Huỳnh Tú Như', '0987654321', 'HG21V7A1', 'Bạc Liêu', 'Nữ', 'CNTT', 47, '9/12/2003', '2000-02-01','Thành Viên ANXK','B1', 'password456');

-- Chèn dữ liệu mẫu vào bảng Lop
INSERT INTO Lop (MaLop, TenLop)
VALUES
('HG21V7A1', 'Công nghệ thông tin Hoà An'),
('HG22V7A1', 'Công nghệ thông tin Hoà An');

-- Chèn dữ liệu mẫu vào bảng NhanVien
INSERT INTO NhanVien (MaNhanVien, HoTen, SDT, GhiChu, GioiTinh, NgaySinh, Password)
VALUES
('CB000001', 'Nguyễn Văn C', '0333555777', 'Quản lý ký túc xá', 'Nam', '1980-05-15', 'admin123'),
('CB000002', 'Lê Thị D', '0444666888', 'Quản lý tài chính', 'Nữ', '1985-06-20', 'admin456');

-- Chèn dữ liệu mẫu vào bảng ThuePhong
INSERT INTO ThuePhong (MaHopDong, MaSinhVien, MaPhong, BatDau, KetThuc, TienDatCoc, GiaThueThucTe)
VALUES
('HD001', 'SV001', 'P101', '2021-09-01', '2022-08-31', 3000000.00, 1500000.00),
('HD002', 'SV002', 'P102', '2021-09-01', '2022-08-31', 4000000.00, 2000000.00);

-- Chèn dữ liệu mẫu vào bảng TT_ThuePhong
INSERT INTO TT_ThuePhong (MaHopDong, ThangNam, SoTien, NgayThanhToan, MaNhanVien)
VALUES
('HD001', '2022-01-01', 1500000.00, '2022-01-15', 'NV001'),
('HD002', '2022-01-01', 2000000.00, '2022-01-16', 'NV002');

-- Chèn dữ liệu mẫu vào bảng PhiDienNuoc
INSERT INTO PhiDienNuoc (Thang, Loai, PhiSuDung, SoTienSVDong, TongSoTienSVDong, TongNoPhong, NgayDong, NamHoc)
VALUES
(1, 'Điện', 500000.00, 200000.00, 800000.00, 200000.00, '2022-02-01', '2021-2022'),
(2, 'Nước', 300000.00, 150000.00, 600000.00, 100000.00, '2022-02-01', '2021-2022');
