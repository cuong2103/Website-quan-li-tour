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
      <span class="text-gray-900 font-medium">Chỉnh sửa nhân viên</span>
    </div>
    <h1 class="text-3xl font-medium text-gray-900">Chỉnh sửa nhân viên</h1>
  </div>



  <?php
  $isOnLeave = false;
  if (!empty($user['leave_start']) && !empty($user['leave_end'])) {
    $today = new DateTime();
    $today->setTime(0, 0, 0);
    $start = new DateTime($user['leave_start']);
    $start->setTime(0, 0, 0);
    $end = new DateTime($user['leave_end']);
    $end->setTime(0, 0, 0);

    if ($today >= $start && $today <= $end) {
      $isOnLeave = true;
    }
  }
  ?>

  <?php if ($isOnLeave): ?>
    <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-400 p-4">
      <div class="flex">
        <div class="flex-shrink-0">
          <i data-lucide="alert-circle" class="h-5 w-5 text-yellow-400"></i>
        </div>
        <div class="ml-3">
          <p class="text-sm text-yellow-700">
            Nhân viên này đang trong thời gian nghỉ phép (<?= date('d/m/Y', strtotime($user['leave_start'])) ?> - <?= date('d/m/Y', strtotime($user['leave_end'])) ?>).
            <br>Trạng thái sẽ bị khóa ở "Tạm ngừng" cho đến khi hết hạn nghỉ phép.
          </p>
        </div>
      </div>
    </div>
  <?php endif; ?>

  <form action="?act=user-update" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $user['id'] ?>">

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
            <div class="mx-auto w-32 h-32 rounded-lg bg-blue-100 flex items-center justify-center mb-4 overflow-hidden">
              <?php if (!empty($user['avatar'])): ?>
                <img src="/uploads/avatar/<?= $user['avatar'] ?>" class="w-full h-full object-cover" id="current-avatar">
              <?php else: ?>
                <span class="text-4xl font-bold text-blue-600" id="avatar-letter"><?= strtoupper(substr($user['fullname'], 0, 1)) ?></span>
              <?php endif; ?>
            </div>
            <p class="text-sm text-gray-600 mb-4">Ảnh hiện tại</p>

            <input type="file" name="avatar" id="avatar-input" class="hidden" accept="image/*" onchange="previewAvatar(this)">
            <label for="avatar-input" class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white font-medium rounded-lg transition-colors">
              <i data-lucide="upload" class="w-4 h-4"></i>
              Thay đổi ảnh
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
              <input type="radio" name="roles" value="admin" <?= $user['roles'] == 'admin' ? 'checked' : '' ?> class="w-4 h-4 text-pink-600">
              <span class="ml-3 flex items-center gap-2">
                <i data-lucide="shield-check" class="w-4 h-4"></i>
                <span class="font-medium">Admin</span>
              </span>
            </label>

            <label class="flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-300 has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
              <input type="radio" name="roles" value="guide" <?= $user['roles'] == 'guide' ? 'checked' : '' ?> class="w-4 h-4 text-blue-600">
              <span class="ml-3 flex items-center gap-2">
                <i data-lucide="user" class="w-4 h-4"></i>
                <span class="font-medium">Hướng dẫn viên</span>
              </span>
            </label>
          </div>

          <div class="mt-4">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Trạng thái</label>
            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500" <?= $isOnLeave ? 'disabled' : '' ?>>
              <option value="1" <?= $user['status'] == 1 ? 'selected' : '' ?>>Hoạt động</option>
              <option value="0" <?= $user['status'] == 0 ? 'selected' : '' ?>>Tạm ngừng</option>
            </select>
            <?php if ($isOnLeave): ?>
              <input type="hidden" name="status" value="0">
            <?php endif; ?>
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
              <input type="text" name="fullname" value="<?= htmlspecialchars($user['fullname']) ?>" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">
                Email <span class="text-red-500">*</span>
              </label>
              <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">
                Số điện thoại <span class="text-red-500">*</span>
              </label>
              <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-2">
                Mật khẩu mới <small class="text-gray-500 font-normal">(để trống nếu không đổi)</small>
              </label>
              <input type="password" name="password"
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
              Cập nhật
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
        const currentAvatar = document.getElementById('current-avatar');
        const avatarLetter = document.getElementById('avatar-letter');

        if (currentAvatar) {
          currentAvatar.src = e.target.result;
        } else if (avatarLetter) {
          avatarLetter.parentElement.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover" id="current-avatar">`;
        }
      }
      reader.readAsDataURL(input.files[0]);
    }
  }
</script>

<?php require_once './views/components/footer.php'; ?>