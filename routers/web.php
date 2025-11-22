<?php
$act = $_GET['act'] ?? '/';

match ($act) {
  // Admin Dashboard
  '/' => (new DashboardController())->Dashboard(),

  // Service_type
  'service-type' => (new ServiceTypeController()) -> index(),
  'service-type-store' => (new ServiceTypeController()) -> store(),
  'service-type-detail' => (new ServiceTypeController()) -> detail($_GET["id"]),
  'service-type-delete' => (new ServiceTypeController()) -> delete($_GET["id"]),
  'service-type-edit' => (new ServiceTypeController()) -> edit($_GET["id"]),
  'service-type-update' => (new ServiceTypeController()) -> update(),

  // Service
  'service' => (new ServiceController()) -> index(),
  'service-detail' => (new ServiceController()) -> detail($_GET["id"]),
  'service-create' => (new ServiceController()) -> create(),
  // 'service-store' => (new ServiceController()) -> store(),
  'service-delete' => (new ServiceController()) -> delete($_GET["id"]),
  'service-edit' => (new ServiceController()) -> edit($_GET['id']),


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
};
