<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';

$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];
unset($_SESSION['errors'], $_SESSION['old']);
?>

<div class="ml-54 mt-28 p-6">

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-semibold text-gray-800">Thêm dịch vụ mới</h2>
            <p class="text-gray-500 text-sm">Tạo mới dịch vụ để sử dụng trong Tour</p>
        </div>
        <a href="?act=service"
            class="px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition flex items-center gap-2 text-sm font-medium">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Quay lại
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form action="?act=service-store" method="POST" class="space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Tên dịch vụ -->
                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Tên dịch vụ <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="<?= $old['name'] ?? '' ?>" placeholder="Nhập tên dịch vụ..."
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-1 focus:ring-orange-500 outline-none transition <?= isset($errors['name']) ? 'border-red-500' : '' ?>">
                    <?php if (isset($errors['name'])): ?>
                        <p class="text-red-500 text-xs mt-1"><?= $errors['name'][0] ?></p>
                    <?php endif; ?>
                </div>

                <!-- Giá -->
                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Giá dự kiến (VNĐ) <span class="text-red-500">*</span></label>
                    <input type="number" min="0" name="estimated_price" value="<?= $old['estimated_price'] ?? '' ?>" placeholder="0"
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-1 focus:ring-orange-500 outline-none transition <?= isset($errors['estimated_price']) ? 'border-red-500' : '' ?>">
                    <?php if (isset($errors['estimated_price'])): ?>
                        <p class="text-red-500 text-xs mt-1"><?= $errors['estimated_price'][0] ?></p>
                    <?php endif; ?>
                </div>

                <!-- Loại dịch vụ -->
                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Loại dịch vụ <span class="text-red-500">*</span></label>
                    <select name="service_type_id" required
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-1 focus:ring-orange-500 outline-none bg-white">
                        <option value="">-- Chọn loại dịch vụ --</option>
                        <?php foreach ($serviceTypes as $type): ?>
                            <option value="<?= $type['id'] ?>"><?= $type['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Đơn vị tính -->
                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Đơn vị tính <span class="text-red-500">*</span></label>
                    <select name="unit" required
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-1 focus:ring-orange-500 outline-none bg-white">
                        <option value="person" <?= (isset($old['unit']) && $old['unit'] == 'person') ? 'selected' : '' ?>>Người</option>
                        <option value="room" <?= (isset($old['unit']) && $old['unit'] == 'room') ? 'selected' : '' ?>>Phòng</option>
                        <option value="vehicle" <?= (isset($old['unit']) && $old['unit'] == 'vehicle') ? 'selected' : '' ?>>Xe</option>
                        <option value="tour" <?= (isset($old['unit']) && $old['unit'] == 'tour') ? 'selected' : '' ?>>Tour</option>
                        <option value="day" <?= (isset($old['unit']) && $old['unit'] == 'day') ? 'selected' : '' ?>>Ngày</option>
                        <option value="meal" <?= (isset($old['unit']) && $old['unit'] == 'meal') ? 'selected' : '' ?>>Suất ăn</option>
                    </select>
                </div>

                <!-- Nhà cung cấp -->
                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Nhà cung cấp <span class="text-red-500">*</span></label>
                    <select name="supplier_id" required
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-1 focus:ring-orange-500 outline-none bg-white">
                        <option value="">-- Chọn nhà cung cấp --</option>
                        <?php foreach ($suppliers as $sup): ?>
                            <option value="<?= $sup['id'] ?>"><?= $sup['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Mô tả -->
            <div class="space-y-1">
                <label class="block text-sm font-medium text-gray-700">Mô tả dịch vụ</label>
                <textarea name="description" rows="4" placeholder="Nhập mô tả..."
                    class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-1 focus:ring-orange-500 outline-none resize-none"></textarea>
            </div>

            <!-- Action Buttons -->
            <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100">
                <a href="?act=service"
                    class="px-5 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition font-medium">
                    Hủy bỏ
                </a>
                <button type="submit"
                    class="px-5 py-2 rounded-lg bg-orange-500 text-white hover:bg-orange-600 transition font-medium shadow-sm flex items-center gap-2">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    Thêm dịch vụ
                </button>
            </div>

        </form>
    </div>
</div>

<?php
require_once './views/components/footer.php';
?>