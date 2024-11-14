
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
-- Tạo bảng Day
CREATE TABLE Day (
    MaDay VARCHAR(10) PRIMARY KEY,
    TenDay VARCHAR(50),
    MaKhuKTX VARCHAR(10),
    FOREIGN KEY (MaKhuKTX) REFERENCES KhuKTX(MaKhuKTX)
);
-- Tạo bảng Lop
CREATE TABLE Lop (
    MaLop VARCHAR(10) PRIMARY KEY,
    TenLop VARCHAR(50)
);

-- Tạo bảng Phong
CREATE TABLE Phong (
    MaPhong VARCHAR(10) PRIMARY KEY,
    TenPhong VARCHAR(50),
    DienTich INT,
    GiaThue DECIMAL(10, 2),
    MaDay VARCHAR(10),
    TrangThaiSuDung VARCHAR(20),
    SucChua INT,
    SoChoThucTe INT,
    DaO INT DEFAULT 0,
    GhiChu TEXT DEFAULT NULL,
    LoaiPhong ENUM('Nam', 'Nữ') NOT NULL,
    FOREIGN KEY (MaDay) REFERENCES Day(MaDay)
);

-- Tạo bảng SinhVien
CREATE TABLE SinhVien (
    MaSinhVien VARCHAR(8) PRIMARY KEY,
    HoTen VARCHAR(50),
    SDT VARCHAR(10),
    Email VARCHAR(50),
    DiaChi VARCHAR(100),
    GioiTinh VARCHAR(10),
    NgaySinh DATE,
    ChucVu VARCHAR(50) DEFAULT NULL,
    MaLop VARCHAR(10),
    Password VARCHAR(255),
    FOREIGN KEY (MaLop) REFERENCES Lop(MaLop)
);
-- Tạo bảng NhanVien
CREATE TABLE NhanVien (
    MaNhanVien VARCHAR(8) PRIMARY KEY,
    HoTen VARCHAR(50),
    SDT VARCHAR(10),
    GhiChu TEXT,
    GioiTinh VARCHAR(10),
    NgaySinh DATE,
    Password VARCHAR(255),
    Role ENUM('Admin', 'NhanVien') NOT NULL

);
-- Tạo bảng HocKi
CREATE TABLE HocKi (
    HocKi ENUM('1', '2', '3'),
    NamHoc VARCHAR(50),
    BatDau DATE,
    KetThuc DATE,
    PRIMARY KEY (HocKi, NamHoc)
);
-- Tạo bảng ThuePhong
CREATE TABLE ThuePhong (
    MaHopDong INT PRIMARY KEY AUTO_INCREMENT,
    MaSinhVien VARCHAR(10),
    MaPhong VARCHAR(10),
    HocKi ENUM('1', '2', '3'),
    NamHoc VARCHAR(50),
    GiaThueThucTe DECIMAL(10, 2),
    FOREIGN KEY (MaSinhVien) REFERENCES SinhVien(MaSinhVien),
    FOREIGN KEY (MaPhong) REFERENCES Phong(MaPhong),
    FOREIGN KEY (HocKi, NamHoc) REFERENCES HocKi(HocKi, NamHoc)

);

-- Tạo bảng TT_ThuePhong
CREATE TABLE TT_ThuePhong (
    MaHopDong INT,
    ThangNam DATE,
    SoTien DECIMAL(10, 2),
    NgayThanhToan DATE,
    MaNhanVien VARCHAR(10),
    PRIMARY KEY (MaHopDong, ThangNam),
    FOREIGN KEY (MaHopDong) REFERENCES ThuePhong(MaHopDong),
    FOREIGN KEY (MaNhanVien) REFERENCES NhanVien(MaNhanVien)
);


-- Tạo bảng DienNuoc
CREATE TABLE DienNuoc (
    ID INT PRIMARY KEY AUTO_INCREMENT,
    Thang INT,
    NamHoc VARCHAR(50),
    PhiDien DECIMAL(10, 2),
    PhiNuoc DECIMAL(10, 2),
    TongTien DECIMAL(10, 2) DEFAULT NULL,
    HocKi ENUM('1', '2', '3'),
    NgayThanhToan DATE,
    MaPhong VARCHAR(10),
    FOREIGN KEY (MaPhong) REFERENCES Phong(MaPhong),
    FOREIGN KEY (HocKi, NamHoc) REFERENCES HocKi(HocKi, NamHoc)
);

INSERT INTO KhuKTX (MaKhuKTX, TenKhuKTX)
VALUES
('A', 'Khu Ký Túc Xá A'),
('B', 'Khu Ký Túc Xá B'),
('C', 'Khu Ký Túc Xá C');

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
('1', 'Kỹ thuật ô tô'),
('2', 'Kỹ thuật y sinh'),
('3', 'Kỹ thuật máy tính'),
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

INSERT INTO Phong (MaPhong, TenPhong, DienTich,GiaThue, MaDay, TrangThaiSuDung, SucChua, SoChoThucTe, LoaiPhong)
VALUES
('A01100', 'AA01100', 30.0, 190000.00*5, 'AA01', 'Đang sử dụng', 5, 5, 'Nam'),
('A01101', 'AA01101', 30.0, 190000.00*5, 'AA01', 'Đang sử dụng', 8, 8, 'Nam'),
('A01102', 'AA01102', 30.0, 390000.00*5, 'AA01', 'Đang sử dụng', 4, 4, 'Nam'),
('A01103', 'AA01103', 30.0, 218000.00*5, 'AA01', 'Đang sử dụng', 7, 7, 'Nam'),
('A01104', 'AA01104', 30.0, 190000.00*5, 'AA01', 'Đang sử dụng', 8, 8, 'Nam'),
('A01105', 'AA01105', 30.0, 190000.00*5, 'AA01', 'Đang sử dụng', 4, 4, 'Nam'),
('A01106', 'AA01106', 30.0, 390000.00*5, 'AA01', 'Đang sử dụng', 4, 4, 'Nam'),
('A01107', 'AA01106', 30.0, 390000.00*5, 'AA01', 'Đang sử dụng', 4, 4, 'Nam'),
('A01108', 'AA01106', 30.0, 390000.00*5, 'AA01', 'Đang sử dụng', 4, 4, 'Nam'),
('A01109', 'AA01106', 30.0, 390000.00*5, 'AA01', 'Đang sử dụng', 4, 4, 'Nam'),
('A01110', 'AA01106', 30.0, 390000.00*5, 'AA01', 'Đang sử dụng', 4, 4, 'Nam'),
('A01201', 'AA01201', 30.0, 190000.00*5, 'AA01', 'Đang sử dụng', 8, 8, 'Nữ'),
('A01202', 'AA01202', 30.0, 190000.00*5, 'AA01', 'Đang sử dụng', 8, 8, 'Nữ'),
('A01203', 'AA01203', 30.0, 190000.00*5, 'AA01', 'Đang sử dụng', 8, 8, 'Nữ'),
('A01204', 'AA01204', 30.0, 190000.00*5, 'AA01', 'Đang sử dụng', 8, 8, 'Nữ'),
('A01205', 'AA01205', 30.0, 190000.00*5, 'AA01', 'Đang sử dụng', 8, 8, 'Nữ'),
('A01206', 'AA01206', 30.0, 190000.00*5, 'AA01', 'Đang sử dụng', 4, 4, 'Nữ'),
('A01208', 'AA01208', 30.0, 190000.00*5, 'AA01', 'Đang sử dụng', 4, 4, 'Nữ'),
('A01209', 'AA01209', 30.0, 190000.00*5, 'AA01', 'Đang sử dụng', 8, 8, 'Nữ'),
('A01210', 'AA01210', 30.0, 515000.00*5, 'AA01', 'Đang sử dụng', 3, 3, 'Nữ'),
('A01211', 'AA01211', 30.0, 190000.00*5, 'AA01', 'Đang sử dụng', 3, 3, 'Nữ'),
('A01212', 'AA01212', 30.0, 190000.00*5, 'AA01', 'Đang sử dụng', 8, 8, 'Nữ'),
('A01213', 'AA01213', 30.0, 190000.00*5, 'AA01', 'Đang sử dụng', 8, 8, 'Nữ');

-- Chèn dữ liệu mẫu vào bảng SinhVien
INSERT INTO SinhVien (MaSinhVien, HoTen, SDT,Email, MaLop, DiaChi, GioiTinh,  NgaySinh,ChucVu, Password)
VALUES
('B2111908', 'Nguyễn Quốc Việt', '0123456789','vietb2111908@student.ctu.edu.vn' ,'V7', 'An Giang', 'Nam',  '2003-4-9', 'Thành Viên ANXK',  '$2y$10$7QH.c6EoG0As1W0ree6DnugSCeBvc/9PNHR13VNm7IXhcxMjyZCaO'),
('B2111893', 'Trương Huỳnh Tú Như', '0987654321','nhub211189@student.ctu.edu.vn'  ,'V7', 'Bạc Liêu', 'Nữ', '2003-12-9','Thành Viên ANXK','$2y$10$7QH.c6EoG0As1W0ree6DnugSCeBvc/9PNHR13VNm7IXhcxMjyZCaO'),
('B2111909', 'Lê Văn A', '0123456780', 'a.b2111909@student.ctu.edu.vn', 'V7', 'Cần Thơ', 'Nam', '2003-05-10', 'Thành Viên ANXK', ''),
('B2111910', 'Trần Thị B', '0123456781', 'b.b2111910@student.ctu.edu.vn', 'V7', 'Hậu Giang', 'Nam', '2003-06-11', 'Thành Viên ANXK', ''),
('B2111911', 'Nguyễn Văn C', '0123456782', 'c.b2111911@student.ctu.edu.vn', 'V7', 'Sóc Trăng', 'Nam', '2003-07-12', 'Thành Viên ANXK', ''),
('B2111912', 'Phạm Thị D', '0123456783', 'd.b2111912@student.ctu.edu.vn', 'V7', 'Vĩnh Long', 'Nam', '2003-08-13', 'Thành Viên ANXK', ''),
('B2111913', 'Hoàng Văn E', '0123456784', 'e.b2111913@student.ctu.edu.vn', 'V7', 'Đồng Tháp', 'Nam', '2003-09-14', 'Thành Viên ANXK', ''),
('B2111914', 'Đỗ Thị F', '0123456785', 'f.b2111914@student.ctu.edu.vn', 'V7', 'Kiên Giang', 'Nam', '2003-10-15', 'Thành Viên ANXK', ''),
('B2111915', 'Lý Văn G', '0123456786', 'g.b2111915@student.ctu.edu.vn', 'V7', 'Bến Tre', 'Nam', '2003-11-16', 'Thành Viên ANXK', ''),
('B2111916', 'Ngô Thị H', '0123456787', 'h.b2111916@student.ctu.edu.vn', 'V7', 'Trà Vinh', 'Nam', '2003-12-17', 'Thành Viên ANXK', ''),
('B2111917', 'Vũ Văn I', '0123456788', 'i.b2111917@student.ctu.edu.vn', 'V7', 'Tiền Giang', 'Nam', '2003-01-18', 'Thành Viên ANXK', ''),
('B2111918', 'Phan Thị J', '0123456789', 'j.b2111918@student.ctu.edu.vn', 'V7', 'Long An', 'Nam', '2003-02-19', 'Thành Viên ANXK', ''),
('B2111919', 'Trịnh Văn K', '0123456790', 'k.b2111919@student.ctu.edu.vn', 'V7', 'An Giang', 'Nữ', '2003-03-20', 'Thành Viên ANXK', ''),
('B2111920', 'Lương Thị L', '0123456791', 'l.b2111920@student.ctu.edu.vn', 'V7', 'Bạc Liêu', 'Nữ', '2003-04-21', 'Thành Viên ANXK', ''),
('B2111921', 'Tạ Văn M', '0123456792', 'm.b2111921@student.ctu.edu.vn', 'V7', 'Cà Mau', 'Nữ', '2003-05-22', 'Thành Viên ANXK', ''),
('B2111922', 'Đinh Thị N', '0123456793', 'n.b2111922@student.ctu.edu.vn', 'V7', 'Cần Thơ', 'Nữ', '2003-06-23', 'Thành Viên ANXK', ''),
('B2111923', 'Hồ Văn O', '0123456794', 'o.b2111923@student.ctu.edu.vn', 'V7', 'Hậu Giang', 'Nữ', '2003-07-24', 'Thành Viên ANXK', ''),
('B2111924', 'Nguyễn Thị P', '0123456795', 'p.b2111924@student.ctu.edu.vn', 'V7', 'Sóc Trăng', 'Nữ', '2003-08-25', 'Thành Viên ANXK', ''),
('B2111925', 'Trần Văn Q', '0123456796', 'q.b2111925@student.ctu.edu.vn', 'V7', 'Vĩnh Long', 'Nữ', '2003-09-26', 'Thành Viên ANXK', ''),
('B2111926', 'Phạm Thị R', '0123456797', 'r.b2111926@student.ctu.edu.vn', 'V7', 'Đồng Tháp', 'Nữ', '2003-10-27', 'Thành Viên ANXK', ''),
('B2111927', 'Hoàng Văn S', '0123456798', 's.b2111927@student.ctu.edu.vn', 'V7', 'Kiên Giang', 'Nữ', '2003-11-28', 'Thành Viên ANXK', ''),
('B2111928', 'Đỗ Thị T', '0123456799', 't.b2111928@student.ctu.edu.vn', 'V7', 'Bến Tre', 'Nữ', '2003-12-29', 'Thành Viên ANXK', '');

INSERT INTO NhanVien (MaNhanVien, HoTen, SDT, GioiTinh, NgaySinh,Role, Password)
VALUES
('CB000001', 'Nguyễn Văn C', '0333555777',  'Nam', '1980-05-15', 'Admin','$2y$10$XaJgTLKSk2FwThXYAkTq9.HG5DUTxL.ixJdoHGxbzQPloBUbdIjbK'),
('CB000002', 'Lê Thị D', '0444666888',  'Nữ', '1985-06-20', 'NhanVien','$2y$10$XaJgTLKSk2FwThXYAkTq9.HG5DUTxL.ixJdoHGxbzQPloBUbdIjbK');

-- Chèn dữ liệu mẫu vào bảng HocKi
INSERT INTO HocKi (HocKi, NamHoc, BatDau, KetThuc)
VALUES
('1', '2024', '2024-09-1', '2024-12-01'),
('2', '2024', '2024-01-1', '2024-05-31'),
('3', '2024', '2024-06-1', '2024-08-31'),
('1', '2023', '2023-09-1', '2023-12-30');

-- Chèn dữ liệu mẫu vào bảng ThuePhong
INSERT INTO ThuePhong (MaSinhVien, MaPhong,HocKi,NamHoc  , GiaThueThucTe)
VALUES
( 'B2111908', 'A01100','1','2024',950000.00),
('B2111893', 'A01202','1','2024',950000.00),
('B2111909', 'A01100', '3','2024',950000.00),
('B2111910', 'A01102', '1','2024',090000.00),
('B2111911', 'A01103','1','2024',950000.00),
( 'B2111912', 'A01104','3','2024',950000.00),
( 'B2111913', 'A01105', '1','2024',950000.00),
( 'B2111914', 'A01106','1','2024',950000.00),
( 'B2111915', 'A01107','1','2024',950000.00),
( 'B2111916', 'A01208','1','2024',950000.00),
( 'B2111917', 'A01109','1','2024',950000.00),
( 'B2111918', 'A01110','1','2024',950000.00),
( 'B2111919', 'A01201','1','2024',950000.00),
( 'B2111920', 'A01210','1','2024',950000.00),
( 'B2111921', 'A01209','1','2024',950000.00),
( 'B2111922', 'A01210', '1','2024',575000.00),
( 'B2111923', 'A01208','1','2024',950000.00),
( 'B2111924', 'A01202','1','2024',950000.00),
( 'B2111925', 'A01204','1','2024',950000.00),
('B2111926', 'A01203', '1','2024', 950000.00);

-- Chèn dữ liệu mẫu vào bảng TT_ThuePhong
INSERT INTO TT_ThuePhong (MaHopDong, ThangNam, SoTien, NgayThanhToan, MaNhanVien)
VALUES
(1, '2023-01-01', 950000.00, '2023-01-10', 'CB000001'),
(2, '2023-01-01', 950000.00, '2023-01-10', 'CB000001'),
(3, '2023-01-01', 1950000.00, '2023-01-10', 'CB000001'),
(4, '2023-01-01', 1090000.00, '2023-01-10', 'CB000001'),
(5, '2023-01-01', 950000.00, '2023-01-10', 'CB000001'),
(6, '2023-01-01', 950000.00, '2023-01-10', 'CB000001'),
(7, '2023-01-01', 1950000.00, '2023-01-10', 'CB000001'),
(8, '2023-01-01', 950000.00, '2023-01-10', 'CB000001'),
(9, '2023-01-01', 950000.00, '2023-01-10', 'CB000001'),
(10, '2023-01-01', 950000.00, '2023-01-10', 'CB000001'),
(11, '2023-01-01', 950000.00, '2023-01-10', 'CB000001'),
(12, '2023-01-01', 950000.00, '2023-01-10', 'CB000001'),
(13, '2023-01-01', 950000.00, '2023-01-10', 'CB000001'),
(14, '2023-01-01', 950000.00, '2023-01-10', 'CB000001'),
(15, '2023-01-01', 950000.00, '2023-01-10', 'CB000001'),
(16, '2023-01-01', 2575000.00, '2023-01-10', 'CB000001'),
(17, '2023-01-01', 950000.00, '2023-01-10', 'CB000001'),
(18, '2023-01-01', 950000.00, '2023-01-10', 'CB000001'),
(19, '2023-01-01', 950000.00, '2023-01-10', 'CB000001'),
(20, '2023-01-01', 950000.00, '2023-01-10', 'CB000001');

-- Chèn dữ liệu mẫu vào bảng DienNuoc
INSERT INTO DienNuoc (Thang, NamHoc, PhiDien, PhiNuoc, HocKi, MaPhong)
VALUES
(1, '2024', 50000.00, 30000.00, '2',  'A01100'),
(2, '2024', 60000.00, 35000.00, '2',  'A01101'),
(3, '2024', 55000.00, 32000.00, '2',  'A01102'),
(4, '2024', 58000.00, 34000.00, '2',  'A01103'),
(5, '2024', 62000.00, 36000.00, '2',  'A01104'),
(6, '2024', 50000.00, 30000.00, '3',  'A01105'),
(7, '2024', 60000.00, 35000.00, '3',  'A01106'),
(8, '2024', 55000.00, 32000.00, '3',  'A01201'),
(9, '2024', 58000.00, 34000.00, '1',  'A01202'),
(10, '2024', 62000.00, 36000.00, '1',  'A01203'),
(11, '2024', 50000.00, 30000.00, '1',  'A01204'),
(12, '2024', 60000.00, 35000.00, '1',  'A01205'),
(1, '2024', 55000.00, 32000.00, '2',  'A01206'),
(2, '2024', 58000.00, 34000.00, '2',  'A01208'),
(3, '2024', 62000.00, 36000.00, '2',  'A01209'),
(4, '2024', 50000.00, 30000.00, '2',  'A01210'),
(5, '2024', 60000.00, 35000.00, '2',  'A01211'),
(6, '2024', 55000.00, 32000.00, '3',  'A01212'),
(7, '2024', 58000.00, 34000.00, '3',  'A01213'),
(8, '2024', 62000.00, 36000.00, '3',   'A01100');





