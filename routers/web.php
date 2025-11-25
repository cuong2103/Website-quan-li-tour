<?php
session_start();
$act = $_GET['act'] ?? '/';

if ($act !== 'login-admin'  && $act !== 'check-login-admin' && $act !== 'logout-admin') {
  checkLoginAdmin();
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
  'supplier-create' => (new SupplierController())->create(),
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
  'login-admin' => (new AuthController())->formLogin(),
  'check-login-admin' => (new AuthController())->login(),
  'logout-admin' => (new AuthController())->logout(),

  //customers
  'customers' => (new CustomerController())->index(),
  'customer-create' => (new CustomerController())->create(),
  'customer-update' => (new CustomerController())->update(),
  'customer-detail' => (new CustomerController())->detail(),
  'customer-edit' => (new CustomerController())->edit(),
  'customer-delete' => (new CustomerController())->delete(),
};
