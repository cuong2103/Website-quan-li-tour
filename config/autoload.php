<?php
spl_autoload_register(function ($className) {
  // Danh sách các thư mục chứa class
  $paths = [
    './controllers/admin/',
    './controllers/guide/',
    './models/',
  ];
  // Tìm và load file
  foreach ($paths as $path) {
    $file = $path . $className . '.php';
    if (file_exists($file)) {
      require_once $file;
      return;
    }
  }
});
