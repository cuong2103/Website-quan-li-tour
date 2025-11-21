<!DOCTYPE html>
<html lang="vi" class="h-full">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tour Manager - Admin Panel</title>
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
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
          </button>
          <div class="flex items-center space-x-3">
            <div class="w-9 h-9 rounded-full bg-gray-300 flex items-center justify-center text-gray-600 font-semibold">N</div>
            <div>
              <p class="text-sm font-medium text-gray-900">Nguyễn Văn A</p>
              <p class="text-xs text-gray-500">Admin</p>
            </div>
          </div>
        </div>
      </div>

    </header>