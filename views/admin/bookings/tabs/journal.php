<div class="bg-white border shadow rounded-xl p-5">
  <div class="flex justify-between items-center mb-4">
    <h3 class="font-semibold text-lg">Nhật ký tour</h3>
    <?php
    if (isset($tourAssignment) && $tourAssignment):
      // Kiểm tra tour đang diễn ra (start_date <= today <= end_date)
      $today = date('Y-m-d');
      $isUpcoming = ($today < $booking['start_date']);
      $isCompleted = ($today > $booking['end_date']);
      $canWriteJournal = (!$isUpcoming && !$isCompleted);
    endif;
    ?>
  </div>

  <?php if (isset($tourAssignment) && $tourAssignment): ?>
    <?php if (!$canWriteJournal): ?>
      <div class="mb-4 p-4 rounded-lg <?= $isUpcoming ? 'bg-yellow-50 border-yellow-200' : 'bg-gray-50 border-gray-200' ?> border">
        <div class="flex items-center gap-2">
          <i data-lucide="<?= $isUpcoming ? 'clock' : 'check-circle' ?>" class="w-5 h-5 <?= $isUpcoming ? 'text-yellow-600' : 'text-gray-600' ?>"></i>
          <span class="font-medium <?= $isUpcoming ? 'text-yellow-800' : 'text-gray-800' ?>">
            <?php if ($isUpcoming): ?>
              Chưa đến thời gian khởi hành! Tour bắt đầu từ <?= date('d/m/Y', strtotime($booking['start_date'])) ?>
            <?php else: ?>
              Tour đã kết thúc! Bạn không thể viết thêm nhật ký.
            <?php endif; ?>
          </span>
        </div>
      </div>
    <?php endif; ?>

    <table class="w-full text-sm">
      <thead class="bg-gray-100">
        <tr>
          <th class="p-3 text-left">Ngày</th>
          <th class="p-3 text-left">Loại</th>
          <th class="p-3 text-left">Nội dung</th>
          <th class="p-3 text-left">Hành động</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($journals)): ?>
          <?php foreach ($journals as $j): ?>
            <tr class="border-t hover:bg-gray-50">
              <td class="p-3"><?= date('d/m/Y', strtotime($j['date'])) ?></td>
              <td class="p-3">
                <?php if ($j['type'] == 'incident'): ?>
                  <span class="text-red-600 font-medium flex items-center gap-1">
                    <i data-lucide="alert-triangle" class="w-3 h-3"></i> Sự cố
                  </span>
                <?php else: ?>
                  <span class="text-blue-600 font-medium flex items-center gap-1">
                    <i data-lucide="book-open" class="w-3 h-3"></i> Nhật ký ngày
                  </span>
                <?php endif; ?>
              </td>
              <td class="p-3 max-w-md truncate"><?= htmlspecialchars($j['content']) ?></td>
              <td class="p-3">
                <div class="flex gap-2">
                  <a href="<?= BASE_URL . '?act=booking-journal-detail&id=' . $j['id'] ?>"
                    class="text-gray-600 hover:bg-gray-100 p-1 rounded transition-colors" title="Xem chi tiết">
                    <i data-lucide="eye" class="w-5 h-5"></i>
                  </a>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="4" class="p-4 text-center text-gray-500">Chưa có nhật ký nào.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  <?php else: ?>
    <div class="text-center p-4 text-gray-500">
      Chưa có phân công tour cho booking này.
    </div>
  <?php endif; ?>
</div>