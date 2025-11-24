<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';
?>
<main class="pt-28 px-8 bg-gray-50 min-h-screen overflow-y-auto">
  <!-- Modal/Overlay tạo tour -->
  <div class="max-w-12xl mx-auto  ">
    <!-- Header của form -->
    <div class="flex items-center justify-between mb-8">
      <div class="flex items-center gap-4">
        <button onclick="history.back()" class="p-2 hover:bg-gray-100 rounded-lg transition">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
        </button>
        <div>
          <h2 class="text-2xl font-bold text-gray-900">Tạo Tour mới</h2>
          <p class="text-sm text-gray-600">Nhập thông tin chi tiết về tour</p>
        </div>
      </div>
      <div class="flex gap-3">
        <button class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium">
          Hủy
        </button>
        <button class="px-6 py-2.5 bg-black text-white rounded-lg hover:bg-gray-900 transition font-medium">
          Lưu Tour
        </button>
      </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 space-y-10">

      <!-- 1. Thông tin cơ bản -->
      <div>
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Thông tin cơ bản</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">Tên tour</label>
            <input type="text" placeholder="VD: Tour Hà Nội - Sapa 3N2Đ" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
          </div>
          <div class="col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">Giới thiệu</label>
            <textarea rows="3" placeholder="Mô tả ngắn về tour..." class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"></textarea>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Danh mục</label>
            <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
              <option>Chọn danh mục</option>
              <option>Miền Bắc</option>
              <option>Miền Trung</option>
              <option>Miền Nam</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Trạng thái</label>
            <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
              <option>Hoạt động</option>
              <option>Tạm dừng</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Giá người lớn (VND)</label>
            <input type="text" value="4500000" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Giá trẻ em (VND)</label>
            <input type="text" value="4000000" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
          </div>
        </div>
      </div>

      <!-- 2. Lịch trình tour -->
      <div>
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-lg font-semibold text-gray-900">Lịch trình tour</h3>
          <button class="flex items-center gap-2 text-blue-600 hover:text-blue-700 font-medium text-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Thêm ngày
          </button>
        </div>

        <!-- Ngày 1 -->
        <div class="border border-gray-200 rounded-xl p-6 space-y-5">
          <div class="flex items-center justify-between">
            <h4 class="font-medium text-gray-900">Ngày 1</h4>
            <button class="text-blue-600 hover:text-blue-700 text-sm font-medium">x</button>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="col-span-2">
              <label class="block text-sm font-medium text-gray-700 mb-2">Điểm đến</label>
              <input type="text" placeholder="Chọn điểm đến..." class="w-full px-4 py-3 border border-gray-300 rounded-lg">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Thời gian đến</label>
              <input type="text" placeholder="..., 8:30" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Thời gian đi</label>
              <input type="text" placeholder="..., 8:30" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Mô tả hoạt động</label>
            <textarea rows="3" placeholder="Mô tả các hoạt động trong ngày..." class="w-full px-4 py-3 border border-gray-300 rounded-lg resize-none"></textarea>
          </div>
        </div>
      </div>

      <!-- 3. Dịch vụ bao gồm -->
      <div>
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-lg font-semibold text-gray-900">Dịch vụ bao gồm</h3>
          <button class="flex items-center gap-2 text-blue-600 hover:text-blue-700 font-medium text-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Thêm dịch vụ
          </button>
        </div>

        <div class="space-y-4">
          <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
            <div class="flex items-center gap-4">
              <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h-4m-6 0H5" />
                </svg>
              </div>
              <div>
                <p class="font-medium text-gray-900">Khách sạn 4 sao</p>
                <p class="text-sm text-gray-600">Phòng đôi tiêu chuẩn</p>
              </div>
            </div>
            <button class="text-red-600 hover:text-red-700">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>

          <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
            <div class="flex items-center gap-4">
              <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <div>
                <p class="font-medium text-gray-900">Bữa ăn</p>
                <p class="text-sm text-gray-600">3 bữa chính/ngày</p>
              </div>
            </div>
            <button class="text-red-600 hover:text-red-700">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>

          <!-- Thêm dịch vụ khác tương tự -->
        </div>
      </div>

      <!-- 4. Chính sách -->
      <div>
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Chính sách</h3>
        <div class="space-y-4">
          <label class="flex items-start gap-3 cursor-pointer">
            <input type="checkbox" checked class="mt-1 w-4 h-4 text-blue-600 rounded focus:ring-blue-500">
            <div>
              <p class="font-medium text-gray-900">Chính sách hủy tour</p>
              <p class="text-sm text-gray-600">Hủy trước 7 ngày hoàn 80% tiền</p>
            </div>
          </label>
          <label class="flex items-start gap-3 cursor-pointer">
            <input type="checkbox" checked class="mt-1 w-4 h-4 text-blue-600 rounded focus:ring-blue-500">
            <div>
              <p class="font-medium text-gray-900">Chính sách trẻ em</p>
              <p class="text-sm text-gray-600">Trẻ em dưới 5 tuổi miễn phí</p>
            </div>
          </label>
          <label class="flex items-start gap-3 cursor-pointer">
            <input type="checkbox" class="mt-1 w-4 h-4 text-blue-600 rounded focus:ring-blue-500">
            <div>
              <p class="font-medium text-gray-900">Chính sách bảo hiểm</p>
              <p class="text-sm text-gray-600">Bảo hiểm du lịch toàn diện</p>
            </div>
          </label>
        </div>
      </div>

    </div>
  </div>
</main>


<?php
require_once './views/components/footer.php';
?>