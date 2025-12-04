<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">

  <div class="flex justify-between flex-wrap items-center gap-3 mb-4">
    <h2 class="text-base font-semibold text-gray-800">Hợp đồng booking</h2>
    <a href="<?= BASE_URL ?>?act=contract-create&booking_id=<?= $booking['id'] ?>"
      class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium">
      <i class="w-4 h-4" data-lucide="file-plus"></i>
      Tạo hợp đồng
    </a>
  </div>

  <?php if (!empty($bookingContracts)): ?>
    <div class="space-y-3">
      <?php foreach ($bookingContracts as $c): ?>
        <div class="p-4 border border-gray-100 rounded-xl bg-white shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-4 hover:bg-gray-50">

          <!-- Thông tin -->
          <div class="flex flex-col flex-1 gap-2">

            <p class="font-medium text-gray-800 flex items-center gap-1">
              <i class="w-4 h-4 text-blue-600" data-lucide="file-text"></i>
              <?= htmlspecialchars($c['contract_name']) ?>
            </p>

            <p class="text-sm text-gray-500 flex items-center gap-1">
              <i class="w-4 h-4 text-green-600" data-lucide="calendar-check"></i>
              Hiệu lực: <?= !empty($c['effective_date']) ? date('Y-m-d', strtotime($c['effective_date'])) : '—' ?>
            </p>

            <p class="text-sm text-gray-500 flex items-center gap-1">
              <i class="w-4 h-4 text-red-600" data-lucide="calendar-x"></i>
              Hết hạn: <?= !empty($c['expiry_date']) ? date('Y-m-d', strtotime($c['expiry_date'])) : '—' ?>
            </p>

            <p class="text-sm text-gray-500 flex items-center gap-1">
              <i class="w-4 h-4 text-purple-600" data-lucide="pen-tool"></i>
              Người ký: <?= htmlspecialchars($_SESSION['currentUser']['fullname']) ?>
            </p>

            <p class="text-sm">
              <?php if (!empty($c['file_url'])): ?>
                <a href="<?= htmlspecialchars($c['file_url']) ?>"
                  target="_blank"
                  class="text-blue-600 underline inline-flex items-center gap-1 bg-blue-50 border border-blue-100 px-2 py-1 rounded-lg text-xs font-medium hover:bg-blue-100">
                  <i class="w-4 h-4" data-lucide="download"></i>
                  <?= htmlspecialchars($c['file_name'] ?? 'Tải file') ?>
                </a>
              <?php else: ?>
                <span class="text-gray-400 flex items-center gap-1">
                  <i class="w-4 h-4" data-lucide="file-off"></i>Không có file
                </span>
              <?php endif; ?>
            </p>
          </div>

          <!-- Màu trạng thái -->
          <?php
          $statusClass = 'bg-gray-100 text-gray-700';
          $statusText = 'Không xác định';

          if ($c['status'] === 'active') {
            $statusClass = 'bg-green-200 text-green-700';
            $statusText = 'Đang hoạt động';
          } elseif ($c['status'] === 'inactive') {
            $statusClass = 'bg-red-200 text-red-700';
            $statusText = 'Ngừng hoạt động';
          } elseif ($c['status'] === 'expired') {
            $statusClass = 'bg-yellow-200 text-yellow-700';
            $statusText = 'Hết hạn';
          }
          ?>
          <span class="px-3 py-1 rounded-lg text-xs font-semibold <?= $statusClass ?>">
            <?= $statusText ?>
          </span>

          <!-- nút -->
          <div class="flex items-center gap-2 text-gray-600">
            <a href="<?= BASE_URL ?>?act=contract-edit&id=<?= $c['id'] ?>" class="p-1 hover:text-blue-600">
              <i class="w-4 h-4" data-lucide="square-pen"></i>
            </a>

            <a href="<?= BASE_URL ?>?act=contract-detail&id=<?= $c['id'] ?>" class="p-1 hover:text-blue-600">
              <i class="w-4 h-4" data-lucide="eye"></i>
            </a>

            <a href="<?= BASE_URL ?>?act=contract-delete&id=<?= $c['id'] ?>"
              onclick="return confirm('Bạn có chắc muốn xóa hợp đồng này?');"
              class="p-1 hover:text-red-600 text-red-500">
              <i class="w-4 h-4" data-lucide="trash-2"></i>
            </a>
          </div>

        </div>
      <?php endforeach; ?>
    </div>

  <?php else: ?>
    <p class="text-gray-500 text-sm">Chưa có hợp đồng nào.</p>
  <?php endif; ?>
</div>