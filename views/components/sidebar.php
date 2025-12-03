<aside class="w-64 bg-white shadow-lg h-screen fixed inset-y-0 left-0 flex flex-col z-50">
  <!-- Logo -->
  <div class="px-6 py-7 border-b border-gray-200">
    <div class="flex items-center space-x-3">
      <!-- Icon xanh-->
      <div class="relative w-12 h-12">
        <?= $role === 'Admin' ? '<div class="absolute inset-0 bg-orange-500 rounded-2xl"></div>
        <i class="w-7 h-7 text-white absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2" data-lucide="map-pin"></i>' : '<div class="absolute inset-0 bg-blue-500 rounded-2xl"></div>
        <i data-lucide="map" class="w-7 h-7 text-white absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2"></i>' ?>
      </div>

      <!-- Text -->
      <div>
        <?= $role === 'Admin' ? '<h1 class="text-xl font-bold text-gray-900">Tour Manager</h1>
        <p class="text-xs text-gray-500 ">Admin Panel</p>' : ' <h1 class="text-xl font-bold text-gray-900">Tour Guide</h1>
        <p class="text-xs text-gray-500 ">Hướng dẫn viên</p>' ?>
      </div>
    </div>
  </div>
  <!-- Menu -->
  <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">
    <?php if ($role === 'Admin'): ?>

      <a href="#" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg bg-indigo-50 text-indigo-700">
        <i class="mr-3 w-6 h-6" data-lucide="layout-dashboard"></i>
        Dashboard
      </a>

      <!-- Quản lý và điều hành tour -->
      <a href="<?= BASE_URL . '?act=bookings' ?>" class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium text-gray-700  hover:bg-gray-100 rounded-lg transition">
        <div class="flex items-center">
          <i class="mr-3 w-6 h-6" data-lucide="clipboard"></i>
          Quản lý và điều hành tour
        </div>
      </a>

      <!-- Quản lý tour -->
      <a href="<?= BASE_URL ?>?act=tours" class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium text-gray-700  hover:bg-gray-100 rounded-lg transition">
        <div class="flex items-center">
          <i class="mr-3 w-6 h-6" data-lucide="calendar"></i>
          Quản lý tour
        </div>
      </a>

      <!-- Dữ liệu -->
      <div class="menu-group">
        <button class="menu-toggle w-full flex items-center justify-between px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition">
          <div class="flex items-center">
            <i class="mr-3 w-6 h-6" data-lucide="menu"></i>
            Dữ liệu
          </div>
          <i class="w-4 h-4" data-lucide="chevron-down"></i>
        </button>
        <div class="submenu pl-12 space-y-1 overflow-hidden transition-all duration-300 max-h-0">
          <a href="<?= BASE_URL . '?act=destination' ?>" class="block px-4 py-2 text-sm text-gray-600 
        
        hover:bg-gray-100 rounded">Địa điểm</a>
          <a href="<?= BASE_URL ?>?act=policies" class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded">Chính sách</a>
          <a href="<?= BASE_URL ?>?act=categories" class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded">Danh mục</a>
          <a href="<?= BASE_URL ?>?act=service" class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded">Dịch vụ</a>
          <a href="<?= BASE_URL ?>?act=service-type" class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded">Loại Dịch vụ</a>
        </div>
      </div>

      <!-- Khách hàng -->
      <a href="<?= BASE_URL ?>?act=customers" class="flex items-center px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition">
        <i class="mr-3 w-6 h-6" data-lucide="users-round"></i>
        Khách hàng
      </a>

      <!-- Nhà cung cấp -->
      <a href="<?= BASE_URL . '?act=suppliers' ?>" class="flex items-center px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition">
        <i class="mr-3 w-6 h-6" data-lucide="building-2"></i>
        Nhà cung cấp
      </a>

      <!-- Quản lý nhân viên -->
      <div class="menu-group">
        <button class="menu-toggle w-full flex items-center justify-between px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition">
          <div class="flex items-center">
            <i class="mr-3 w-6 h-6" data-lucide="users"></i>
            Nhân viên
          </div>
          <i class="w-4 h-4" data-lucide="chevron-down"></i>
        </button>
        <div class="submenu pl-12 space-y-1 overflow-hidden transition-all duration-300 max-h-0">
          <a href="<?= BASE_URL . '?act=user' ?>" class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded">Danh sách nhân viên</a>
          <a href="<?= BASE_URL . '?act=user-on-leave' ?>" class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded">Nhân viên nghỉ phép</a>
        </div>
      </div>
      
      <!-- Thông báo -->
      <a href="<?= BASE_URL . '?act=notifications' ?>" class="flex items-center px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition">
        <i class="mr-3 w-6 h-6" data-lucide="megaphone"></i>
        Quản lí thông báo
      </a>




      <!-- Thêm các menu Admin khác ở đây -->
    <?php else: ?>
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
      <a href="<?= BASE_URL ?>?act=journal"
        class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium text-gray-700  hover:bg-gray-100 rounded-lg transition">
        <div class="flex items-center">
          <i data-lucide="notebook-pen" class="mr-3 w-6 h-6"></i>
          Viết nhật ký
        </div>
      </a>

      <!-- Tài khoản -->
      <a href="<?= BASE_URL ?>?act="
        class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium text-gray-700  hover:bg-gray-100 rounded-lg transition">
        <div class="flex items-center">
          <i data-lucide="user" class="mr-3 w-6 h-6"></i>
          Tài khoản
        </div>
      <?php endif; ?>

      <!-- Thông báo -->
      <a href="<?= BASE_URL ?>?act=my-notifications"
        class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium text-gray-700  hover:bg-gray-100 rounded-lg transition">
        <div class="flex items-center">
          <i data-lucide="bell" class="mr-3 w-6 h-6"></i>
          Thông báo
        </div>
        <?= ($_SESSION['unreadCount'] ?? '') > 0 ? '<span class="ml-auto w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">' . $_SESSION['unreadCount'] . '</span>' : '' ?>
      </a>
  </nav>
  <!-- Đăng xuất -->
  <div class="border-t border-gray-200 px-4 py-4">
    <a href="<?= BASE_URL . '?act=logout' ?>" class="flex items-center px-4 py-3 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition">
      <i class="mr-3 w-6 h-6 text-red-500" data-lucide="log-out"></i>
      Đăng xuất
    </a>
  </div>
</aside>