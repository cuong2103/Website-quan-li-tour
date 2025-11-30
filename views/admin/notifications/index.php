<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';
?>

<div class=" pt-28 px-6 pb-8">
  <div class="mb-6 flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-bold text-gray-900">Quản lý Thông báo</h1>
      <p class="text-sm text-gray-500 mt-1">Tổng số: <?= $totalNotifications ?> thông báo</p>
    </div>
    <a href="<?= BASE_URL ?>?act=notification-create"
      class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 flex items-center gap-2">
      <i class="w-5 h-5" data-lucide="plus"></i>
      Tạo thông báo mới
    </a>
  </div>

  <div class="bg-white rounded-lg shadow">
    <div class="overflow-x-auto">
      <table class="w-full">
        <thead class="bg-gray-50 border-b border-gray-200">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tiêu đề</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Loại</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Người nhận</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Đã đọc</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ngày tạo</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hành động</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <?php if (empty($notifications)): ?>
            <tr>
              <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                Chưa có thông báo nào
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($notifications as $notification): ?>
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">
                  <div class="font-medium text-gray-900"><?= htmlspecialchars($notification['title']) ?></div>
                  <div class="text-sm text-gray-500 truncate max-w-md">
                    <?= htmlspecialchars(substr($notification['message'], 0, 100)) ?>...
                  </div>
                </td>
                <td class="px-6 py-4">
                  <?php
                  $typeColors = [
                    'general' => 'bg-gray-100 text-gray-800',
                    'booking' => 'bg-blue-100 text-blue-800',
                    'tour' => 'bg-green-100 text-green-800',
                    'payment' => 'bg-yellow-100 text-yellow-800',
                    'urgent' => 'bg-red-100 text-red-800'
                  ];
                  $typeLabels = [
                    'general' => 'Chung',
                    'booking' => 'Booking',
                    'tour' => 'Tour',
                    'payment' => 'Thanh toán',
                    'urgent' => 'Khẩn cấp'
                  ];
                  $colorClass = $typeColors[$notification['type']] ?? 'bg-gray-100 text-gray-800';
                  $label = $typeLabels[$notification['type']] ?? $notification['type'];
                  ?>
                  <span class="px-2 py-1 text-xs font-medium rounded-full <?= $colorClass ?>">
                    <?= $label ?>
                  </span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-900">
                  <?= $notification['total_recipients'] ?? 0 ?>
                </td>
                <td class="px-6 py-4 text-sm text-gray-900">
                  <?= $notification['read_count'] ?? 0 ?>
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">
                  <?= date('d/m/Y H:i', strtotime($notification['created_at'])) ?>
                </td>
                <td class="px-6 py-4 text-sm">
                  <div class="flex items-center gap-2">
                    <a href="<?= BASE_URL ?>?act=notification-detail&id=<?= $notification['id'] ?>"
                      class="text-indigo-600 hover:text-indigo-900">
                      <i class="w-5 h-5" data-lucide="eye"></i>
                    </a>
                    <a href="<?= BASE_URL ?>?act=notification-edit&id=<?= $notification['id'] ?>"
                      class="text-blue-600 hover:text-blue-900" title="Chỉnh sửa">
                      <i class="w-5 h-5" data-lucide="edit"></i>
                    </a>
                    <a href="<?= BASE_URL ?>?act=notification-delete&id=<?= $notification['id'] ?>"
                      onclick="return confirm('Bạn có chắc muốn xóa thông báo này?')"
                      class="text-red-600 hover:text-red-900">
                      <i class="w-5 h-5" data-lucide="trash-2"></i>
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
</div>

<?php require_once './views/components/footer.php'; ?>