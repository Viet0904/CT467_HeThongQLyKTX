-- Tạo bảng KhuKTX
CREATE TABLE KhuKTX (
    MaKhuKTX VARCHAR(10) PRIMARY KEY,
    TenKhuKTX VARCHAR(50)
);

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
    ConTrong INT,
    GhiChu TEXT,
    GioiTinh VARCHAR(10),
    FOREIGN KEY (MaDay) REFERENCES Day(MaDay)
);

-- Tạo bảng Lop
CREATE TABLE Lop (
    MaLop VARCHAR(10) PRIMARY KEY,
    TenLop VARCHAR(50),
    MaKhoa VARCHAR(10),
    FOREIGN KEY (MaKhoa) REFERENCES Khoa(MaKhoa)
);

-- Tạo bảng PhiDienNuoc
CREATE TABLE PhiDienNuoc (
    STT INT PRIMARY KEY AUTO_INCREMENT,
    Thang INT,
    Loai VARCHAR(20),
    PhiSuDung DECIMAL(10, 2),
    SoTienSVDong DECIMAL(10, 2),
    TongSoTienSVDong DECIMAL(10, 2),
    TongNoPhong DECIMAL(10, 2),
    NgayDong DATE,
    NamHoc VARCHAR(20)
);

-- Tạo bảng Day
CREATE TABLE Day (
    MaDay VARCHAR(10) PRIMARY KEY,
    TenDay VARCHAR(50),
    MaKhuKTX VARCHAR(10),
    FOREIGN KEY (MaKhuKTX) REFERENCES KhuKTX(MaKhuKTX)
);

-- Tạo bảng SinhVien
CREATE TABLE SinhVien (
    MaSinhVien VARCHAR(8) PRIMARY KEY,
    HoTen VARCHAR(50),
    SDT VARCHAR(10),
    MaLop VARCHAR(10),
    DiaChi VARCHAR(100),
    GioiTinh VARCHAR(10),
    KhoaHoc INT,
    NgaySinh DATE,
    ChucVu VARCHAR(50),
    MaDay VARCHAR(10),
    Password VARCHAR(255),
    FOREIGN KEY (MaLop) REFERENCES Lop(MaLop),
    FOREIGN KEY (MaDay) REFERENCES Day(MaDay)
);

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

-- Tạo bảng Khoa
CREATE TABLE Khoa (
    MaKhoa VARCHAR(10) PRIMARY KEY,
    TenKhoa VARCHAR(50)
);

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
