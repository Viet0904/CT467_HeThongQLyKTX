<?php
include_once __DIR__ . '/../../config/dbadmin.php';
include_once __DIR__ . '/../../partials/header.php';
include_once __DIR__ . '/../../partials/heading.php';

$message = '';
// $maSinhVien = $_GET['msv'] ?? '';

$currentPhong = '';
if (isset($_GET['MaDay'])) {
    $maDay = strtoupper(trim($_GET['MaDay'])) ?? '';
    $tenDay = $_GET['TenDay'] ?? '';
} else {
    $maDay = '';
    $tenDay = '';
}


// Xử lý form gửi đi
if (isset($_POST['MaDay']) && isset($_POST['TenDay']) && isset($_POST['MaKhuKTX'])) {
    $maDay = strtoupper(trim($_POST['MaDay']));
    $tenDay = $_POST['TenDay'];
    $makhuktx = $_POST['MaKhuKTX'];

    try {
        $stmt = $dbh->prepare("CALL SuaDay(:maDay, :tenDay,:makhuktx, @message, @errorCode)");
        $stmt->execute([
            ':maDay' => $maDay,
            ':tenDay' => $tenDay,
            ':makhuktx' => $makhuktx,
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
    echo '<script>alert("' . $message . '")</script>';
}

?>

<body>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <?php include_once __DIR__ . '/sidebar.php'; ?>
            <div class="col px-0">
                <div class="my-2" style="margin-left: 260px;">
                    <div class="modal-header-1">
                        <h5 class="modal-title mt-2">Sửa Dãy KTX</h5>
                    </div>

                    <div class="modal-user mt-3">
                        <form action="edit_day.php" method="POST">

                            <div class="row row-add mb-3">
                                <div class="col-md-4">
                                    <label for="MaKhuKTX" class="form-label">Mã Dãy</label>
                                    <input type="text" class="form-control" id="MaDay" name="MaDay" required value="<?php echo htmlspecialchars($maDay); ?>">
                                </div>
                                <div class="col-md-4">
                                    <label for="TenKhuKTX" class="form-label">Tên Dãy</label>
                                    <input type="text" class="form-control" id="TenDay" name="TenDay" required value="<?php echo htmlspecialchars($tenDay); ?>">
                                </div>
                                <div class="col-md-4">
                                    <label for="MaKhuKTX" class="form-label">Mã Khu KTX</label>
                                    <select class="form-control" id="MaKhuKTX" name="MaKhuKTX" required>
                                        <option value="">Chọn Mã Khu KTX</option>
                                        <?php
                                        if (!empty($maDay)) {
                                            $query = "SELECT MaKhuKTX FROM Day WHERE MaDay = :maDay";
                                            $stmt = $dbh->prepare($query);
                                            $stmt->execute([':maDay' => $maDay]);
                                            $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                            if ($row) {
                                                echo '<option value="' . $row['MaKhuKTX'] . '" selected>' . $row['MaKhuKTX'] . '</option>';
                                            }
                                        }

                                        $query = "SELECT MaKhuKTX FROM KhuKTX";
                                        $stmt = $dbh->prepare($query);
                                        $stmt->execute();
                                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                            echo '<option value="' . $row['MaKhuKTX'] . '">' . $row['MaKhuKTX'] . '</option>';
                                        }
                                        ?>
                                    </select>
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