<?php
include_once __DIR__ . '/../../partials/header.php';
include_once __DIR__ . '/../../partials/heading.php';
include_once __DIR__ . '/../../config/dbadmin.php'; // Đảm bảo kết nối DB ở đây

?>

<body>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <?php include_once __DIR__ . '/sidebar.php'; ?>

            <div class="col px-0">
                <!-- Nội dung chính -->
                <div class="my-2" style="margin-left: 260px;">
                    <div class="modal-header-1">
                        <h5 class="modal-title mt-2">Hồ sơ quản trị viên</h5>
                    </div>

                    <div class="modal-user">
                        <form action="" method="POST">
                            <h5 class="mt-1"><b>Hồ sơ</b></h5>

                            <div class="mb-3">
                                <label for="tenQt" class="form-label">Tên quản trị viên</label>
                                <input type="text" class="form-control" id="tenQt" name="tenQt" value="<?= htmlspecialchars($admin['tenQt'] ?? '') ?>">
                            </div>

                            <div class="mb-3">
                                <label for="maQt" class="form-label">Mã quản trị viên</label>
                                <input type="text" class="form-control" id="maQt" name="maQt" value="<?= htmlspecialchars($admin['maQt'] ?? '') ?>">
                            </div>

                            <div class="mb-3">
                                <label for="soLienLac" class="form-label">Số liên lạc</label>
                                <input type="text" class="form-control" id="soLienLac" name="soLienLac" value="<?= htmlspecialchars($admin['soLienLac'] ?? '') ?>">
                            </div>

                            <div class="row row-add">
                                <div class="col-md-6">
                                    <label for="gender" class="form-label">Giới tính</label>
                                    <input type="text" class="form-control" id="gender" name="gender" value="<?= htmlspecialchars($admin['gender'] ?? '') ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="ngaySinh" class="form-label">Ngày sinh</label>
                                    <input type="date" class="form-control" id="ngaySinh" name="ngaySinh" value="<?= htmlspecialchars($admin['ngaySinh'] ?? '') ?>">
                                </div>
                            </div>

                            <div class="text-end mt-3">
                                <button type="submit" class="btn btn-primary" style="background-color: #db3077;">Cập nhật</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
