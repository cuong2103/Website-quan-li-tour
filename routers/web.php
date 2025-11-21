<?php
session_start();
$act = $_GET['act'] ?? '/';

if($act !== 'login-admin'  && $act !== 'check-login-admin' && $act !== 'logout-admin'  ){
  checkLoginAdmin();
}

match ($act) {
  // Admin Dashboard
  '/' => (new DashboardController())->Dashboard(),

  'suppliers' => (new SupplierController())->index(),
  'supplier-edit' => (new SupplierController())->edit(),
  'supplier-create' => (new SupplierController())->create(),
  'supplier-update' => (new SupplierController())->update(),
  'supplier-detail' => (new SupplierController())->detail(),
  'supplier-delete' => (new SupplierController())->delete(),

  'service-type' => (new ServiceTypeController())->index(),
  'store' => (new ServiceTypeController())->store(),
  'detail' => (new ServiceTypeController())->detail($_GET["id"]),
  'delete' => (new ServiceTypeController())->delete($_GET["id"]),
  'edit' => (new ServiceTypeController())->edit($_GET["id"]),
  'update' => (new ServiceTypeController())->update(),

  // Categories
  "categories" => (new CategoryController())->index(),
  "categories-store" => (new CategoryController())->store(),
  "categories-delete" => (new CategoryController())->delete(),
  "categories-edit" => (new CategoryController())->edit(),
  "categories-update" => (new CategoryController())->update(),
  // Destination hiển thị địa điểm
  'destination' => (new DestinationController())->index(),
  // Destination form thêm địa điểm
  'destination-create' => (new DestinationController())->create(),
  // Destination xử lí thêm địa điểm
  'destination-store' => (new DestinationController())->store(),
  // Destination form sửa sản phẩm
  'destination-edit' => (new DestinationController())->edit(),
  // Destination xử lí sửa sản phẩm
  'destination-update' => (new DestinationController())->update(),
  // Xóa địa điểm
  'destination-delete' => (new DestinationController())->delete(),
  // Xóa ảnh cũ
  'destination-delete-image' => (new DestinationController())->deleteImage(),
  // Xem chi tiết
  'destination-detail' => (new DestinationController())->detail(),



  // Auth admin
  'login-admin' => (new AuthController()) ->formLogin(),
  'check-login-admin' => (new AuthController()) ->login(),
  'logout-admin' => (new AuthController())->logout(),
};
