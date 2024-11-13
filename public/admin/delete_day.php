<?php
include_once __DIR__ . '/../../config/dbadmin.php';

$maDay = $_GET['MaDay'] ?? '';

// Gọi hàm XoaKhuKTX
if (isset($maDay)) {


    try {
        $stmt = $dbh->prepare("CALL XoaDay(:maDay, @p_Message, @p_ErrorCode)");
        $stmt->execute([
            ':maDay' => $maDay,
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
    echo '<script>alert("' . $message . '"); window.location.href="manage_day.php";</script>';
}




?>

<body>

</body>

</html>