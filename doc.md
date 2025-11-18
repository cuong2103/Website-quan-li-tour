### Cấu trúc thư mục

1. commons // File dùng chung cả dự án
2. uploads // Folder lưu trữ file upload

3. controllers // Xử lý logic
4. models // Thao tác cơ sở dữ liệu
5. views // Hiển thị
6. index.php // Điều hướng

// Không sử dụng đường dẫn tuyệt đối(file.php)
localhost/mvc-basic/trang-chu.php - Không dùng cách này nữa
-> Dùng route: Gọi đến hàm trong controller để thực thi(hàm không trả về)

- Hàm không trả về: không có return - Chỉ dùng để thực thi đoạn mã, và hàm này không trả về giá trị
- Hàm có trả về: Có return - Thực thi đoạn mã và trả về giá trị

Class - object - Hàm - Mảng

Mô hình MVC

- Model: Dùng để tương tác với cơ sở dữ liệu
- View: Hiển trị, nhận yêu cầu người dùng
- Controller: Dùng để thực thi các đoạn mã xử lý logic

- Cấu hình DB trong file commons/env.php
  tour-management/
  │
  ├── app/
  │ ├── controllers/
  │ │ ├── BaseController.php
  │ │ ├── AuthController.php
  │ │ │
  │ │ ├── admin/
  │ │ │ ├── DashboardController.php
  │ │ │ ├── TourController.php
  │ │ │ ├── BookingController.php
  │ │ │ ├── CustomerController.php
  │ │ │ ├── ServiceController.php
  │ │ │ ├── ServiceTypeController.php
  │ │ │ ├── SupplierController.php
  │ │ │ ├── DestinationController.php
  │ │ │ ├── CountryController.php
  │ │ │ ├── CategoryController.php
  │ │ │ ├── UserController.php
  │ │ │ ├── RoleController.php
  │ │ │ ├── PaymentController.php
  │ │ │ ├── PolicyController.php
  │ │ │ ├── TourAssignmentController.php
  │ │ │ ├── ContractController.php
  │ │ │ ├── NotificationController.php
  │ │ │ └── ReportController.php
  │ │ │
  │ │ └── guide/
  │ │ ├── DashboardController.php
  │ │ ├── TourAssignmentController.php
  │ │ ├── JournalController.php
  │ │ ├── CheckInController.php
  │ │ ├── ItineraryController.php
  │ │ ├── CustomerController.php
  │ │ └── NotificationController.php
  │ │
  │ ├── models/
  │ │ ├── User.php
  │ │ ├── Role.php
  │ │ ├── Tour.php
  │ │ ├── Booking.php
  │ │ ├── Customer.php
  │ │ ├── Service.php
  │ │ ├── ServiceType.php
  │ │ ├── Supplier.php
  │ │ ├── Destination.php
  │ │ ├── Country.php
  │ │ ├── Category.php
  │ │ ├── Itinerary.php
  │ │ ├── Payment.php
  │ │ ├── Policy.php
  │ │ ├── TourAssignment.php
  │ │ ├── Journal.php
  │ │ ├── CustomerCheckIn.php
  │ │ ├── CustomerContract.php
  │ │ └── Notification.php
  │ │
  │ ├── views/
  │ │ ├── components/
  │ │ │ ├── header.php
  │ │ │ ├── sidebar.php
  │ │ │ ├── footer.php
  │ │ │ ├── alerts.php
  │ │ │ └── notification_dropdown.php
  │ │ │
  │ │ ├── admin/
  │ │ │ ├── dashboard.php
  │ │ │ │
  │ │ │ ├── tours/
  │ │ │ │ ├── index.php
  │ │ │ │ ├── create.php
  │ │ │ │ ├── edit.php
  │ │ │ │ └── detail.php
  │ │ │ │
  │ │ │ ├── bookings/
  │ │ │ │ ├── index.php
  │ │ │ │ ├── create.php
  │ │ │ │ ├── edit.php
  │ │ │ │ └── detail.php
  │ │ │ │
  │ │ │ ├── customers/
  │ │ │ │ ├── index.php
  │ │ │ │ ├── create.php
  │ │ │ │ ├── edit.php
  │ │ │ │ └── detail.php
  │ │ │ │
  │ │ │ ├── services/
  │ │ │ │ ├── index.php
  │ │ │ │ ├── create.php
  │ │ │ │ └── edit.php
  │ │ │ │
  │ │ │ ├── service-types/
  │ │ │ │ ├── index.php
  │ │ │ │ ├── create.php
  │ │ │ │ └── edit.php
  │ │ │ │
  │ │ │ ├── suppliers/
  │ │ │ │ ├── index.php
  │ │ │ │ ├── create.php
  │ │ │ │ └── edit.php
  │ │ │ │
  │ │ │ ├── destinations/
  │ │ │ │ ├── index.php
  │ │ │ │ ├── create.php
  │ │ │ │ └── edit.php
  │ │ │ │
  │ │ │ ├── countries/
  │ │ │ │ ├── index.php
  │ │ │ │ ├── create.php
  │ │ │ │ └── edit.php
  │ │ │ │
  │ │ │ ├── categories/
  │ │ │ │ ├── index.php
  │ │ │ │ ├── create.php
  │ │ │ │ └── edit.php
  │ │ │ │
  │ │ │ ├── users/
  │ │ │ │ ├── index.php
  │ │ │ │ ├── create.php
  │ │ │ │ └── edit.php
  │ │ │ │
  │ │ │ ├── roles/
  │ │ │ │ ├── index.php
  │ │ │ │ ├── create.php
  │ │ │ │ └── edit.php
  │ │ │ │
  │ │ │ ├── payments/
  │ │ │ │ ├── index.php
  │ │ │ │ ├── create.php
  │ │ │ │ └── detail.php
  │ │ │ │
  │ │ │ ├── policies/
  │ │ │ │ ├── index.php
  │ │ │ │ ├── create.php
  │ │ │ │ └── edit.php
  │ │ │ │
  │ │ │ ├── tour-assignments/
  │ │ │ │ ├── index.php
  │ │ │ │ ├── create.php
  │ │ │ │ └── edit.php
  │ │ │ │
  │ │ │ ├── contracts/
  │ │ │ │ ├── index.php
  │ │ │ │ ├── create.php
  │ │ │ │ └── detail.php
  │ │ │ │
  │ │ │ ├── notifications/
  │ │ │ │ └── index.php
  │ │ │ │
  │ │ │ └── reports/
  │ │ │ ├── revenue.php
  │ │ │ ├── tours.php
  │ │ │ ├── bookings.php
  │ │ │ └── customers.php
  │ │ │
  │ │ └── guide/
  │ │ ├── dashboard.php
  │ │ │
  │ │ ├── tour-assignments/
  │ │ │ ├── index.php
  │ │ │ └── detail.php
  │ │ │
  │ │ ├── journals/
  │ │ │ ├── index.php
  │ │ │ ├── create.php
  │ │ │ └── edit.php
  │ │ │
  │ │ ├── checkins/
  │ │ │ ├── index.php
  │ │ │ └── create.php
  │ │ │
  │ │ ├── itineraries/
  │ │ │ └── index.php
  │ │ │
  │ │ ├── customers/
  │ │ │ └── index.php
  │ │ │
  │ │ └── notifications/
  │ │ └── index.php
  │ │
  │ ├── middleware/
  │ │ ├── AuthMiddleware.php
  │ │ ├── AdminMiddleware.php
  │ │ └── GuideMiddleware.php
  │ │
  │ └── helpers/
  │ ├── Session.php
  │ ├── Validator.php
  │ ├── Upload.php
  │ ├── Format.php
  │ ├── Pagination.php
  │ └── Notification.php
  │
  ├── config/
  │ ├── config.php
  │ ├── database.php
  │ └── routes.php
  │
  ├── public/
  │ ├── index.php
  │ ├── .htaccess
  │ │
  │ ├── assets/
  │ │ ├── css/
  │ │ │ ├── admin.css
  │ │ │ ├── guide.css
  │ │ │ ├── auth.css
  │ │ │ └── common.css
  │ │ │
  │ │ ├── js/
  │ │ │ ├── admin.js
  │ │ │ ├── guide.js
  │ │ │ ├── common.js
  │ │ │ └── notifications.js
  │ │ │
  │ │ ├── images/
  │ │ │ ├── logo.png
  │ │ │ └── default-avatar.png
  │ │ │
  │ │ └── vendor/
  │ │ ├── bootstrap/
  │ │ ├── jquery/
  │ │ └── fontawesome/
  │ │
  │ └── uploads/
  │ ├── tours/
  │ ├── destinations/
  │ ├── journals/
  │ ├── checkins/
  │ ├── contracts/
  │ └── avatars/
  │
  ├── core/
  │ ├── Database.php
  │ ├── Router.php
  │ ├── Controller.php
  │ ├── Model.php
  │ └── View.php
  │
  ├── vendor/
  │ └── (Composer dependencies)
  │
  ├── .env
  ├── .env.example
  ├── .gitignore
  ├── composer.json
  └── README.md
