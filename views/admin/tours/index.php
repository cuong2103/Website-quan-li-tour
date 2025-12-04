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
      <i data-lucide="plus" class="w-5 h-5"></i>
      Tạo Tour mới
    </a>
  </div>

  <!-- Bộ lọc -->
  <form method="GET" action="<?= BASE_URL ?>" class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
    <input type="hidden" name="act" value="tours">

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
      <!-- Search box - Tên tour -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
          <i data-lucide="search" class="w-4 h-4 inline"></i>
          Tìm kiếm tên tour
        </label>
        <input type="text" name="name" value="<?= htmlspecialchars($_GET['name'] ?? '') ?>"
          placeholder="Nhập tên tour..."
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
      </div>

      <!-- Dropdown - Danh mục -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
          <i data-lucide="folder" class="w-4 h-4 inline"></i>
          Danh mục
        </label>
        <select name="category_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
          <option value="">Tất cả danh mục</option>
          <?php foreach ($categories as $category): ?>
            <option value="<?= $category['id'] ?>" <?= ($_GET['category_id'] ?? '') == $category['id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($category['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Dropdown - Trạng thái -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
          <i data-lucide="activity" class="w-4 h-4 inline"></i>
          Trạng thái
        </label>
        <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
          <option value="">Tất cả</option>
          <option value="active" <?= ($_GET['status'] ?? '') == 'active' ? 'selected' : '' ?>>Hoạt động</option>
          <option value="inactive" <?= ($_GET['status'] ?? '') == 'inactive' ? 'selected' : '' ?>>Tạm dừng</option>
        </select>
      </div>

      <!-- Dropdown - Loại tour -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
          <i data-lucide="tag" class="w-4 h-4 inline"></i>
          Loại tour
        </label>
        <select name="is_fixed" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
          <option value="">Tất cả</option>
          <option value="1" <?= ($_GET['is_fixed'] ?? '') === '1' ? 'selected' : '' ?>>Tour cố định</option>
          <option value="0" <?= ($_GET['is_fixed'] ?? '') === '0' ? 'selected' : '' ?>>Tour thường</option>
        </select>
      </div>

      <!-- Dropdown - Số ngày -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
          <i data-lucide="calendar" class="w-4 h-4 inline"></i>
          Số ngày
        </label>
        <select name="duration" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
          <option value="">Tất cả</option>
          <option value="1-3" <?= ($_GET['duration'] ?? '') == '1-3' ? 'selected' : '' ?>>1-3 ngày</option>
          <option value="4-7" <?= ($_GET['duration'] ?? '') == '4-7' ? 'selected' : '' ?>>4-7 ngày</option>
          <option value="7+" <?= ($_GET['duration'] ?? '') == '7+' ? 'selected' : '' ?>>Trên 7 ngày</option>
        </select>
      </div>

      <!-- Dropdown - Điểm đến -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
          <i data-lucide="map-pin" class="w-4 h-4 inline"></i>
          Điểm đến
        </label>
        <select name="destination_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
          <option value="">Tất cả điểm đến</option>
          <?php foreach ($destinations as $destination): ?>
            <option value="<?= $destination['id'] ?>" <?= ($_GET['destination_id'] ?? '') == $destination['id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($destination['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Khoảng giá -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
          <i data-lucide="dollar-sign" class="w-4 h-4 inline"></i>
          Khoảng giá
        </label>
        <select id="price_range_preset" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
          <option value="">Tất cả</option>
          <option value="0-5000000">Dưới 5 triệu</option>
          <option value="5000000-10000000">5-10 triệu</option>
          <option value="10000000-20000000">10-20 triệu</option>
          <option value="20000000-50000000">20-50 triệu</option>
          <option value="50000000-999999999">Trên 50 triệu</option>
        </select>
        <input type="hidden" name="min_price" id="min_price" value="<?= htmlspecialchars($_GET['min_price'] ?? '') ?>">
        <input type="hidden" name="max_price" id="max_price" value="<?= htmlspecialchars($_GET['max_price'] ?? '') ?>">
      </div>

      <!-- Reset Button - Nổi bật -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2 invisible">Action</label>
        <a href="<?= BASE_URL ?>?act=tours" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-orange-50 border border-orange-200 rounded-lg hover:bg-orange-100 transition text-sm text-orange-700 font-semibold">
          <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
          Reset
        </a>
      </div>
    </div>
  </form>

  <!-- Danh sách tour dạng grid -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    <?php if (empty($tours)): ?>
      <div class="col-span-full flex flex-col items-center justify-center py-16 text-center bg-white rounded-xl border border-gray-200 border-dashed">
        <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mb-4">
          <i data-lucide="map" class="w-12 h-12 text-gray-300"></i>
        </div>
        <h3 class="text-lg font-medium text-gray-900 mb-1">Chưa có tour nào</h3>
        <p class="text-gray-500 mb-6">Hãy tạo tour đầu tiên để bắt đầu kinh doanh</p>
        <a href="<?= BASE_URL ?>?act=tours-create" class="flex items-center gap-2 bg-black text-white px-5 py-2.5 rounded-lg hover:bg-gray-800 transition font-medium">
          <i data-lucide="plus" class="w-4 h-4"></i>
          Tạo Tour mới
        </a>
      </div>
    <?php else: ?>
      <?php foreach ($tours as $tour): ?>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-lg transition overflow-hidden">
          <div class="p-6">
            <div class="flex justify-between h-12 items-start mb-4">
              <h3 class="font-semibold text-gray-900 w-[70%] text-base"><?= $tour['name'] ?></h3>
              <div class="flex flex-col gap-1 items-end">
                <?= $tour['status'] == "active" ? '<span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-full">Hoạt động</span>' : '<span class="px-3 py-1 bg-gray-100 text-gray-600 text-xs font-medium rounded-full">Tạm dừng</span>' ?>
                <?php if (!empty($tour['is_fixed'])): ?>
                  <span class="px-3 py-1 bg-purple-100 text-purple-700 text-xs font-medium rounded-full">Cố định</span>
                <?php endif; ?>
              </div>
            </div>

            <div class="space-y-2 text-sm text-gray-600">
              <div class="flex justify-between">
                <span>Giá người lớn:</span>
                <span class="font-semibold text-gray-900"><?= number_format($tour['adult_price']) ?>đ</span>
              </div>
              <div class="flex justify-between">
                <span>Giá trẻ em:</span>
                <span class="font-semibold text-gray-900"><?= number_format($tour['child_price']) ?>đ</span>
              </div>
              <div class="flex justify-between">
                <span>Số ngày:</span>
                <span class="font-medium text-gray-900"><?= $tour['duration_days'] ?>N<?= $tour['duration_days'] - 1 ?>Đ</span>
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
    <?php endif; ?>
  </div>
</main>

<script>
  // Auto-submit với debounce
  let timer;
  const form = document.querySelector("form");

  document.querySelectorAll("form input[type='text'], form select:not(#price_range_preset)").forEach(element => {
    element.addEventListener("input", () => {
      clearTimeout(timer);
      timer = setTimeout(() => form.submit(), 600);
    });
  });

  // Price range logic
  const pricePreset = document.getElementById('price_range_preset');
  const minPriceInput = document.getElementById('min_price');
  const maxPriceInput = document.getElementById('max_price');

  // Set current value nếu đang có filter
  const currentMin = minPriceInput.value;
  const currentMax = maxPriceInput.value;
  if (currentMin && currentMax) {
    const currentVal = `${currentMin}-${currentMax}`;
    const options = Array.from(pricePreset.options);
    const matchingOption = options.find(opt => opt.value === currentVal);
    if (matchingOption) {
      pricePreset.value = currentVal;
    }
  }

  // Khi chọn preset
  pricePreset.addEventListener('change', function() {
    if (this.value === '') {
      minPriceInput.value = '';
      maxPriceInput.value = '';
    } else {
      const [min, max] = this.value.split('-');
      minPriceInput.value = min;
      maxPriceInput.value = max;
    }
    form.submit();
  });
</script>

<?php
require_once './views/components/footer.php';
?>