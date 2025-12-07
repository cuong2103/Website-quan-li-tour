<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
  <div class="flex justify-between items-center mb-6">
    <h2 class="text-base font-semibold text-gray-800 flex items-center gap-2">
      <i class="w-5 h-5 text-indigo-500" data-lucide="map-pin"></i>
      Lịch trình chi tiết
    </h2>
  </div>

  <?php if (!empty($itinerary_days)): ?>
    <div class="space-y-6">
      <?php foreach ($itinerary_days as $day => $items): ?>
        <!-- Day Header -->
        <div class="relative">
          <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 bg-indigo-500 text-white rounded-full flex items-center justify-center font-bold text-sm">
              <?= $day ?>
            </div>
            <div>
              <h3 class="font-semibold text-gray-800">Ngày <?= $day ?></h3>
              <p class="text-sm text-gray-500"><?= count($items) ?> điểm đến</p>
            </div>
          </div>

          <!-- Timeline -->
          <div class="ml-5 border-l-2 border-indigo-200 pl-6 space-y-4">
            <?php foreach ($items as $index => $row): ?>
              <div class="relative">
                <!-- Dot on timeline -->
                <div class="absolute -left-[30px] w-3 h-3 bg-indigo-400 rounded-full border-2 border-white"></div>

                <div class="bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition">
                  <div class="flex items-start justify-between gap-4">
                    <div class="flex-1">
                      <h4 class="font-medium text-gray-800 flex items-center gap-2">
                        <i class="w-4 h-4 text-indigo-500" data-lucide="map-pin"></i>
                        <?= htmlspecialchars($row['destination_name']) ?>
                      </h4>

                      <!-- Time -->
                      <div class="flex items-center gap-2 mt-2 text-sm text-gray-600">
                        <i class="w-4 h-4 text-gray-400" data-lucide="clock"></i>
                        <span class="font-medium"><?= $row['arrival_time'] ?></span>
                        <i class="w-4 h-4 text-gray-400" data-lucide="arrow-right"></i>
                        <span class="font-medium"><?= $row['departure_time'] ?></span>
                      </div>

                      <!-- Description -->
                      <?php if (!empty($row['description'])): ?>
                        <p class="mt-3 text-sm text-gray-600 leading-relaxed">
                          <?= nl2br(htmlspecialchars($row['description'])) ?>
                        </p>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>

        <?php if ($day !== array_key_last($itinerary_days)): ?>
          <hr class="border-gray-200">
        <?php endif; ?>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <div class="text-center py-12">
      <i class="w-16 h-16 text-gray-300 mx-auto mb-4" data-lucide="map"></i>
      <p class="text-gray-500 text-sm">Chưa có lịch trình chi tiết cho tour này.</p>
      <p class="text-gray-400 text-xs mt-1">Lịch trình sẽ được cập nhật từ thông tin Tour.</p>
    </div>
  <?php endif; ?>
</div>