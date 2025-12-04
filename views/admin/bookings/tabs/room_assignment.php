<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
  <div class="flex justify-between flex-wrap items-center gap-3 mb-4">
    <h2 class="text-base font-semibold text-gray-800">Xếp phòng khách sạn</h2>

    <div class="flex items-center gap-2">
      <!-- Form Upload Excel -->
      <form action="<?= BASE_URL ?>?act=booking-import-rooms" method="POST" enctype="multipart/form-data" class="flex items-center gap-2">
        <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
        <input type="file" name="excel_file" accept=".xlsx, .xls" class="text-sm border border-gray-300 rounded-lg p-1" required>
        <button type="submit" class="px-3 py-1.5 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-sm font-medium flex items-center gap-1">
          <i class="w-4 h-4" data-lucide="upload"></i> Upload Excel
        </button>
      </form>
      <a href="<?= BASE_URL ?>?act=booking-export-rooms&booking_id=<?= $booking['id'] ?>"
        class="inline-flex items-center gap-2 px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg text-sm font-medium">
        <i class="w-4 h-4" data-lucide="download"></i>
        Export Excel
      </a>
    </div>
  </div>

  <div class="overflow-x-auto">
    <table class="w-full text-sm text-left text-gray-500">
      <thead class="text-xs text-gray-700 uppercase bg-gray-50">
        <tr>
          <th class="px-4 py-3">Khách hàng</th>
          <th class="px-4 py-3">Số phòng</th>
          <th class="px-4 py-3">Ghi chú</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($customers)): ?>
          <?php foreach ($customers as $c): ?>
            <tr class="bg-white border-b hover:bg-gray-50">
              <td class="px-4 py-3 font-medium text-gray-900">
                <?= htmlspecialchars($c['name']) ?>
              </td>
              <td class="px-4 py-3">
                <?php if (!empty($c['room_number'])): ?>
                  <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-semibold">
                    <?= htmlspecialchars($c['room_number']) ?>
                  </span>
                <?php else: ?>
                  <span class="text-gray-400 italic">Chưa xếp</span>
                <?php endif; ?>
              </td>
              <td class="px-4 py-3 text-gray-600">
                <?= htmlspecialchars($c['notes'] ?? '') ?>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="3" class="px-4 py-3 text-center text-gray-500 italic">Chưa có khách hàng nào.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>