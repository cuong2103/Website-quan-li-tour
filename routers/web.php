<?php
$act = $_GET['act'] ?? '/';


match ($act) {
  // Admin Dashboard
  '/' => (new DashboardController())->Dashboard(),
  'suppliers' => (new SupplierController())->index(),
  'supplier-list' => (new SupplierController())->index(),
  'supplier-edit' => (new SupplierController())->edit(),
  'supplier-add' => (new SupplierController())->create(),
  'supplier-update' => (new SupplierController())->update(),
  'supplier-detail' => (new SupplierController())->detail(),
  'supplier-delete' => (new SupplierController())->delete(),
};
