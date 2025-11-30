<?php require_once './views/components/header.php'; ?>
<?php require_once './views/components/sidebar.php'; ?>

<div class="pt-28 px-6 pb-8">
  <div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Tạo thông báo mới</h1>
    <p class="text-sm text-gray-500 mt-1">Gửi thông báo đến nhân viên</p>
  </div>

  <form method="POST" action="<?= BASE_URL ?>?act=notification-store" class="space-y-6">
    <div class="bg-white rounded-lg shadow p-6 space-y-6">
      <!-- Tiêu đề -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
          Tiêu đề <span class="text-red-500">*</span>
        </label>
        <input type="text" name="title" value="<?= htmlspecialchars($title ?? '') ?>"
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
          placeholder="Nhập tiêu đề thông báo">
        <?php if (isset($errors['title'])): ?>
          <p class="text-red-500 text-sm mt-1"><?= $errors['title'][0] ?></p>
        <?php endif; ?>
      </div>

      <!-- Nội dung -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
          Nội dung <span class="text-red-500">*</span>
        </label>
        <textarea name="message" rows="6"
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
          placeholder="Nhập nội dung thông báo"><?= htmlspecialchars($message ?? '') ?></textarea>
        <?php if (isset($errors['message'])): ?>
          <p class="text-red-500 text-sm mt-1"><?= $errors['message'] ?></p>
        <?php endif; ?>
      </div>

      <!-- Loại thông báo -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Loại thông báo</label>
        <select name="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
          <option value="general">Chung</option>
          <option value="booking">Booking</option>
          <option value="tour">Tour</option>
          <option value="payment">Thanh toán</option>
          <option value="urgent">Khẩn cấp</option>
        </select>
      </div>

      <!-- Người nhận -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-3">
          Người nhận <span class="text-red-500">*</span>
        </label>

        <div class="space-y-3">
          <!-- Tất cả nhân viên -->
          <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
            <input type="radio" name="recipient_type" value="all" class="w-4 h-4 text-indigo-600" checked
              onchange="toggleRecipientOptions(this.value)">
            <span class="ml-3 text-sm font-medium text-gray-700">Tất cả nhân viên</span>
          </label>

          <!-- Theo vai trò -->
          <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
            <input type="radio" name="recipient_type" value="role" class="w-4 h-4 text-indigo-600"
              onchange="toggleRecipientOptions(this.value)">
            <span class="ml-3 text-sm font-medium text-gray-700">Theo vai trò</span>
          </label>
          <div id="role-select" class="ml-7 hidden">
            <select name="role" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
              <option value="admin">Admin</option>
              <option value="guide">Hướng dẫn viên</option>
            </select>
          </div>

          <!-- Chọn cụ thể -->
          <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
            <input type="radio" name="recipient_type" value="specific" class="w-4 h-4 text-indigo-600"
              onchange="toggleRecipientOptions(this.value)">
            <span class="ml-3 text-sm font-medium text-gray-700">Chọn người cụ thể</span>
          </label>
          <div id="user-select" class="ml-7 hidden">
            <div class="border border-gray-300 rounded-lg p-4 max-h-60 overflow-y-auto space-y-2">
              <?php
              $groupedUsers = [];
              foreach ($users as $user) {
                $groupedUsers[$user['roles']][] = $user;
              }
              ?>
              <?php foreach ($groupedUsers as $role => $roleUsers): ?>
                <div class="mb-3">
                  <div class="text-xs font-semibold text-gray-500 uppercase mb-2">
                    <?= $role === 'admin' ? 'Admin' : 'Hướng dẫn viên' ?>
                  </div>
                  <?php foreach ($roleUsers as $user): ?>
                    <label class="flex items-center py-2 px-2 hover:bg-gray-50 rounded cursor-pointer">
                      <input type="checkbox" name="selected_users[]" value="<?= $user['id'] ?>"
                        class="w-4 h-4 text-indigo-600 rounded">
                      <span class="ml-3 text-sm text-gray-700"><?= htmlspecialchars($user['fullname']) ?></span>
                      <span class="ml-2 text-xs text-gray-500">(<?= htmlspecialchars($user['email']) ?>)</span>
                    </label>
                  <?php endforeach; ?>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </div>

      <!-- Buttons -->
      <div class="flex items-center gap-3 pt-4">
        <button type="submit"
          class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 flex items-center gap-2">
          <i class="w-5 h-5" data-lucide="send"></i>
          Gửi thông báo
        </button>
        <a href="<?= BASE_URL ?>?act=notifications"
          class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
          Hủy
        </a>
      </div>
    </div>
  </form>
</div>

<script>
  function toggleRecipientOptions(type) {
    document.getElementById('role-select').classList.add('hidden');
    document.getElementById('user-select').classList.add('hidden');

    if (type === 'role') {
      document.getElementById('role-select').classList.remove('hidden');
    } else if (type === 'specific') {
      document.getElementById('user-select').classList.remove('hidden');
    }
  }
</script>

<?php require_once './views/components/footer.php'; ?>