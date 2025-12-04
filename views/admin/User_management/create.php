<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';
?>

<main class="mt-24 p-6 min-h-screen bg-gray-50 font-sans">
  <!-- Breadcrumb -->
  <div class="mb-8">
    <div class="flex items-center gap-3 text-sm mb-4">
      <a href="?act=user" class="text-gray-600 hover:text-orange-600 flex items-center gap-2">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
        Quay lại
      </a>
      <span class="text-gray-400">/</span>
      <span class="text-gray-900 font-medium">Thêm nhân viên mới</span>
    </div>
    <h1 class="text-3xl font-medium text-gray-900">Thêm nhân viên mới</h1>
    <p class="text-gray-600 mt-2">Điền thông tin để tạo tài khoản nhân viên mới</p>
  </div>

  <form action="?act=user-store" method="POST" enctype="multipart/form-data">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

      <!-- Left Column -->
      <div class="space-y-6">
        <!-- Avatar Card -->
        <div class="bg-white rounded-lg border border-gray-200 p-6">
          <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <i data-lucide="image" class="w-5 h-5 text-orange-500"></i>
            Ảnh đại diện
          </h3>

          <div class="text-center">
            <div class="mx-auto w-32 h-32 rounded-lg bg-gray-100 border-2 border-dashed border-gray-300 flex items-center justify-center mb-4 overflow-hidden">
              <div id="avatar-preview" class="hidden w-full h-full"></div>
              <div id="avatar-placeholder">
                <i data-lucide="user" class="w-12 h-12 text-gray-400 mx-auto"></i>
              </div>
            </div>

            <input type="file" name="avatar" id="avatar-input" class="hidden" accept="image/*" onchange="previewAvatar(this)">
            <label for="avatar-input" class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white font-medium rounded-lg transition-colors">
              <i data-lucide="upload" class="w-4 h-4"></i>
              Tải ảnh lên
            </label>
            <p class="text-sm text-gray-500 mt-2">JPG, PNG (Tối đa 2MB)</p>
          </div>
        </div>

        <!-- Role & Status Card -->
        <div class="bg-white rounded-lg border border-gray-200 p-6">
          <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <i data-lucide="shield" class="w-5 h-5 text-orange-500"></i>
            Phân quyền
          </h3>

          <div class="space-y-3">
            <label class="flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-pink-300 has-[:checked]:border-pink-500 has-[:checked]:bg-pink-50">
              <input type="radio" name="roles" value="admin" class="w-4 h-4 text-pink-600">
              <span class="ml-3 flex items-center gap-2">
                <i data-lucide="shield-check" class="w-4 h-4"></i>
                <span class="font-medium">Admin</span>
              </span>
            </label>

            <label class="flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-300 has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
              <input type="radio" name="roles" value="guide" checked class="w-4 h-4 text-blue-600">
              <span class="ml-3 flex items-center gap-2">
                <i data-lucide="user" class="w-4 h-4"></i>
                <span class="font-medium">Hướng dẫn viên</span>
              </span>
            </label>
          </div>

          <div class="mt-4">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Trạng thái</label>
            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
              <option value="1">Hoạt động</option>
              <option value="0">Tạm ngừng</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Right Column -->
      <div class="lg:col-span-2">
        <div class="bg-white rounded-lg border border-gray-200 p-6">
          <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <i data-lucide="info" class="w-5 h-5 text-orange-500"></i>
            Thông tin cá nhân
          </h3>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">
                Họ và tên <span class="text-red-500">*</span>
              </label>
              <input type="text" name="fullname" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                placeholder="Nguyễn Văn A">
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">
                Email <span class="text-red-500">*</span>
              </label>
              <input type="email" name="email" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                placeholder="email@example.com">
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">
                Số điện thoại <span class="text-red-500">*</span>
              </label>
              <input type="text" name="phone" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                placeholder="0123456789">
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">
                Mật khẩu <span class="text-red-500">*</span>
              </label>
              <input type="password" name="password" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                placeholder="••••••••">
            </div>
          </div>

          <!-- Buttons -->
          <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-gray-200">
            <a href="?act=user" class="px-6 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50">
              Hủy bỏ
            </a>
            <button type="submit" class="px-6 py-2 bg-orange-500 hover:bg-orange-600 text-white font-medium rounded-lg flex items-center gap-2">
              <i data-lucide="check" class="w-4 h-4"></i>
              Tạo nhân viên
            </button>
          </div>
        </div>
      </div>
    </div>
  </form>
</main>

<script>
  function previewAvatar(input) {
    if (input.files && input.files[0]) {
      const reader = new FileReader();
      reader.onload = function(e) {
        document.getElementById('avatar-preview').innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
        document.getElementById('avatar-preview').classList.remove('hidden');
        document.getElementById('avatar-placeholder').classList.add('hidden');
      }
      reader.readAsDataURL(input.files[0]);
    }
  }
</script>

<?php require_once './views/components/footer.php'; ?>