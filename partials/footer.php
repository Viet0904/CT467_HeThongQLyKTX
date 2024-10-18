<div class="image_main pt-5">
    <div class="container">
        <div class="row">
            <div class="col-4">
                <h6 class="text-white pb-1"><b>Về chúng tôi</b></h6>
                <p class="text-white">Nền tảng quản lý và trải nghiệm khách hàng.</p>
                <p class="text-white">Địa chỉ: Khoa Phát triển Nông thôn, Trường Đại học Cần Thơ</p>
                <p><a href="" class="text-decoration-none color-text-gray">+1(123)-779-9380 </a></p>
                <p><a href="" class="text-decoration-none color-text-gray">info@myeitik.com</a></p>
            </div>
            <div class="col-4 px-5">
                <div class="row">
                    <h6 class="text-white pb-1"><b>Công ty</b></h6>
                    <div class="col">
                        <p><a href="/../about.php" class="text-decoration-none color-text-gray">Về chúng tôi</a></p>
                        <p><a href="" class="text-decoration-none color-text-gray">Các dịch vụ</p>
                        <p><a href="" class="text-decoration-none color-text-gray">Nhóm</a></p>
                        <p><a href="" class="text-decoration-none color-text-gray">Trang cá nhân</a></p>
                    </div>
                    <div class="col">
                        <p><a href="" class="text-decoration-none color-text-gray">Hợp tác</a></p>
                        <p><a href="" class="text-decoration-none color-text-gray">Doanh nghiệp</a></p>
                        <p><a href="" class="text-decoration-none color-text-gray">Nghề nghiệp</a></p>
                        <p><a href="" class="text-decoration-none color-text-gray">Câu hỏi thường gặp</a></p>
                    </div>
                </div>

            </div>
            <div class="col-4 px-5">
                <h6 class="text-white pb-1"><b>Định hướng</b></h6>
                <p><a href="" class="text-decoration-none color-text-gray">Trang chủ</a></p>
                <p><a href="/admin/index.php" class="text-decoration-none color-text-gray">Người quản trị</a></p>
                <p><a href="/user/index.php" class="text-decoration-none color-text-gray">Người dùng</a></p>
                <h6 class="text-white"><b>Social</b></h6>
                <div class="mt-3">
                    <a href="#" class="ml-2 text-decoration-none ">
                        <i class="fa-brands fa-facebook p-2 fa-lg " style="color: #c8c8c8;"></i>
                    </a>
                    <a href="#" class="ml-2 text-decoration-none">
                        <i class="fa-brands fa-twitter p-2 fa-lg" style="color: #c8c8c8;"></i>
                    </a>
                    <a href="#" class="ml-2 text-decoration-none">
                        <i class="fa-brands fa-instagram p-2 fa-lg" style="color: #c8c8c8;"></i>
                    </a>
                    <a href="#" class="ml-2 text-decoration-none">
                        <i class="fa-brands fa-linkedin p-2 fa-lg" style="color: #c8c8c8;"></i>
                    </a>
                    <a href="#" class="ml-2 text-decoration-none">
                        <i class="fa-brands fa-google-plus-g fa-lg p-2" style="color: #c8c8be;"></i>
                    </a>
                </div>
            </div>
        </div>
        <p class="text-white text-center pt-5 pb-4 mb-0">Hệ thống quản lý khách hàng @ 2023 — Được thiết kế bởi Như và Việt</p>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0-alpha1/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        // Hiển thị overlay
        $('#overlay').fadeIn();

        // Đợi 0.1 giây và sau đó làm mờ phần dưới trang và ẩn overlay
        setTimeout(function() {
            $('body').css('filter', 'blur(5px)');
            $('#overlay').fadeOut();
            // Quay lại bình thường sau 0.1 giây và xóa hiệu ứng làm mờ
            setTimeout(function() {
                $('body').css('filter', 'none');
            }, 100);
        }, 30); // 0.03s
    });
</script>