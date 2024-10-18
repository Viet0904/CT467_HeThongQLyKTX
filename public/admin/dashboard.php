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
?>

<!-- Nội dung trang dashboard -->

<body>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <?php
            include_once __DIR__ . '/sidebar.php';
            ?>
            <div class="col py-5">
                <div class="p-4 row">
                    <div class="col text-center">
                        <div class="width-icon">
                            <div class="bg-blue stats-left">
                                <h5 class="text-white">Tổng cộng</h5>
                                <h4 class="text-white pt-2">Khách hàng</h4>
                            </div>
                            <div class="bg-gray stats-right">
                                <?php
                                $sql = "SELECT ClientID from tblclient ";
                                $query = $dbh->prepare($sql);
                                $query->execute();
                                $tclients = $query->rowCount();
                                ?>
                                <label class="text-black" for="">
                                    <?php echo htmlspecialchars($tclients); ?>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col text-center">
                        <div class="width-icon">
                            <div class="bg-blue-1 stats-left">
                                <h5 class="text-white">Tổng cộng</h5>
                                <h4 class="text-white pt-2">Dịch vụ</h4>
                            </div>
                            <div class="bg-gray stats-right">
                                <?php
                                $sql = "SELECT ID from tblservices ";
                                $query = $dbh->prepare($sql);
                                $query->execute();
                                $tservices = $query->rowCount();
                                ?>
                                <label class="text-black" for="">
                                    <?php echo htmlspecialchars($tservices); ?>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col text-center">
                        <div class="width-icon">
                            <div class="bg-blue stats-left">
                                <h5 class="text-white">Hôm nay</h5>
                                <?php
                                $sql6 = "select  sum(tblservices.ServicePrice) as todaysale
from tblinvoice join tblservices  on tblservices.ID=tblinvoice.ServiceId where date(PostingDate)=CURDATE()";

                                $query6 = $dbh->prepare($sql6);
                                $query6->execute();
                                $results6 = $query6->fetchAll(PDO::FETCH_OBJ);
                                $todays_sale_total = 0;
                                foreach ($results6 as $row6) {
                                    $todays_sale_total += $row6->todaysale;
                                }
                                ?>
                                <h4 class="text-white pt-2">Doanh số($)</h4>
                            </div>
                            <div class="bg-gray stats-right">
                                <label class="text-black" for="">
                                    <?php echo htmlspecialchars($todays_sale_total); ?>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-4 row pt-5">
                    <div class="col text-center pt-2">
                        <div class="width-icon">
                            <div class="bg-blue-1 stats-left">
                                <h5 class="text-white">Hôm qua</h5>
                                <?php
                                $sql7 = "select  sum(tblservices.ServicePrice) as totalcost
 from tblinvoice join tblservices  on tblservices.ID=tblinvoice.ServiceId where date(PostingDate)=CURDATE()-1;";

                                $query7 = $dbh->prepare($sql7);
                                $query7->execute();
                                $results7 = $query7->fetchAll(PDO::FETCH_OBJ);
                                $yest_sale = 0;
                                foreach ($results7 as $row7) {
                                    $yest_sale += $row7->totalcost;
                                }
                                ?>
                                <h4 class="text-white pt-2">Doanh số($)</h4>
                            </div>
                            <div class="bg-gray stats-right">
                                <label class="text-black" for="">
                                    <?php echo htmlspecialchars($yest_sale); ?>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col text-center pt-2">
                        <div class="width-icon">
                            <div class="bg-blue stats-left">
                                <h5 class="text-white">Tuần trước </h5>
                                <h4 class="text-white pt-2">Doanh số($)</h4>
                            </div>
                            <div class="bg-gray stats-right">
                                <?php
                                $sql8 = "select  sum(tblservices.ServicePrice) as totalcost
 from tblinvoice 
  join tblservices  on tblservices.ID=tblinvoice.ServiceId where date(PostingDate)>=(DATE(NOW()) - INTERVAL 7 DAY);";

                                $query8 = $dbh->prepare($sql8);
                                $query8->execute();
                                $results8 = $query8->fetchAll(PDO::FETCH_OBJ);
                                $sevendays_sale = 0;
                                foreach ($results8 as $row8) {
                                    $sevendays_sale += $row8->totalcost;
                                }
                                ?>
                                <label class="text-black" for="">
                                    <?php echo htmlspecialchars($sevendays_sale); ?>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col text-center pt-2">
                        <div class="width-icon">
                            <div class="bg-blue-1 stats-left">
                                <h5 class="text-white">Tổng cộng</h5>
                                <h4 class="text-white pt-2">Doanh số($)</h4>
                            </div>
                            <div class="bg-gray stats-right">
                                <?php
                                $sql9 = "select  sum(tblservices.ServicePrice) as totalcost
 from tblinvoice join tblservices  on tblservices.ID=tblinvoice.ServiceId";
                                $query9 = $dbh->prepare($sql9);
                                $query9->execute();
                                $results9 = $query9->fetchAll(PDO::FETCH_OBJ);
                                $total_sale = 0;
                                foreach ($results9 as $row9) {
                                    $total_sale += $row9->totalcost;
                                }
                                ?>
                                <label class="text-black" for="">
                                    <?php echo htmlspecialchars($total_sale); ?>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    include_once __DIR__ . '/../../partials/footer.php';
    ?>
</body>

</html>