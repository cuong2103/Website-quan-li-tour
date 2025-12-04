<div class="bg-white border shadow rounded-xl p-5">
  <?php if (!empty($itinerary_days)): ?>
    <?php foreach ($itinerary_days as $day => $items): ?>
      <h3 class="font-semibold text-lg mb-3">Ngày <?= $day ?></h3>
      <div class="border-l ml-2 pl-4 space-y-4">
        <?php foreach ($items as $row): ?>
          <div>
            <div class="font-medium"><?= htmlspecialchars($row['destination_name']) ?></div>
            <div class="text-xs text-gray-500"><?= $row['arrival_time'] ?> → <?= $row['departure_time'] ?></div>
            <div class="text-sm mt-1"><?= htmlspecialchars($row['description']) ?></div>
          </div>
        <?php endforeach; ?>
      </div>
      <hr class="my-4">
    <?php endforeach; ?>
  <?php else: ?>
    <p class="text-gray-500">Chưa có lịch trình chi tiết.</p>
  <?php endif; ?>
</div>