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
      <span class="text-gray-900 font-medium">Chi tiết nhân viên</span>
    </div>
    <h1 class="text-3xl font-medium text-gray-900">Chi tiết nhân viên</h1>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    <!-- Left Column - Avatar & Info -->
    <div class="bg-white rounded-lg border border-gray-200 p-8">
      <div class="flex items-center gap-6 mb-8">
        <!-- Avatar lớn -->
        <div class="w-40 h-40 rounded-lg bg-blue-100 flex items-center justify-center overflow-hidden flex-shrink-0">
          <?php if (!empty($user['avatar'])): ?>
            <img src="/uploads/avatar/<?= $user['avatar'] ?>" alt="Avatar" class="w-full h-full object-cover">
          <?php else: ?>
            <span class="text-6xl font-bold text-blue-600"><?= strtoupper(substr($user['fullname'], 0, 1)) ?></span>
          <?php endif; ?>
        </div>

        <!-- Info bên cạnh avatar -->
        <div class="flex-1">
          <h2 class="text-2xl font-bold text-gray-900 mb-2"><?= htmlspecialchars($user["fullname"]) ?></h2>

          <!-- Role Badge -->
          <div class="mb-3">
            <?php if ($user["roles"] == 'admin'): ?>
              <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium rounded-lg bg-pink-100 text-pink-800">
                <i data-lucide="shield-check" class="w-4 h-4"></i>
                Admin
              </span>
            <?php else: ?>
              <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium rounded-lg bg-blue-100 text-blue-800">
                <i data-lucide="user" class="w-4 h-4"></i>
                Hướng dẫn viên
              </span>
            <?php endif; ?>
          </div>

          <!-- Status Badge -->
          <div>
            <?php if ($user['status'] == 1): ?>
              <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium rounded-lg bg-green-100 text-green-800">
                <i data-lucide="check-circle" class="w-4 h-4"></i>
                Đang hoạt động
              </span>
            <?php else: ?>
              <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium rounded-lg bg-gray-100 text-gray-800">
                <i data-lucide="x-circle" class="w-4 h-4"></i>
                Tạm dừng
              </span>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- Thông tin cá nhân theo hàng ngang -->
      <div class="border-t border-gray-200 pt-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
          <i data-lucide="info" class="w-5 h-5 text-orange-500"></i>
          Thông tin cá nhân
        </h3>

        <div class="space-y-4">
          <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
            <i data-lucide="user" class="w-5 h-5 text-gray-400"></i>
            <div class="flex-1">
              <label class="block text-sm font-medium text-gray-500">Họ và tên</label>
              <p class="text-gray-900 font-semibold"><?= htmlspecialchars($user["fullname"]) ?></p>
            </div>
          </div>

          <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
            <i data-lucide="mail" class="w-5 h-5 text-gray-400"></i>
            <div class="flex-1">
              <label class="block text-sm font-medium text-gray-500">Email</label>
              <p class="text-gray-900 font-semibold"><?= htmlspecialchars($user["email"]) ?></p>
            </div>
          </div>

          <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
            <i data-lucide="phone" class="w-5 h-5 text-gray-400"></i>
            <div class="flex-1">
              <label class="block text-sm font-medium text-gray-500">Số điện thoại</label>
              <p class="text-gray-900 font-semibold"><?= htmlspecialchars($user["phone"]) ?></p>
            </div>
          </div>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="flex gap-3 mt-6 pt-6 border-t border-gray-200">
        <a href="?act=user-edit&id=<?= $user['id'] ?>"
          class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-orange-500 hover:bg-orange-600 text-white font-medium rounded-lg transition-colors">
          <i data-lucide="pencil" class="w-4 h-4"></i>
          Chỉnh sửa
        </a>

        <button onclick="openLeaveModal()"
          class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-lg transition-colors">
          <i data-lucide="calendar" class="w-4 h-4"></i>
          Nghỉ phép
        </button>
      </div>
    </div>

    <!-- Right Column - Leave Info -->
    <div class="space-y-6">
      <?php if (!empty($user['leave_start']) || !empty($user['leave_end'])): ?>
        <div class="bg-blue-50 rounded-lg border border-blue-200 p-6">
          <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <i data-lucide="calendar" class="w-5 h-5 text-blue-600"></i>
            Thông tin nghỉ phép
          </h3>

          <div class="space-y-4">
            <div class="flex items-center gap-3 p-3 bg-white rounded-lg">
              <i data-lucide="calendar-days" class="w-5 h-5 text-blue-600"></i>
              <div class="flex-1">
                <label class="block text-sm font-medium text-gray-600">Ngày bắt đầu</label>
                <p class="text-blue-600 font-bold text-lg">
                  <?= !empty($user["leave_start"]) ? date('d/m/Y', strtotime($user["leave_start"])) : '---' ?>
                </p>
              </div>
            </div>

            <div class="flex items-center gap-3 p-3 bg-white rounded-lg">
              <i data-lucide="calendar-check" class="w-5 h-5 text-blue-600"></i>
              <div class="flex-1">
                <label class="block text-sm font-medium text-gray-600">Ngày kết thúc</label>
                <p class="text-blue-600 font-bold text-lg">
                  <?= !empty($user["leave_end"]) ? date('d/m/Y', strtotime($user["leave_end"])) : '---' ?>
                </p>
              </div>
            </div>
          </div>
        </div>
      <?php else: ?>
        <div class="bg-gray-50 rounded-lg border border-gray-200 p-8 text-center">
          <i data-lucide="calendar-x" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
          <p class="text-gray-500 font-medium">Chưa có thông tin nghỉ phép</p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</main>

<!-- Modal -->
<div id="leaveModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
  <div class="bg-white rounded-lg max-w-md w-full p-6">
    <div class="flex items-center justify-between mb-4">
      <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
        <i data-lucide="calendar" class="w-5 h-5 text-blue-600"></i>
        Đăng ký nghỉ phép
      </h3>
      <button onclick="closeLeaveModal()" class="text-gray-400 hover:text-gray-600">
        <i data-lucide="x" class="w-5 h-5"></i>
      </button>
    </div>

    <form action="?act=user-update-leave" method="POST">
      <input type="hidden" name="id" value="<?= $user['id'] ?>">

      <div class="space-y-4">
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">
            Ngày bắt đầu nghỉ <span class="text-red-500">*</span>
          </label>
          <input type="date" name="leave_start" value="<?= $user['leave_start'] ?? '' ?>"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">
            Ngày kết thúc nghỉ <span class="text-red-500">*</span>
          </label>
          <input type="date" name="leave_end" value="<?= $user['leave_end'] ?? '' ?>"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
          <p class="text-sm text-yellow-800">
            <strong>Lưu ý:</strong> Để xóa thông tin nghỉ phép, hãy để trống cả 2 trường và nhấn Lưu.
          </p>
        </div>
      </div>

      <div class="flex gap-3 mt-6">
        <button type="button" onclick="closeLeaveModal()"
          class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50">
          Hủy
        </button>
        <button type="submit"
          class="flex-1 px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-lg">
          Lưu
        </button>
      </div>
    </form>
  </div>
</div>

<script>
  function openLeaveModal() {
    document.getElementById('leaveModal').classList.remove('hidden');
  }

  function closeLeaveModal() {
    document.getElementById('leaveModal').classList.add('hidden');
  }

  document.getElementById('leaveModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeLeaveModal();
  });
</script>

<?php require_once './views/components/footer.php'; ?>