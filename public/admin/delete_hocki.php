<?php
include_once __DIR__ . '/../../config/dbadmin.php';

$maHocKi = $_GET['HocKi'] ?? '';
$namHoc = $_GET['NamHoc'] ?? '';

// Gọi hàm XoaKhuKTX
if (isset($maHocKi)) {



    try {
        $stmt = $dbh->prepare("CALL XoaHocKi(:maHocKi, :namHoc, @p_Message, @p_ErrorCode)");
        $stmt->execute([
            ':maHocKi' => $maHocKi,
            ':namHoc' => $namHoc,
        ]);

        $result = $dbh->query("SELECT @p_Message AS message, @p_ErrorCode AS errorCode")->fetch(PDO::FETCH_ASSOC);

        $message = $result['message'];
        $errorCode = $result['errorCode'];
        if ($errorCode != 0) {
            throw new Exception($message);
        }
    } catch (Exception $e) {
        $message = $e->getMessage();
    }
    echo '<script>alert("' . $message . '"); window.location.href="manage_hocki.php";</script>';
}




?>

<body>

</body>

</html>