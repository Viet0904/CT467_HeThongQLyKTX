<?php
include_once __DIR__ . '/../../config/dbadmin.php';
include_once __DIR__ . '/../../partials/header.php';
include_once __DIR__ . '/../../partials/heading.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Kết nối cơ sở dữ liệu và thực hiện truy vấn
        $stmt = $dbh->prepare("INSERT INTO DienNuoc (maPhong, thang, namhoc, hocki) VALUES (?, ?, ?, ?)");
        $stmt->execute([$_POST['maPhong'], $_POST['thang'], $_POST['namhoc'], $_POST['hocki']]);
        $successMessage = "Dữ liệu đã được thêm thành công.";
    } catch (PDOException $e) {
        if ($e->getCode() == '45000') {
            $errorMessage = "Không thể thêm trùng dữ liệu. Vui lòng thêm lại";
            echo "<script>alert('{$errorMessage}');</script>";
        } else {
            exit("Error: " . $e->getMessage());
        }
    }
    if (isset($successMessage)) {
        echo "<script>alert('{$successMessage}');</script>";
    }
}
$maPhong = $_GET['maphong'] ?? '';
try {
    $stmt = $dbh->prepare("SELECT MaPhong FROM Phong");
    $stmt->execute();
    $phongList = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    exit("Error: " . $e->getMessage());
}

try {
    $stmt = $dbh->prepare("SELECT HocKi FROM DienNuoc");
    $stmt->execute();
    $HocKi = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    exit("Error: " . $e->getMessage());
}
?>
?>


<body>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <?php
            include_once __DIR__ . '/sidebar.php';
            ?>

            <div class="col px-0">
                <!-- Nội dung chính -->
                <div class="my-2" style="margin-left: 260px;">
                    <div class="modal-header-1">
                        <h5 class="modal-title mt-2">Thêm Điện Nước Phòng</h5>
                    </div>

                    <!-- Hiển thị thông báo -->
                    <?php if (!empty($message)): ?>
                        <div class="alert alert-info mt-3"><?php echo $message; ?></div>
                    <?php endif; ?>

                    <div class="modal-user">
                        <form action="add_diennuoc.php" method="POST">

                            <!-- School Details Section -->
                            <h5 class="mt-1"><b>Chi tiết Phòng</b></h5>
                            <div class="row row-add mb-3">
                                <div class="col-md-4">
                                    <label for="maPhong" class="form-label">Mã Phòng</label>
                                    <select class="form-control" id="maPhong" name="maPhong" required>
                                        <option value="<?php echo htmlspecialchars($maPhong); ?>" selected><?php echo htmlspecialchars($maPhong); ?></option>
                                        <?php foreach ($phongList as $phong): ?>
                                            <?php if ($phong['MaPhong'] !== $maPhong): ?>
                                                <option value="<?= htmlspecialchars($phong['MaPhong']) ?>"><?= htmlspecialchars($phong['MaPhong']) ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>

                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <labe for="thang" class="form-label">Tháng</label>
                                        <select class="form-control" id="thang" name="thang" required>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                            <option value="10">10</option>
                                            <option value="11">11</option>
                                            <option value="11">12</option>
                                        </select>
                                </div>
                            </div>
                            <div class="row row-add mb-3">
                                <div class="col-md-4">
                                    <label for="namhoc" class="form-label">Năm Học</label>
                                    <select class="form-control" id="namhoc" name="namhoc" required>
                                        <?php
                                        $currentYear = date('Y');
                                        for ($year = 2020; $year <= $currentYear; $year++): ?>
                                            <option value="<?= $year ?>"><?= $year ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="hocki" class="form-label">Học kì</label>
                                    <select class="form-control" id="hocki" name="hocki" required>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row row-add mb-3">
                                <div class="col-md-4">
                                    <label for="phiDien" class="form-label">Phí sử dụng Điện</label>
                                    <input type="number" class="form-control" id="phiDien" name="phiDien" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="phiNuoc" class="form-label">Phí sử dụng Nước</label>
                                    <input type="number" class="form-control" id="phiNuoc" name="phiNuoc" required>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="text-end mt-2">
                                <button type="submit" class="btn btn-primary"
                                    style="background-color: #db3077;">Lưu</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
    </div>
</body>

<!-- Bootstrap JS và Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-oBqDVmMz4fnFO9gybBogGzPztE1M5rZG/8Xlqh8fATrSWJZDmmW4Ll48dWkOVbCH"
    crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
    integrity="sha384-shoIXUoVOFk60M7DuE4bfOY1pNIqcd9tPCSZrhTDQTXkNv8El+fEfXksqNhUNuUc"
    crossorigin="anonymous"></script>

<script>
    // Hàm mở và đóng dropdown khi bấm tên admin
    function toggleDropdown(event) {
        event.stopPropagation(); // Ngăn chặn sự kiện click bên ngoài
        var dropdown = document.getElementById("dropdownMenu");
        dropdown.style.display = (dropdown.style.display === "block") ? "none" : "block"; // Toggle dropdown
    }

    // Hàm mở và đóng dropdown khi bấm vào nút Action
    function toggleActionDropdown(id) {
        var dropdown = document.getElementById(id);
        if (dropdown.style.display === "none" || dropdown.style.display === "") {
            dropdown.style.display = "block"; // Hiển thị dropdown
        } else {
            dropdown.style.display = "none"; // Ẩn dropdown
        }
    }

    // Đóng tất cả các dropdown nếu click bên ngoài
    window.onclick = function(event) {
        var dropdownMenu = document.getElementById("dropdownMenu");

        // Đóng dropdown của tên admin nếu click bên ngoài
        if (!event.target.matches('#userDropdown') && !event.target.matches('.ms-1') && !dropdownMenu.contains(event.target)) {
            dropdownMenu.style.display = "none"; // Đảm bảo đóng dropdown
        }
    }
</script>

</html>