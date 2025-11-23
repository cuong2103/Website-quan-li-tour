<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';
?>
<main class="p-6">

  <!-- Tiêu đề trang -->
  <h1 class="text-2xl font-semibold text-gray-900 mb-2">Quản lý Danh mục Tour</h1>
  <p class="text-sm text-gray-600 mb-8">Tổ chức các tour thành cấu trúc danh mục phân cấp</p>

  <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">

    <!-- ================== CỘT TRÁI: Form thêm danh mục ================== -->
    <div class="xl:col-span-1">
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">

        <h2 class="text-lg font-medium text-gray-900 mb-6">Thêm Danh mục mới</h2>

        <form action="<?= BASE_URL ?>?act=categories-store" method="POST">
          <!-- Tên danh mục -->
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Tên danh mục <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="<?= $_POST['name'] ?? '' ?>"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="Ví dụ: Du lịch Trong nước, Châu Âu, Châu Á...">
            <?php if (!empty($errors['name'])): ?>
              <div class="text-red-500"><?= $errors['name'][0] ?></div>
            <?php endif; ?>

          </div>

          <!-- Danh mục cha -->
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Danh mục cha</label>
            <select name="parent_id"
              class="w-full px-3 py-2  border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
              <option value="">-- Là Danh mục gốc --</option>
              <?php renderOption($tree, "", $_POST["parent_id"]); ?>
            </select>
            <p class="text-xs text-gray-500 mt-1">Để trống nếu đây là danh mục gốc</p>
          </div>

          <!-- Thông báo lưu ý -->
          <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4 mb-6">
            <div class="flex">
              <div class="flex-shrink-0">
                <i data-lucide="alert-circle" class="h-5 w-5 text-yellow-600"></i>
              </div>
              <div class="ml-3 text-sm text-yellow-800">
                <p class="font-medium">Gợi ý:</p>
                <ul class="list-disc list-inside mt-1 space-y-1">
                  <li>Tạo danh mục cha trước (ví dụ: "Du lịch Trong nước")</li>
                  <li>Sau đó tạo danh mục con (ví dụ: "Miền Bắc")</li>
                  <li>Có thể tạo tối đa 3-4 cấp độ</li>
                </ul>
              </div>
            </div>
          </div>

          <!-- Nút lưu -->
          <button type="submit"
            class="w-full bg-orange-400 text-white py-3 rounded-md font-medium hover:bg-orange-500 transition flex items-center justify-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Lưu Danh mục
          </button>
        </form>

      </div>
    </div>

    <!-- ================== CỘT PHẢI: Cấu trúc danh mục (cây) ================== -->
    <div class="xl:col-span-1 ">
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-lg font-medium text-gray-900">Cấu trúc Danh mục (10)</h2>

        </div>

        <div class="border border-gray-200 rounded-lg p-2 h-[30rem] overflow-y-auto">
          <?= renderCategory($tree) ?>
        </div>

        <!-- Hướng dẫn nhỏ bên dưới -->
        <div class="mt-8 pt-6 border-t border-gray-200">
          <p class="text-sm font-medium text-gray-700 mb-2">Hướng dẫn:</p>
          <ul class="text-sm text-gray-600 space-y-1">
            <li>• Click để thu gọn/mở rộng</li>
            <li>• Danh mục đang mở</li>
            <li>• Danh mục đã đóng</li>
          </ul>
        </div>

      </div>
    </div>

  </div>
</main>
</div>


<?php
require_once './views/components/footer.php';
?>