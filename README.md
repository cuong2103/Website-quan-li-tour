# 🧳 Tour Management System — Hệ thống Quản lý Tour Du lịch

> Hệ thống quản lý toàn diện cho doanh nghiệp lữ hành: quản lý tour, booking, khách hàng, hướng dẫn viên và tài chính — xây dựng bằng PHP MVC thuần.

---

## 📌 Tổng quan dự án

**Tour Management System** là một ứng dụng web back-office được xây dựng theo kiến trúc **MVC (Model–View–Controller)** bằng PHP thuần, phục vụ nhu cầu vận hành thực tế của một công ty lữ hành. Hệ thống hỗ trợ **2 role**: Quản trị viên (Admin) và Hướng dẫn viên (Guide), với luồng nghiệp vụ khép kín từ tạo tour → booking → phân công → check-in → nhật ký → hoàn thành.

---

## ✨ Tính năng nổi bật

### 👨‍💼 Dành cho Admin

| Module | Chức năng |
|--------|-----------|
| **Dashboard** | Thống kê doanh thu, số booking, biểu đồ 6 tháng gần nhất |
| **Quản lý Tour** | CRUD tour, lịch trình theo ngày (itinerary), chính sách tour |
| **Quản lý Booking** | Tạo/sửa booking, quản lý khách hàng, dịch vụ, thanh toán, hợp đồng, xếp phòng, check-in |
| **Quản lý Khách hàng** | CRUD khách hàng, import Excel |
| **Quản lý Nhân sự** | Quản lý tài khoản nhân viên, phê duyệt đơn xin nghỉ, phân công tour |
| **Dịch vụ & Nhà cung cấp** | Quản lý dịch vụ (khách sạn, xe, ăn uống…), nhà cung cấp |
| **Điểm đến** | CRUD điểm đến, hình ảnh điểm đến |
| **Tài chính** | Ghi nhận thanh toán (cọc, toàn phần, hoàn tiền), tự động cập nhật trạng thái booking |
| **Thông báo** | Gửi thông báo nội bộ theo nhóm người nhận |

### 🧭 Dành cho Hướng dẫn viên (Guide)

| Module | Chức năng |
|--------|-----------|
| **Lịch tour** | Xem lịch được phân công, tiến độ tour hiện tại |
| **Chi tiết tour** | Xem khách hàng, phòng, dịch vụ, lộ trình (itinerary) |
| **Check-in** | Tạo đợt check-in, ghi nhận khách đã check-in, export Excel |
| **Nhật ký tour** | Viết/sửa/xóa nhật ký hàng ngày (daily, incident, other), upload ảnh |
| **Đơn xin nghỉ** | Nộp đơn xin nghỉ, kiểm tra xung đột lịch tour tự động |

---

## 🏗️ Kiến trúc & Công nghệ

```
Backend  : PHP 8.x thuần — MVC tự xây, không dùng framework
Database : MySQL 8.x
Frontend : Tailwind CSS + Lucide Icons + Vanilla JavaScript
Routing  : Custom router (routers/web.php) — pattern ?act=action
Auth     : Session-based authentication, Role-based access control
Upload   : Native PHP file upload (journal images, Excel import)
Excel    : SimpleXLSX / SimpleXLSXGen (thư viện PHP)
```

### Sơ đồ luồng nghiệp vụ

```
Tạo Tour → Booking → Thanh toán → Phân công HDV → Check-in → Nhật ký → Hoàn thành
   ↓            ↓          ↓              ↓
Lịch trình  Khách hàng  Hợp đồng    Xin nghỉ / Báo cáo
```

---

## 📂 Cấu trúc thư mục

```
Website-quan-li-tour/
├── assets/            # CSS, JS, ảnh tĩnh
├── commons/           # Helper functions (validate, message, session...)
├── config/
│   ├── autoload.php   # Tự động load model và controller
│   ├── env.php        # Cấu hình môi trường (DB, URL...)
│   └── env.example.php
├── controllers/
│   ├── admin/         # Controllers cho admin (Booking, Tour, User...)
│   └── guide/         # Controllers cho guide (Checkin, Journal, Leave...)
├── models/            # Data layer — PDO queries
├── routers/
│   └── web.php        # Định tuyến URL → Controller → Action
├── views/
│   ├── admin/         # Views admin (bookings, tours, customers...)
│   ├── guide/         # Views guide (schedule, tour-assignments...)
│   ├── auth/          # Trang đăng nhập
│   └── components/    # Header, sidebar dùng chung
├── uploads/           # File upload (ảnh nhật ký, chứng từ thanh toán)
├── lib/               # Thư viện bên thứ ba (SimpleXLSX)
├── db.sql             # Schema database
├── data.sql           # Dữ liệu mẫu (seed)
└── setup.php          # Script khởi tạo tài khoản admin (xóa sau khi dùng)
```

---

## 🚀 Hướng dẫn cài đặt

### Yêu cầu
- PHP >= 8.0
- MySQL >= 8.0
- Web server: Apache / Nginx / Laragon / XAMPP

### Bước 1 — Clone dự án
```bash
git clone https://github.com/<your-username>/Website-quan-li-tour.git
cd Website-quan-li-tour
```

### Bước 2 — Cấu hình môi trường
```bash
cp config/env.example.php config/env.php
```
Mở `config/env.php` và chỉnh sửa:
```php
define('BASE_URL',     'http://localhost/Website-quan-li-tour/');
define('DB_HOST',      'localhost');
define('DB_USERNAME',  'root');
define('DB_PASSWORD',  '');
define('DB_NAME',      'tours_management');
```

### Bước 3 — Tạo database
Trong **phpMyAdmin** (hoặc MySQL Workbench):
```sql
CREATE DATABASE tours_management CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```
Sau đó import theo thứ tự:
1. `db.sql` → tạo cấu trúc bảng
2. `data.sql` → import dữ liệu mẫu *(tuỳ chọn)*

### Bước 4 — Khởi tạo tài khoản Admin
Truy cập URL sau **1 lần duy nhất**:
```
http://localhost/Website-quan-li-tour/setup.php
```
> ⚠️ **Xóa `setup.php`** ngay sau khi tạo tài khoản thành công!

### Bước 5 — Đăng nhập
```
URL      : http://localhost/Website-quan-li-tour/
Email    : admin@gmail.com
Password : 12345678
```

---

## 🔐 Phân quyền

| Role | Quyền hạn |
|------|-----------|
| `admin` | Toàn quyền hệ thống |
| `guide` | Chỉ xem tour được phân công, check-in, viết nhật ký, xin nghỉ |

---

## 💡 Điểm kỹ thuật đáng chú ý

- **Custom MVC Router**: Không dùng framework, tự xây router qua `?act=` pattern
- **Server-side Validation**: Validate đầu vào tại Controller trước khi lưu DB
- **Role-based Authorization**: Kiểm tra quyền truy cập theo session ở mỗi Controller
- **Business Logic Protection**: Chặn sửa/xóa booking đang diễn ra hoặc đã hoàn thành
- **Auto Status Update**: Tự động cập nhật status booking (paid → in_progress → completed) theo ngày
- **Excel Import/Export**: Upload danh sách khách hàng, xếp phòng từ file Excel
- **Ownership Check**: Guide chỉ được xem/sửa/xóa dữ liệu của chính mình
- **Bcrypt Password**: Mật khẩu được hash bằng `password_hash()` + `password_verify()`

*Dự án được xây dựng nhằm mục đích học tập và thực hành xây dựng hệ thống thực tế với PHP MVC.*
