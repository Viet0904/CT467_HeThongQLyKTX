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
## Bước 5: Mở Apache của XAMP

![image](https://github.com/user-attachments/assets/61a7ba1d-1b1c-4f89-9367-5fe13f9e4b5f)

### Lưu ý: Chỉ cần Mở Apache không cần mở MySQL
## Bước 6: Truy cập web
Sau đó truy cập Link [http://htqlktx.localhost](http://htqlktx.localhost/)


# Hướng dẫn chuyển nhánh 

## Chuyển Branch
```
git checkout Tên_Nhánh
```

## Commit và Push Lên Remote
```
git add .
git commit -m "Thêm tính năng X"
```
## Đẩy branch của bạn lên GitHub:
```
git push origin Tên_Nhánh
```

# Kéo các thay đổi mới từ main về nhánh của bạn

## Chuyển sang nhánh của bạn
```
git checkout Ten_Nhánh
```

## Kéo các thay đổi mới từ main
```
git pull origin main
```
