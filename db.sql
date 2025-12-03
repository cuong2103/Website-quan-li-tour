## 🗃️ Full Database Schema SQL Script


CREATE TABLE `users` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `fullname` varchar(255), 
  `password` varchar(255) NOT NULL,
  `email` varchar(100),
  `phone` varchar(20),
  `avatar` varchar(500),
  `roles` enum('admin','guide') DEFAULT 'guide',
  `status` boolean DEFAULT true,
  `created_by` int,
  `updated_by` int,
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp
);

CREATE TABLE `categories` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(100),
  `description` text,
  `parent_id` int,
  `created_by` int,
  `updated_by` int,
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp
);


CREATE TABLE `destinations` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `category_id` int,
  `name` varchar(255),
  `locations` varchar(500),
  `description` text,
  `created_by` int,
  `updated_by` int,
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp
);

CREATE TABLE `destination_images` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `destination_id` int,
  `image_url` varchar(500),
  `created_by` int,
  `updated_by` int,
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp
);

CREATE TABLE `service_types` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(100),
  `description` text,
  `created_by` int,
  `updated_by` int,
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp
);

CREATE TABLE `suppliers` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255),
  `email` varchar(100),
  `phone` varchar(20),
  `destination_id` int,
  `status` boolean DEFAULT true,
  `created_by` int,
  `updated_by` int,
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp
);

CREATE TABLE `services` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `service_type_id` int,
  `supplier_id` int,
  `name` varchar(255),
  `description` text,
  `estimated_price` decimal(12,0),
  `created_by` int,
  `updated_by` int,
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp
);

CREATE TABLE `tours` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `category_id` int,
  `tour_code` VARCHAR(20),
  `name` varchar(255),
  `introduction` text,
  `adult_price` decimal(12,0),
  `child_price` decimal(12,0),
  `status` varchar(20) DEFAULT 'active',
  `duration_days` int,
  `is_fixed` boolean DEFAULT false,
  `created_by` int,
  `updated_by` int,
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp
);

CREATE TABLE tour_services (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `tour_id` INT,
  `service_id` INT,
  `default_quantity` INT DEFAULT 1 ,
  `created_by` int,
  `updated_by` int,
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp
);

CREATE TABLE `booking_services` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `booking_id` int,
  `tour_id` int,
  `service_id` int,
  `quantity` int,
  `current_price` decimal(12,0),
  `created_by` int,
  `updated_by` int,
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp
);

CREATE TABLE `itineraries` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `tour_id` int,
  `destination_id` int,
  `description` text,
  `order_number` int,
  `arrival_time` time,
  `departure_time` time,
  `created_by` int,
  `updated_by` int,
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp
);

CREATE TABLE `policies` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `title` varchar(255),
  `content` text,
  `created_by` int,
  `updated_by` int,
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp
);

CREATE TABLE `tour_policies` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `policy_id` int,
  `tour_id` int,
  `created_by` int,
  `updated_by` int,
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp
);

CREATE TABLE `customers` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255),
  `email` varchar(100),
  `phone` varchar(20),
  `address` varchar(500),
  `passport` varchar(50),
  `citizen_id` varchar(20),
  `gender` enum('male', 'female', 'other'),
  `created_by` int,
  `updated_by` int,
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp
);


CREATE TABLE `bookings` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `tour_id` int,
  `booking_code` varchar(20),
  `adult_count` int,
  `child_count` int,
  `service_amount` decimal(12,0), 
  `total_amount` decimal(12,0),
  `deposit_amount` decimal(12,0),
  `remaining_amount` decimal(12,0),
  `start_date` date,
  `end_date` date,
  `status` tinyint DEFAULT 1, -- 1: chưa thanh toán 2: đã cọc 3: đã thanh toán
  `special_requests` text,
  `created_by` int,
  `updated_by` int,
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp
);

CREATE TABLE `booking_customers` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `booking_id` int,
  `customer_id` int,
  `is_representative` boolean DEFAULT false,
  `room_number` varchar(20),
  `notes` text,
  `created_by` int,
  `updated_by` int,
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp
);

CREATE TABLE `payments` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `booking_id` int,
  `payment_method` varchar(50),
  `amount` decimal(12,0),
  `type` varchar(50),
  `status` varchar(20) DEFAULT 'pending',
  `payment_date` timestamp DEFAULT (now()),
  `created_by` int,
  `updated_by` int,
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp
);

CREATE TABLE `customer_contracts` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `booking_id` int,
  `contract_name` varchar(255),
  `effective_date` timestamp,
  `expiry_date` timestamp,
  `signer_id` int,
  `customer_id` int,
  `status` varchar(50),
  `file_name` varchar(255),
  `file_url` text,
  `created_by` int,
  `updated_by` int,
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp
);

CREATE TABLE `tour_assignments` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `booking_id` int,
  `guide_id` int,
  `status` tinyint DEFAULT 1,
  `created_by` int,
  `updated_by` int,
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp
);

CREATE TABLE `customer_checkins` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `tour_assignment_id` int,
  `customer_id` int,
  `checkin_time` timestamp,
  `created_by` int,
  `updated_by` int,
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp
);

CREATE TABLE `journals` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `tour_assignment_id` int,
  `date` date,
  `content` text,
  `type` varchar(50),
  `created_by` int,
  `updated_by` int,
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp
);

CREATE TABLE `journal_images` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `journal_id` int,
  `image_url` varchar(500),
  `description` text,
  `created_by` int,
  `updated_by` int,
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp
);

CREATE TABLE `notifications` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `type` varchar(50) DEFAULT 'general', -- general, booking, tour, payment, urgent
  `created_by` int,
  `updated_by` int,
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp
);

CREATE TABLE `notification_recipients` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `notification_id` int NOT NULL,
  `user_id` int NOT NULL,
  `is_read` boolean DEFAULT false,
  `read_at` timestamp NULL,
  `created_by` int,
  `updated_by` int,
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp
);


CREATE TABLE `incurred_expenses`(
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `booking_id` int,
  `amount` decimal(12,0),
  `description` text,
  `created_by` int,
  `updated_by` int,
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp
);

-- Bảng `categories`
ALTER TABLE `categories` 
ADD FOREIGN KEY (`parent_id`) REFERENCES `categories`(`id`); -- parent category

-- Bảng `destinations`
ALTER TABLE `destinations` 
ADD FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`);

-- Bảng `destination_images`
ALTER TABLE `destination_images` 
ADD FOREIGN KEY (`destination_id`) REFERENCES `destinations`(`id`);

-- Bảng `suppliers`
ALTER TABLE `suppliers` 
ADD FOREIGN KEY (`destination_id`) REFERENCES `destinations`(`id`);

-- Bảng `services`
ALTER TABLE `services` 
ADD FOREIGN KEY (`service_type_id`) REFERENCES `service_types`(`id`);
ALTER TABLE `services` 
ADD FOREIGN KEY (`supplier_id`) REFERENCES `suppliers`(`id`);

-- Bảng `tours`
ALTER TABLE `tours` 
ADD FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`);

-- Bảng `tour_services`
ALTER TABLE `tour_services` 
ADD FOREIGN KEY (`tour_id`) REFERENCES `tours`(`id`);
ALTER TABLE `tour_services` 
ADD FOREIGN KEY (`service_id`) REFERENCES `services`(`id`);

-- Bảng `booking_services`
ALTER TABLE `booking_services` 
ADD FOREIGN KEY (`booking_id`) REFERENCES `bookings`(`id`);
ALTER TABLE `booking_services` 
ADD FOREIGN KEY (`tour_id`) REFERENCES `tours`(`id`);
ALTER TABLE `booking_services` 
ADD FOREIGN KEY (`service_id`) REFERENCES `services`(`id`);

-- Bảng `itineraries`
ALTER TABLE `itineraries` 
ADD FOREIGN KEY (`tour_id`) REFERENCES `tours`(`id`);
ALTER TABLE `itineraries` 
ADD FOREIGN KEY (`destination_id`) REFERENCES `destinations`(`id`);

-- Bảng `tour_policies`
ALTER TABLE `tour_policies` 
ADD FOREIGN KEY (`policy_id`) REFERENCES `policies`(`id`);
ALTER TABLE `tour_policies` 
ADD FOREIGN KEY (`tour_id`) REFERENCES `tours`(`id`);

-- Bảng `booking_customers`
ALTER TABLE `booking_customers` 
ADD FOREIGN KEY (`booking_id`) REFERENCES `bookings`(`id`);
ALTER TABLE `booking_customers` 
ADD FOREIGN KEY (`customer_id`) REFERENCES `customers`(`id`);

-- Bảng `bookings`
ALTER TABLE `bookings` 
ADD FOREIGN KEY (`tour_id`) REFERENCES `tours`(`id`);

-- Bảng `payments`
ALTER TABLE `payments` 
ADD FOREIGN KEY (`booking_id`) REFERENCES `bookings`(`id`);

-- Bảng `customer_contracts`
ALTER TABLE `customer_contracts` 
ADD FOREIGN KEY (`booking_id`) REFERENCES `bookings`(`id`);
ALTER TABLE `customer_contracts` 
ADD FOREIGN KEY (`customer_id`) REFERENCES `customers`(`id`);
ALTER TABLE `customer_contracts` 
ADD FOREIGN KEY (`signer_id`) REFERENCES `users`(`id`);

-- Bảng `tour_assignments`
ALTER TABLE `tour_assignments` 
ADD FOREIGN KEY (`booking_id`) REFERENCES `bookings`(`id`);
ALTER TABLE `tour_assignments` 
ADD FOREIGN KEY (`guide_id`) REFERENCES `users`(`id`); -- guide_id là user_id

-- Bảng `customer_checkins`
ALTER TABLE `customer_checkins` 
ADD FOREIGN KEY (`tour_assignment_id`) REFERENCES `tour_assignments`(`id`);
ALTER TABLE `customer_checkins` 
ADD FOREIGN KEY (`customer_id`) REFERENCES `customers`(`id`);

-- Bảng `journals`
ALTER TABLE `journals` 
ADD FOREIGN KEY (`tour_assignment_id`) REFERENCES `tour_assignments`(`id`);

-- Bảng `journal_images`
ALTER TABLE `journal_images` 
ADD FOREIGN KEY (`journal_id`) REFERENCES `journals`(`id`);


-- Bảng `incurred_expenses`
ALTER TABLE `incurred_expenses` 
ADD FOREIGN KEY (`booking_id`) REFERENCES `bookings`(`id`);

-- Bảng `notifications` và `notification_recipients`
ALTER TABLE `notifications` 
ADD FOREIGN KEY (`created_by`) REFERENCES `users`(`id`);

ALTER TABLE `notification_recipients` 
ADD FOREIGN KEY (`notification_id`) REFERENCES `notifications`(`id`) ON DELETE CASCADE;

ALTER TABLE `notification_recipients` 
ADD FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE;