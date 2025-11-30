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
  'supplier-edit' => (new SupplierController())->edit(),
  'supplier-create' => (new SupplierController())->store(),
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
  'user' => (new UserManagementController())->index(),
  'user-getById' => (new UserManagementController())->detail(),
  'user-create' => (new UserManagementController())->create(),
  'user-edit' => (new UserManagementController())->edit(),
  'user-store' => (new UserManagementController())->store(),
  'user-update' => (new UserManagementController())->update(),
  'user-delete' => (new UserManagementController())->delete(),



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


  // bookings
  'bookings' => (new BookingController())->index(),
  'booking-create' => (new BookingController())->create(),
  'booking-store' => (new BookingController())->store(),
  'booking-edit' => (new BookingController())->edit(),
  'booking-update' => (new BookingController())->update(),
  'booking-delete' => (new BookingController())->delete(),
  'booking-detail' => (new BookingController())->detail(),

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

  'policies-create' => (new PolicyController())->create(),
  'policies-edit' => (new PolicyController())->edit(),
  'policies-store' => (new PolicyController())->store(),
  'policies-update' => (new PolicyController())->update(),
  'policies-delete' => (new PolicyController())->delete(),
  'policies-detail' => (new PolicyController())->detail(),

  // Tour_Assignments
  'tour-assignments' => (new TourAssignmentController())->index(),
  'tour-assignment-create' => (new TourAssignmentController())->create(),
  'tour-assignment-store' => (new TourAssignmentController())->store(),
  'tour-assignment-edit' => (new TourAssignmentController())->edit(),
  'tour-assignment-update' => (new TourAssignmentController())->update(),
  'tour-assignment-delete' => (new TourAssignmentController())->delete(),

  // Hướng dẫn viên
  'my-schedule' => (new MyScheduleController())->index(),

  '403' => require_once './views/forbidden.php',
  default => require_once './views/notFound.php',
};
