<!-- Sidebar -->
<aside class="w-64 bg-white shadow-lg h-screen fixed inset-y-0 left-0 flex flex-col z-50">
  <!-- Logo -->
  <div class="px-6 py-7 border-b border-gray-200">
    <div class="flex items-center space-x-3">
      <!-- Icon cam tròn có biểu tượng vị trí -->
      <div class="relative w-12 h-12">
        <div class="absolute inset-0 bg-blue-500 rounded-2xl"></div>
        <i data-lucide="map" class="w-7 h-7 text-white absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2"></i>
      </div>

      <!-- Text -->
      <div>
        <h1 class="text-xl font-bold text-gray-900">Tour Guide</h1>
        <p class="text-xs text-gray-500 ">Hướng dẫn viên</p>
      </div>
    </div>
  </div>

  <!-- Menu -->
  <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">
    <a href="<?= BASE_URL . '?act=my-schedule' ?>"
      class="flex items-center px-4 py-3 text-sm font-medium rounded-lg
              hover:bg-gray-100 text-gray-700">
      <i data-lucide="home" class="mr-3 w-6 h-6"></i>
      Trang chủ
    </a>

    <!-- Quản lý Booking -->
    <a href="<?= BASE_URL . '?act=guide-tour-assignments' ?>" class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium text-gray-700  hover:bg-gray-100 rounded-lg transition">
      <div class="flex items-center">
        <i class="mr-3 w-6 h-6" data-lucide="clipboard"></i>
        Tour của tôi
      </div>
    </a>

    <!-- Viết nhật kí -->
    <a href="<?= BASE_URL ?>?act="
      class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium text-gray-700  hover:bg-gray-100 rounded-lg transition">
      <div class="flex items-center">
        <i data-lucide="notebook-pen" class="mr-3 w-6 h-6"></i>
        Viết nhật ký
      </div>
    </a>

    <!-- Thông báo -->
    <a href="<?= BASE_URL ?>?act="
      class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium text-gray-700  hover:bg-gray-100 rounded-lg transition">
      <div class="flex items-center">
      <i data-lucide="bell" class="mr-3 w-6 h-6"></i>
      Thông báo
      </div>
    </a>

    <!-- Tài khoản -->
    <a href="<?= BASE_URL ?>?act="
      class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium text-gray-700  hover:bg-gray-100 rounded-lg transition">
      <div class="flex items-center">
      <i data-lucide="user" class="mr-3 w-6 h-6"></i>
      Tài khoản
      </div>
  </nav>

  <!-- Đăng xuất -->
  <div class="border-t border-gray-200 px-4 py-4">
    <a href="<?= BASE_URL . '?act=logout' ?>" class="flex items-center px-4 py-3 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition">
      <i class="mr-3 w-6 h-6 text-red-500" data-lucide="log-out"></i>
      Đăng xuất
    </a>
  </div>
</aside>