<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';
?>

<main class="pt-28 px-6 bg-gray-50 min-h-screen overflow-y-auto">
  <div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
      <div class="flex items-center gap-4">
        <button onclick="history.back()" class="p-2 hover:bg-gray-100 rounded-lg transition">
          <i data-lucide="arrow-left" class="w-6 h-6"></i>
        </button>
        <div>
          <h2 class="text-3xl font-bold text-gray-900">Chi tiết tour</h2>
          <p class="text-sm text-gray-600">Xem toàn bộ thông tin tour • Mã tour: <?= htmlspecialchars($tour['tour_code']) ?></p>
        </div>
      </div>
      <div class="flex gap-3">
        <a href="<?= BASE_URL ?>?act=tours-edit&id=<?= $tour['id'] ?>" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
          <i data-lucide="edit" class="w-4 h-4 inline-block mr-2"></i>
          Chỉnh sửa
        </a>
      </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 space-y-10">

      <!-- 1. Thông tin cơ bản -->
      <section>
        <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center gap-3">
          <span class="bg-blue-100 text-blue-700 w-8 h-8 rounded-full flex items-center justify-center text-sm">1</span>
          Thông tin cơ bản
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">Tên tour</label>
            <p class="text-lg font-medium text-gray-900"><?= htmlspecialchars($tour['name']) ?></p>
          </div>

          <div class="col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">Giới thiệu ngắn</label>
            <p class="text-gray-700 leading-relaxed"><?= nl2br(htmlspecialchars($tour['introduction'])) ?></p>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Mã tour</label>
            <p class="text-gray-900 font-mono font-semibold"><?= htmlspecialchars($tour['tour_code']) ?></p>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Danh mục</label>
            <p class="text-gray-900"><?= htmlspecialchars($tour['category_name']) ?></p>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Thời gian</label>
            <p class="text-gray-900">
              <?php
              $days = $tour['duration_days'];
              $nights = $days > 0 ? $days - 1 : 0;
              echo "{$days} ngày {$nights} đêm";
              ?>
            </p>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Trạng thái</label>
            <div class="flex items-center gap-2">
              <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                              <?= $tour['status'] == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                <?= $tour['status'] == 'active' ? 'Đang hoạt động' : 'Tạm dừng' ?>
              </span>
              <?php if ($tour['is_fixed']): ?>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                  Tour cố định
                </span>
              <?php endif; ?>
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Giá người lớn</label>
            <p class="text-xl font-semibold text-green-600"><?= number_format($tour['adult_price'], 0, ',', '.') ?> ₫</p>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Giá trẻ em</label>
            <p class="text-xl font-semibold text-orange-600"><?= number_format($tour['child_price'], 0, ',', '.') ?> ₫</p>
          </div>

          <div class="col-span-2 pt-4 border-t border-gray-200">
            <div class="grid grid-cols-2 gap-4 text-sm">
              <div>
                <label class="block text-gray-500 mb-1">Ngày tạo</label>
                <p class="text-gray-900"><?= date('d/m/Y H:i', strtotime($tour['created_at'])) ?></p>
              </div>
              <?php if (!empty($tour['updated_at'])): ?>
                <div>
                  <label class="block text-gray-500 mb-1">Cập nhật lần cuối</label>
                  <p class="text-gray-900"><?= date('d/m/Y H:i', strtotime($tour['updated_at'])) ?></p>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </section>

      <!-- 2. Dịch vụ đi kèm (Nếu có) -->
      <?php if (!empty($services)): ?>
        <section>
          <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center gap-3">
            <span class="bg-blue-100 text-blue-700 w-8 h-8 rounded-full flex items-center justify-center text-sm">2</span>
            Dịch vụ đi kèm
          </h3>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php foreach ($services as $service): ?>
              <div class="p-4 border border-gray-200 rounded-lg bg-gray-50">
                <h4 class="font-medium text-gray-900"><?= htmlspecialchars($service['name']) ?></h4>
                <p class="text-sm text-gray-500 mt-1"><?= number_format($service['estimated_price']) ?> VND</p>
              </div>
            <?php endforeach; ?>
          </div>
        </section>
      <?php endif; ?>

      <!-- 3. Lịch trình chi tiết -->
      <section>
        <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center gap-3">
          <span class="bg-blue-100 text-blue-700 w-8 h-8 rounded-full flex items-center justify-center text-sm">3</span>
          Lịch trình tour
        </h3>
        <?php if (!empty($itineraries)): ?>
          <div class="space-y-6">
            <?php foreach ($itineraries as $day): ?>
              <div class="border border-gray-200 rounded-xl p-6 hover:border-blue-300 transition">
                <div class="flex items-center justify-between mb-4">
                  <h4 class="text-lg font-semibold text-blue-700">Ngày <?= $day['order_number'] ?></h4>
                  <span class="text-sm text-gray-500 flex items-center gap-2">
                    <i data-lucide="clock" class="w-4 h-4"></i>
                    <?= date('H:i', strtotime($day['arrival_time'])) ?> → <?= date('H:i', strtotime($day['departure_time'])) ?>
                  </span>
                </div>
                <h5 class="font-medium text-gray-900 mb-3 flex items-center gap-2">
                  <i data-lucide="clock" class="w-5 h-5 text-red-500"></i>
                  <?= htmlspecialchars($day['destination']) ?>
                </h5>
                <div class="text-gray-600 leading-relaxed pl-7">
                  <?= nl2br(htmlspecialchars($day['description'])) ?>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="text-center py-12 text-gray-500">
            <i data-lucide="clock" class="w-16 h-16 mx-auto mb-4 text-gray-300"></i>
            <p>Chưa có lịch trình nào</p>
          </div>
        <?php endif; ?>
      </section>

      <!-- 4. Chính sách -->
      <section>
        <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center gap-3">
          <span class="bg-blue-100 text-blue-700 w-8 h-8 rounded-full flex items-center justify-center text-sm">4</span>
          Chính sách tour
        </h3>
        <?php if (!empty($policies)): ?>
          <div class="space-y-5">
            <?php foreach ($policies as $policy): ?>
              <div class="flex gap-4 p-5 bg-gray-50 rounded-lg border border-gray-100 hover:border-gray-200 transition">
                <div class="flex-shrink-0">
                  <div class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center font-semibold">
                    <i data-lucide="clock" class="w-5 h-5"></i>
                  </div>
                </div>
                <div class="flex-1">
                  <h4 class="font-semibold text-gray-900 mb-1"><?= htmlspecialchars($policy['name']) ?></h4>
                  <p class="text-sm text-gray-600 leading-relaxed"><?= nl2br(htmlspecialchars($policy['content'])) ?></p>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="text-center py-12 text-gray-500">
            <i data-lucide="clock" class="w-16 h-16 mx-auto mb-4 text-gray-300"></i>
            <p>Chưa có chính sách nào</p>
          </div>
        <?php endif; ?>
      </section>

    </div>

    <!-- Action buttons -->
    <div class="flex justify-between items-center mt-6 pb-8">
      <button onclick="history.back()" class="px-6 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition font-medium text-gray-700">
        <i data-lucide="arrow-left" class="w-5 h-5 inline-block mr-2"></i>
        Quay lại
      </button>

      <div class="flex gap-3">
        <a href="<?= BASE_URL ?>?act=tours-delete&id=<?= $tour['id'] ?>"
          onclick="return confirm('Bạn có chắc chắn muốn xóa tour này?')"
          class="px-6 py-3 border border-red-300 text-red-600 rounded-lg hover:bg-red-50 transition font-medium">
          <i data-lucide="trash-2" class="w-5 h-5 inline-block mr-2"></i>
          Xóa tour
        </a>
        <a href="<?= BASE_URL ?>?act=tours-edit&id=<?= $tour['id'] ?>"
          class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
          <i data-lucide="square-pencil" class="w-5 h-5 inline-block mr-2"></i>
          Chỉnh sửa
        </a>
      </div>
    </div>
  </div>
</main>

<?php require_once './views/components/footer.php'; ?>