
CREATE TABLE `users` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `fullname` VARCHAR(255), 
  `password` VARCHAR(255) NOT NULL, 
  `email` VARCHAR(100) UNIQUE NOT NULL,
  `phone` VARCHAR(20),
  `avatar` VARCHAR(500), 
  `roles` ENUM('admin','guide') DEFAULT 'guide', 
  `status` BOOLEAN DEFAULT true, 
  `leave_start` DATE,
  `leave_end` DATE,
  `created_by` INT,
  `updated_by` INT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP
);


CREATE TABLE `categories` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(100) UNIQUE NOT NULL, 
  `description` TEXT, 
  `parent_id` INT, 
  `created_by` INT,
  `updated_by` INT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP
);


CREATE TABLE `destinations` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `category_id` INT,
  `name` VARCHAR(255) NOT NULL, 
  `locations` VARCHAR(500),
  `description` TEXT,
  `created_by` INT,
  `updated_by` INT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP
);


CREATE TABLE `destination_images` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `destination_id` INT NOT NULL, 
  `image_url` VARCHAR(500) NOT NULL,
  `created_by` INT,
  `updated_by` INT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE `service_types` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(100) UNIQUE NOT NULL, 
  `description` TEXT,
  `created_by` INT,
  `updated_by` INT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE `suppliers` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(100), 
  `phone` VARCHAR(20),
  `destination_id` INT,
  `status` BOOLEAN DEFAULT true, 
  `created_by` INT,
  `updated_by` INT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP
);


CREATE TABLE `services` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `service_type_id` INT NOT NULL,
  `supplier_id` INT NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `description` TEXT,
  `estimated_price` DECIMAL(12,0), 
  `created_by` INT,
  `updated_by` INT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP
);


CREATE TABLE `tours` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `category_id` INT,
  `tour_code` VARCHAR(20) UNIQUE NOT NULL, 
  `name` VARCHAR(255) NOT NULL,
  `introduction` TEXT,
  `adult_price` DECIMAL(12,0) NOT NULL, 
  `child_price` DECIMAL(12,0) NOT NULL,
  `status` ENUM('active', 'inactive', 'draft', 'cancelled') DEFAULT 'active', 
  `duration_days` INT NOT NULL, 
  `is_fixed` BOOLEAN DEFAULT false, 
  `created_by` INT,
  `updated_by` INT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE `tour_services` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `tour_id` INT NOT NULL,
  `service_id` INT NOT NULL,
  `default_quantity` INT DEFAULT 1,
  `created_by` INT,
  `updated_by` INT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY unique_tour_service (`tour_id`, `service_id`) 
);

CREATE TABLE `itineraries` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `tour_id` INT NOT NULL,
  `destination_id` INT NOT NULL,
  `description` TEXT,
  `order_number` INT NOT NULL, 
  `arrival_time` TIME, 
  `departure_time` TIME,
  `created_by` INT,
  `updated_by` INT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE `policies` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `content` TEXT NOT NULL,
  `created_by` INT,
  `updated_by` INT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE `tour_policies` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `policy_id` INT NOT NULL,
  `tour_id` INT NOT NULL,
  `created_by` INT,
  `updated_by` INT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY unique_tour_policy (`tour_id`, `policy_id`) 
);

CREATE TABLE `customers` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(100), 
  `phone` VARCHAR(20), 
  `address` VARCHAR(500),
  `passport` VARCHAR(50), 
  `citizen_id` VARCHAR(20),
  `gender` ENUM('male', 'female', 'other'), 
  `created_by` INT,
  `updated_by` INT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE `bookings` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `tour_id` INT NOT NULL,
  `booking_code` VARCHAR(20) UNIQUE NOT NULL, 
  `adult_count` INT NOT NULL DEFAULT 0, 
  `child_count` INT NOT NULL DEFAULT 0, 
  `service_amount` DECIMAL(12,0) DEFAULT 0, 
  `total_amount` DECIMAL(12,0) NOT NULL, 
  `deposit_amount` DECIMAL(12,0) DEFAULT 0, 
  `remaining_amount` DECIMAL(12,0) DEFAULT 0, 
  `start_date` DATE NOT NULL, 
  `end_date` DATE NOT NULL, 
  `status` ENUM('pending', 'deposited', 'paid', 'cancelled', 'completed') DEFAULT 'pending', 
  `special_requests` TEXT, 
  `created_by` INT,
  `updated_by` INT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE `booking_services` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `booking_id` INT NOT NULL,
  `tour_id` INT NOT NULL,
  `service_id` INT NOT NULL,
  `quantity` INT NOT NULL DEFAULT 1, 
  `current_price` DECIMAL(12,0) NOT NULL, 
  `created_by` INT,
  `updated_by` INT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP
);


CREATE TABLE `booking_customers` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `booking_id` INT NOT NULL,
  `customer_id` INT NOT NULL,
  `is_representative` BOOLEAN DEFAULT false, 
  `room_number` VARCHAR(20), 
  `notes` TEXT,
  `created_by` INT,
  `updated_by` INT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY unique_booking_customer (`booking_id`, `customer_id`) 
);

CREATE TABLE `payments` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `booking_id` INT NOT NULL,
  `payment_method` ENUM('cash', 'bank_transfer') NOT NULL,
  `amount` DECIMAL(12,0) NOT NULL, 
  `type` ENUM('deposit', 'full_payment', 'remaining', 'refund') NOT NULL, 
  `status` ENUM('pending', 'completed', 'failed', 'cancelled') DEFAULT 'pending', 
  `payment_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `created_by` INT,
  `updated_by` INT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE `customer_contracts` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `booking_id` INT NOT NULL,
  `contract_name` VARCHAR(255) NOT NULL,
  `effective_date` DATE NOT NULL, 
  `expiry_date` DATE, 
  `signer_id` INT, 
  `customer_id` INT NOT NULL, 
  `status` ENUM('draft', 'active', 'expired', 'cancelled') DEFAULT 'draft', 
  `file_name` VARCHAR(255),
  `file_url` TEXT, 
  `created_by` INT,
  `updated_by` INT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP
);


CREATE TABLE `tour_assignments` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `booking_id` INT NOT NULL,
  `guide_id` INT NOT NULL, 
  `status` ENUM('assigned', 'in_progress', 'completed', 'cancelled') DEFAULT 'assigned', 
  `created_by` INT,
  `updated_by` INT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE `tour_checkin_links` ( 
  `id` INT PRIMARY KEY AUTO_INCREMENT, 
  `tour_assignment_id` INT NOT NULL, 
  `title` VARCHAR(255) NOT NULL,
  `note` TEXT, 
  `created_by` INT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `customer_checkins` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `tour_checkin_link_id` INT NOT NULL,
  `customer_id` INT NOT NULL,
  `checkin_time` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `created_by` INT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY unique_link_customer (`tour_checkin_link_id`, `customer_id`)
);

CREATE TABLE `journals` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `tour_assignment_id` INT NOT NULL,
  `date` DATE NOT NULL,
  `content` TEXT NOT NULL,
  `type` ENUM('daily', 'incident', 'other') DEFAULT 'daily', 
  `created_by` INT,
  `updated_by` INT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE `journal_images` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `journal_id` INT NOT NULL,
  `image_url` VARCHAR(500),
  `description` TEXT,
  `created_by` INT,
  `updated_by` INT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP
);


CREATE TABLE `notifications` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `message` TEXT NOT NULL,
  `type` ENUM('general', 'booking', 'tour', 'payment', 'urgent', 'system') DEFAULT 'general', 
  `priority` ENUM('low', 'medium', 'high') DEFAULT 'medium', 
  `created_by` INT,
  `updated_by` INT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE `notification_recipients` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `notification_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  `is_read` BOOLEAN DEFAULT false, 
  `read_at` TIMESTAMP NULL, 
  `created_by` INT,
  `updated_by` INT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY unique_notification_user (`notification_id`, `user_id`)
);

CREATE TABLE `incurred_expenses`(
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `booking_id` INT NOT NULL,
  `amount` DECIMAL(12,0) NOT NULL, 
  `description` TEXT NOT NULL, 
  `expense_date` DATE NOT NULL, 
  `category` VARCHAR(100),
  `receipt_url` VARCHAR(500), 
  `created_by` INT,
  `updated_by` INT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP
);



ALTER TABLE `categories`
  ADD CONSTRAINT `fk_categories_parent` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ;


ALTER TABLE `destinations`
  ADD CONSTRAINT `fk_destinations_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

ALTER TABLE `destination_images`
  ADD CONSTRAINT `fk_destination_images_destination` FOREIGN KEY (`destination_id`) REFERENCES `destinations` (`id`) ;


ALTER TABLE `suppliers`
  ADD CONSTRAINT `fk_suppliers_destination` FOREIGN KEY (`destination_id`) REFERENCES `destinations` (`id`) ON DELETE SET NULL;

ALTER TABLE `services`
  ADD CONSTRAINT `fk_services_service_type` FOREIGN KEY (`service_type_id`) REFERENCES `service_types` (`id`) ,
  ADD CONSTRAINT `fk_services_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ;


ALTER TABLE `tours`
  ADD CONSTRAINT `fk_tours_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

ALTER TABLE `tour_services`
  ADD CONSTRAINT `fk_tour_services_tour` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ,
  ADD CONSTRAINT `fk_tour_services_service` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ;

ALTER TABLE `itineraries`
  ADD CONSTRAINT `fk_itineraries_tour` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ,
  ADD CONSTRAINT `fk_itineraries_destination` FOREIGN KEY (`destination_id`) REFERENCES `destinations` (`id`) ;

ALTER TABLE `tour_policies`
  ADD CONSTRAINT `fk_tour_policies_policy` FOREIGN KEY (`policy_id`) REFERENCES `policies` (`id`) ,
  ADD CONSTRAINT `fk_tour_policies_tour` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ;


ALTER TABLE `bookings`
  ADD CONSTRAINT `fk_bookings_tour` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ;

ALTER TABLE `booking_services`
  ADD CONSTRAINT `fk_booking_services_booking` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ,
  ADD CONSTRAINT `fk_booking_services_tour` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ,
  ADD CONSTRAINT `fk_booking_services_service` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ;

ALTER TABLE `booking_customers`
  ADD CONSTRAINT `fk_booking_customers_booking` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ,
  ADD CONSTRAINT `fk_booking_customers_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ;


ALTER TABLE `payments`
  ADD CONSTRAINT `fk_payments_booking` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ;

ALTER TABLE `customer_contracts`
  ADD CONSTRAINT `fk_customer_contracts_booking` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ,
  ADD CONSTRAINT `fk_customer_contracts_signer` FOREIGN KEY (`signer_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_customer_contracts_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ;


ALTER TABLE `tour_assignments`
  ADD CONSTRAINT `fk_tour_assignments_booking` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ,
  ADD CONSTRAINT `fk_tour_assignments_guide` FOREIGN KEY (`guide_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT;


ALTER TABLE `customer_checkins`
  ADD CONSTRAINT `fk_customer_checkins_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ,
  ADD CONSTRAINT `fk_customer_checkins_link` FOREIGN KEY (`tour_checkin_link_id`) REFERENCES `tour_checkin_links` (`id`) ON DELETE CASCADE;
ALTER TABLE `tour_checkin_links`
  ADD CONSTRAINT `fk_tour_checkin_links_assignment` FOREIGN KEY (`tour_assignment_id`) REFERENCES `tour_assignments` (`id`);
ALTER TABLE `customer_checkins`
  ADD CONSTRAINT `fk_customer_checkins_link` FOREIGN KEY (`tour_checkin_link_id`) REFERENCES `tour_checkin_links`(`id`);


ALTER TABLE `journals`
  ADD CONSTRAINT `fk_journals_tour_assignment` FOREIGN KEY (`tour_assignment_id`) REFERENCES `tour_assignments` (`id`) ;

ALTER TABLE `journal_images`
  ADD CONSTRAINT `fk_journal_images_journal` FOREIGN KEY (`journal_id`) REFERENCES `journals` (`id`) ;


ALTER TABLE `notification_recipients`
  ADD CONSTRAINT `fk_notification_recipients_notification` FOREIGN KEY (`notification_id`) REFERENCES `notifications` (`id`) ,
  ADD CONSTRAINT `fk_notification_recipients_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ;


ALTER TABLE `incurred_expenses`
  ADD CONSTRAINT `fk_incurred_expenses_booking` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ;