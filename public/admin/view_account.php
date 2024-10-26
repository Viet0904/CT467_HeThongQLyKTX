<?php
include_once __DIR__ . '/../../partials/header.php';
include_once __DIR__ . '/../../partials/heading.php';
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
                        <h5 class="modal-title mt-2">Account Details</h5>
                    </div>

                    <div class="modal-user">
                        <form action="" method="POST">
                            <!-- School Details Section -->
                            <div class="col-md-4">
                                <h5 class="mt-1"><b>School Details</b></h5>
                            </div>
                            <div class="row row-add mt-2">
                                <div class="col-md-4 ">
                                    <label for="schoolID"> <b>School ID/Code</b></label>
                                    <p class="mb-2 mt-1 mx-3">B2111837</p>
                                </div>
                                <div class="col-md-4">
                                    <label for="course"><b>Class</b></label>
                                    <p class="mb-2 mt-1 mx-3">Hg218732</p>
                                </div>
                            </div>
                            <div class="row row-add">
                                <div class="col-md-4">
                                    <label for="department"><b>Department</b></label>
                                    <p class="mt-1 mb-2 mx-3">Truong Cong Nghe Thong Tin & TT</p>
                                </div>
                            </div>

                            <!-- Đường phân cách -->
                            <hr style="border: none; border-top: 1px solid #a9a9a9; margin: 1px 0;">

                            <!-- Personal Information Section -->
                            <h5 class="mt-3"><b>Personal Information</b></h5>
                            <div class="row row-add">
                                <div class="col-md-4">
                                    <label for="firstName"><b>Name</b></label>
                                    <p class="mb-2 mt-1 mx-3">Pham Gia Khang</p>
                                </div>
                            </div>

                            <div class="row row-add">
                                <div class="col-md-4">
                                    <label for="gender"><b>Gender</b></label>
                                    <p class="mb-2 mt-1 mx-3">Male</p>
                                </div>
                                <div class="col-md-4">
                                    <label for="contact"><b>Contact #</b></label>
                                    <p class="mb-2 mt-1 mx-3">0937367183</p>
                                </div>
                                <div class="col-md-4">
                                    <label for="email"><b>Email</b></label>
                                    <p class="mb-2 mt-1 mx-3">pkhang@gmail.com</p>
                                </div>
                            </div>

                            <div class="row row-add">
                                <div class="col-md-12">
                                    <label for="address"><b>Address</b></label>
                                    <p class="mb-2 mt-1 mx-3">fakljfaklsfakls</p>
                                </div>
                            </div>



                        </form>
                    </div>

                    <div class="modal-user" style="margin-top: 20px">
                        <form action="" method="POST">
                            <!-- School Details Section -->
                            <div class="col-md-4">
                                <h5 class="mt-1"><b>Room Information</b></h5>
                            </div>
                            <!-- Đường phân cách -->
                            <hr style="border: none; border-top: 1px solid #282827; margin: 1px 0;">
                            <div class="col-md-4 mt-2">
                                <label for="schoolID"> <b>Account Code</b></label>
                                <p class="mb-2 mt-1 mx-3">Hf1333234</p>
                            </div>

                            <div class="row row-add">
                                <div class="col-md-6">
                                    <label for="gender"><b>Room</b></label>
                                    <p class="mb-2 mt-1 mx-3">BB04115</p>
                                </div>
                                <div class="col-md-6">
                                    <label for="contact"><b>Price</b></label>
                                    <p class="mb-2 mt-1 mx-3">390.000</p>
                                </div>
                            </div>

                            <!-- Đường phân cách -->
                            <hr style="border: none; border-top: 1px solid #282827; margin: 1px 0;">

                            <!-- Submit Button -->
                            <div class="row-add d-flex justify-content-center align-items-center mt-3">
                                <div class="mx-2">
                                    <a href="manage_account.php" class="btn" style="background-color: #db3077;">
                                        <p style="color: white" class="mb-0">Edit</p>
                                    </a>
                                </div>
                                <div class="mx-2">
                                    <a href="#" class="btn btn-danger">Delete</a>
                                </div>
                                <div class="mx-2">
                                    <a href="account_list.php" class="btn btn-secondary">Back</a>
                                </div>
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
    window.onclick = function (event) {
        var dropdownMenu = document.getElementById("dropdownMenu");

        // Đóng dropdown của tên admin nếu click bên ngoài
        if (!event.target.matches('#userDropdown') && !event.target.matches('.ms-1') && !dropdownMenu.contains(event.target)) {
            dropdownMenu.style.display = "none"; // Đảm bảo đóng dropdown
        }
    }
</script>

</html>