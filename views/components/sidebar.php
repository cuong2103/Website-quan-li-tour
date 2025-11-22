<!-- Sidebar -->
<aside class="w-64 bg-white shadow-lg h-screen fixed inset-y-0 left-0 flex flex-col z-50">
  <!-- Logo -->
  <div class="px-6 py-7 border-b border-gray-200">
    <div class="flex items-center space-x-3">
      <!-- Icon cam tròn có biểu tượng vị trí -->
      <div class="relative w-12 h-12">
        <div class="absolute inset-0 bg-orange-500 rounded-2xl"></div>
        <svg class="w-7 h-7 text-white absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2" fill="currentColor" viewBox="0 0 24 24">
          <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" />
        </svg>
      </div>

      <!-- Text -->
      <div>
        <h1 class="text-xl font-bold text-gray-900">Tour Manager</h1>
        <p class="text-xs text-gray-500 ">Admin Panel</p>
      </div>
    </div>
  </div>

  <!-- Menu -->
  <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">
    <!-- Dashboard -->
    <a href="#" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg bg-indigo-50 text-indigo-700">
      <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
      </svg>
      Dashboard
    </a>

    <!-- Quản lý Booking -->
    <div class="menu-group">
      <button class="menu-toggle w-full flex items-center justify-between px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition">
        <div class="flex items-center">
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
          </svg>
          Quản lý Booking
        </div>
        <svg class="arrow w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
      </button>
      <div class="submenu pl-12 space-y-1 overflow-hidden transition-all duration-300 max-h-0">
        <a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded">Danh sách Booking</a>
        <a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded">Thống kê Booking</a>
      </div>
    </div>

    <!-- Quản lý Tour -->
    <div class="menu-group">
      <button class="menu-toggle w-full flex items-center justify-between px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition">
        <div class="flex items-center">
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>
          Quản lý Tour
        </div>
        <svg class="arrow w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
      </button>
      <div class="submenu pl-12 space-y-1 overflow-hidden transition-all duration-300 max-h-0">
        <a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded">Danh sách Tour</a>
        <a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded">Lịch khởi hành</a>
      </div>
    </div>

    <!-- Điều hành Tour -->
    <a href="#" class="flex items-center px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition">
      <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
      </svg>
      Điều hành Tour
    </a>

    <!-- Dữ liệu -->
    <div class="menu-group">
      <button class="menu-toggle w-full flex items-center justify-between px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition">
        <div class="flex items-center">
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
          Dữ liệu
        </div>
        <svg class="arrow w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
      </button>
      <div class="submenu pl-12 space-y-1 overflow-hidden transition-all duration-300 max-h-0">
        <a href="<?= BASE_URL . '?act=destination' ?>" class="block px-4 py-2 text-sm text-gray-600 
        
        hover:bg-gray-100 rounded">Địa điểm</a>
        <a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded">Chính sách</a>
        <a href="<?= BASE_URL ?>?act=service-type" class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded">Loại Dịch vụ</a>
        <a href="<?= BASE_URL ?>?act=categories" class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded">Danh mục</a>
        <!-- <a href="" class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded">Loại Dịch vụ</a> -->
        <a href="<?= BASE_URL ?>?act=service" class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded">Dịch vụ</a>
      </div>
    </div>

    <!-- Khách hàng -->
    <a href="#" class="flex items-center px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition">
      <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
      </svg>
      Khách hàng
    </a>

    <!-- Nhà cung cấp -->
    <a href="#" class="flex items-center px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition">
      <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h-4m-6 0H5a2 2 0 00-2 2v0a2 2 0 002 2h14a2 2 0 002-2v0a2 2 0 00-2-2z" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M9 7h6M9 11h6M12 17v4" />
      </svg>
      Nhà cung cấp
    </a>

    <!-- Nhân viên -->
    <a href="#" class="flex items-center px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition">
      <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
      </svg>
      Nhân viên
    </a>

    <!-- Lịch sử -->
    <a href="#" class="flex items-center px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition">
      <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>
      Lịch sử
    </a>

    <!-- Thông báo -->
    <a href="#" class="flex items-center px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition">
      <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
      </svg>
      Thông báo
    </a>
  </nav>

  <!-- Đăng xuất -->
  <div class="border-t border-gray-200 px-4 py-4">
    <a href="#" class="flex items-center px-4 py-3 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition">
      <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
      </svg>
      Đăng xuất
    </a>
  </div>
</aside>