<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';
?>

<main class="mt-24 p-6 min-h-screen bg-gray-50 font-sans">
  <!-- Header -->
  <div class="mb-8">
    <div class="flex justify-between items-center">
      <div>
        <h1 class="text-3xl font-bold text-gray-900">Quản lý nhân viên</h1>
        <p class="text-gray-600 mt-2">Quản lý danh sách nhân viên và phân quyền hệ thống</p>
      </div>
      <a href="?act=user-create"
        class="inline-flex items-center gap-2 bg-orange-500 hover:bg-orange-600 text-white font-medium px-4 py-2.5 rounded-lg transition-colors">
        <i data-lucide="plus" class="w-5 h-4"></i>
        Thêm nhân viên
      </a>
    </div>
  </div>

  <!-- Stats Cards -->
  <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <?php
    $totalUsers = count($users);
    $admins = count(array_filter($users, fn($u) => $u['roles'] == 'admin'));
    $guides = count(array_filter($users, fn($u) => $u['roles'] == 'guide'));
    $active = count(array_filter($users, fn($u) => $u['status'] == 1));
    ?>

    <div class="bg-white rounded-lg p-6 border border-gray-200">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-gray-500 text-sm">Tổng nhân viên</p>
          <p class="text-3xl font-bold text-gray-900 mt-2"><?= $totalUsers ?></p>
        </div>
        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
          <i data-lucide="users" class="w-6 h-6 text-blue-600"></i>
        </div>
      </div>
    </div>

    <div class="bg-white rounded-lg p-6 border border-gray-200">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-gray-500 text-sm">Admin</p>
          <p class="text-3xl font-bold text-pink-600 mt-2"><?= $admins ?></p>
        </div>
        <div class="w-12 h-12 bg-pink-100 rounded-lg flex items-center justify-center">
          <i data-lucide="shield-check" class="w-6 h-6 text-pink-600"></i>
        </div>
      </div>
    </div>

    <div class="bg-white rounded-lg p-6 border border-gray-200">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-gray-500 text-sm">Hướng dẫn viên</p>
          <p class="text-3xl font-bold text-blue-600 mt-2"><?= $guides ?></p>
        </div>
        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
          <i data-lucide="user" class="w-6 h-6 text-blue-600"></i>
        </div>
      </div>
    </div>

    <div class="bg-white rounded-lg p-6 border border-gray-200">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-gray-500 text-sm">Đang hoạt động</p>
          <p class="text-3xl font-bold text-green-600 mt-2"><?= $active ?></p>
        </div>
        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
          <i data-lucide="check-circle" class="w-6 h-6 text-green-600"></i>
        </div>
      </div>
    </div>
  </div>

  <!-- Search & Filter -->
  <div class="bg-white rounded-lg border border-gray-200 p-4 mb-6">
    <form id="searchForm" method="GET" action="" class="flex flex-col md:flex-row gap-4">
      <input type="hidden" name="act" value="user">

      <!-- Search Box -->
      <div class="flex-1">
        <div class="relative">
          <i data-lucide="search" class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
          <input type="text" id="searchInput" name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
            placeholder="Tìm theo tên, email, số điện thoại..."
            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
            autocomplete="off">
        </div>
      </div>

      <!-- Role Filter -->
      <div class="w-full md:w-48">
        <select id="roleSelect" name="role" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
          <option value="">Tất cả vai trò</option>
          <option value="admin" <?= ($_GET['role'] ?? '') == 'admin' ? 'selected' : '' ?>>Admin</option>
          <option value="guide" <?= ($_GET['role'] ?? '') == 'guide' ? 'selected' : '' ?>>Hướng dẫn viên</option>
        </select>
      </div>
    </form>
  </div>

  <!-- Table -->
  <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
    <div class="p-6 border-b border-gray-200">
      <h2 class="text-x font-medium text-gray-900">Danh sách nhân viên (<?= count($users) ?>)</h2>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full">
        <thead class="bg-gray-50">
          <tr>
            <th class="text-left text-xs font-semibold text-gray-600 uppercase px-6 py-4">Nhân viên</th>
            <th class="text-left text-xs font-semibold text-gray-600 uppercase px-6 py-4">Liên hệ</th>
            <th class="text-left text-xs font-semibold text-gray-600 uppercase px-6 py-4">Vai trò</th>
            <th class="text-left text-xs font-semibold text-gray-600 uppercase px-6 py-4">Trạng thái</th>
            <th class="text-center text-xs font-semibold text-gray-600 uppercase px-6 py-4">Hành động</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <?php if (empty($users)): ?>
            <tr>
              <td colspan="5" class="text-center py-12">
                <i data-lucide="users" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
                <p class="text-gray-500">Chưa có nhân viên nào</p>
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($users as $user): ?>
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">
                  <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center overflow-hidden">
                      <?php if (!empty($user['avatar'])): ?>
                        <img src="<?= UPLOADS_URL ?>avatar/<?= $user['avatar'] ?>" alt="Avatar" class="w-full h-full object-cover">
                      <?php else: ?>
                        <span class="text-blue-600 font-bold"><?= strtoupper(substr($user["fullname"], 0, 1)) ?></span>
                      <?php endif; ?>
                    </div>
                    <div>
                      <p class="font-semibold text-gray-900"><?= htmlspecialchars($user["fullname"]) ?></p>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="space-y-1">
                    <p class="text-gray-900 text-sm"><?= htmlspecialchars($user["email"]) ?></p>
                    <p class="text-gray-600 text-sm"><?= htmlspecialchars($user["phone"]) ?></p>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <?php if ($user["roles"] == 'admin'): ?>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-medium rounded-full bg-pink-100 text-pink-800">
                      <i data-lucide="shield-check" class="w-3 h-3"></i>
                      Admin
                    </span>
                  <?php else: ?>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                      <i data-lucide="user" class="w-3 h-3"></i>
                      Hướng dẫn viên
                    </span>
                  <?php endif; ?>
                </td>
                <td class="px-6 py-4">
                  <?php if ($user['status'] == 1): ?>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                      <i data-lucide="check-circle" class="w-3 h-3"></i>
                      Hoạt động
                    </span>
                  <?php else: ?>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">
                      <i data-lucide="x-circle" class="w-3 h-3"></i>
                      Tạm dừng
                    </span>
                  <?php endif; ?>
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center justify-center gap-2">
                    <a href="?act=user-detail&id=<?= $user['id'] ?>"
                      class="p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                      title="Xem chi tiết">
                      <i data-lucide="eye" class="w-4 h-4"></i>
                    </a>
                    <!-- Sửa -->
                    <a href="?act=user-edit&id=<?= $user['id'] ?>&from=index" 
                       class="text-gray-500 hover:text-yellow-600 transition-colors" 
                       title="Chỉnh sửa">
                      <i data-lucide="square-pen" class="w-5 h-5"></i>
                    </a>
                    <a href="?act=user-delete&id=<?= $user['id'] ?>"
                      class="p-2 text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                      onclick="return confirm('Bạn có chắc muốn xóa <?= htmlspecialchars($user['fullname']) ?>?')"
                      title="Xóa">
                      <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </a>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</main>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const roleSelect = document.getElementById('roleSelect');
    const form = document.getElementById('searchForm');
    let timeout = null;

    // Auto focus vào ô search nếu đang có giá trị search (sau khi reload)
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('search')) {
      searchInput.focus();
      // Đưa con trỏ về cuối dòng
      const val = searchInput.value;
      searchInput.value = '';
      searchInput.value = val;
    }

    // Debounce search input (0.5s)
    searchInput.addEventListener('input', function() {
      clearTimeout(timeout);
      timeout = setTimeout(function() {
        form.submit();
      }, 500);
    });

    // Auto submit khi chọn role
    roleSelect.addEventListener('change', function() {
      form.submit();
    });
  });
</script>

<?php require_once './views/components/footer.php'; ?>