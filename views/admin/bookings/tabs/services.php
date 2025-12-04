<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
  <div class="flex justify-between flex-wrap items-center gap-3 mb-4">
    <h2 class="text-base font-semibold text-gray-800">Danh sách dịch vụ</h2>

    <div class="flex items-center gap-2">
      <a href="<?= BASE_URL . '?act=booking-edit&id=' . $booking['id']  ?>"
        class="inline-flex items-center gap-2 px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg text-sm font-medium">
        <i class="w-4 h-4" data-lucide="square-pen"></i>
        Chỉnh sửa dịch vụ
      </a>
    </div>
  </div>
  <?php if (!empty($bookingServices)): ?>
    <ul class="space-y-2 text-gray-800 text-sm">
      <?php foreach ($bookingServices as $s): ?>
        <li class="flex items-center justify-between bg-gray-50 border border-gray-100 rounded-lg px-4 py-3">
          <div class="flex items-center gap-3">
            <i class="w-5 h-5 text-blue-600" data-lucide="check-circle"></i>
            <div>
              <p class="font-medium"><?= htmlspecialchars($s['name']) ?></p>
              <span class="font-semibold text-gray-700">
                <?= number_format(($s['current_price'] ?? 0) * $s['quantity'], 0, ',', '.') ?>đ
              </span>
            </div>
          </div>
          <span class="font-semibold text-gray-700">
            <?= number_format($s['current_price'] * $s['quantity'], 0, ',', '.') ?>đ
          </span>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php else: ?>
    <p class="text-gray-500 text-sm flex items-center gap-2">
      <i class="w-4 h-4 text-gray-400" data-lucide="info"></i>
      Chưa có dịch vụ nào.
    </p>
  <?php endif; ?>
</div>