<?php

$today = date('Y-m-d');
$canCheckinNow = ($today >= $assignment['start_date'] && $today <= $assignment['end_date']);

?>

<div class="bg-white border shadow rounded-xl p-5">
  <div class="flex justify-between items-center mb-4">
    <h3 class="font-semibold text-lg">Quản lý Check-in</h3>
    <div class="flex gap-2">
      <?php if ($canCheckinNow): ?>
        <button onclick="document.getElementById('createCheckinModal').classList.remove('hidden')"
          class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 flex items-center gap-2">
          <i data-lucide="plus" class="w-4 h-4"></i>
          Tạo đợt check-in mới
        </button>
      <?php else: ?>
        <button disabled class="bg-gray-300 text-gray-500 px-4 py-2 rounded-lg text-sm cursor-not-allowed flex items-center gap-2"
          title="Không thể tạo check-in vào lúc này">
          <i data-lucide="plus" class="w-4 h-4"></i>
          Tạo đợt check-in mới
        </button>
      <?php endif; ?>

    </div>
  </div>

  <?php if (!$canCheckinNow): ?>
    <div class="mb-4 p-4 rounded-lg <?= $today < $assignment['start_date'] ? 'bg-yellow-50 border border-yellow-200' : 'bg-gray-50 border border-gray-200' ?>">
      <div class="flex items-center gap-2">
        <i data-lucide="<?= $today < $assignment['start_date'] ? 'clock' : 'check-circle' ?>" class="w-5 h-5 <?= $today < $assignment['start_date'] ? 'text-yellow-600' : 'text-gray-600' ?>"></i>
        <span class="font-medium <?= $today < $assignment['start_date'] ? 'text-yellow-800' : 'text-gray-700' ?>">
          <?php if ($today < $assignment['start_date']): ?>
            Chưa đến thời gian khởi hành! Tour bắt đầu từ <?= date('d/m/Y', strtotime($assignment['start_date'])) ?>
          <?php else: ?>
            Tour đã kết thúc từ ngày <?= date('d/m/Y', strtotime($assignment['end_date'])) ?>
          <?php endif; ?>
        </span>
      </div>
    </div>
  <?php endif; ?>

  <table class="w-full text-sm">
    <thead class="bg-gray-100">
      <tr>
        <th class="p-3 text-left">STT</th>
        <th class="p-3 text-left">Tiêu đề</th>
        <th class="p-3 text-left">Ghi chú</th>
        <th class="p-3 text-left">Thời gian tạo</th>
        <th class="p-3 text-left">Người tạo</th>
        <th class="p-3 text-left">Hành động</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($checkinLinks)): ?>
        <?php foreach ($checkinLinks as $i => $link): ?>
          <tr class="border-t hover:bg-gray-50">
            <td class="p-3"><?= $i + 1 ?></td>
            <td class="p-3 font-medium"><?= htmlspecialchars($link['title']) ?></td>
            <td class="p-3 text-gray-600"><?= htmlspecialchars($link['note'] ?? '-') ?></td>
            <td class="p-3 text-gray-600"><?= date('H:i d/m/Y', strtotime($link['created_at'])) ?></td>
            <td class="p-3 text-gray-600">
              <?php
              // Lấy tên người tạo nếu có
              if ($link['created_by']) {
                $userSql = "SELECT fullname FROM users WHERE id = ?";
                $userStmt = connectDB()->prepare($userSql);
                $userStmt->execute([$link['created_by']]);
                $userName = $userStmt->fetchColumn();
                echo htmlspecialchars($userName ?? 'N/A');
              } else {
                echo 'N/A';
              }
              ?>
            </td>
            <td class="p-3">
              <div class="flex gap-2">
                <a href="<?= BASE_URL . '?act=guide-checkin-detail&link_id=' . $link['id'] . '&assignment_id=' . $assignment['tour_id'] ?>"
                  class="text-blue-600 hover:bg-blue-50 px-3 py-1 rounded text-xs font-medium flex items-center gap-1"
                  title="Xem chi tiết & Check-in">
                  <i data-lucide="users" class="w-4 h-4"></i>
                  Chi tiết
                </a>
                <form action="<?= BASE_URL . '?act=guide-checkin-delete' ?>" method="POST"
                  onsubmit="return confirm('Bạn có chắc muốn xóa đợt check-in này?')">
                  <input type="hidden" name="link_id" value="<?= $link['id'] ?>">
                  <input type="hidden" name="assignment_id" value="<?= $assignment['tour_id'] ?>">
                  <button type="submit" class="text-red-600 hover:bg-red-50 px-3 py-1 rounded text-xs font-medium"
                    title="Xóa">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                  </button>
                </form>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="6" class="p-8 text-center text-gray-500">
            <div class="flex flex-col items-center gap-2">
              <i data-lucide="clipboard-list" class="w-12 h-12 text-gray-300"></i>
              <p>Chưa có đợt check-in nào</p>
              <p class="text-xs">Tạo đợt check-in mới để bắt đầu điểm danh khách hàng</p>
            </div>
          </td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<!-- Modal tạo đợt check-in mới -->
<div id="createCheckinModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
  <div class="bg-white rounded-xl p-6 w-full max-w-md mx-4">
    <h3 class="text-lg font-semibold mb-4">Tạo đợt check-in mới</h3>
    <form action="<?= BASE_URL . '?act=guide-checkin-create' ?>" method="POST">
      <input type="hidden" name="assignment_id" value="<?= $assignment['tour_id'] ?>">

      <div class="mb-4">
        <label class="block text-sm font-medium mb-2">Tiêu đề <span class="text-red-500">*</span></label>
        <input type="text" name="title" required
          placeholder="VD: Check-in sáng ngày 1, Check-in khách sạn, v.v..."
          class="w-full border rounded-lg p-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
      </div>

      <div class="mb-4">
        <label class="block text-sm font-medium mb-2">Ghi chú</label>
        <textarea name="note" rows="3"
          placeholder="Ghi chú thêm về đợt check-in này (không bắt buộc)"
          class="w-full border rounded-lg p-2 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"></textarea>
      </div>

      <div class="flex gap-3 justify-end">
        <button type="button" onclick="document.getElementById('createCheckinModal').classList.add('hidden')"
          class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg text-sm">
          Hủy
        </button>
        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm">
          Tạo đợt check-in
        </button>
      </div>
    </form>
  </div>
</div>