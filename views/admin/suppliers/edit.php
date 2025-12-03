<?php
require_once "./views/components/header.php";
require_once "./views/components/sidebar.php";
?>

<div class="ml-54 mt-28 p-6">

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-semibold text-gray-800">Cập nhật nhà cung cấp</h2>
            <p class="text-gray-500 text-sm">Chỉnh sửa thông tin đối tác</p>
        </div>
        <a href="?act=suppliers"
            class="px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition flex items-center gap-2 text-sm font-medium">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Quay lại
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form action="?act=supplier-update&id=<?= $supplier['id'] ?>" method="POST" class="space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Tên -->
                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Tên nhà cung cấp <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="<?= htmlspecialchars($supplier['name']) ?>"
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-1 focus:ring-orange-500 outline-none transition" required>
                    <?php if (!empty($errors['name'])): ?>
                        <p class="text-red-500 text-xs mt-1"><?= $errors['name'][0] ?></p>
                    <?php endif; ?>
                </div>

                <!-- Email -->
                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Email liên hệ <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="<?= htmlspecialchars($supplier['email']) ?>"
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-1 focus:ring-orange-500 outline-none transition" required>
                    <?php if (!empty($errors['email'])): ?>
                        <p class="text-red-500 text-xs mt-1"><?= $errors['email'][0] ?></p>
                    <?php endif; ?>
                </div>

                <!-- Phone -->
                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Số điện thoại <span class="text-red-500">*</span></label>
                    <input type="text" name="phone" value="<?= htmlspecialchars($supplier['phone']) ?>"
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-1 focus:ring-orange-500 outline-none transition" required>
                    <?php if (!empty($errors['phone'])): ?>
                        <p class="text-red-500 text-xs mt-1"><?= $errors['phone'][0] ?></p>
                    <?php endif; ?>
                </div>

                <!-- Destination -->
                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Địa điểm hoạt động <span class="text-red-500">*</span></label>
                    <select name="destination_id" required
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-1 focus:ring-orange-500 outline-none bg-white">
                        <option value="">-- Chọn địa điểm --</option>
                        <?php foreach ($destinations as $dest): ?>
                            <option value="<?= $dest['id'] ?>" <?= ($supplier['destination_id'] == $dest['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($dest['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (!empty($errors['destination_id'])): ?>
                        <p class="text-red-500 text-xs mt-1"><?= $errors['destination_id'][0] ?></p>
                    <?php endif; ?>
                </div>

                <!-- Status -->
                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Trạng thái</label>
                    <select name="status"
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-1 focus:ring-orange-500 outline-none bg-white">
                        <option value="1" <?= ($supplier['status'] == 1) ? 'selected' : '' ?>>Hoạt động</option>
                        <option value="0" <?= ($supplier['status'] == 0) ? 'selected' : '' ?>>Ngừng hoạt động</option>
                    </select>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100">
                <a href="?act=suppliers"
                    class="px-5 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition font-medium">
                    Hủy bỏ
                </a>
                <button type="submit"
                    class="px-5 py-2 rounded-lg bg-orange-500 text-white hover:bg-orange-600 transition font-medium shadow-sm flex items-center gap-2">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    Lưu thay đổi
                </button>
            </div>

        </form>
    </div>
</div>

<?php require_once "./views/components/footer.php"; ?>