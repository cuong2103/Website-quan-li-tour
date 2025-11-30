<?php
$currentUser = $_SESSION['currentUser'] ?? null;

$fullname = $currentUser['fullname'] ?? 'User';
$role = ($currentUser['roles'] == 'admin')  ? 'Admin' : 'Hướng dẫn viên';
$avatar = strtoupper(mb_substr($fullname, 0, 1));
?>

<!DOCTYPE html>
<html lang="vi" class="h-full scroll-smooth">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tour Guide</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="<?= BASE_URL ?>assets/common.js"></script>

</head>

<body class="h-full bg-gray-50 flex">
  <!-- Container để show alert -->


  <!-- Main content (giữ nguyên header như trước) -->
  <div class="flex-1 ml-64 flex flex-col">
    <header class="bg-white shadow-sm border-b border-gray-200 fixed top-0 left-64 right-0 z-40">
      <div id="alert-message"
        class="fixed top-5 right-5 bg-red-500 text-white px-4 py-2 rounded shadow-lg opacity-0 transition-opacity duration-500">
      </div>
      <div class="px-6 py-7 flex items-center justify-between">
        <div class="flex-1 max-w-2xl">
          <div class="flex-1 max-w-2xl">
            <div class="relative">
              <input type="text" placeholder="Tìm kiếm booking, tour, khách hàng..."
                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
              <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </div>
          </div>
        </div>
        <div class="flex items-center space-x-4">
          <button class="relative p-2 text-gray-600 hover:bg-gray-100 rounded-full">
            <i data-lucide="bell"></i>
            <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
          </button>
          <div class="flex items-center space-x-3">
            <div class="w-9 h-9 rounded-full bg-gray-300 flex items-center justify-center text-gray-600 font-semibold">
              <?= $avatar ?>
            </div>
            <div>
              <p class="text-sm font-medium text-gray-900"><?= htmlspecialchars($fullname) ?></p>
              <p class="text-xs text-gray-500"><?= $role ?></p>
            </div>
          </div>

        </div>
      </div>

    </header>