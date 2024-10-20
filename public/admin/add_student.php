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
                    <div class="modal-header">
                        <h5 class="modal-title">Register New Student</h5>
                    </div>

                    <div class="modal-add-user">
                        <form>
                            <!-- School Details Section -->
                            <h6 class="mt-2"><b>School Details</b></h6>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="schoolID" class="form-label">School ID/Code</label>
                                    <input type="text" class="form-control" id="schoolID">
                                </div>
                                <div class="col-md-4">
                                    <label for="department" class="form-label">Department</label>
                                    <input type="text" class="form-control" id="department">
                                </div>
                                <div class="col-md-4">
                                    <label for="course" class="form-label">Course</label>
                                    <input type="text" class="form-control" id="course">
                                </div>
                            </div>
    
                            <!-- Personal Information Section -->
                            <h6><b>Personal Information</b></h6>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="firstName" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="firstName">
                                </div>
                                <div class="col-md-4">
                                    <label for="middleName" class="form-label">Middle Name</label>
                                    <input type="text" class="form-control" id="middleName" placeholder="optional">
                                </div>
                                <div class="col-md-4">
                                    <label for="lastName" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="lastName">
                                </div>
                            </div>
    
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="gender" class="form-label">Gender</label>
                                    <select class="form-select" id="gender">
                                        <option selected>Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="contact" class="form-label">Contact #</label>
                                    <input type="text" class="form-control" id="contact">
                                </div>
                                <div class="col-md-4">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email">
                                </div>
                            </div>
    
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="address" class="form-label">Address</label>
                                    <input type="text" class="form-control" id="address">
                                </div>
                            </div>
    
                            <!-- Emergency Details Section -->
                            <h6><b>Emergency Details</b></h6>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="emergencyName" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="emergencyName">
                                </div>
                                <div class="col-md-4">
                                    <label for="emergencyContact" class="form-label">Contact #</label>
                                    <input type="text" class="form-control" id="emergencyContact">
                                </div>
                                <div class="col-md-4">
                                    <label for="emergencyRelation" class="form-label">Relation</label>
                                    <input type="text" class="form-control" id="emergencyRelation">
                                </div>
                            </div>
    
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="emergencyAddress" class="form-label">Address</label>
                                    <input type="text" class="form-control" id="emergencyAddress">
                                </div>
                            </div>
    
                            <!-- Submit Button -->
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary"
                                    style="background-color: #db3077;">Register</button>
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
    window.onclick = function (event) {
        var dropdownMenu = document.getElementById("dropdownMenu");

        // Đóng dropdown của tên admin nếu click bên ngoài
        if (!event.target.matches('#userDropdown') && !event.target.matches('.ms-1') && !dropdownMenu.contains(event.target)) {
            dropdownMenu.style.display = "none"; // Đảm bảo đóng dropdown
        }
    }
</script>

</html>