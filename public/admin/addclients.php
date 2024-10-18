<?php
// Khởi tạo phiên làm việc
if (!isset($_SESSION)) {
    session_start();
}
// Kiểm tra xem vai trò đã được lưu trong session hay chưa
if (isset($_SESSION['Role'])) {
    $role = $_SESSION['Role'];
    if ($role === 'user') {
        echo "<script>alert('Bạn không có quyền truy cập vào trang này.')
        window.location.href='../../user/dashboard.php';
        </script>";
        die();
    }
}
// nếu chưa đăng nhập thì chuyển hướng về trang đăng nhập
else {
    echo "<script>alert('Vui lòng đăng nhập.')
    window.location.href='./index.php';
    </script>";
    die();
}

// Bắt đầu nội dung trang
ob_start();
include_once __DIR__ . '/../../partials/header.php';
include_once __DIR__ . '/../../partials/heading.php';
require_once __DIR__ . '/../../config/dbadmin.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $contactName = $_POST['contactName'];
    $address = $_POST['address'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $password = $_POST['password']; 
    $notes = $_POST['w3review'];

    // Băm mật khẩu thành dạng bcrypt
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Kiểm tra xem email đã tồn tại trong cơ sở dữ liệu hay chưa
    $emailCheckQuery = "SELECT COUNT(*) as count FROM tblclient WHERE Email = :email";
    $stmtCheck = $dbh->prepare($emailCheckQuery);
    $stmtCheck->bindParam(':email', $email, PDO::PARAM_STR);
    $stmtCheck->execute();
    $result = $stmtCheck->fetch(PDO::FETCH_ASSOC);

    if ($result['count'] > 0) {
        echo "<script>alert('Địa chỉ email đã tồn tại trong cơ sở dữ liệu.');</script>";
    } else {
        // Nếu email không tồn tại, thực hiện thêm khách hàng
        try {
            $query = "INSERT INTO tblclient (ContactName, Address, Cellphnumber, Email, Password, Notes) VALUES (:contactName, :address, :mobile, :email, :password, :notes)";
            $stmt = $dbh->prepare($query);
            $stmt->bindParam(':contactName', $contactName, PDO::PARAM_STR);
            $stmt->bindParam(':address', $address, PDO::PARAM_STR);
            $stmt->bindParam(':mobile', $mobile, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR); 
            $stmt->bindParam(':notes', $notes, PDO::PARAM_STR);
            $stmt->execute();
            echo "<script>alert('Thêm khách hàng thành công!');</script>";
        } catch (PDOException $e) {
            echo "Lỗi thêm khách hàng: " . $e->getMessage();
        }
    }
}
?>

<body>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <?php
            include_once __DIR__ . '/sidebar.php';
            ?>
            <div class="col pr-0">

                <div class="p-4 pe-0">
                    <h5>
                        <a href="./dashboard.php" class="text-decoration-none"><small>Trang chủ</small></a>
                        <small>/ Thêm khách hàng</small>
                    </h5>
                </div>
                <div class="pb-3 pt-2 px-4 pe-0">
                    <h3>Thêm khách hàng</h3>
                </div>

                <div class="modal-body p-4 mb-5 border mx-4 rounded-2">
                    <form method="POST" id="customerForm">
                        <div class="mb-3">
                            <label for="" class="form-label">Tên liên hệ</label>
                            <input name="contactName" type="text" class="form-control border rounded-1" id="" aria-describedby="" placeholder="Nhập tên liên hệ">
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Địa chỉ</label>
                            <textarea id="address" class="border w-100 rounded-1" name="address" rows="4" cols="50" placeholder="Address"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="" class="form-label">Số điện thoại di động</label>
                            <input type="text" name="mobile" class="form-control border rounded-1" id="" aria-describedby="" placeholder="Số điện thoại di động">
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Địa chỉ email</label>
                            <input type="email" class="form-control border rounded-1" id="" aria-describedby="" name="email" placeholder="Địa chỉ email">
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Mật khẩu</label>
                            <input type="password" name="password" class="form-control border rounded-1" id="" aria-describedby="" placeholder="Mật khẩu">
                        </div>
                        <div class="mb-3">
                            <label for="" note="note" class="form-label w-100">Ghi chú</label>
                            <textarea id="" class="border w-100 rounded-1" name="w3review" rows="4" cols="50" placeholder="Ghi chú">
                            </textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <span class="text-decoration-none text-white">Save</span>
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <?php
    include_once __DIR__ . '/../../partials/footer.php';
    ?>
    <script src="../assets/js/addclients.js"></script>
</body>

</html>