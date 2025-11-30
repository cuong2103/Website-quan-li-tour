<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';

$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];
unset($_SESSION['errors'], $_SESSION['old']);
?>

<div class="ml-54 mt-28 p-6">

    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-2xl font-semibold">Thêm Dịch Vụ Mới</h2>
            <p class="text-gray-500 text-sm">Tạo mới dịch vụ để sử dụng trong Tour</p>
        </div>

        <a href="?act=service"
            class="bg-gray-200 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg font-medium transition flex items-center gap-2">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
            Quay lại
        </a>
    </div>

    <!-- Form tạo dịch vụ -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 w-full mx-auto">

        <form action="?act=service-store" method="POST" class="space-y-10">

            <!-- Hai cột -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                <!-- Tên dịch vụ -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Tên dịch vụ *</label>
                    <input type="text" name="name" value="<?= $old['name'] ?? '' ?>"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 focus:bg-white
                        focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition <?= isset($errors['name']) ? 'border-red-500' : '' ?>">
                    <?php if (isset($errors['name'])): ?>
                        <p class="text-red-500 text-sm mt-1"><?= $errors['name'][0] ?></p>
                    <?php endif; ?>
                </div>

                <!-- Giá -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Giá dịch vụ (VNĐ) *</label>
                    <input type="number" min="0" name="estimated_price" value="<?= $old['estimated_price'] ?? '' ?>"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 focus:bg-white
                        focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition <?= isset($errors['estimated_price']) ? 'border-red-500' : '' ?>">
                    <?php if (isset($errors['estimated_price'])): ?>
                        <p class="text-red-500 text-sm mt-1"><?= $errors['estimated_price'][0] ?></p>
                    <?php endif; ?>
                </div>

                <!-- Loại dịch vụ -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Loại dịch vụ *</label>
                    <select name="service_type_id" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 focus:bg-white
                        focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition">
                        <option value="">-- Chọn loại --</option>
                        <?php foreach ($serviceTypes as $type): ?>
                            <option value="<?= $type['id'] ?>">
                                <?= $type['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Nhà cung cấp -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Nhà cung cấp *</label>
                    <select name="supplier_id" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 focus:bg-white
                        focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition">
                        <option value="">-- Chọn nhà cung cấp --</option>
                        <?php foreach ($suppliers as $sup): ?>
                            <option value="<?= $sup['id'] ?>"><?= $sup['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

            </div>

            <!-- Mô tả -->
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Mô tả dịch vụ</label>
                <textarea name="description" rows="4"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 focus:bg-white
                    focus:ring-2 focus:ring-orange-500 focus:border-orange-500 resize-none transition"></textarea>
            </div>

            <!-- Nút -->
            <div class="flex justify-end gap-4 pt-6 border-t">

                <a href="?act=service"
                    class="px-6 py-3 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium transition">
                    Hủy
                </a>

                <button type="submit"
                    class="px-6 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 font-medium transition flex items-center gap-2 shadow-sm">
                    Thêm dịch vụ
                </button>

            </div>

        </form>
    </div>

</div>

<?php
require_once './views/components/footer.php';
?>