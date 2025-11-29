<?php 
require_once './views/components/header.php';
require_once './views/components/sidebar.php';
// dd($user);
?>

<main class="mt-28 p-6 min-h-screen bg-gray-50">
  <!-- Breadcrumb + Tiêu đề -->
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
        <span class="text-gray-900 font-medium">Chỉnh sửa nhân viên</span>
      </div>
      <h1 class="text-2xl font-bold text-gray-900 mt-3">Chỉnh sửa nhân viên</h1>
    </div>
  </div>

  <!-- Form sửa -->
<form action="?act=user-update" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
    <input type="hidden" name="id" value="<?= $user['id'] ?>">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <div class="lg:col-span-1 space-y-6">
        <!-- Avatar hiện tại -->
        <div class="text-center">
          <div class="mx-auto w-40 h-40 rounded-full bg-blue-100 flex items-center justify-center mb-4 text-6xl font-bold text-blue-600">
            <?php if(!empty($user['avatar'])): ?>
              <img src="/uploads/avatars/<?= $user['avatar'] ?>" class="w-full h-full object-cover rounded-full">
            <?php else: ?>
              <?= strtoupper(substr($user['fullname'], 0, 1)) ?>
            <?php endif; ?>
          </div>
          <p class="text-sm text-gray-600">Ảnh đại diện hiện tại</p>

  <!-- Upload avatar mới -->
  <label class="block text-sm font-medium text-gray-700 mt-3">Thay đổi avatar:</label>
  <input type="file" name="avatar" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
</div>

        <!-- Vai trò -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-3">Vai trò</label>
          <div class="space-y-3">
            <label class="flex items-center gap-3 cursor-pointer">
              <input type="radio" name="roles" value="admin" <?= $user['roles'] == 'admin' ? 'checked' : '' ?> class="w-4 h-4 text-orange-600">
              <span class="text-gray-800">Admin</span>
              <span class="ml-auto inline-flex px-3 py-1 text-xs font-medium rounded-full bg-pink-100 text-pink-800">Quyền cao nhất</span>
            </label>
            <label class="flex items-center gap-3 cursor-pointer">
              <input type="radio" name="roles" value="guide" <?= $user['roles'] == 'guide' ? 'checked' : '' ?> class="w-4 h-4 text-orange-600">
              <span class="text-gray-800">Hướng dẫn viên</span>
              <span class="ml-auto inline-flex px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">Dẫn tour</span>
            </label>
          </div>
        </div>

        <!-- Trạng thái -->
        <div>
          <select name="status">
            <option value="1" <?= $user['status'] == 1 ? 'selected' : '' ?>>Hoạt động</option>
            <option value="0" <?= $user['status'] == 0 ? 'selected' : '' ?>>Tạm ngừng</option>
          </select>
        </div>
      </div>

      <!-- Cột phải: Thông tin chi tiết -->
      <div class="lg:col-span-2 space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Họ và tên <span class="text-red-500">*</span></label>
            <input type="text" name="fullname" value="<?= $user['fullname'] ?>" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
            <input type="email" name="email" value="<?= $user['email'] ?>" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Số điện thoại <span class="text-red-500">*</span></label>
            <input type="text" name="phone" value="<?= $user['phone'] ?>" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Mật khẩu mới <small class="text-gray-500">(để trống nếu không đổi)</small></label>
            <input type="password" name="password" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500" placeholder="••••••••">
          </div>
        </div>
              
        <!-- Thông tin bổ sung (tùy chọn mở rộng sau) -->
        <div class="border-t pt-6">
          <p class="text-sm text-gray-600 mb-4">Các thông tin khác ...</p>
        </div>

        <!-- Nút hành động -->
        <div class="flex justify-end gap-4 pt-8 border-t">
          <a href="?act=user" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors">
            Hủy bỏ
          </a>
          <button type="submit" class="px-8 py-2.5 bg-orange-500 hover:bg-orange-600 text-white font-medium rounded-lg transition-colors flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            Cập nhật nhân viên
          </button>
        </div>
      </div>
    </div>
  </form>
</main>

<?php require_once './views/components/footer.php'; ?>