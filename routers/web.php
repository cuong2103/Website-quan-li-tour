<?php
$act = $_GET['act'] ?? '/';

match ($act) {
  // Admin Dashboard
  '/' => (new DashboardController())->Dashboard(),

  'service-type' => (new ServiceTypeController()) -> index(),
  'store' => (new ServiceTypeController()) -> store(),
  'detail' => (new ServiceTypeController()) -> detail($_GET["id"]),
  'delete' => (new ServiceTypeController()) -> delete($_GET["id"]),
  'edit' => (new ServiceTypeController()) -> edit($_GET["id"]),
  'update' => (new ServiceTypeController()) -> update(),

};
