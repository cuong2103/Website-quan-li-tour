<aside class="w-64 bg-white shadow-lg h-screen fixed inset-y-0 left-0 flex flex-col z-50">

  <div class="px-6 py-7 border-b border-gray-200">
    <div class="flex items-center space-x-3">
      <div class="relative w-12 h-12">
        <div class="absolute inset-0 bg-blue-500 rounded-2xl"></div>
        <i data-lucide="map" class="w-7 h-7 text-white absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2"></i>
      </div>
      <div>
        <h1 class="text-xl font-bold text-gray-900">HDV Panel</h1>
        <p class="text-xs text-gray-500">Guide Dashboard</p>
      </div>
    </div>
  </div>

  <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">

    <a href="<?= BASE_URL ?>?act=guide-dashboard"
       class="flex items-center px-4 py-3 text-sm font-medium rounded-lg
              hover:bg-gray-100 text-gray-700">
      <i data-lucide="home" class="mr-3 w-6 h-6"></i>
      Trang chủ
    </a>

    <a href="<?= BASE_URL ?>?act=guide-tours"
       class="flex items-center px-4 py-3 text-sm font-medium rounded-lg
              hover:bg-gray-100 text-gray-700">
      <i data-lucide="briefcase" class="mr-3 w-6 h-6"></i>
      Tour của tôi
    </a>

    <a href="<?= BASE_URL ?>?act=guide-diary"
       class="flex items-center px-4 py-3 text-sm font-medium rounded-lg
              hover:bg-gray-100 text-gray-700">
      <i data-lucide="notebook-pen" class="mr-3 w-6 h-6"></i>
      Viết nhật ký
    </a>

    <a href="<?= BASE_URL ?>?act=guide-notifications"
       class="flex items-center px-4 py-3 text-sm font-medium rounded-lg
              hover:bg-gray-100 text-gray-700">
      <i data-lucide="bell" class="mr-3 w-6 h-6"></i>
      Thông báo
    </a>

  </nav>

  <div class="border-t border-gray-200 px-4 py-4">
    <a href="<?= BASE_URL ?>?act=logout-admin"
       class="flex items-center px-4 py-3 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg">
      <i data-lucide="log-out" class="mr-3 w-6 h-6 text-red-500"></i>
      Đăng xuất
    </a>
  </div>
</aside>
