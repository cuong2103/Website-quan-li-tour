<?php
$act = $_GET['act'] ?? '/';

match ($act) {
  // Admin Dashboard
  '/' => (new DashboardController())->Dashboard(),
};
