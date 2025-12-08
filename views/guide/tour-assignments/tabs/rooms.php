<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
  <div class="flex justify-between flex-wrap items-center gap-3 mb-4">
    <h2 class="text-base font-semibold text-gray-800">Danh sách phòng khách sạn</h2>

    <div class="flex items-center gap-2">
      <a href="<?= BASE_URL ?>?act=guide-tour-assignments-export-rooms&id=<?= $assignmentId ?>"
        class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium">
        <i class="w-4 h-4" data-lucide="download"></i>
        Xuất Excel
      </a>
    </div>
  </div>

  <div class="overflow-x-auto">
    <table class="w-full text-sm text-left text-gray-500">
      <thead class="text-xs text-gray-700 uppercase bg-gray-50">
        <tr>
          <th class="px-4 py-3">STT</th>
          <th class="px-4 py-3">Khách hàng</th>
          <th class="px-4 py-3">Số phòng</th>
          <th class="px-4 py-3">Ghi chú</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($customers)): ?>
          <?php foreach ($customers as $i => $c): ?>
            <tr class="bg-white border-b hover:bg-gray-50">
              <td class="px-4 py-3 text-gray-900">
                <?= $i + 1 ?>
              </td>
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
            <td colspan="4" class="px-4 py-8 text-center">
              <div class="flex flex-col items-center gap-2 text-gray-500">
                <i data-lucide="bed-double" class="w-12 h-12 text-gray-300"></i>
                <p class="font-medium">Chưa có khách hàng nào</p>
                <p class="text-xs">Danh sách phòng sẽ hiển thị khi có khách hàng trong tour</p>
              </div>
            </td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <?php if (!empty($customers)): ?>
    <div class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-100">
      <div class="flex items-start gap-2">
        <i data-lucide="info" class="w-5 h-5 text-blue-600 mt-0.5"></i>
        <div class="text-sm text-blue-800">
          <p class="font-medium mb-1">Lưu ý:</p>
          <ul class="list-disc list-inside space-y-1 text-xs">
            <li>Danh sách phòng chỉ để xem, không thể chỉnh sửa</li>
            <li>Nếu cần thay đổi số phòng, vui lòng liên hệ quản lý</li>
            <li>Sử dụng nút "Xuất Excel" để tải danh sách về máy</li>
          </ul>
        </div>
      </div>
    </div>
  <?php endif; ?>
</div>
