<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';
?>

<main class="pt-28 px-6 pb-20 overflow-auto scrollbar-hide">

    <!-- Tiêu đề và nút thêm -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Quản lí địa điểm</h1>
            <p class="text-sm text-gray-600">Danh sách các điểm đến du lịch</p>
        </div>
        <div class="flex space-x-3">
            <a href="<?= BASE_URL . '?act=destination-create' ?>"
                class="px-5 py-2.5 text-white text-sm font-medium rounded-lg bg-orange-400 hover:bg-orange-500 flex items-center space-x-2">
                <i data-lucide="plus" class="w-5 h-5"></i>
                <span>Thêm địa điểm</span>
            </a>
        </div>
    </div>

    <!-- Bộ lọc -->
    <form method="GET"
        class="w-full grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-8">

        <input type="hidden" name="act" value="destination">

        <!-- Tên địa điểm -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tên địa điểm</label>
            <input type="text" name="name"
                value="<?= $_GET['name'] ?? '' ?>"
                placeholder="Nhập tên địa điểm"
                class="w-full border border-gray-300 rounded-lg p-2">
        </div>

        <!-- Danh mục -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Danh mục</label>
            <select name="category_id"
                class="w-full border border-gray-300 rounded-lg p-2">
                <option value="">-- Tất cả --</option>
                <?php renderOption($tree, '', $_GET['category_id'] ?? ''); ?>
            </select>
        </div>

        <!-- Ngày tạo từ -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Ngày tạo từ</label>
            <input type="date" name="created_from"
                value="<?= $_GET['created_from'] ?? '' ?>"
                class="w-full border border-gray-300 rounded-lg p-2">
        </div>

        <!-- Ngày tạo đến -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Ngày tạo đến</label>
            <input type="date" name="created_to"
                value="<?= $_GET['created_to'] ?? '' ?>"
                class="w-full border border-gray-300 rounded-lg p-2">
        </div>

    </form>

    <!-- Danh sách địa điểm -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

        <?php foreach ($listDestination as $item): ?>

            <?php
            // Ảnh thumbnail
            $thumb = $item['image_url'] ?? 'default.jpg';
            ?>

            <div class="bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-lg transition overflow-hidden">
                <div class="h-40 w-full overflow-hidden rounded-t-xl">
                    <img src="<?= UPLOADS_URL . 'destinations_image/' . ($item['thumbnail'] ?? 'default.jpg') ?>"
                        alt="Destination Image" class="w-full h-full object-cover">
                </div>

                <div class="p-5">
                    <div class="flex items-start justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900"><?= htmlspecialchars($item['name']) ?></h2>
                            <p class="text-sm flex items-center gap-1 text-gray-500 mt-1"><i class="w-4 h-4 text-blue-500 flex-shrink-0" data-lucide="folder"></i><?= htmlspecialchars($item['category_name'] ?? 'Không rõ danh mục') ?></p>
                        </div>
                    </div>


                    <p class="text-gray-500 text-sm mt-3 line-clamp-2 ">
                        <?= htmlspecialchars(substr($item['locations'] ?? 'Không có mô tả', 0, 120)) ?> ...
                    </p>

                    <!-- Hành động -->
                    <div class="flex justify-start items-center mt-5 gap-3 pt-3 border-t">
                        <a href="<?= BASE_URL . '?act=destination-detail&id=' . $item['id'] ?>"
                            class="flex flex-1 items-center justify-center gap-2 px-3 py-2 border rounded-lg hover:bg-blue-50 text-blue-600 hover:text-blue-700 transition shadow-sm"
                            title="Xem chi tiết">
                            <i class="w-4 h-4" data-lucide="eye"></i>
                            <span class="text-sm font-medium">Xem</span>
                        </a>
                        <a href="<?= BASE_URL . '?act=destination-edit&id=' . $item['id'] ?>"
                            class="flex items-center justify-center gap-2 px-3 py-2 border rounded-lg hover:bg-orange-50 text-orange-600 hover:text-orange-700 transition shadow-sm"
                            title="Chỉnh sửa">
                            <i class="w-4 h-4" data-lucide="square-pen"></i>
                        </a>

                        <a href="<?= BASE_URL ?>?act=destination-delete&id=<?= $item['id'] ?>"
                            onclick="return confirm('Bạn có chắc muốn xóa địa điểm này không?');"
                            class="flex items-center justify-center gap-2 px-3 py-2 border rounded-lg hover:bg-red-50 text-red-600 hover:text-red-700 transition shadow-sm"
                            title="Xóa">
                            <i class="w-4 h-4" data-lucide="trash-2"></i>
                        </a>
                    </div>
                </div>
            </div>

        <?php endforeach; ?>

    </div>

</main>

<script>
    let timer;
    const form = document.querySelector("form");

    document.querySelectorAll("form input, form select").forEach(element => {
        element.addEventListener("input", () => {
            clearTimeout(timer);
            timer = setTimeout(() => form.submit(), 600);
        });
    });
</script>

<?php
require_once './views/components/footer.php';
?>