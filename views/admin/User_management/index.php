<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';
?>

<main class="mt-28 p-6 min-h-screen bg-gray-50">
  <!-- Tiêu đề + nút thêm -->
  <div class="flex justify-between items-center mb-8">
    <div>
      <h1 class="text-2xl font-bold text-gray-900">Quản Lý Người Dùng</h1>
      <p class="text-sm text-gray-600 mt-1">Danh sách nhân viên và phân quyền</p>
    </div>
<!-- nút thêm -->
    <a href="?act=user-create" 
       class="inline-flex items-center gap-2 bg-orange-500 hover:bg-orange-600 text-white font-medium px-5 py-2.5 rounded-lg transition-colors shadow-sm">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
      </svg>
      Thêm nhân viên
    </a>
  </div>

  <!-- Card bảng danh sách -->
  <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="p-6 border-b border-gray-200 flex justify-between items-center">
      <h2 class="text-lg font-semibold text-gray-800">Danh sách nhân viên (<?= count($users) ?>)</h2>
      <!-- Bạn có thể thêm ô tìm kiếm ở đây sau nếu muốn -->
    </div>

    <div class="overflow-x-auto">
      <table class="w-full">
        <thead class="bg-gray-50 border-b border-gray-200">
          <tr>
            <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-6 py-4">Nhân viên</th>
            <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-6 py-4">Email</th>
            <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-6 py-4">Số điện thoại</th>
            <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-6 py-4">Vai trò</th>
            <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-6 py-4">Trạng thái</th>
            <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-6 py-4">Hành động</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <?php if(empty($users)): ?>
            <tr>
              <td colspan="6" class="text-center py-10 text-gray-500">Chưa có nhân viên nào</td>
            </tr>
          <?php else: ?>
            <?php foreach($users as $user): ?>
              <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-5 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0 overflow-hidden">
                      <?php if(!empty($user['avatar'])): ?>
                        <img src="<?= UPLOADS_URL ?>avatar/<?= $user['avatar'] ?>" alt="Avatar" class="w-full h-full object-cover">
                      <?php else: ?>
                        <span class="text-blue-600 font-semibold text-lg">
                          <?= strtoupper(substr($user["fullname"], 0, 1)) ?>
                        </span>
                      <?php endif; ?>
                    </div>
                    <span class="ml-3 font-medium text-gray-900"><?= htmlspecialchars($user["fullname"]) ?></span>
                  </div>
                </td>
                <td class="px-6 py-5 text-gray-700"><?= htmlspecialchars($user["email"]) ?></td>
                <td class="px-6 py-5 text-gray-700"><?= htmlspecialchars($user["phone"]) ?></td>
                <td class="px-6 py-5">
                  <?php if($user["role_id"] == 1): ?>
                    <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-pink-100 text-pink-800">
                      Admin
                    </span>
                  <?php else: ?>
                    <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                      Hướng dẫn viên
                    </span>
                  <?php endif; ?>
                </td>
                <td class="px-6 py-5">
                  <?php 
                  echo $user['status'] == 1 ? '<span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                    Hoạt động
                  </span>' : '<span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">
                    Tạm dừng
                  </span>'; 
                  ?>
                </td>
                <td class="px-6 py-5">
                  <div class="flex items-center gap-4">
                    <!-- Xem chi tiết -->
                    <a href="?act=user-getById&id=<?= $user['id'] ?>" 
                       class="text-gray-500 hover:text-blue-600 transition-colors" 
                       title="Xem chi tiết">
                      <i data-lucide="eye" class="w-5 h-5"></i>
                    </a>
                    <!-- Sửa -->
                    <a href="?act=user-edit&id=<?= $user['id'] ?>" 
                       class="text-gray-500 hover:text-yellow-600 transition-colors" 
                       title="Chỉnh sửa">
                      <i data-lucide="square-pen" class="w-5 h-5"></i>
                    </a>
                    <!-- Xóa (có thể thêm confirm sau) -->
                    <a href="?act=user-delete&id=<?= $user['id'] ?>" 
                       class="text-gray-500 hover:text-red-600 transition-colors"
                       onclick="return confirm('Bạn có chắc muốn xóa nhân viên này?')"
                       title="Xóa">
                      <i data-lucide="trash-2" class="w-5 h-5"></i>
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

<?php require_once './views/components/footer.php'; ?>