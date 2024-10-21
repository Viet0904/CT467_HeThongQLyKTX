use KTX;

CREATE TABLE KhuKTX (
    MaKhuKTX VARCHAR(5) PRIMARY KEY,
    TenKhuKTX VARCHAR(255) NOT NULL
);

CREATE TABLE Day (
    MaDay VARCHAR(10) PRIMARY KEY,
    TenDay VARCHAR(255) NOT NULL,
    MaKhuKTX VARCHAR(5),
    FOREIGN KEY (MaKhuKTX) REFERENCES KhuKTX(MaKhuKTX)
);


CREATE TABLE Phong (
    MaPhong VARCHAR(10) PRIMARY KEY,
    TenPhong VARCHAR(255) NOT NULL,
    DienTich DECIMAL(10, 2),
    SoGiuong INT,
    GiaThue DECIMAL(15, 2),
    MaDay VARCHAR(20),
    TrangThaiSuDung VARCHAR(50),
    SucChua INT,
    SoChoThucTe INT,
    DaO INT,
    ConTrong INT,
    GhiChu VARCHAR(255),
    FOREIGN KEY (MaDay) REFERENCES Day(MaDay)
);


CREATE TABLE SinhVien (
    MaSinhVien VARCHAR(8) PRIMARY KEY,
    HoTen VARCHAR(255) NOT NULL,
    SDT VARCHAR(10),
    MaLop VARCHAR(10),
    DiaChi VARCHAR(255),
    GioiTinh VARCHAR(10),
    NganhHoc VARCHAR(255),
    Khoa VARCHAR(255),
    KhoaHoc VARCHAR(50),
    NgaySinh DATE,
    NgayDangKy DATE,
    ChucVu VARCHAR(50),
    DangDuyet BOOLEAN,
    MaDay VARCHAR(20),
    Password VARCHAR(255),
    FOREIGN KEY (MaDay) REFERENCES Day(MaDay)
);

CREATE TABLE Lop (
    MaLop VARCHAR(20) PRIMARY KEY,
    TenLop VARCHAR(255) NOT NULL
);

CREATE TABLE NhanVien (
    MaNhanVien VARCHAR(20) PRIMARY KEY,
    HoTen VARCHAR(255) NOT NULL,
    SDT VARCHAR(15),
    GhiChu TEXT,
    GioiTinh VARCHAR(10),
    NgaySinh DATE,
    Password VARCHAR(255)
);

CREATE TABLE ThuePhong (
    MaHopDong VARCHAR(20) PRIMARY KEY,
    MaSinhVien VARCHAR(20),
    MaPhong VARCHAR(20),
    BatDau DATE,
    KetThuc DATE,
    TienDatCoc DECIMAL(15, 2),
    GiaThueThucTe DECIMAL(15, 2),
    FOREIGN KEY (MaSinhVien) REFERENCES SinhVien(MaSinhVien),
    FOREIGN KEY (MaPhong) REFERENCES Phong(MaPhong)
);

CREATE TABLE TT_ThuePhong (
    MaHopDong VARCHAR(20),
    ThangNam DATE,
    SoTien DECIMAL(15, 2),
    NgayThanhToan DATE,
    MaNhanVien VARCHAR(20),
    FOREIGN KEY (MaHopDong) REFERENCES ThuePhong(MaHopDong),
    FOREIGN KEY (MaNhanVien) REFERENCES NhanVien(MaNhanVien)
);

CREATE TABLE PhiDienNuoc (
    STT INT AUTO_INCREMENT PRIMARY KEY,
    Thang INT NOT NULL,
    Loai VARCHAR(255) NOT NULL,
    PhiSuDung DECIMAL(15, 2),
    SoTienSVDong DECIMAL(15, 2),
    TongSoTienSVDong DECIMAL(15, 2),
    TongNoPhong DECIMAL(15, 2),
    NgayDong DATE,
    NamHoc VARCHAR(255) NOT NULL
);
