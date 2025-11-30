<?php
$currentUser = $_SESSION['currentUser'] ?? null;

$fullname = $currentUser['fullname'] ?? 'Hướng dẫn viên';
$avatar = strtoupper(mb_substr($fullname, 0, 1));
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>HDV Dashboard</title>

  <script src="https://cdn.tailwindcss.com"></script>
  <script src="<?= BASE_URL ?>assets/common.js"></script>
</head>

<body class="bg-gray-50 h-full flex">
