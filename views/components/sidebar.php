<!-- Sidebar -->
<aside class="w-64 bg-white shadow-lg h-screen fixed inset-y-0 left-0 flex flex-col z-50">
  <!-- Logo -->
  <div class="px-6 py-4 border-b border-gray-200">
    <div class="flex items-center space-x-3">
      <div class="w-10 h-10 bg-indigo-600 rounded-lg flex items-center justify-center text-white font-bold text-xl">
        T
      </div>
      <div>
        <h1 class="text-xl font-bold text-gray-900">Tour Manager</h1>
        <p class="text-xs text-gray-500">Admin Panel</p>
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
        <svg class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
        <svg class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
      </button>
      <div class="submenu pl-12 space-y-1 overflow-hidden transition-all duration-300 max-h-0">
        <a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded">Danh sách Tour</a>
        <a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded">Lịch khởi hành</a>
      </div>
    </div>

    <!-- Dữ liệu -->
    <div class="menu-group">
      <button class="menu-toggle w-full flex items-center justify-between px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition">
        <div class="flex items-center">
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
          Dữ liệu
        </div>
        <svg class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
      </button>
      <div class="submenu pl-12 space-y-1 overflow-hidden transition-all duration-300 max-h-0">
        <a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded">Địa điểm</a>
        <a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded">Chính sách</a>
        <a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded">Loại Dịch vụ</a>
        <a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded">Dịch vụ</a>
      </div>
    </div>

    <!-- Các menu đơn khác (giữ nguyên) -->
    <a href="#" class="flex items-center px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg">
      <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
      </svg>
      Điều hành Tour
    </a>
    <!-- ... thêm các menu khác nếu cần ... -->
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