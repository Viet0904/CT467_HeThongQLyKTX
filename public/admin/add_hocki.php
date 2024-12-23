<?php
include_once __DIR__ . '/../../config/dbadmin.php';
include_once __DIR__ . '/../../partials/header.php';
include_once __DIR__ . '/../../partials/heading.php';

$message = '';
// $maSinhVien = $_GET['msv'] ?? '';

$currentPhong = '';

// Xử lý form gửi đi
if (isset($_POST['HocKi']) && isset($_POST['NamHoc']) && isset($_POST['BatDau']) && isset($_POST['KetThuc'])) {
    $hocKi = $_POST['HocKi'];
    $namHoc = $_POST['NamHoc'];
    $batDau = $_POST['BatDau'];
    $ketThuc = $_POST['KetThuc'];

    try {
        $stmt = $dbh->prepare("CALL ThemHocKi(:hocKi, :namHoc, :batDau, :ketThuc, @message, @errorCode)");
        $stmt->execute([
            ':hocKi' => $hocKi,
            ':namHoc' => $namHoc,
            ':batDau' => $batDau,
            ':ketThuc' => $ketThuc,
        ]);

        $result = $dbh->query("SELECT @message AS message, @errorCode AS errorCode")->fetch(PDO::FETCH_ASSOC);
        $message = $result['message'];
        $errorCode = $result['errorCode'];

        if ($errorCode != 0) {
            throw new Exception($message);
        }
    } catch (Exception $e) {
        $message = $e->getMessage();
    }
    echo '<script>alert("' . $message . '");window.location.href="view_hocki.php";</script>';
}





?>

<body>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <?php include_once __DIR__ . '/sidebar.php'; ?>
            <div class="col px-0">
                <div class="my-2" style="margin-left: 260px;">
                    <div class="modal-header-1">
                        <h5 class="modal-title mt-2">Thêm khu KTX</h5>
                    </div>

                    <div class="modal-user mt-3">
                        <form action="add_hocki.php" method="POST">

                            <div class="row row-add mb-3">
                                <div class="col-md-3">
                                    <label for="HocKi" class="form-label">Học Kì</label>
                                    <select class="form-control" id="HocKi" name="HocKi" required>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                    </select>
                                </div>
                                <div class="col-md-3">

                                    <label for="NamHoc" class="form-label">Năm Học</label>
                                    <?php
                                    $currentYear = date("Y");
                                    ?>
                                    <input type="text" class="form-control" id="NamHoc" name="NamHoc" required value="<?php echo $currentYear; ?>" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label for="BatDau" class="form-label">Bắt Đầu</label>
                                    <input type="date" class="form-control" id="BatDau" name="BatDau" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="KetThuc" class="form-label">Kết Thúc</label>
                                    <input type="date" class="form-control" id="KetThuc" name="KetThuc" required>
                                </div>


                            </div>
                            <div class="text-end mt-2">
                                <button type="submit" class="btn btn-primary" style="background-color: #db3077;">Lưu</button>
                            </div>

                    </div>

                    </form>
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