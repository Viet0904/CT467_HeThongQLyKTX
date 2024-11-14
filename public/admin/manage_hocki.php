<?php
include_once __DIR__ . '/../../config/dbadmin.php'; // Kết nối cơ sở dữ liệu
include_once __DIR__ . '/../../partials/header.php'; // Tiêu đề trang
include_once __DIR__ . '/../../partials/heading.php'; // Đường dẫn

$termId = isset($_GET['id']) ? $_GET['id'] : null;
$termData = [
    'HocKi' => '',
    'NamHoc' => '',
    'BatDau' => '',
    'KetThuc' => ''
];

// Lấy thông tin học kỳ nếu có ID
if ($termId) {
    $stmt = $dbh->prepare("SELECT * FROM HocKi WHERE HocKi = :id");
    $stmt->execute([':id' => $termId]);
    $termData = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$termData) {
        echo "<div class='alert alert-danger'>Học kỳ không tồn tại.</div>";
        exit;
    }

    // Kiểm tra xem học kỳ có sử dụng trong DienNuoc không
    $checkDienNuoc = $dbh->prepare("SELECT COUNT(*) FROM DienNuoc WHERE HocKi = :HocKi");
    $checkDienNuoc->execute([':HocKi' => $termId]);
    $dienNuocCount = $checkDienNuoc->fetchColumn();

    if ($dienNuocCount > 0) {
        echo "<script>alert('Không thể sửa vì học kỳ đã được sử dụng trong điện nước.'); window.location.href = 'view_hocki.php';</script>";
        exit;
    }
}

// Xử lý khi form được gửi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $termName = $_POST['HocKi'];
    $schoolYear = $_POST['NamHoc'];
    $startDate = $_POST['BatDau'];
    $endDate = $_POST['KetThuc'];

    if ($termId) {
        // Cập nhật học kỳ
        $stmt = $dbh->prepare("UPDATE HocKi SET NamHoc = :NamHoc, BatDau = :BatDau, KetThuc = :KetThuc WHERE HocKi = :HocKi");
        $stmt->execute([
            ':HocKi' => $termId,
            ':NamHoc' => $schoolYear,
            ':BatDau' => $startDate,
            ':KetThuc' => $endDate
        ]);
        echo "<script>alert('Cập nhật học kỳ thành công.'); window.location.href = 'view_hocki.php';</script>";
    } else {
        // Kiểm tra học kỳ đã tồn tại chưa
        $checkTerm = $dbh->prepare("SELECT COUNT(*) FROM HocKi WHERE HocKi = :HocKi");
        $checkTerm->execute([':HocKi' => $termName]);
        if ($checkTerm->fetchColumn() > 0) {
            echo "<div class='alert alert-danger'>Học kỳ đã tồn tại.</div>";
        } else {
            // Thêm học kỳ mới
            $stmt = $dbh->prepare("INSERT INTO HocKi (HocKi, NamHoc, BatDau, KetThuc) VALUES (:HocKi, :NamHoc, :BatDau, :KetThuc)");
            $stmt->execute([
                ':HocKi' => $termName,
                ':NamHoc' => $schoolYear,
                ':BatDau' => $startDate,
                ':KetThuc' => $endDate
            ]);
            echo "<script>alert('Thêm học kỳ mới thành công.'); window.location.href = 'view_hocki.php';</script>";
        }
    }
}

$showForm = $termId || isset($_GET['action']) && $_GET['action'] === 'add';
?>

<body>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <?php include_once __DIR__ . '/sidebar.php'; ?>
            <div class="col px-0">
                <div class="my-2" style="margin-left: 260px;">
                    <div class="modal-header-1">
                        <h5 class="modal-title mt-2"><?php echo $showForm ? ($termId ? "Sửa học kỳ" : "Thêm học kỳ mới") : "Danh sách học kỳ"; ?></h5>
                    </div>

                    <?php if ($showForm): ?>
                        <div class="modal-user mt-3">
                            <form action="" method="POST">
                                <div class="form-group">
                                    <label for="HocKi"><b>Tên học kỳ</b></label>
                                    <input type="text" name="HocKi" class="form-control" required value="<?php echo htmlspecialchars($termData['HocKi']); ?>" <?php echo $termId ? 'readonly' : ''; ?>>
                                </div>
                                <div class="form-group">
                                    <label for="NamHoc"><b>Năm học</b></label>
                                    <input type="text" name="NamHoc" class="form-control" required value="<?php echo htmlspecialchars($termData['NamHoc']); ?>" <?php echo $termId ? 'readonly' : ''; ?>>
                                </div>
                                <div class="form-group">
                                    <label for="BatDau"><b>Ngày bắt đầu</b></label>
                                    <input type="date" name="BatDau" class="form-control" required value="<?php echo htmlspecialchars($termData['BatDau']); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="KetThuc"><b>Ngày kết thúc</b></label>
                                    <input type="date" name="KetThuc" class="form-control" required value="<?php echo htmlspecialchars($termData['KetThuc']); ?>">
                                </div>
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-success"><?php echo $termId ? "Cập nhật" : "Thêm mới"; ?></button>
                                    <a href="view_hocki.php" class="btn btn-secondary">Trở về</a>
                                </div>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
