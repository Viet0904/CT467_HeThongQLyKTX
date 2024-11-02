
-- Kiểm tra và xóa database nếu đã tồn tại
DROP DATABASE IF EXISTS htqlktx;

-- Tạo database mới
create database htqlktx;
use htqlktx;

-- Tạo bảng KhuKTX
CREATE TABLE KhuKTX (
    MaKhuKTX VARCHAR(10) PRIMARY KEY,
    TenKhuKTX VARCHAR(50)
);


INSERT INTO KhuKTX (MaKhuKTX, TenKhuKTX)
VALUES
('A', 'Khu Ký Túc Xá A'),
('B', 'Khu Ký Túc Xá B'),
('C', 'Khu Ký Túc Xá C');

-- Tạo bảng Day
CREATE TABLE Day (
    MaDay VARCHAR(10) PRIMARY KEY,
    TenDay VARCHAR(50),
    MaKhuKTX VARCHAR(10),
    FOREIGN KEY (MaKhuKTX) REFERENCES KhuKTX(MaKhuKTX)
);
INSERT INTO Day (MaDay, TenDay, MaKhuKTX) VALUES 
('AA01', 'AA01', 'A'),
('AA02', 'AA02', 'A'),
('AB01', 'AB01', 'A'),
('AB02', 'AB02', 'A'),
('AB08', 'AB08', 'A'),
('AB09', 'AB09', 'A'),
('AB11', 'AB11', 'A'),
('AB12', 'AB12', 'A'),
('AB13', 'AB13', 'A'),
('AB14', 'AB14', 'A'),
('AB15', 'AB15', 'A'),
('AB19', 'AB19', 'A'),
('AB20', 'AB20', 'A'),
('AB21', 'AB21', 'A'),
('AB22', 'AB22', 'A'),
('AB23', 'AB23', 'A'),
('AC01', 'AC01', 'A'),
('AC02', 'AC02', 'A'),
('AC03', 'AC03', 'A'),
('AC04', 'AC04', 'A'),
('AC05', 'AC05', 'A'),
('AC06', 'AC06', 'A'),
('AC07', 'AC07', 'A'),
('AC08', 'AC08', 'A'),
('AC09', 'AC09', 'A'),
('AC10', 'AC10', 'A'),
('AC11', 'AC11', 'A'),
('AC12', 'AC12', 'A'),
('AC15', 'AC15', 'A'),
('AD01', 'AD01', 'A'),
('AD02', 'AD02', 'A'),
('BB01', 'BB01', 'B'),
('BB02', 'BB02', 'B'),
('BB03', 'BB03', 'B'),
('BB04', 'BB04', 'B'),
('BB05', 'BB05', 'B'),
('BB06', 'BB06', 'B'),
('BB07', 'BB07', 'B'),
('BB08', 'BB08', 'B'),
('CA01', 'CA01', 'C'),
('CA02', 'CA02', 'C'),
('CA06', 'CA06', 'C'),
('CA07', 'CA07', 'C'),
('CA08', 'CA08', 'C'),
('CA09', 'CA09', 'C'),
('CC01', 'CC01', 'C');

-- Tạo bảng Lop
CREATE TABLE Lop (
    MaLop VARCHAR(10) PRIMARY KEY,
    TenLop VARCHAR(50)
);
INSERT INTO Lop (MaLop, TenLop)
VALUES
('01', 'Sư phạm Toán học'),
('02', 'Sư phạm Vật lý'),
('08', 'Công nghệ thực phẩm'),
('09', 'Sư phạm Hóa học'),
('10', 'Sư phạm Sinh học'),
('13', 'Nuôi trồng thủy sản'),
('16', 'Sư phạm Địa lý'),
('17', 'Sư phạm Ngữ văn'),
('18', 'Sư phạm Lịch sử'),
('19', 'Nông học'),
('20', 'Kế toán'),
('21', 'Tài chính - Ngân hàng'),
('22', 'Quản trị kinh doanh'),
('22A', 'Quản trị kinh doanh - học tại Hòa An'),
('23', 'Kinh tế nông nghiệp'),
('23A', 'Kinh tế nông nghiệp - học tại Hòa An'),
('25', 'Quản lý đất đai'),
('32', 'Luật'),
('38', 'Khoa học môi trường'),
('45', 'Marketing'),
('48', 'Kỹ thuật cơ khí'),
('57', 'Kỹ thuật môi trường'),
('63', 'Luật - học tại Hòa An'),
('66', 'Công nghệ sinh học'),
('67', 'Thú y'),
('69', 'Hóa học'),
('72', 'Khoa học đất'),
('73', 'Bảo vệ thực vật'),
('76', 'Bệnh học thủy sản'),
('80', 'Thông tin - thư viện'),
('82', 'Công nghệ chế biến thủy sản'),
('83', 'Quản lý công nghiệp'),
('89', 'Toán ứng dụng'),
('90', 'Kinh tế tài nguyên thiên nhiên'),
('94', 'Sinh học'),
('95', 'Hệ thống thông tin'),
('96', 'Kỹ thuật phần mềm'),
('B1', 'Kỹ thuật ô tô'),
('B2', 'Kỹ thuật y sinh'),
('B3', 'Kỹ thuật máy tính'),
('D1', 'Truyền thông đa phương tiện'),
('D2', 'An toàn thông tin'),
('D3', 'Thống kê'),
('D4', 'Kỹ thuật cấp thoát nước'),
('D5', 'Logistics và Quản lý chuỗi cung ứng'),
('E1', 'Giáo dục mầm non'),
('E2', 'Sư phạm Khoa học tự nhiên'),
('N1', 'Báo chí'),
('N2', 'Du lịch'),
('N2A', 'Du lịch - học tại Hòa An'),
('S1', 'Chăn nuôi'),
('S2', 'Quản lý thủy sản'),
('S3', 'Kỹ thuật cơ điện tử'),
('S7', 'Kiến trúc'),
('S8', 'Quy hoạch vùng và đô thị'),
('S9', 'Luật kinh tế'),
('T1', 'Kỹ thuật xây dựng công trình giao thông'),
('T3', 'Hóa dược'),
('T4', 'Kỹ thuật vật liệu'),
('T5', 'Kỹ thuật điện'),
('T6', 'Kỹ thuật điện tử - viễn thông'),
('T7', 'Kỹ thuật xây dựng'),
('T8', 'Kỹ thuật xây dựng công trình thủy'),
('T9', 'Mạng máy tính và truyền thông dữ liệu'),
('U1', 'Vật lý kỹ thuật'),
('U3', 'Triết học'),
('U4', 'Xã hội học'),
('U5', 'Công nghệ sau thu hoạch'),
('U7A', 'Kinh doanh nông nghiệp - học tại Hòa An'),
('U8', 'Sư phạm Tin học'),
('V1', 'Ngôn ngữ Anh'),
('V1A', 'Ngôn ngữ Anh - học tại Hòa An'),
('V5', 'Kiểm toán'),
('V6', 'Công nghệ kỹ thuật hóa học'),
('V7', 'Công nghệ thông tin'),
('V7A', 'Công nghệ thông tin - học tại Hòa An'),
('V8', 'Sinh học ứng dụng'),
('V9', 'Chính trị học'),
('W1', 'Kinh tế'),
('W2', 'Quản trị dịch vụ du lịch và lữ hành'),
('W3', 'Kinh doanh thương mại'),
('W4', 'Kinh doanh quốc tế'),
('W7', 'Văn học'),
('X1', 'Sư phạm Tiếng Anh'),
('X2', 'Sư phạm Tiếng Pháp'),
('X3', 'Giáo dục Tiểu học'),
('X4', 'Giáo dục Công dân'),
('X6', 'Giáo dục Thể chất'),
('X7', 'Quản lý tài nguyên và môi trường'),
('X8', 'Khoa học cây trồng'),
('X9', 'Công nghệ rau hoa quả và cảnh quan'),
('Y8', 'Kỹ thuật điều khiển và tự động hóa'),
('Z6', 'Khoa học máy tính'),
('Z9', 'Ngôn ngữ Pháp');




-- Tạo bảng Phong
CREATE TABLE Phong (
    MaPhong VARCHAR(10) PRIMARY KEY,
    TenPhong VARCHAR(50),
    DienTich INT,
    SoGiuong INT,
    GiaThue DECIMAL(10, 2),
    MaDay VARCHAR(10),
    TrangThaiSuDung VARCHAR(20),
    SucChua INT,
    SoChoThucTe INT,
    DaO INT,
    GhiChu TEXT,
    LoaiPhong ENUM('Nam', 'Nữ') NOT NULL,
    FOREIGN KEY (MaDay) REFERENCES Day(MaDay)
);

INSERT INTO Phong (MaPhong, TenPhong, DienTich, SoGiuong, GiaThue, MaDay, TrangThaiSuDung, SucChua, SoChoThucTe, DaO, LoaiPhong)
VALUES
('AA01100', 'AA01100', 30.0, 5, 190000.00, 'AA01', 'Đang sử dụng', 5, 5, 5, 'Nam'),
('AA01101', 'AA01101', 30.0, 8, 190000.00, 'AA01', 'Đang sử dụng', 8, 8, 8, 'Nam'),
('AA01102', 'AA01102', 30.0, 4, 390000.00, 'AA01', 'Đang sử dụng', 4, 4, 4, 'Nam'),
('AA01103', 'AA01103', 30.0, 7, 218000.00, 'AA01', 'Đang sử dụng', 7, 7, 7, 'Nam'),
('AA01104', 'AA01104', 30.0, 8, 190000.00, 'AA01', 'Đang sử dụng', 8, 8, 8, 'Nam'),
('AA01105', 'AA01105', 30.0, 4, 190000.00, 'AA01', 'Đang sử dụng', 4, 4, 4, 'Nam'),
('AA01106', 'AA01106', 30.0, 4, 390000.00, 'AA01', 'Đang sử dụng', 4, 4, 4, 'Nam'),
('AA01201', 'AA01201', 30.0, 8, 190000.00, 'AA01', 'Đang sử dụng', 8, 8, 8, 'Nam'),
('AA01202', 'AA01202', 30.0, 8, 190000.00, 'AA01', 'Đang sử dụng', 8, 8, 7, 'Nam'),
('AA01203', 'AA01203', 30.0, 8, 190000.00, 'AA01', 'Đang sử dụng', 8, 8, 8, 'Nữ'),
('AA01204', 'AA01204', 30.0, 8, 190000.00, 'AA01', 'Đang sử dụng', 8, 8, 8, 'Nam'),
('AA01205', 'AA01205', 30.0, 8, 190000.00, 'AA01', 'Đang sử dụng', 8, 8, 8, 'Nam'),
('AA01206', 'AA01206', 30.0, 4, 190000.00, 'AA01', 'Đang sử dụng', 4, 4, 4, 'Nữ'),
('AA01208', 'AA01208', 30.0, 4, 190000.00, 'AA01', 'Đang sử dụng', 4, 4, 4, 'Nam'),
('AA01209', 'AA01209', 30.0, 8, 190000.00, 'AA01', 'Đang sử dụng', 8, 8, 8, 'Nam'),
('AA01210', 'AA01210', 30.0, 3, 515000.00, 'AA01', 'Đang sử dụng', 3, 3, 3, 'Nữ'),
('AA01211', 'AA01211', 30.0, 3, 190000.00, 'AA01', 'Đang sử dụng', 3, 3, 3, 'Nam'),
('AA01212', 'AA01212', 30.0, 8, 190000.00, 'AA01', 'Đang sử dụng', 8, 8, 8, 'Nam'),
('AA01213', 'AA01213', 30.0, 8, 190000.00, 'AA01', 'Đang sử dụng', 8, 8, 7, 'Nam');



-- Tạo bảng SinhVien
CREATE TABLE SinhVien (
    MaSinhVien VARCHAR(8) PRIMARY KEY,
    HoTen VARCHAR(50),
    SDT VARCHAR(10),
    Email VARCHAR(50),
    DiaChi VARCHAR(100),
    GioiTinh VARCHAR(10),
    KhoaHoc INT,
    NgaySinh DATE,
    ChucVu VARCHAR(50),
    MaLop VARCHAR(10),
    Password VARCHAR(255),
    FOREIGN KEY (MaLop) REFERENCES Lop(MaLop)
);

-- Chèn dữ liệu mẫu vào bảng SinhVien
INSERT INTO SinhVien (MaSinhVien, HoTen, SDT,Email, MaLop, DiaChi, GioiTinh, KhoaHoc, NgaySinh,ChucVu, Password)
VALUES
('B2111908', 'Nguyễn Quốc Việt', '0123456789','vietb2111908@student.ctu.edu.vn' ,'V7', 'An Giang', 'Nam',  47, '2003-4-9', 'Thành Viên ANXK',  '$2y$10$7QH.c6EoG0As1W0ree6DnugSCeBvc/9PNHR13VNm7IXhcxMjyZCaO'),
('B2111893', 'Trương Huỳnh Tú Như', '0987654321','nhub211189@student.ctu.edu.vn'  ,'V7', 'Bạc Liêu', 'Nữ', 47, '2003-12-9','Thành Viên ANXK','$2y$10$7QH.c6EoG0As1W0ree6DnugSCeBvc/9PNHR13VNm7IXhcxMjyZCaO');



-- Tạo bảng NhanVien
CREATE TABLE NhanVien (
    MaNhanVien VARCHAR(8) PRIMARY KEY,
    HoTen VARCHAR(50),
    SDT VARCHAR(10),
    GhiChu TEXT,
    GioiTinh VARCHAR(10),
    NgaySinh DATE,
    Password VARCHAR(255)
);


INSERT INTO NhanVien (MaNhanVien, HoTen, SDT, GioiTinh, NgaySinh, Password)
VALUES
('CB000001', 'Nguyễn Văn C', '0333555777',  'Nam', '1980-05-15', '$2y$10$XaJgTLKSk2FwThXYAkTq9.HG5DUTxL.ixJdoHGxbzQPloBUbdIjbK'),
('CB000002', 'Lê Thị D', '0444666888',  'Nữ', '1985-06-20', '$2y$10$XaJgTLKSk2FwThXYAkTq9.HG5DUTxL.ixJdoHGxbzQPloBUbdIjbK');







-- Tạo bảng ThuePhong
CREATE TABLE ThuePhong (
    MaHopDong VARCHAR(10) PRIMARY KEY,
    MaSinhVien VARCHAR(10),
    MaPhong VARCHAR(10),
    BatDau DATE,
    KetThuc DATE,
    TienDatCoc DECIMAL(10, 2),
    GiaThueThucTe DECIMAL(10, 2),
    FOREIGN KEY (MaSinhVien) REFERENCES SinhVien(MaSinhVien),
    FOREIGN KEY (MaPhong) REFERENCES Phong(MaPhong)
);

-- Tạo bảng TT_ThuePhong
CREATE TABLE TT_ThuePhong (
    MaHopDong VARCHAR(10),
    ThangNam DATE,
    SoTien DECIMAL(10, 2),
    NgayThanhToan DATE,
    MaNhanVien VARCHAR(10),
    PRIMARY KEY (MaHopDong, ThangNam),
    FOREIGN KEY (MaHopDong) REFERENCES ThuePhong(MaHopDong),
    FOREIGN KEY (MaNhanVien) REFERENCES NhanVien(MaNhanVien)
);

-- Tạo bảng đăng ký phòng
CREATE TABLE dangKyPhong (
    MaDangKy INT PRIMARY KEY AUTO_INCREMENT,
	MaSinhVien VARCHAR(8),
	MaPhong VARCHAR(10),
    TrangThaiDangKy ENUM('Đang Chờ Duyệt', 'Đã Duyệt', 'Đã Huỷ', 'Từ chối') DEFAULT 'Đang Chờ Duyệt',
	NgayDangKy DATE,
    ngayDuyet DATE,
	MaNhanVien VARCHAR(8),
    FOREIGN KEY (MaSinhVien) REFERENCES SinhVien(MaSinhVien),
    FOREIGN KEY (MaPhong) REFERENCES Phong(MaPhong),
    FOREIGN KEY (MaNhanVien) REFERENCES NhanVien(MaNhanVien)
);

