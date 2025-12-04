<div class="bg-white border shadow rounded-xl p-5">
  <?php if (!empty($services)): ?>
    <ul class="list-disc ml-5 text-sm text-gray-700">
      <?php foreach ($services as $s): ?>
        <li><?= htmlspecialchars($s['service_name']) ?>
          <?php if (!empty($s['quantity'])): ?> (Số lượng: <?= $s['quantity'] ?>)<?php endif; ?>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php else: ?>
    <p class="text-gray-500">Chưa có dịch vụ kèm theo.</p>
  <?php endif; ?>
</div>