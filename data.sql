-- Dữ liệu Mẫu cho Hệ thống Quản lý Tour Du lịch

-- 1. Bảng `roles` (Vai trò)
INSERT INTO `roles` (`name`, `description`, `created_by`) VALUES
('Administrator', 'Quản trị viên hệ thống', 1),
('Tour Guide', 'Hướng dẫn viên du lịch', 1),
('Sales Staff', 'Nhân viên bán hàng/đặt tour', 1),
('Content Editor', 'Biên tập nội dung tour', 1);

-- 2. Bảng `users` (Người dùng) - ĐÃ THÊM PASSWORD (NOT NULL)
INSERT INTO `users` (`fullname`, `password`, `email`, `phone`, `role_id`, `created_by`) VALUES
('Nguyễn Văn A', '$2y$10$abcdefghijklmnopqrstuvwxyz123456', 'admin@example.com', '0901112222', 1, 1), -- ID 1: Administrator
('Lê Thị B', '$2y$10$bcdefghijklmnopqrstuvwxyz1234567', 'guide.b@example.com', '0902223333', 2, 1), -- ID 2: Tour Guide
('Phạm Văn C', '$2y$10$cdefghijklmnopqrstuvwxyz12345678', 'sales.c@example.com', '0903334444', 3, 1), -- ID 3: Sales Staff
('Trần Thị D', '$2y$10$defghijklmnopqrstuvwxyz123456789', 'editor.d@example.com', '0904445555', 4, 1); -- ID 4: Content Editor

-- 3. Bảng `categories` (Danh mục Tour)
INSERT INTO `categories` (`name`, `description`, `created_by`) VALUES
('Châu Á', 'Các tour du lịch ở khu vực Châu Á', 1),
('Châu Âu', 'Các tour du lịch ở khu vực Châu Âu', 1),
('Nghỉ dưỡng', 'Các tour du lịch nghỉ dưỡng cao cấp', 4),
('Khám phá', 'Các tour du lịch khám phá và mạo hiểm', 4);

-- 4. Bảng `countries` (Quốc gia)
INSERT INTO `countries` (`category_id`, `name`, `description`, `created_by`) VALUES
(1, 'Việt Nam', 'Đất nước du lịch nổi tiếng ở Đông Nam Á', 4), -- ID 1
(1, 'Thái Lan', 'Xứ sở Chùa Vàng', 4), -- ID 2
(2, 'Pháp', 'Kinh đô ánh sáng và sự lãng mạn', 4), -- ID 3
(2, 'Ý', 'Quê hương của ẩm thực và kiến trúc La Mã', 4); -- ID 4

-- 5. Bảng `destinations` (Điểm đến)
INSERT INTO `destinations` (`country_id`, `name`, `address`, `description`, `created_by`) VALUES
(1, 'Hà Nội', 'Miền Bắc Việt Nam', 'Thủ đô ngàn năm văn hiến', 4), -- ID 1
(1, 'Phú Quốc', 'Kiên Giang, Việt Nam', 'Đảo ngọc nghỉ dưỡng', 4), -- ID 2
(3, 'Paris', 'Île-de-France, Pháp', 'Địa điểm không thể bỏ qua tại Pháp', 4), -- ID 3
(4, 'Rome', 'Lazio, Ý', 'Thành phố Vĩnh Hằng', 4); -- ID 4

-- 6. Bảng `destination_images` (Ảnh điểm đến)
INSERT INTO `destination_images` (`destination_id`, `image_url`, `created_by`) VALUES
(1, 'images/hanoi_oldquarter.jpg', 4),
(2, 'images/phuquoc_beach.jpg', 4);

-- 7. Bảng `service_types` (Loại dịch vụ)
INSERT INTO `service_types` (`name`, `description`, `created_by`) VALUES
('Khách sạn', 'Dịch vụ lưu trú', 1), -- ID 1
('Vận chuyển', 'Dịch vụ xe đưa đón', 1), -- ID 2
('Ăn uống', 'Dịch vụ nhà hàng, bữa ăn', 1), -- ID 3
('Vé tham quan', 'Vé vào cổng các điểm du lịch', 1); -- ID 4

-- 8. Bảng `suppliers` (Nhà cung cấp)
INSERT INTO `suppliers` (`name`, `email`, `phone`, `destination_id`, `created_by`) VALUES
('Khách sạn A Hà Nội', 'hotelA@hanoi.com', '0241234567', 1, 3), -- ID 1
('Công ty Vận tải B', 'transportB@vietnam.com', '0987654321', 2, 3), -- ID 2
('Nhà hàng C Phú Quốc', 'restaurantC@pq.com', '0777123123', 2, 3), -- ID 3
('Hãng hàng không D', 'airlineD@global.com', '0123456789', 3, 3); -- ID 4

-- 9. Bảng `services` (Dịch vụ)
INSERT INTO `services` (`service_type_id`, `supplier_id`, `name`, `type`, `description`, `price`, `created_by`) VALUES
(1, 1, 'Phòng Deluxe Double', 'Lưu trú', 'Phòng 2 người lớn, view thành phố', 1200000.00, 3), -- ID 1
(2, 2, 'Xe 16 chỗ', 'Thuê xe', 'Xe du lịch đời mới, có tài xế', 2500000.00, 3), -- ID 2
(3, 3, 'Bữa trưa Hải sản', 'Ăn uống', 'Thực đơn hải sản cao cấp', 350000.00, 3), -- ID 3
(4, NULL, 'Vé cáp treo Hòn Thơm', 'Vé', 'Vé khứ hồi cáp treo', 500000.00, 3); -- ID 4

-- 10. Bảng `tours` (Tour Du lịch)
INSERT INTO `tours` (`name`, `introduction`, `adult_price`, `child_price`, `base_price`, `created_by`) VALUES
('Hà Nội - Vịnh Hạ Long 3 ngày 2 đêm', 'Khám phá di sản thiên nhiên thế giới', 5000000.00, 2500000.00, 3500000.00, 4), -- ID 1
('Du lịch đảo Phú Quốc 4 ngày 3 đêm', 'Nghỉ dưỡng tại đảo ngọc với bãi biển tuyệt đẹp', 8500000.00, 4250000.00, 6000000.00, 4), -- ID 2
('Tour lãng mạn Paris 5 ngày', 'Tham quan tháp Eiffel, Khải Hoàn Môn và bảo tàng Louvre', 25000000.00, 15000000.00, 18000000.00, 4), -- ID 3
('Rome Cổ đại 4 ngày', 'Khám phá đấu trường Colosseum và Vatican', 20000000.00, 10000000.00, 15000000.00, 4); -- ID 4

-- 11. Bảng `tour_services` (Dịch vụ kèm theo Tour)
INSERT INTO `tour_services` (`tour_id`, `service_id`, `description`, `created_by`) VALUES
(2, 1, 'Bao gồm 3 đêm phòng Deluxe tại khách sạn 4 sao', 4), -- Tour PQ dùng KS A HN (ID 1), nên thực tế điểm đến có thể không khớp
(2, 3, 'Bao gồm 3 bữa trưa hải sản', 4),
(2, 4, 'Bao gồm vé cáp treo Hòn Thơm', 4),
(1, 2, 'Bao gồm xe 16 chỗ từ Hà Nội đi Hạ Long', 4);

-- 12. Bảng `itineraries` (Chương trình Tour)
INSERT INTO `itineraries` (`tour_id`, `destination_id`, `description`, `order_number`, `created_by`) VALUES
(1, 1, 'Ngày 1: Đến Hà Nội, tham quan Hồ Gươm và phố cổ.', 1, 4),
(1, 1, 'Ngày 2: Di chuyển đến Hạ Long, lên du thuyền và thăm hang động.', 2, 4),
(2, 2, 'Ngày 1: Đến Phú Quốc, nhận phòng và tắm biển.', 1, 4),
(2, 2, 'Ngày 3: Tham quan Vinpearl Safari.', 3, 4);

-- 13. Bảng `policies` (Chính sách)
INSERT INTO `policies` (`content`, `created_by`) VALUES
('Chính sách hoàn hủy tour trước 30 ngày được hoàn 100% tiền cọc.', 1),
('Chính sách bảo hiểm du lịch bắt buộc cho mọi hành khách.', 1),
('Quy định về hành lý xách tay và ký gửi.', 1);

-- 14. Bảng `tour_policies` (Chính sách áp dụng cho Tour)
INSERT INTO `tour_policies` (`policy_id`, `tour_id`, `description`, `created_by`) VALUES
(1, 1, 'Chính sách hoàn hủy chuẩn cho tour trong nước.', 4),
(2, 3, 'Bao gồm bảo hiểm du lịch quốc tế.', 4);

-- 15. Bảng `customers` (Khách hàng)
INSERT INTO `customers` (`name`, `email`, `phone`, `address`, `created_by`) VALUES
('Hoàng Minh Tâm', 'tam.hm@client.com', '0919998888', 'TP. Hồ Chí Minh', 3), -- ID 1
('Nguyễn Xuân Huy', 'huy.nx@client.com', '0928887777', 'Hà Nội', 3), -- ID 2
('Trần Thanh Nga', 'nga.tt@client.com', '0937776666', 'Đà Nẵng', 3), -- ID 3
('Vũ Văn Khánh', 'khanh.vv@client.com', '0946665555', 'Hải Phòng', 3); -- ID 4

-- 16. Bảng `bookings` (Đặt tour)
INSERT INTO `bookings` (`tour_id`, `adult_count`, `child_count`, `total_amount`, `deposit_amount`, `remaining_amount`, `status`, `created_by`) VALUES
(1, 2, 1, 12500000.00, 5000000.00, 7500000.00, 1, 3), -- ID 1: Tour HN-HL
(2, 4, 0, 34000000.00, 10000000.00, 24000000.00, 1, 3), -- ID 2: Tour PQ
(3, 2, 0, 50000000.00, 20000000.00, 30000000.00, 1, 3); -- ID 3: Tour Paris

-- 17. Bảng `booking_customers` (Khách hàng trong Đặt tour)
INSERT INTO `booking_customers` (`booking_id`, `customer_id`, `is_representative`) VALUES
(1, 1, TRUE),  -- Tâm là người đại diện Booking 1
(1, 2, FALSE), -- Huy đi cùng Booking 1
(2, 3, TRUE),  -- Nga là người đại diện Booking 2
(2, 4, FALSE); -- Khánh đi cùng Booking 2

-- 18. Bảng `payments` (Thanh toán)
INSERT INTO `payments` (`booking_id`, `payment_method`, `amount`, `type`, `status`, `created_by`) VALUES
(1, 'Chuyển khoản', 5000000.00, 'deposit', 'completed', 3), -- Cọc Booking 1
(2, 'Thẻ tín dụng', 10000000.00, 'deposit', 'completed', 3), -- Cọc Booking 2
(3, 'Chuyển khoản', 20000000.00, 'deposit', 'completed', 3), -- Cọc Booking 3
(1, 'Tiền mặt', 7500000.00, 'final', 'completed', 3); -- Thanh toán nốt Booking 1

-- 19. Bảng `customer_contracts` (Hợp đồng khách hàng)
INSERT INTO `customer_contracts` (`booking_id`, `contract_name`, `signing_date`, `customer_id`, `status`) VALUES
(1, 'Hợp đồng Tour HN-HL_001', '2025-11-15 10:00:00', 1, 'Signed'),
(2, 'Hợp đồng Tour PQ_002', '2025-11-10 14:30:00', 3, 'Signed');

-- 20. Bảng `tour_assignments` (Phân công tour)
INSERT INTO `tour_assignments` (`booking_id`, `guide_id`, `start_date`, `end_date`, `created_by`) VALUES
(1, 2, '2026-01-10', '2026-01-12', 1), -- ID 1: Guide B (ID 2) cho Booking 1
(2, 2, '2026-02-05', '2026-02-08', 1); -- ID 2: Guide B (ID 2) cho Booking 2

-- 21. Bảng `customer_checkins` (Check-in khách hàng)
INSERT INTO `customer_checkins` (`tour_assignment_id`, `customer_id`, `notes`, `checkin_time`, `location`, `created_by`) VALUES
(1, 1, 'Khách hàng có mặt tại điểm hẹn sân bay.', '2026-01-10 07:30:00', 'Sân bay Nội Bài, Hà Nội', 2),
(1, 2, 'Đi cùng người đại diện.', '2026-01-10 07:31:00', 'Sân bay Nội Bài, Hà Nội', 2);

-- 22. Bảng `journals` (Nhật ký tour)
INSERT INTO `journals` (`tour_assignment_id`, `date`, `content`, `type`, `created_by`) VALUES
(1, '2026-01-10', 'Ngày 1 hoàn thành. Khách hàng vui vẻ, không phát sinh vấn đề.', 'Daily Report', 2),
(1, '2026-01-11', 'Ngày 2 thăm Vịnh Hạ Long. Chất lượng dịch vụ du thuyền tốt.', 'Daily Report', 2);

-- 23. Bảng `journal_images` (Ảnh nhật ký)
INSERT INTO `journal_images` (`journal_id`, `image_url`, `description`) VALUES
(1, 'journal/booking1_day1_group.jpg', 'Ảnh chụp nhóm khách hàng ngày 1'),
(2, 'journal/booking1_day2_halong.jpg', 'Ảnh chụp du thuyền tại Hạ Long');

-- 24. Bảng `notifications` (Thông báo)
INSERT INTO `notifications` (`user_id`, `title`, `message`, `is_read`, `created_by`) VALUES
(1, 'Booking Mới', 'Một booking mới (ID: 3) đã được tạo.', FALSE, 3),
(3, 'Thanh toán Thành công', 'Thanh toán cuối cùng cho Booking ID: 1 đã hoàn tất.', TRUE, 3),
(2, 'Phân công Tour', 'Bạn đã được phân công cho Tour ID: 2, khởi hành 2026-02-05.', FALSE, 1);