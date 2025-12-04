<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
  <div class="flex justify-between flex-wrap items-center gap-3 mb-4">
    <h2 class="text-base font-semibold text-gray-800">Danh sách khách hàng</h2>

    <div class="flex items-center gap-2">
      <!-- Form Upload Excel -->
      <form action="<?= BASE_URL ?>?act=booking-upload-customers" method="POST" enctype="multipart/form-data" class="flex items-center gap-2">
        <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
        <input type="file" name="file" accept=".xlsx, .xls" class="text-sm border border-gray-300 rounded-lg p-1" required>
        <button type="submit" class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium flex items-center gap-1">
          <i class="w-4 h-4" data-lucide="upload"></i> Upload Excel
        </button>
      </form>
      <a href="<?= BASE_URL ?>?act=booking-export-customers&booking_id=<?= $booking['id'] ?>"
        class="inline-flex items-center gap-2 px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg text-sm font-medium">
        <i class="w-4 h-4" data-lucide="download"></i>
        Export Excel
      </a>
    </div>
  </div>

  <?php if (!empty($customers)): ?>
    <div class="space-y-3">
      <?php foreach ($customers as $c): ?>
        <div class="p-4 border border-gray-100 rounded-xl bg-white shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-4 hover:bg-gray-50">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
              <i data-lucide="user" class="w-5 h-5"></i>
            </div>
            <div class="flex flex-col">
              <p class="font-medium text-gray-800 mb-1">
                <?= htmlspecialchars($c['name']) ?>
                <?php if ($c['is_representative']): ?>
                  <span class="ml-2 px-2 py-0.5 bg-green-100 text-green-700 text-xs rounded-full">Người đại diện</span>
                <?php endif; ?>
              </p>
              <div class="flex items-center gap-3 text-sm text-gray-500">
                <span class="flex items-center gap-1"><i class="w-3 h-3" data-lucide="phone"></i> <?= htmlspecialchars($c['phone']) ?></span>
                <span class="flex items-center gap-1"><i class="w-3 h-3" data-lucide="mail"></i> <?= htmlspecialchars($c['email']) ?></span>
              </div>
            </div>
          </div>

          <div class="flex items-center gap-2 text-gray-600">
            <a href="<?= BASE_URL ?>?act=customer-edit&id=<?= $c['id'] ?>" class="p-1 hover:text-blue-600">
              <i class="w-4 h-4" data-lucide="square-pen"></i>
            </a>
            <a href="<?= BASE_URL ?>?act=booking-remove-customer&booking_id=<?= $booking['id'] ?>&customer_id=<?= $c['id'] ?>"
              onclick="return confirm('Xóa khách này khỏi booking?')"
              class="p-1 hover:text-red-600 text-red-500">
              <i class="w-4 h-4" data-lucide="trash-2"></i>
            </a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <p class="text-gray-500 text-sm">Chưa có khách hàng nào.</p>
  <?php endif; ?>
</div>