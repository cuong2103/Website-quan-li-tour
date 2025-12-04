<?php
session_start();
$act = $_GET['act'] ?? '/';

if ($act !== 'login'  && $act !== 'check-login' && $act !== 'logout') {
  checkLogin();
}

match ($act) {
  // Admin Dashboard
  '/' => (new DashboardController())->Dashboard(),

  'tours' => (new TourController())->index(),
  'tours-create' => (new TourController())->create(),
  'tours-store' => (new TourController())->store(),
  'tours-delete' => (new TourController())->delete(),
  'tours-detail' => (new TourController())->detail(),
  'tours-edit' => (new TourController())->edit(),
  'tours-update' => (new TourController())->update(),

  'suppliers' => (new SupplierController())->index(),
  'supplier-create' => (new SupplierController())->create(),
  'supplier-store' => (new SupplierController())->store(),
  'supplier-edit' => (new SupplierController())->edit(),
  'supplier-update' => (new SupplierController())->update(),
  'supplier-detail' => (new SupplierController())->detail(),
  'supplier-delete' => (new SupplierController())->delete(),

  // service-type
  'service-type' => (new ServiceTypeController())->index(),
  'service-type-store' => (new ServiceTypeController())->store(),
  'service-type-detail' => (new ServiceTypeController())->detail(),
  'service-type-delete' => (new ServiceTypeController())->delete(),
  'service-type-edit' => (new ServiceTypeController())->edit(),
  'service-type-update' => (new ServiceTypeController())->update(),

  //service
  'service' => (new ServiceController())->index(),
  'service-detail' => (new ServiceController())->detail(),
  'service-create' => (new ServiceController())->create(),
  'service-store' => (new ServiceController())->store(),
  'service-edit' => (new ServiceController())->edit(),
  'service-delete' => (new ServiceController())->delete(),
  'service-update' => (new ServiceController())->update(),


  //user_management
  'user' => (new UserController())->index(),
  'user-getById' => (new UserController())->detail(),
  'user-create' => (new UserController())->create(),
  'user-edit' => (new UserController())->edit(),
  'user-store' => (new UserController())->store(),
  'user-update' => (new UserController())->update(),
  'user-update-leave' => (new UserController())->updateLeave(),
  'user-delete' => (new UserController())->delete(),
  'user-detail' => (new UserController())->detail(),
  'user-on-leave' => (new UserController())->onLeave(),
  'user-end-leave' => (new UserController())->endLeave(),

  // Notifications
  'notifications' => (new NotificationController())->index(),
  'notification-create' => (new NotificationController())->create(),
  'notification-store' => (new NotificationController())->store(),
  'notification-detail' => (new NotificationController())->detail(),
  'read-notification' => (new NotificationController())->read(),
  'mark-all-notifications-read' => (new NotificationController())->markAllRead(),
  'notification-delete' => (new NotificationController())->delete(),
  'my-notifications' => (new NotificationController())->myNotifications(),
  'notification-edit' => (new NotificationController())->edit(),
  'notification-update' => (new NotificationController())->update(),

  // Categories
  "categories" => (new CategoryController())->index(),
  "categories-store" => (new CategoryController())->store(),
  "categories-delete" => (new CategoryController())->delete(),
  "categories-edit" => (new CategoryController())->edit(),
  "categories-update" => (new CategoryController())->update(),

  // Destination hiển thị địa điểm
  'destination' => (new DestinationController())->index(),
  'destination-create' => (new DestinationController())->create(),
  'destination-store' => (new DestinationController())->store(),
  'destination-edit' => (new DestinationController())->edit(),
  'destination-update' => (new DestinationController())->update(),
  'destination-delete' => (new DestinationController())->delete(),
  'destination-delete-image' => (new DestinationController())->deleteImage(),
  'destination-detail' => (new DestinationController())->detail(),

  // Auth admin
  'login' => (new AuthController())->formLogin(),
  'check-login' => (new AuthController())->login(),
  'logout' => (new AuthController())->logout(),

  //customers
  'customers' => (new CustomerController())->index(),
  'customer-create' => (new CustomerController())->create(),
  'customer-update' => (new CustomerController())->update(),
  'customer-detail' => (new CustomerController())->detail(),
  'customer-edit' => (new CustomerController())->edit(),
  'customer-delete' => (new CustomerController())->delete(),
  'customer-export' => (new CustomerController())->exportCustomers(),
  'customer-import' => (new CustomerController())->importCustomers(),


  // bookings
  'bookings' => (new BookingController())->index(),
  'booking-create' => (new BookingController())->create(),
  'booking-store' => (new BookingController())->store(),
  'booking-edit' => (new BookingController())->edit(),
  'booking-update' => (new BookingController())->update(),
  'booking-delete' => (new BookingController())->delete(),
  'booking-detail' => (new BookingController())->detail(),
  'booking-upload-customers' => (new BookingController())->uploadCustomers(),
  'booking-export-customers' => (new BookingController())->exportCustomers(),
  'booking-add-customer' => (new BookingController())->addCustomer(),
  'booking-remove-customer' => (new BookingController())->removeCustomer(),
  'booking-import-rooms' => (new BookingController())->importRoomArrangement(),
  'booking-export-rooms' => (new BookingController())->exportRoomArrangement(),

  // contracts
  'contracts' => (new ContractController())->index(),
  'contract-create' => (new ContractController())->create(),
  'contract-store' => (new ContractController())->store(),
  'contract-edit' => (new ContractController())->edit(),
  'contract-update' => (new ContractController())->update(),
  'contract-delete' => (new ContractController())->delete(),
  'contract-detail' => (new ContractController())->detail(),

  // payments
  'payments' => (new PaymentController())->index(),
  'payment-create' => (new PaymentController())->create(),
  'payment-store' => (new PaymentController())->store(),
  'payment-edit' => (new PaymentController())->edit(),
  'payment-update' => (new PaymentController())->update(),
  'payment-delete' => (new PaymentController())->delete(),
  'payment-detail' => (new PaymentController())->detail(),

  //policies
  'policies' => (new PolicyController())->index(),

  'policy-create' => (new PolicyController())->create(),
  'policy-edit' => (new PolicyController())->edit(),
  'policy-store' => (new PolicyController())->store(),
  'policy-update' => (new PolicyController())->update(),
  'policy-delete' => (new PolicyController())->delete(),
  'policy-detail' => (new PolicyController())->detail(),

  // Tour_Assignments
  'tour-assignment-create' => (new TourAssignmentController())->create(),
  'tour-assignment-store' => (new TourAssignmentController())->store(),
  'tour-assignment-edit' => (new TourAssignmentController())->edit(),
  'tour-assignment-update' => (new TourAssignmentController())->update(),
  'tour-assignment-delete' => (new TourAssignmentController())->delete(),

  // 
  'my-schedule' => (new MyScheduleController())->index(),
  // Guide tour_Assignments
  'guide-tour-assignments' => (new GuideTourAssignmentController())->index(),
  'guide-tour-assignments-detail' => (new GuideTourAssignmentController())->detail(),
  'guide-tour-assignments-export-checkin' => (new GuideTourAssignmentController())->exportCheckinList(),
  'guide-tour-assignments-checkin' => (new GuideTourAssignmentController())->checkinStore(),
  'guide-tour-assignments-checkin-destroy' => (new GuideTourAssignmentController())->checkinDestroy(),

  // Journal
  'journal' => (new JournalController())->index(),
  'journal-create' => (new JournalController())->create(),
  'journal-store' => (new JournalController())->store(),
  'journal-edit' => (new JournalController())->edit(),
  'journal-update' => (new JournalController())->update(),
  'journal-delete' => (new JournalController())->delete(),
  'journal-images-delete' => (new JournalController())->deleteImage(),
  'journal-detail' => (new JournalController())->detail(),
  '403' => require_once './views/forbidden.php',
  default => require_once './views/notFound.php',
};
