<?php
$act = $_GET['act'] ?? '/';

match ($act) {
  // Admin Dashboard
  '/' => (new DashboardController())->Dashboard(),

  // Categories
  "categories" => (new CategoryController())->index(),
  "categories-store" => (new CategoryController())->store(),
  "categories-delete" => (new CategoryController())->delete(),
  "categories-edit" => (new CategoryController())->edit(),
  "categories-update" => (new CategoryController())->update(),
};
