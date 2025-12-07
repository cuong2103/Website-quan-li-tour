<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
  <div class="flex justify-between flex-wrap items-center gap-3 mb-4">
    <h2 class="text-base font-semibold text-gray-800">Xếp phòng khách sạn</h2>

    <div class="flex items-center gap-2">

      <!-- Form Upload Excel -->
      <button onclick="document.getElementById('importModal').classList.remove('hidden')" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-md flex items-center gap-2">
        <i class="w-4 h-4" data-lucide="upload"></i> Tải lên Excel
      </button>
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
        <?php
        if (!empty($customers)): ?>
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
<div id="importModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white rounded-lg p-6 w-full max-w-md">
    <div class="flex items-center justify-between mb-4">
      <h3 class="text-lg font-semibold text-gray-800">Tải lên danh sách khách hàng</h3>
      <button onclick="document.getElementById('importModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
        <i class="w-5 h-5" data-lucide="x"></i>
      </button>
    </div>

    <form action="<?= BASE_URL ?>?act=booking-import-rooms" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
      <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-2">Chọn file Excel</label>
        <input type="file" name="file" accept=".xlsx,.xls" required
          class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
        <p class="text-xs text-gray-500 mt-1">Định dạng: .xlsx hoặc .xls</p>
      </div>

      <div class="mb-4 p-3 bg-blue-50 rounded-lg">
        <p class="text-sm text-gray-700 mb-2"><strong>Định dạng file Excel:</strong></p>
        <p class="text-xs text-gray-600">Cột 1: STT</p>
        <p class="text-xs text-gray-600">Cột 2: Họ tên</p>
        <p class="text-xs text-gray-600">Cột 3: Số phòng</p>
        <p class="text-xs text-gray-600">Cột 4: Ghi chú</p>
      </div>

      <div class="mb-4">
        <a href="<?= BASE_URL ?>?act=booking-export-template-customers" class="text-sm text-blue-600 hover:underline flex items-center gap-1">
          <i class="w-4 h-4" data-lucide="download"></i>
          Tải file xếp phòng
        </a>
      </div>

      <div class="flex gap-2">
        <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')"
          class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">
          Hủy
        </button>
        <button type="submit" class="flex-1 px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-md">
          Tải lên
        </button>
      </div>
    </form>
  </div>
</div>