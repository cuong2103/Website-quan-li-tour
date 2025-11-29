<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';
?>

<main class="mt-28 p-6 min-h-screen bg-gray-50">

  <!-- Breadcrumb -->
  <div class="flex items-center justify-between mb-8">
    <div>
      <div class="flex items-center gap-4">
        <a href="?act=user" class="text-gray-600 hover:text-gray-900 flex items-center gap-2">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
          </svg>
          <span>Nhân viên</span>
        </a>
        <span class="text-gray-400">/</span>
        <span class="text-gray-900 font-medium">Thêm nhân viên mới</span>
      </div>
      <h1 class="text-2xl font-bold text-gray-900 mt-3">Thêm nhân viên mới</h1>
    </div>
  </div>

  <form action="?act=user-store" method="POST" enctype="multipart/form-data" 
        class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

      <!-- Cột trái -->
      <div class="lg:col-span-1 space-y-6">

        <!-- Avatar -->
          <div class="text-center">
            <?php if(!empty($user['avatar'])): ?>
              <!-- Nếu đã có avatar (chỉ áp dụng cho edit, create mới thì bỏ) -->
              <img src="/uploads/avatar/<?= $user['avatar'] ?>" 
                  class="mx-auto w-40 h-40 rounded-full mb-4 object-cover border border-gray-300" 
                  alt="Avatar">
            <?php else: ?>
              <!-- Khung mặc định -->
              <div class="mx-auto w-40 h-40 rounded-full bg-gray-100 border-2 border-dashed border-gray-300 
                          flex items-center justify-center mb-4">
                <div class="text-center">
                  <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                  </svg>
                  <p class="text-xs text-gray-500 mt-2">Ảnh đại diện</p>
                </div>
              </div>
            <?php endif; ?>
            
            <!-- Input file -->
            <input type="file" name="avatar" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg"
                  accept="">
            <p class="text-sm text-gray-500 mt-1">Chọn ảnh đại diện (jpg, png,...)</p>
          </div>

        <!-- Vai trò -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-3">Vai trò</label>
          <div class="space-y-3">
            <label class="flex items-center gap-3 cursor-pointer">
              <input type="radio" name="roles" value="1" class="w-4 h-4 text-orange-600 focus:ring-orange-500">
              <span class="text-gray-800">Admin</span>
            </label>

            <label class="flex items-center gap-3 cursor-pointer">
              <input type="radio" name="roles" value="2" checked class="w-4 h-4 text-orange-600 focus:ring-orange-500">
              <span class="text-gray-800">Hướng dẫn viên</span>
            </label>
          </div>
        </div>

        <!-- Trạng thái -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-3">Trạng thái</label>
          <select name="status" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg">
            <option value="1">Hoạt động</option>
            <option value="0">Tạm ngừng</option>
          </select>
        </div>

      </div>

      <!-- Cột phải -->
      <div class="lg:col-span-2 space-y-6">

        <!-- Thông tin cơ bản -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Họ và tên *</label>
            <input type="text" name="fullname" required 
              class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
            <input type="email" name="email" required
              class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Số điện thoại *</label>
            <input type="text" name="phone" required
              class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Mật khẩu *</label>
            <input type="password" name="password" required
              class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Ngày sinh</label>
            <input type="date" name="birthday"
              class="w-full px-4 py-2.5 border border-gray-300 rounded-lg">
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Giới tính</label>
            <select name="gender" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg">
              <option value="male">Nam</option>
              <option value="female">Nữ</option>
              <option value="other">Khác</option>
            </select>
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Địa chỉ</label>
          <input type="text" name="address"
            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg"
            placeholder="Số nhà, đường, phường/xã, quận/huyện, tỉnh">
        </div>

        <!-- Nút -->
        <div class="flex justify-end gap-4 pt-8 border-t">
          <a href="?act=user" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium">
            Hủy bỏ
          </a>

          <button type="submit"
            class="px-8 py-2.5 bg-orange-500 hover:bg-orange-600 text-white font-medium rounded-lg flex items-center gap-2">
            Tạo nhân viên
          </button>
        </div>

      </div>
    </div>

  </form>
</main>

<?php require_once './views/components/footer.php'; ?>
