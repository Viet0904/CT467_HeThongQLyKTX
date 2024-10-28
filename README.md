## Hệ Thống Quản Lý KTX

## 

## Bước 1: Truy Cập MySQL WorkBench đăng nhập với root
Mở Query Tab mới Hoặc Ctrl + T để mở.
### Lưu ý: Bước này Set Password tài khoản Root thành rỗng
```
ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY ''; 
FLUSH PRIVILEGES;
```

## Bước 2: Tạo các Database và các bảng
Mở File Database_Workbench.sql thực chạy.

## Bước 3: Clone dự án về máy
`git clone https://github.com/Viet0904/CT467_HeThongQLyKTX.git`

## Bước 4: Chỉnh cấu hình vhosts nếu cần thiết
Nếu bạn không muốn thêm dự án vào htdocs của XAMP thì cấu hình lại vhosts như sau:
Vào `C:\xampp\apache\conf\extra\httpd-vhosts.conf`
```
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

### Lưu ý: Chỉnh sửa lại đường dẫn theo bạn 
## Bước 5: Truy cập web
Mở Apache và MySQL

Sau đó truy cập Link [http://htqlktx.localhost](http://htqlktx.localhost/)

