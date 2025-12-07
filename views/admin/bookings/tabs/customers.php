<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
  <div class="flex justify-between flex-wrap items-center gap-3 mb-4">
    <h2 class="text-base font-semibold text-gray-800">Danh sách khách hàng</h2>

    <div class="flex items-center gap-2">
      <!-- Form Upload Excel -->
      <button onclick="document.getElementById('importModal').classList.remove('hidden')" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-md flex items-center gap-2">
        <i class="w-4 h-4" data-lucide="upload"></i> Tải lên Excel
      </button>
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
<div id="importModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white rounded-lg p-6 w-full max-w-md">
    <div class="flex items-center justify-between mb-4">
      <h3 class="text-lg font-semibold text-gray-800">Tải lên danh sách khách hàng</h3>
      <button onclick="document.getElementById('importModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
        <i class="w-5 h-5" data-lucide="x"></i>
      </button>
    </div>

    <form action="<?= BASE_URL ?>?act=booking-upload-customers" method="POST" enctype="multipart/form-data">
      <div class="mb-4">
        <input type="hidden" name="id" value="<?= $booking['id'] ?>">
        <label class="block text-sm font-medium text-gray-700 mb-2">Chọn file Excel</label>
        <input type="file" name="file" accept=".xlsx,.xls" required
          class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
        <p class="text-xs text-gray-500 mt-1">Định dạng: .xlsx hoặc .xls</p>
      </div>

      <div class="mb-4 p-3 bg-blue-50 rounded-lg">
        <p class="text-sm text-gray-700 mb-2"><strong>Định dạng file Excel:</strong></p>
        <p class="text-xs text-gray-600">Cột 1: STT</p>
        <p class="text-xs text-gray-600">Cột 2: Họ tên</p>
        <p class="text-xs text-gray-600">Cột 3: Số điện thoại</p>
        <p class="text-xs text-gray-600">Cột 4: Email</p>
        <p class="text-xs text-gray-600">Cột 5: Địa chỉ</p>
        <p class="text-xs text-gray-600">Cột 6: Giới tính (Nam/Nữ/Khác)</p>
        <p class="text-xs text-gray-600">Cột 7: Hộ chiếu</p>
        <p class="text-xs text-gray-600">Cột 8: Căn cước công dân (CCCD)</p>
      </div>

      <div class="mb-4">
        <a href="<?= BASE_URL ?>?act=booking-export-template-customers" class="text-sm text-blue-600 hover:underline flex items-center gap-1">
          <i class="w-4 h-4" data-lucide="download"></i>
          Tải file mẫu
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