<?php
$act = $_GET['act'] ?? '/';

match ($act) {
  // Admin Dashboard
  '/' => (new DashboardController())->Dashboard(),
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
