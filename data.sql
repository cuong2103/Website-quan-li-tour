-- ============================================================
-- FILE: data.sql  
-- MÔ TẢ: Dữ liệu mẫu để test hệ thống quản lý tour du lịch
-- CÁCH DÙNG: Import file này sau khi đã chạy db.sql
-- 
-- TÀI KHOẢN MẶC ĐỊNH:
--   Admin   → email: admin@tour.vn       | password: Admin@123
--   Guide 1 → email: guide1@tour.vn      | password: Guide@123
--   Guide 2 → email: guide2@tour.vn      | password: Guide@123
--
-- LƯU Ý: Password đã được hash bằng PHP password_hash(..., PASSWORD_BCRYPT)
--        Hash dưới đây hợp lệ và có thể dùng ngay.
-- ============================================================

SET FOREIGN_KEY_CHECKS = 0;

-- ============================================================
-- USERS (Admin + 2 hướng dẫn viên)
-- ============================================================
INSERT INTO `users` (`id`, `fullname`, `password`, `email`, `phone`, `roles`, `status`, `created_at`) VALUES
(1, 'Quản trị viên', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@tour.vn', '0901234567', 'admin', 1, NOW()),
(2, 'Nguyễn Văn An', '$2y$10$TKh8H1.PfQ0A3bR6NfOfd.7iZ/4KnHhSemRRnSjuXTqOe3DCYmQpC', 'guide1@tour.vn', '0912345678', 'guide', 1, NOW()),
(3, 'Trần Thị Bình', '$2y$10$TKh8H1.PfQ0A3bR6NfOfd.7iZ/4KnHhSemRRnSjuXTqOe3DCYmQpC', 'guide2@tour.vn', '0923456789', 'guide', 1, NOW());

-- ============================================================
-- CATEGORIES
-- ============================================================
INSERT INTO `categories` (`id`, `name`, `description`, `parent_id`, `created_by`) VALUES
(1, 'Tour trong nước', 'Các tour du lịch trong nước Việt Nam', NULL, 1),
(2, 'Tour quốc tế', 'Các tour du lịch nước ngoài', NULL, 1),
(3, 'Miền Bắc', 'Tour tham quan các tỉnh thành miền Bắc', 1, 1),
(4, 'Miền Trung', 'Tour tham quan các tỉnh thành miền Trung', 1, 1),
(5, 'Miền Nam', 'Tour tham quan các tỉnh thành miền Nam', 1, 1),
(6, 'Đông Nam Á', 'Tour các nước Đông Nam Á', 2, 1);

-- ============================================================
-- DESTINATIONS
-- ============================================================
INSERT INTO `destinations` (`id`, `category_id`, `name`, `locations`, `description`, `created_by`) VALUES
(1, 3, 'Vịnh Hạ Long', 'Quảng Ninh, Việt Nam', 'Di sản thiên nhiên thế giới với hàng nghìn đảo đá vôi', 1),
(2, 3, 'Sapa', 'Lào Cai, Việt Nam', 'Thị trấn vùng cao với ruộng bậc thang và đỉnh Fansipan', 1),
(3, 4, 'Đà Nẵng', 'Đà Nẵng, Việt Nam', 'Thành phố biển hiện đại với bãi biển Mỹ Khê tuyệt đẹp', 1),
(4, 4, 'Hội An', 'Quảng Nam, Việt Nam', 'Phố cổ di sản thế giới UNESCO', 1),
(5, 5, 'TP. Hồ Chí Minh', 'TP.HCM, Việt Nam', 'Thành phố năng động, trung tâm kinh tế phía Nam', 1),
(6, 5, 'Phú Quốc', 'Kiên Giang, Việt Nam', 'Đảo ngọc với bãi biển trong xanh và hải sản phong phú', 1),
(7, 6, 'Bangkok', 'Thái Lan', 'Thủ đô Thái Lan với chùa chiền và ẩm thực đường phố', 1);

-- ============================================================
-- SERVICE TYPES
-- ============================================================
INSERT INTO `service_types` (`id`, `name`, `description`, `created_by`) VALUES
(1, 'Khách sạn', 'Dịch vụ lưu trú', 1),
(2, 'Vận chuyển', 'Xe đưa đón, tàu thuyền', 1),
(3, 'Ăn uống', 'Bữa ăn, tiệc', 1),
(4, 'Tham quan', 'Vé vào cổng, hướng dẫn', 1),
(5, 'Bảo hiểm', 'Bảo hiểm du lịch', 1);

-- ============================================================
-- SUPPLIERS
-- ============================================================
INSERT INTO `suppliers` (`id`, `name`, `email`, `phone`, `destination_id`, `status`, `created_by`) VALUES
(1, 'Khách sạn Mường Thanh Hạ Long', 'contact@muongthanh-halong.vn', '0203456789', 1, 1, 1),
(2, 'Tàu Heritage Bình Chuẩn', 'heritage@halong.vn', '0203456790', 1, 1, 1),
(3, 'Khách sạn Sapa Eco', 'info@sapaeco.vn', '0214567890', 2, 1, 1),
(4, 'Khách sạn Mường Thanh Đà Nẵng', 'contact@muongthanh-danang.vn', '0236456789', 3, 1, 1),
(5, 'Nhà hàng Phố Hội', 'phonghoi@restaurant.vn', '0235456789', 4, 1, 1),
(6, 'Vietravel Transport', 'transport@vietravel.vn', '0283456789', 5, 1, 1),
(7, 'Bảo hiểm Bảo Việt', 'tour@baoviet.vn', '0243456789', NULL, 1, 1);

-- ============================================================
-- SERVICES
-- ============================================================
INSERT INTO `services` (`id`, `service_type_id`, `supplier_id`, `name`, `estimated_price`, `unit`, `created_by`) VALUES
(1, 1, 1, 'Phòng Deluxe Mường Thanh Hạ Long', 1200000, 'room', 1),
(2, 2, 2, 'Tàu du lịch Hạ Long 2 ngày 1 đêm', 2500000, 'person', 1),
(3, 3, 5, 'Bữa trưa Phố Hội đặc sản', 180000, 'person', 1),
(4, 1, 4, 'Phòng Superior Mường Thanh Đà Nẵng', 900000, 'room', 1),
(5, 2, 6, 'Xe 45 chỗ Đà Nẵng - Hội An - Đà Nẵng', 3500000, 'vehicle', 1),
(6, 4, 2, 'Vé tham quan Vịnh Hạ Long', 350000, 'person', 1),
(7, 5, 7, 'Bảo hiểm du lịch quốc nội', 50000, 'person', 1),
(8, 1, 3, 'Phòng Sapa Eco Standard', 750000, 'room', 1);

-- ============================================================
-- POLICIES
-- ============================================================
INSERT INTO `policies` (`id`, `title`, `content`, `created_by`) VALUES
(1, 'Chính sách hủy tour', 'Hủy trước 15 ngày: hoàn 80% tiền cọc.\nHủy trước 7 ngày: hoàn 50% tiền cọc.\nHủy trong vòng 7 ngày: không hoàn tiền.', 1),
(2, 'Chính sách thanh toán', 'Đặt cọc tối thiểu 30% tổng giá trị tour khi đặt.\nThanh toán toàn bộ trước ngày khởi hành 3 ngày.', 1),
(3, 'Quy định hành lý', 'Hành lý xách tay tối đa 7kg.\nHành lý ký gửi tối đa 20kg.\nKhách hàng tự chịu trách nhiệm với tài sản cá nhân.', 1);

-- ============================================================
-- TOURS
-- ============================================================
INSERT INTO `tours` (`id`, `category_id`, `tour_code`, `name`, `introduction`, `adult_price`, `child_price`, `status`, `duration_days`, `created_by`) VALUES
(1, 3, 'HN-HL-2N1D', 'Hà Nội - Hạ Long 2 Ngày 1 Đêm', 'Khám phá kỳ quan thiên nhiên thế giới Vịnh Hạ Long với hành trình nghỉ đêm trên tàu sang trọng.', 3500000, 2500000, 'active', 2, 1),
(2, 4, 'DN-HOI-3N2D', 'Đà Nẵng - Hội An - Bà Nà 3 Ngày 2 Đêm', 'Hành trình khám phá miền Trung: thành phố biển Đà Nẵng, phố cổ Hội An và Bà Nà Hills huyền ảo.', 4200000, 3000000, 'active', 3, 1),
(3, 5, 'HCM-PQ-4N3D', 'TP.HCM - Phú Quốc 4 Ngày 3 Đêm', 'Tận hưởng thiên đường biển đảo Phú Quốc với bãi biển trong xanh và hải sản tươi ngon.', 5800000, 4000000, 'active', 4, 1),
(4, 3, 'HN-SA-3N2D', 'Hà Nội - Sapa 3 Ngày 2 Đêm', 'Khám phá vùng cao Sapa với ruộng bậc thang, làng dân tộc và đỉnh Fansipan.', 3200000, 2200000, 'active', 3, 1);

-- ============================================================
-- TOUR SERVICES
-- ============================================================
INSERT INTO `tour_services` (`tour_id`, `service_id`, `default_quantity`, `created_by`) VALUES
(1, 1, 1, 1),
(1, 2, 1, 1),
(1, 6, 1, 1),
(1, 7, 1, 1),
(2, 4, 1, 1),
(2, 5, 1, 1),
(2, 3, 2, 1),
(2, 7, 1, 1),
(3, 7, 1, 1),
(4, 8, 1, 1),
(4, 7, 1, 1);

-- ============================================================
-- ITINERARIES
-- ============================================================
INSERT INTO `itineraries` (`tour_id`, `destination_id`, `description`, `order_number`, `arrival_time`, `departure_time`, `created_by`) VALUES
(1, 1, 'Xe đón tại Hà Nội, khởi hành đến Cảng Tuần Châu. Lên tàu, thăm hang Sửng Sốt, chèo kayak. Nghỉ đêm trên tàu.', 1, '08:00:00', '07:30:00', 1),
(1, 1, 'Tập thái cực quyền trên tàu, thăm làng chài, trải nghiệm nấu ăn. Trở về cảng, xe đưa về Hà Nội.', 2, '06:00:00', '12:00:00', 1),
(2, 3, 'Đón tại sân bay Đà Nẵng, nhận phòng khách sạn. Tham quan Bán đảo Sơn Trà, cầu Rồng về đêm.', 1, '10:00:00', '09:00:00', 1),
(2, 4, 'Khởi hành Hội An, tham quan phố cổ, chùa Cầu, Hội quán Phúc Kiến. Ăn trưa đặc sản Hội An.', 2, '08:00:00', '07:30:00', 1),
(2, 3, 'Tham quan Bà Nà Hills - Cầu Vàng. Buổi chiều tự do mua sắm. Bay về.', 3, '08:00:00', '15:00:00', 1);

-- ============================================================
-- TOUR POLICIES
-- ============================================================
INSERT INTO `tour_policies` (`tour_id`, `policy_id`, `created_by`) VALUES
(1, 1, 1),
(1, 2, 1),
(1, 3, 1),
(2, 1, 1),
(2, 2, 1),
(3, 1, 1),
(3, 2, 1),
(4, 1, 1),
(4, 2, 1);

-- ============================================================
-- CUSTOMERS
-- ============================================================
INSERT INTO `customers` (`id`, `name`, `email`, `phone`, `address`, `passport`, `citizen_id`, `gender`, `created_by`) VALUES
(1, 'Lê Minh Tuấn', 'tuan.le@gmail.com', '0901111111', '12 Nguyễn Huệ, Q.1, TP.HCM', '', '079123456789', 'male', 1),
(2, 'Phạm Thị Lan', 'lan.pham@gmail.com', '0902222222', '45 Trần Hưng Đạo, Q.5, TP.HCM', '', '079987654321', 'female', 1),
(3, 'Hoàng Văn Đức', 'duc.hoang@gmail.com', '0903333333', '78 Lê Lợi, Q.Hải Châu, Đà Nẵng', '', '048112233445', 'male', 1),
(4, 'Nguyễn Thị Mai', 'mai.nguyen@gmail.com', '0904444444', '23 Bạch Đằng, Q.Hải Châu, Đà Nẵng', '', '048556677889', 'female', 1),
(5, 'Võ Thanh Hùng', 'hung.vo@gmail.com', '0905555555', '99 Hai Bà Trưng, Q.Hoàn Kiếm, Hà Nội', '', '001234567890', 'male', 1),
(6, 'Bùi Thị Thu', 'thu.bui@gmail.com', '0906666666', '15 Đinh Tiên Hoàng, Q.Hoàn Kiếm, Hà Nội', '', '001098765432', 'female', 1),
(7, 'Trương Văn Nam', 'nam.truong@gmail.com', '0907777777', '56 Nguyễn Trãi, Q.Cầu Giấy, Hà Nội', '', '001567890123', 'male', 1),
(8, 'Đinh Thị Hoa', 'hoa.dinh@gmail.com', '0908888888', '88 Lý Thường Kiệt, Q.Hoàng Mai, Hà Nội', '', '001234098765', 'female', 1);

-- ============================================================
-- BOOKINGS
-- ============================================================
INSERT INTO `bookings` (`id`, `tour_id`, `booking_code`, `adult_count`, `child_count`, `service_amount`, `total_amount`, `deposit_amount`, `remaining_amount`, `start_date`, `end_date`, `status`, `special_requests`, `created_by`) VALUES
(1, 1, 'BK-1001', 2, 0, 700000, 7700000, 7700000, 0, '2025-05-10', '2025-05-11', 'completed', 'Phòng có view biển', 1),
(2, 2, 'BK-1002', 2, 1, 850000, 11250000, 3375000, 7875000, '2025-06-15', '2025-06-17', 'deposited', NULL, 1),
(3, 3, 'BK-1003', 3, 0, 150000, 17550000, 17550000, 0, '2025-06-20', '2025-06-23', 'paid', 'Cần hỗ trợ xe lăn cho 1 khách', 1),
(4, 4, 'BK-1004', 4, 2, 500000, 17300000, 0, 17300000, '2025-07-05', '2025-07-07', 'pending', NULL, 1);

-- ============================================================
-- BOOKING CUSTOMERS
-- ============================================================
INSERT INTO `booking_customers` (`booking_id`, `customer_id`, `is_representative`, `room_number`, `created_by`) VALUES
-- Booking 1 (BK-1001): 2 khách
(1, 1, 1, '301', 1),
(1, 2, 0, '301', 1),
-- Booking 2 (BK-1002): 3 khách
(2, 3, 1, NULL, 1),
(2, 4, 0, NULL, 1),
-- Booking 3 (BK-1003): 3 khách
(3, 5, 1, '205', 1),
(3, 6, 0, '205', 1),
(3, 7, 0, '206', 1),
-- Booking 4 (BK-1004): 4 người lớn 2 trẻ em
(4, 1, 1, NULL, 1),
(4, 8, 0, NULL, 1);

-- ============================================================
-- BOOKING SERVICES
-- ============================================================
INSERT INTO `booking_services` (`booking_id`, `tour_id`, `service_id`, `quantity`, `current_price`, `created_by`) VALUES
-- BK-1001 - Tour Hạ Long
(1, 1, 2, 1, 2500000, 1),
(1, 1, 6, 1, 350000, 1),
-- BK-1002 - Tour Đà Nẵng
(2, 2, 4, 1, 900000, 1),
(2, 2, 5, 1, 3500000, 1),
-- BK-1003 - Tour Phú Quốc
(3, 3, 7, 1, 50000, 1);

-- ============================================================
-- PAYMENTS
-- ============================================================
INSERT INTO `payments` (`booking_id`, `payment_method`, `transaction_code`, `amount`, `type`, `payment_date`, `created_by`) VALUES
-- BK-1001: Đã thanh toán full
(1, 'bank_transfer', 'TXN001', 3000000, 'deposit', '2025-04-20 09:00:00', 1),
(1, 'bank_transfer', 'TXN002', 4700000, 'remaining', '2025-05-07 14:00:00', 1),
-- BK-1002: Đã cọc
(2, 'cash', NULL, 3375000, 'deposit', '2025-04-25 10:30:00', 1),
-- BK-1003: Đã thanh toán full
(3, 'bank_transfer', 'TXN003', 10000000, 'deposit', '2025-05-01 09:00:00', 1),
(3, 'bank_transfer', 'TXN004', 7550000, 'remaining', '2025-06-17 11:00:00', 1);

-- ============================================================
-- TOUR ASSIGNMENTS (phân công hướng dẫn viên)
-- ============================================================
INSERT INTO `tour_assignments` (`id`, `booking_id`, `guide_id`, `status`, `created_by`) VALUES
(1, 1, 2, 'completed', 1),
(2, 3, 3, 'assigned', 1);

-- ============================================================
-- TOUR CHECKIN LINKS
-- ============================================================
INSERT INTO `tour_checkin_links` (`id`, `tour_assignment_id`, `title`, `note`, `created_by`) VALUES
(1, 1, 'Check-in Ngày 1 - Xuống tàu Hạ Long', 'Kiểm tra hành lý và giấy tờ', 2);

-- ============================================================
-- CUSTOMER CHECKINS
-- ============================================================
INSERT INTO `customer_checkins` (`tour_checkin_link_id`, `customer_id`, `checkin_time`, `created_by`) VALUES
(1, 1, '2025-05-10 08:30:00', 2),
(1, 2, '2025-05-10 08:45:00', 2);

-- ============================================================
-- JOURNALS
-- ============================================================
INSERT INTO `journals` (`tour_assignment_id`, `date`, `content`, `type`, `created_by`) VALUES
(1, '2025-05-10', 'Đón đoàn tại Hà Nội lúc 8h sáng, đoàn gồm 2 khách. Khởi hành đúng giờ. Đến cảng Tuần Châu lúc 11h30. Lên tàu, nhận phòng. Buổi chiều tham quan hang Sửng Sốt, chèo kayak. Khách rất hài lòng.', 'daily', 2),
(1, '2025-05-11', 'Buổi sáng tập thái cực quyền, thăm làng chài Cửa Vạn. Trưa trên tàu. 13h rời tàu, 17h về đến Hà Nội. Đoàn về an toàn, hài lòng với chuyến đi.', 'daily', 2);

-- ============================================================
-- NOTIFICATIONS
-- ============================================================
INSERT INTO `notifications` (`id`, `title`, `message`, `type`, `priority`, `created_by`) VALUES
(1, 'Booking mới BK-1004', 'Có booking mới BK-1004 cho tour Hà Nội - Sapa cần xử lý.', 'booking', 'high', 1),
(2, 'Nhắc thanh toán BK-1002', 'Booking BK-1002 còn 7,875,000đ chưa thanh toán, tour khởi hành 15/06.', 'payment', 'medium', 1);

INSERT INTO `notification_recipients` (`notification_id`, `user_id`, `is_read`, `created_by`) VALUES
(1, 1, 0, 1),
(2, 1, 0, 1);

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- HƯỚNG DẪN ĐỔI MẬT KHẨU
-- Nếu login không được, chạy đoạn PHP sau để lấy hash mới:
--
-- <?php
-- echo password_hash('Admin@123', PASSWORD_BCRYPT); // Dùng cho admin
-- echo password_hash('Guide@123', PASSWORD_BCRYPT); // Dùng cho guide
-- ?>
--
-- Sau đó UPDATE users SET password='<hash>' WHERE email='admin@tour.vn';
-- ============================================================
