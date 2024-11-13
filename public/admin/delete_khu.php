<?php
include_once __DIR__ . '/../../config/dbadmin.php';

$makhuktx = $_GET['MaKhuKTX'] ?? '';

// Gọi hàm XoaKhuKTX
if (isset($makhuktx)) {


    try {
        $stmt = $dbh->prepare("CALL XoaKhuKTX(:maKhu, @p_Message, @p_ErrorCode)");
        $stmt->execute([
            ':maKhu' => $makhuktx,
        ]);

        $result = $dbh->query("SELECT @p_Message AS message, @errorCode AS errorCode")->fetch(PDO::FETCH_ASSOC);

        $message = $result['message'];
        $errorCode = $result['errorCode'];
        if ($errorCode != 0) {
            throw new Exception($message);
        }
    } catch (Exception $e) {
        $message = $e->getMessage();
    }
    echo '<script>alert("' . $message . '"); window.location.href="manage_khu.php";</script>';
}




?>

<body>

</body>

</html>