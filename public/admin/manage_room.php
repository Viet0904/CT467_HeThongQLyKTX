<?php
include_once __DIR__ . '/../../config/dbadmin.php';
include_once __DIR__ . '/../../partials/header.php';
include_once __DIR__ . '/../../partials/heading.php';

$roomId = isset($_GET['id']) ? $_GET['id'] : null;
$roomData = [
    'MaPhong' => '',
    'MaDay' => '',
    'TenPhong' => '',
    'LoaiPhong' => 'Nam',
    'DienTich' => '',
    'SoGiuong' => '',
    'SucChua' => '',
    'SoChoThucTe' => '',
    'DaO' => '',
    'GiaThue' => '0.0',
    'TrangThaiSuDung' => 'Chưa sử dụng'
];

if ($roomId) {
    $stmt = $dbh->prepare("SELECT *, SoChoConLai(MaPhong) AS ConTrong FROM Phong WHERE MaPhong = :id");
    $stmt->execute([':id' => $roomId]);
    $roomData = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$roomData) {
        // Handle case where room does not exist
        echo "Phòng không tồn tại.";
        exit;
    }
}
?>

<body>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <?php include_once __DIR__ . '/sidebar.php'; ?>

            <div class="col px-0">
                <!-- Nội dung chính -->
                <div class="my-2" style="margin-left: 260px;">
                    <div class="modal-header-1">
                        <h5 class="modal-title mt-2"><?php echo $roomId ? 'Chỉnh sửa phòng' : 'Thêm phòng mới'; ?></h5>
                    </div>

                    <div class="modal-user mt-3">
                        <form action="/admin/action/manage_room_action.php" method="POST">
                            <input type="hidden" name="maphong" value="<?php echo $roomData['MaPhong']; ?>">
                            <div class="row row-add mb-3 mt-1">
                                <div class="col-md-4">
                                    <label for="maphong" class="form-label">Mã phòng</label>
                                    <input type="text" class="form-control" id="maphong" name="maphong" value="<?php echo $roomData['MaPhong']; ?>" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="MaDay" class="form-label">Mã dãy</label>
                                    <input type="text" class="form-control" id="MaDay" name="MaDay" value="<?php echo $roomData['MaDay']; ?>" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="tenphong" class="form-label">Tên phòng</label>
                                    <input type="text" class="form-control" id="tenphong" name="tenphong" value="<?php echo $roomData['TenPhong']; ?>" required>
                                </div>
                            </div>

                            <div class="row row-add mb-3">
                                <div class="col-md-4">
                                    <label for="loaiphong" class="form-label">Loại phòng</label>
                                    <select class="form-select" id="loaiphong" name="loaiphong">
                                        <option value="Nam" <?php echo $roomData['LoaiPhong'] === 'Nam' ? 'selected' : ''; ?>>Nam</option>
                                        <option value="Nữ" <?php echo $roomData['LoaiPhong'] === 'Nữ' ? 'selected' : ''; ?>>Nữ</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="dientich" class="form-label">Diện tích</label>
                                    <input type="text" class="form-control" id="dientich" name="dientich" value="<?php echo $roomData['DienTich']; ?>" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="sogiuong" class="form-label">Số giường</label>
                                    <input type="number" class="form-control" id="sogiuong" name="sogiuong" value="<?php echo $roomData['SoGiuong']; ?>" required>
                                </div>
                            </div>

                            <div class="row row-add mb-3">
                                <div class="col-md-4">
                                    <label for="succhua" class="form-label">Sức chứa</label>
                                    <input type="number" class="form-control" id="succhua" name="succhua" value="<?php echo $roomData['SucChua']; ?>" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="sochothucte" class="form-label">Số chỗ thực tế</label>
                                    <input type="number" class="form-control" id="sochothucte" name="sochothucte" value="<?php echo $roomData['SoChoThucTe']; ?>" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="dao" class="form-label">Đã ở</label>
                                    <input type="number" class="form-control" id="dao" name="dao" value="<?php echo $roomData['DaO']; ?>" required>
                                </div>
                            </div>

                            <div class="row row-add mb-3">
                                <div class="col-md-12">
                                    <label for="giathue" class="form-label">Giá thuê</label>
                                    <input type="text" class="form-control" id="giathue" name="giathue" value="<?php echo number_format($roomData['GiaThue'], 2); ?>" required>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label for="statusSelect" class="form-label">Trạng thái</label>
                                <select class="form-select width-status" id="statusSelect" name="trangthai">
                                    <option value="Đang sử dụng" <?php echo $roomData['TrangThaiSuDung'] === 'Đang sử dụng' ? 'selected' : ''; ?>>Đang sử dụng</option>
                                    <option value="Chưa sử dụng" <?php echo $roomData['TrangThaiSuDung'] === 'Chưa sử dụng' ? 'selected' : ''; ?>>Chưa sử dụng</option>
                                </select>
                            </div>

                            <!-- Submit Button -->
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary" style="background-color: #db3077;">Lưu</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<!-- Bootstrap JS và Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gybBogGzPztE1M5rZG/8Xlqh8fATrSWJZDmmW4Ll48dWkOVbCH" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-shoIXUoVOFk60M7DuE4bfOY1pNIqcd9tPCSZrhTDQTXkNv8El+fEfXksqNhUNuUc" crossorigin="anonymous"></script>
</html>
