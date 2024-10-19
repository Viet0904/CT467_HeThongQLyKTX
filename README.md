## Hệ Thống Quản Lý KTX

## Bước 1: Tạo CSDL
Truy cập <a href="http://localhost/phpmyadmin/">MYySQL</a> tạo CSDL tên htqlktx

## Bước 2: Tạo các bảng
<p>Insert file KTX.sql thì folder database</p>

## Bước 3: Clone dự án về máy
`git clone https://github.com/Viet0904/CT467_HeThongQLyKTX.git`

## Bước 4: Chỉnh cấu hình vhosts nếu cần thiết
<p>Nếu bạn không muốn thêm dự án vào htdocs của XAMP thì cấu hình lại vhosts như sau</p>
<p>Vào C:\xampp\apache\conf\extra\httpd-vhosts.conf</p>
```plaintext
<VirtualHost *:80>
    DocumentRoot "D:/Workspace/CTU/QuanTriDuLieu/HeThongQlyKTX/public"
    ServerName htqlktx.localhost
    # Set access permission
    <Directory "D:/Workspace/CTU/QuanTriDuLieu/HeThongQlyKTX">
        Options Indexes FollowSymLinks Includes ExecCGI
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```
<p><b>Lưu ý: Chỉnh sửa lại đường dẫn theo bạn</b></p>

## Bước 5: Truy cập web
<p> Mở Apache và MySQL</p>
<p> Sau đó truy cập Link http://htqlktx.localhost/ <a href="http://htqlktx.localhost/">Click vào đây</a> </p>
