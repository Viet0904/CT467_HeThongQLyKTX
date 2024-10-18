<?php
include_once __DIR__ . '/../partials/header.php';
include_once __DIR__ . '/../partials/heading.php';
?>

<body>
    <div class="image_main">
        <div class="container">
            <main>
                <div class="row py-5">
                    <div class="col pt-5">
                        <h5 class="text-white">CHÀO MỪNG BẠN ĐẾN VỚI !!</h5>
                        <h1 class="text-white pt-2"><b>Hệ thống quản lý khách hàng</b></h1>
                        <p class="text-white py-3">MyEitik là giải pháp hỗ trợ doanh nghiệp Việt Nam áp dụng
                            công nghệ số hóa quy trình quản lý nguồn khách hàng, đồng thời nâng cao tiềm năng của doanh
                            nghiệp
                        </p>
                        <a href="./about.php"
                            class="text-decoration-none bg-white mt-3 mb-4 w-25 p-3 border rounded-2 bg-blue-hover">
                            <span class="my-2 bg-blue-hover">Về chúng tôi</span>
                        </a>
                    </div>
                    <div class="col pt-2 pb-5">
                        <img src="./assets/images/ql.jpeg" alt="" class="width-ql width-ql-tb rounded-1">
                    </div>
                </div>
            </main>
        </div>
    </div>

    <div class="container text-center py-5">
        <div class="container w-50 text-center">
            <h2 class="text-primary"><b>Mục tiêu</b></h2>
            <p class="my-3">MyEitik là giải pháp hỗ trợ doanh nghiệp Việt Nam áp dụng công nghệ số hóa quy trình quản lý nguồn khách hàng, 
                đồng thời nâng cao tiềm năng của doanh nghiệp</p>
        </div>
        <div class="row my-5">
            <div class="col-6">
                <div class="container border border-4 border-info w-75 text-center rounded-4 height-frame">
                    <img src="./assets/images/eye.jpg" alt="">
                    <h3 class="p-4 pb-0">Tầm nhìn của chúng tôi</h3>
                    <p class="p-4">Trở thành nền tảng quản lý và gia tăng trải nghiệm khách hàng hàng đầu Việt Nam.</p>
                </div>
            </div>
            <div class="col-6">
                <div class="container border border-4 border-info w-75 text-center rounded-4 height-frame">
                    <img src="./assets/images/group.jpg" alt="">
                    <h3 class="p-4 pb-0">Sứ mệnh của chúng tôi</h3>
                    <p class="p-4">Giúp doanh nghiệp giảm chi phí quản lý khách hàng và mang lại trải nghiệm hài lòng
                        cho khách hàng, từ đó tối đa hoá hiệu suất, gia tăng sự gắn kết.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-secondary-subtle">
    <div class="container bg-secondary-subtle">
        <div class="pt-5">
            <h2 class="text-primary text-center">Đánh giá</h2>
        </div>
        <div id="carouselExampleSlidesOnly" class="carousel slide w-75 container" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active mb-5 mt-4">
                    <div class="row">
                        <div class="col-6">
                            <img src="./assets/images/qlkh1.jpg" class="d-block" alt="...">
                        </div>
                        <div class="col-6 bg-white">
                            <div class="p-5">
                                <p class="text-left pt-5"><em>Cảm ơn MyEitik đã giúp chúng tôi quản lý khách hàng tốt hơn, và giúp chúng tôi tiết kiệm được nhiều thời gian hơn</em></p>
                                <p class="pt-4 mb-0"><b>John Campbell</b></p>
                                <p class="text-muted">CEO & Co-founder</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="carousel-item mb-5 mt-4">
                    <div class="row">
                        <div class="col-6">
                            <img src="./assets/images/qlkh2.jpg" class="d-block" alt="...">
                        </div>
                        <div class="col-6 bg-white">
                            <div class="p-5">
                                <p class="text-left pt-5"><em>Trang web rất sáng tạo, nhờ có trang web này mà tôi có thể quản lý khách hàng của mình một cách dễ dàng hơn</em></p>
                                <p class="pt-4 mb-0"><b>Jostdam</b></p>
                                <p class="text-muted">CEO & Ann</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="carousel-item mb-5 mt-4">
                    <div class="row">
                        <div class="col-6">
                            <img src="./assets/images/qlkh3.jpg" class="d-block" alt="...">
                        </div>
                        <div class="col-6 bg-white">
                            <div class="p-5">
                                <p class="text-left pt-5"><em>Tôi rất hài lòng với trang web này, một trang web quản lý khách hàng tuyệt vời, tôi sẽ giới thiệu cho bạn bè của tôi</em></p>
                                <p class="pt-4 mb-0"><b>Yang Yang</b></p>
                                <p class="text-muted">CEO & Co-founder</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    

    <?php
    include_once __DIR__ . '/../partials/footer.php';
    ?>
</body>

</html>