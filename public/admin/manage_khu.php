<?php
include_once __DIR__ . '/../../config/dbadmin.php';
include_once __DIR__ . '/../../partials/header.php';
include_once __DIR__ . '/../../partials/heading.php';


// Gọi function GetAllKhuKTX từ SQL
$query = "SELECT * FROM KhuKTX";
$stmt = $dbh->prepare($query);
$stmt->execute();
$areas = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<body>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <?php
            include_once __DIR__ . '/sidebar.php';
            ?>

            <div class="col px-0">
                <!-- Nội dung chính -->
                <div class=" mt-4"
                    style="max-width: 1275px; margin-left: 253px; border: 1px solid #ddd; background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);">
                    <div style="padding: 2px; background-color: rgb(219, 48, 119); border-radius: 6px;"></div>
                    <div class="container-fluid py-3 px-2" style="padding: 20px;">
                        <!-- Phần header của List of Rooms -->
                        <div class="d-flex justify-content-between align-items-center">
                            <h5>Danh sách Khu KTX</h5>

                        </div>
                        <?php



                        if (count($areas) > 0) {
                            echo '<table class="table table-bordered table-striped table-hover mt-3">';
                            echo '<thead class="table-primary">';
                            echo '<tr>';
                            echo '<th>STT</th>';
                            echo '<th>Mã Khu</th>';
                            echo '<th>Tên Khu</th>';
                            echo '<th>Hoạt động</th>';


                            echo '</tr>';
                            echo '</thead>';
                            echo '<tbody>';

                            // Xuất dữ liệu của từng hàng
                            $stt =   1;
                            foreach ($areas as $area) {
                                // $ConTrong = $row["SoChoThucTe"] - $row["DaO"];
                                echo '<tr>';
                                echo '<td>' . $stt++ . '</td>';
                                echo '<td>' . htmlspecialchars($area["MaKhuKTX"]) . '</td>';
                                echo '<td>' . htmlspecialchars($area["TenKhuKTX"]) . '</td>';
                                echo '<td>
                                    <div class="dropdown position-relative float-end">
                                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" onclick="toggleActionDropdown(\'actionDropdownMenu' . htmlspecialchars($stt) . '\')">
                                            Hoạt động
                                        </button>
                                        <div id="actionDropdownMenu' . htmlspecialchars($stt) . '" class="dropdown-menu position-absolute p-0" style="display: none; min-width: 100px;">
                                            <a class="dropdown-item py-2" href="add_khu.php">Thêm</a>
                                            <a class="dropdown-item py-2" href="edit_khu.php?MaKhuKTX=' . htmlspecialchars($area['MaKhuKTX']) . '&TenKhuKTX=' . htmlspecialchars($area['TenKhuKTX']) . '">Sửa</a>
                                            <a class="dropdown-item py-2" href="delete_khu.php?MaKhuKTX=' . htmlspecialchars($area['MaKhuKTX']) . '">Xoá</a>
                                        </div>
                                    </div>
                                  </td>';
                                echo '</tr>';
                            }

                            echo '</tbody>';
                            echo '</table>';
                        } else {
                            echo "0 kết quả";
                        }
                        ?>



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