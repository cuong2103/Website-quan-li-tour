<?php
require_once "./views/components/header.php";
require_once "./views/components/sidebar.php";
?>

<div class="ml-54 pt-28 p-6">

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
                    <input type="text" name="name" value="<?= htmlspecialchars($_SESSION['old']['name'] ?? $supplier['name'] ?? '') ?>"
                        class="w-full px-4 py-2.5 rounded-lg border <?= isset($_SESSION['validate_errors']['name']) ? 'border-red-500 bg-red-50' : 'border-gray-300' ?> focus:border-orange-500 focus:ring-1 focus:ring-orange-500 outline-none transition" required>
                    <?php if (!empty($_SESSION['validate_errors']['name'])): ?>
                        <p class="text-red-500 text-sm mt-1"><?= is_array($_SESSION['validate_errors']['name']) ? implode(', ', $_SESSION['validate_errors']['name']) : $_SESSION['validate_errors']['name'] ?></p>
                    <?php endif; ?>
                </div>

                <!-- Email -->
                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Email liên hệ <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="<?= htmlspecialchars($_SESSION['old']['email'] ?? $supplier['email'] ?? '') ?>"
                        class="w-full px-4 py-2.5 rounded-lg border <?= isset($_SESSION['validate_errors']['email']) ? 'border-red-500 bg-red-50' : 'border-gray-300' ?> focus:border-orange-500 focus:ring-1 focus:ring-orange-500 outline-none transition" required>
                    <?php if (!empty($_SESSION['validate_errors']['email'])): ?>
                        <p class="text-red-500 text-sm mt-1"><?= is_array($_SESSION['validate_errors']['email']) ? implode(', ', $_SESSION['validate_errors']['email']) : $_SESSION['validate_errors']['email'] ?></p>
                    <?php endif; ?>
                </div>

                <!-- Phone -->
                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Số điện thoại <span class="text-red-500">*</span></label>
                    <input type="text" name="phone" value="<?= htmlspecialchars($_SESSION['old']['phone'] ?? $supplier['phone'] ?? '') ?>"
                        class="w-full px-4 py-2.5 rounded-lg border <?= isset($_SESSION['validate_errors']['phone']) ? 'border-red-500 bg-red-50' : 'border-gray-300' ?> focus:border-orange-500 focus:ring-1 focus:ring-orange-500 outline-none transition" required>
                    <?php if (!empty($_SESSION['validate_errors']['phone'])): ?>
                        <p class="text-red-500 text-sm mt-1"><?= is_array($_SESSION['validate_errors']['phone']) ? implode(', ', $_SESSION['validate_errors']['phone']) : $_SESSION['validate_errors']['phone'] ?></p>
                    <?php endif; ?>
                </div>

                <!-- Destination -->
                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Địa điểm hoạt động <span class="text-red-500">*</span></label>
                    <select name="destination_id" required
                        class="w-full px-4 py-2.5 rounded-lg border <?= isset($_SESSION['validate_errors']['destination_id']) ? 'border-red-500 bg-red-50' : 'border-gray-300' ?> focus:border-orange-500 focus:ring-1 focus:ring-orange-500 outline-none bg-white">
                        <option value="">-- Chọn địa điểm --</option>
                        <?php 
                        $selectedDest = $_SESSION['old']['destination_id'] ?? $supplier['destination_id'];
                        foreach ($destinations as $dest): 
                        ?>
                            <option value="<?= $dest['id'] ?>" <?= ($selectedDest == $dest['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($dest['name'] ?? '') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (!empty($_SESSION['validate_errors']['destination_id'])): ?>
                        <p class="text-red-500 text-sm mt-1"><?= is_array($_SESSION['validate_errors']['destination_id']) ? implode(', ', $_SESSION['validate_errors']['destination_id']) : $_SESSION['validate_errors']['destination_id'] ?></p>
                    <?php endif; ?>
                </div>

                <!-- Status -->
                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Trạng thái</label>
                    <select name="status"
                        class="w-full px-4 py-2.5 rounded-lg border <?= isset($_SESSION['validate_errors']['status']) ? 'border-red-500 bg-red-50' : 'border-gray-300' ?> focus:border-orange-500 focus:ring-1 focus:ring-orange-500 outline-none bg-white">
                        <?php $selectedStatus = $_SESSION['old']['status'] ?? $supplier['status'] ?? 1; ?>
                        <option value="1" <?= ($selectedStatus == 1) ? 'selected' : '' ?>>Hoạt động</option>
                        <option value="0" <?= ($selectedStatus == 0) ? 'selected' : '' ?>>Ngừng hoạt động</option>
                    </select>
                    <?php if (!empty($_SESSION['validate_errors']['status'])): ?>
                        <p class="text-red-500 text-sm mt-1"><?= is_array($_SESSION['validate_errors']['status']) ? implode(', ', $_SESSION['validate_errors']['status']) : $_SESSION['validate_errors']['status'] ?></p>
                    <?php endif; ?>
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

<?php 
unset($_SESSION['validate_errors']);
unset($_SESSION['old']);
require_once "./views/components/footer.php"; 
?>