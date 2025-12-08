<div class="bg-white border shadow rounded-xl p-5">
  <?php if (!empty($services)): ?>
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="bg-gray-100">
          <tr>
            <th class="p-3 text-left">STT</th>
            <th class="p-3 text-left">Tên dịch vụ</th>
            <th class="p-3 text-left">Số lượng</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($services as $i => $s): ?>
            <tr class="border-t hover:bg-gray-50">
              <td class="p-3"><?= $i + 1 ?></td>
              <td class="p-3 font-medium"><?= htmlspecialchars($s['name']) ?></td>
              <td class="p-3"><?= $s['quantity'] ?? 1 ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <div class="text-center py-8">
      <i data-lucide="package" class="w-12 h-12 text-gray-300 mx-auto mb-2"></i>
      <p class="text-gray-500">Chưa có dịch vụ kèm theo.</p>
    </div>
  <?php endif; ?>
</div>