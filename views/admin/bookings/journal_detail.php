<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';
?>

<main class="pt-28 px-6 min-w-7xl mx-6">
  <!-- Header -->
  <div class="flex items-center justify-between mb-8">
    <div class="flex items-center gap-4">
      <button onclick="history.back()" class="p-2 hover:bg-gray-100 rounded-lg transition">
        <i data-lucide="chevron-left" class="w-6 h-6"></i>
      </button>
      <div>
        <h1 class="text-3xl font-bold text-gray-900">Chi tiết nhật ký</h1>
        <p class="text-gray-500 mt-1">
          Tour: <?= htmlspecialchars($tour['tour_name'] ?? 'N/A') ?>
          <span class="mx-2">•</span>
          Mã: <?= htmlspecialchars($tour['booking_code'] ?? 'N/A') ?>
        </p>
      </div>
    </div>
    <div class="flex gap-3">
      <a href="<?= BASE_URL . '?act=booking-detail&id=' . $tour['booking_id'] . '&tab=journal' ?>"
        class="flex items-center gap-2 text-gray-600 hover:text-gray-900 transition-colors bg-white px-4 py-2 rounded-lg border border-gray-200 shadow-sm hover:bg-gray-50">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
        <span>Quay lại Booking</span>
      </a>
    </div>
  </div>

  <!-- Content Card -->
  <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden p-8">

    <!-- Meta info -->
    <div class="flex flex-wrap gap-6 mb-8 pb-6 border-b border-gray-100">
      <div>
        <span class="block text-sm font-medium text-gray-500 mb-1">Ngày ghi nhận</span>
        <span class="text-lg font-semibold text-gray-900">
          <?= date('d/m/Y', strtotime($journal['date'])) ?>
        </span>
      </div>
      <div>
        <span class="block text-sm font-medium text-gray-500 mb-1">Loại nhật ký</span>
        <?php if ($journal['type'] == 'incident'): ?>
          <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-sm font-medium bg-red-50 text-red-700 border border-red-100">
            <i data-lucide="alert-triangle" class="w-4 h-4"></i> Sự cố
          </span>
        <?php else: ?>
          <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-sm font-medium bg-blue-50 text-blue-700 border border-blue-100">
            <i data-lucide="book-open" class="w-4 h-4"></i> Nhật ký ngày
          </span>
        <?php endif; ?>
      </div>
      <div>
        <span class="block text-sm font-medium text-gray-500 mb-1">Người viết</span>
        <span class="text-lg text-gray-900">
          <?= htmlspecialchars($journal['created_by_name'] ?? 'N/A') ?>
        </span>
      </div>
    </div>

    <!-- Main Content -->
    <div class="mb-8">
      <h3 class="text-lg font-semibold text-gray-900 mb-4">Nội dung</h3>
      <div class="prose max-w-none text-gray-700 leading-relaxed whitespace-pre-line bg-gray-50 p-6 rounded-lg border border-gray-100">
        <?= htmlspecialchars($journal['content']) ?>
      </div>
    </div>

    <!-- Images -->
    <?php if (!empty($images)): ?>
      <div>
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Hình ảnh đính kèm (<?= count($images) ?>)</h3>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
          <?php foreach ($images as $img): ?>
            <div class="relative group rounded-xl overflow-hidden border border-gray-200 shadow-sm cursor-pointer inline-block">
              <img src="<?= BASE_URL . 'uploads/journals/' . $img['image_url'] ?>"
                class="max-w-full h-auto object-contain transition-transform duration-300 group-hover:scale-105">
            </div>
        </div>
      <?php endforeach; ?>
      </div>
  </div>
<?php endif; ?>

</div>
</main>

<?php require_once './views/components/footer.php'; ?>