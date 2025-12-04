<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';

?>

<main class="flex-1 p-6 p-8 overflow-y-auto bg-gray-50">
  <div class="max-w-5xl mx-auto">
    <h2 class="text-2xl font-bold text-gray-900 mb-2">Tài khoản của tôi</h2>
    <p class="text-gray-600 mb-8">Quản lý thông tin cá nhân và bảo mật</p>
<div class="grid grid-cols-2 gap-8">
  <div class="lg:col-span-2 space-y-8">
    <!-- Thông tin cá nhân -->
    <div class="bg-white rounded-2xl shadow-sm p-6 lg:p-8">
      <h3 class="text-lg font-semibold mb-6">Thông tin cá nhân</h3>

      <div class="flex flex-col md:flex-row gap-8">
        <!-- Avatar -->
        <div class="relative shrink-0">
          <div class="w-32 h-32 bg-gray-200 rounded-full border-4 border-white shadow-lg flex items-center justify-center overflow-hidden">
            <?php if (!empty($user['avatar'])): ?>
              <img src="/uploads/avatar/<?= $user['avatar'] ?>" alt="Avatar" class="w-full h-full object-cover">
            <?php else: ?>
              <i class="fas fa-user text-5xl text-gray-400"></i>
            <?php endif; ?>
          </div>
          <!-- <button class="absolute bottom-1 right-1 bg-blue-600 hover:bg-blue-700 text-white rounded-full p-2 shadow-lg transition">
            <i class="fas fa-camera text-sm"></i>
          </button> -->
          <p class="text-xs text-gray-500 text-center mt-3 leading-tight">
            Tải ảnh lên<br>JPG, PNG tối đa 5MB
          </p>
        </div>

        <!-- Form thông tin -->
        <form action="/update-profile" method="POST" class="flex-1 space-y-5">
          <input type="hidden" name="source" value="profile">
          <input type="hidden" name="id" value="<?= $user['id'] ?>">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Họ và tên</label>
            <input type="text" name="fullname" value="<?= htmlspecialchars($user['fullname'] ?? '') ?>" disabled
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" disabled
              class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 cursor-not-allowed">
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Số điện thoại</label>
            <input type="tel" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" disabled
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
          </div>
          <br>
            <a href="?act=profile-edit&id=<?= $user['id'] ?>&from=profile" class="bg-orange-500 hover:bg-orange-600 text-white font-medium px-8 py-3 rounded-lg transition shadow">Sửa hồ sơ</a>
        </form>
      </div>
    </div>

    <!-- Đổi mật khẩu -->
    <div class="bg-white rounded-2xl shadow-sm p-6 lg:p-8">
      <h3 class="text-lg font-semibold mb-6">Đổi mật khẩu</h3>
      <form action="/index.php?act=change-password&id=<?= $user['id'] ?>"   method="POST" class="max-w-md space-y-5">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Mật khẩu hiện tại</label>
          <input type="password" name="current_password" placeholder="Nhập mật khẩu hiện tại"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" required>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Mật khẩu mới</label>
          <input type="password" name="new_password" placeholder="Nhập mật khẩu mới"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" required>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Xác nhận mật khẩu mới</label>
          <input type="password" name="confirm_password" placeholder="Nhập lại mật khẩu mới"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" required>
        </div>

        <button type="submit"
          class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-8 py-3 rounded-lg transition">
          Đổi mật khẩu
        </button>
        <!-- <a href="/forgot-password" class="block mt-3 text-blue-600 font-medium hover:underline">Quên mật khẩu?</a> -->
      </form>
    </div>
  </div>
</div>

  </div>
</main>

<?php
require_once './views/components/footer.php';
?>
