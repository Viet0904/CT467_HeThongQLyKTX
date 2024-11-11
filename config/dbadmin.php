<?php
// DB credentials.
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PORT', '3306');
define('DB_PASS', '');
define('DB_NAME', 'htqlktx');
define("APPNAME", "Hệ thống quản lý KTX");
// Establish database connection.
try {
    $dbh = new PDO("mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8mb4'"));
} catch (PDOException $e) {
    exit("Error: " . $e->getMessage());
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SERVER['REQUEST_URI'] === '/api/payment') {
    $data = json_decode(file_get_contents('php://input'), true);
    $maPhong = $data['MaPhong'];
    $thang = $data['Thang'];
    $namHoc = $data['NamHoc'];
    $hocKi = $data['HocKi'];

    try {
        $stmt = $dbh->prepare("UPDATE DienNuoc SET NgayThanhToan = NOW() WHERE MaPhong = ? AND Thang = ? AND NamHoc = ? AND HocKi = ?");
        $stmt->bindValue(1, $maPhong);
        $stmt->bindValue(2, $thang);
        $stmt->bindValue(3, $namHoc);
        $stmt->bindValue(4, $hocKi);
        $stmt->execute();

        http_response_code(200);
        echo json_encode(['success' => true, 'message' => 'Thanh toán thành công!']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Đã xảy ra lỗi: ' . $e->getMessage()]);
    }
    exit;
}
