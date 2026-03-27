# 🧳 Dự án Web Quản Lý Tour Du Lịch ✈️

Chào mừng bạn đến với **Dự án Website Quản Lý Tour Du Lịch**. Đây là một hệ thống web được xây dựng theo mô hình MVC (Model-View-Controller) bằng ngôn ngữ PHP, cung cấp cái nhìn tổng quan và các công cụ mạnh mẽ để quản lý các hoạt động điều hành tour du lịch.

## 🌟 Chức Năng Chính

Hệ thống bao gồm các phân hệ chính dành cho Quản trị viên (Admin), Nhân sự, và Hướng dẫn viên, bao gồm:

- **Quản lý Tour & Lịch Trình (Tours & Destinations)**: Quản lý danh sách các tour du lịch, điểm đến, lộ trình chi tiết.
- **Quản lý Đặt Chỗ (Bookings)**: Theo dõi và xử lý các yêu cầu đặt tour từ khách hàng.
- **Quản lý Khách Hàng (Customers)**: Lưu trữ thông tin khách hàng, lịch sử đặt tour.
- **Quản lý Nhân Sự & Phân Công (Users & Tour Assignments)**: Quản lý tài khoản nhân viên, hướng dẫn viên và tiến hành phân công hướng dẫn tour (Tour Assignment, Check-in).
- **Quản lý Dịch Vụ & Nhà Cung Cấp (Services & Suppliers)**: Quản lý các nhà cung cấp dịch vụ (khách sạn, xe cộ, vé tham quan) và các danh mục dịch vụ.
- **Quản lý Tài Chính (Payments & Contracts)**: Quản lý các giao dịch thanh toán, hợp đồng với các đối tác và các chính sách giá.
- **Giao tiếp & Thông báo (Notifications)**: Hệ thống thông báo nhắc việc nội bộ cho nhân viên và hướng dẫn viên.
- **Báo Cáo & Thống Kê (Dashboard)**: Cung cấp biểu đồ và số liệu thống kê tổng quan về tình hình kinh doanh.

## 🛠️ Công Nghệ Sử Dụng

- **Backend**: PHP thuần với mô hình cấu trúc MVC.
- **Frontend**: HTML, CSS, JavaScript.
- **Cơ sở dữ liệu**: MySQL.
- **Kiến trúc**: Sử dụng `config/autoload.php` để tải các class tự động và `routers/web.php` để định tuyến người dùng.

## 📂 Cấu Trúc Thư Mục

```text
📦Website-quan-li-tour-main
 ┣ 📂assets/         # Chứa tài nguyên tĩnh tĩnh: CSS, JS, Hình ảnh thiết kế
 ┣ 📂commons/        # Các hàm hỗ trợ dùng chung (Helper functions)
 ┣ 📂config/         # Cấu hình môi trường (Kết nối database, autoload)
 ┣ 📂controllers/    # Các lớp điều khiển xử lý logic
 ┣ 📂lib/            # Các thư viện bên thứ bên thứ ba (nếu có)
 ┣ 📂models/         # Các lớp thao tác trực tiếp với cơ sở dữ liệu
 ┣ 📂routers/        # Chứa file định tuyến web.php
 ┣ 📂uploads/        # Thư mục lưu trữ file ảnh, tệp tài liệu upload
 ┣ 📂views/          # Chứa giao diện đồ hoạ cho người dùng
 ┣ 📜README.md       # Tài liệu dự án (File bạn đang đọc)
 ┣ 📜db.sql          # File backup chuẩn thông tin CSDL
 ┗ 📜index.php       # Điểm vào chính của ứng dụng
```

## 🚀 Hướng Dẫn Cài Đặt

### 1️⃣ Khởi tạo Cơ sở dữ liệu

- Mở công cụ quản lý MySQL của bạn (như phpMyAdmin, Laragon Database, MySQL Workbench, DBeaver).
- Tạo một database mới (khuyên dùng charset `utf8mb4_unicode_ci`).
- Import file CSDL `db.sql` vào database vừa khởi tạo.

### 2️⃣ Cấu hình môi trường

Mở thư mục `config/`, tại đây bạn sẽ thấy một mẫu file hệ thống:

```bash
cp config/env.example.php config/env.php
```

Mở file `config/env.php` và thay đổi các hằng số thông tin cấu hình kết nối database (như DB_NAME, DB_USER, DB_PASS) cho khớp với thông số local của bạn.

### 3️⃣ Khởi chạy ứng dụng

- Bạn có thể đặt toàn bộ thư mục gốc vào `htdocs/` của XAMPP, `www/` của Laragon, hoặc thư mục web server nào bạn đang dùng.
- Mở Server chạy Apache/Nginx dịch vụ MySQL.
- Khởi chạy trình duyệt và truy cập vào dự án theo địa chỉ tương ứng. Ví dụ:
Mở trình duyệt: `http://localhost/Website-quan-li-tour-main/`

---

_Dự án Web Quản Lý Tour Du Lịch - Giúp bạn tối ưu hóa vận hành mô hình dịch vụ du lịch!_
