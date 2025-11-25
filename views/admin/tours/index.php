<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';
?>
<main class="mt-28 px-6">

  <!-- Tiêu đề + nút tạo tour -->
  <div class="flex justify-between items-center mb-8">
    <div>
      <h1 class="text-2xl font-bold text-gray-900">Quản lý Tour</h1>
      <p class="text-sm text-gray-600 mt-1">Danh sách tất cả các tour</p>
    </div>

    <a href="<?= BASE_URL ?>?act=tours-create" class="flex items-center gap-3 bg-black text-white px-6 py-3 rounded-lg hover:bg-gray-900 transition font-medium">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
      </svg>
      Tạo Tour mới
    </a>
  </div>

  <!-- Danh sách tour dạng grid -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

    <?php foreach ($tours as $tour): ?>
      <div class="bg-white rounded-xl border border-gray-200  shadow-sm hover:shadow-lg transition overflow-hidden">
        <div class="p-6">
          <div class="flex justify-between h-12 items-start mb-4">
            <h3 class="font-semibold text-gray-900  w-[70%] text-base"><?= $tour['name'] ?></h3>
            <?= $tour['status'] == "active" ? '<span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-full">Hoạt động</span>' : '<span class="px-3 py-1 bg-gray-100 text-gray-600 text-xs font-medium rounded-full">Tạm dừng</span>' ?>

          </div>

          <div class="space-y-2 text-sm text-gray-600">
            <div class="flex justify-between">
              <span>Giá người lớn:</span>
              <span class="font-semibold text-gray-900"><?= $tour['adult_price'] ?>đ</span>
            </div>
            <div class="flex justify-between">
              <span>Giá trẻ em:</span>
              <span class="font-semibold text-gray-900"><?= $tour['child_price'] ?>đ</span>
            </div>
            <div class="flex justify-between">
              <span>Số booking:</span>
              <span class="font-semibold text-orange-600">12</span>
            </div>
          </div>

          <div class="mt-6 flex gap-2">
            <a href="<?= BASE_URL ?>?act=tours-edit&id=<?= $tour['id'] ?>" class="flex items-center justify-center gap-2 flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium text-sm py-2.5 rounded-lg transition-all duration-200 shadow-sm hover:shadow">
              <i data-lucide="square-pen" class="w-4 h-4"></i>
              <span>Sửa</span>
            </a>
            <a href="<?= BASE_URL ?>?act=tours-detail&id=<?= $tour['id'] ?>" class="w-11 h-11 bg-gray-100 rounded-lg hover:bg-gray-200 transition flex items-center justify-center">
              <i class="w-5 h-5" data-lucide="eye"></i>
            </a>
            <a href="<?= BASE_URL ?>?act=tours-delete&id=<?= $tour['id'] ?>" class="w-11 h-11 bg-gray-100 rounded-lg hover:bg-gray-200 transition flex items-center justify-center">
              <i class="w-5 h-5" data-lucide="trash-2"></i>
            </a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>



  </div>
</main>


<?php
require_once './views/components/footer.php';
?>