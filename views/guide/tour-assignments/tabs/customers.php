<div class="bg-white border shadow rounded-xl p-5">
  <table class="w-full text-sm">
    <thead class="bg-gray-100">
      <tr>
        <th class="p-3 text-left">STT</th>
        <th class="p-3 text-left">Tên khách hàng</th>
        <th class="p-3 text-left">Số điện thoại</th>
        <th class="p-3 text-left">Email</th>
        <th class="p-3 text-left">Ghi chú</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($customers as $i => $c): ?>
        <tr class="border-t">
          <td class="p-3"><?= $i + 1 ?></td>
          <td class="p-3"><?= htmlspecialchars($c['name']) ?></td>
          <td class="p-3"><?= htmlspecialchars($c['phone']) ?></td>
          <td class="p-3"><?= htmlspecialchars($c['email']) ?></td>
          <td class="p-3"><?= htmlspecialchars($c['notes'] ?? '-') ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>