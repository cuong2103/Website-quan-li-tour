<?php
require_once "./views/components/header.php";
require_once "./views/components/sidebar.php";
?>

<div class="ml-54 pt-28 p-6">

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-semibold text-gray-800">Thêm nhà cung cấp</h2>
            <p class="text-gray-500 text-sm">Tạo mới đối tác cung cấp dịch vụ</p>
        </div>
        <a href="?act=suppliers"
            class="px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition flex items-center gap-2 text-sm font-medium">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Quay lại
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <?php if (!empty($err)): ?>
            <div class="mb-6 p-4 bg-red-50 border border-red-100 text-red-600 rounded-lg text-sm flex items-center gap-2">
                <i data-lucide="alert-circle" class="w-4 h-4"></i>
                <?= $err ?>
            </div>
        <?php endif; ?>

        <form action="?act=supplier-store" method="POST" class="space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Tên -->
                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Tên nhà cung cấp <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="<?= $_POST['name'] ?? '' ?>" placeholder="Nhập tên nhà cung cấp..."
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-1 focus:ring-orange-500 outline-none transition" required>
                </div>

                <!-- Email -->
                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Email liên hệ <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="<?= $_POST['email'] ?? '' ?>" placeholder="example@domain.com"
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-1 focus:ring-orange-500 outline-none transition" required>
                </div>

                <!-- Phone -->
                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Số điện thoại <span class="text-red-500">*</span></label>
                    <input type="text" name="phone" value="<?= $_POST['phone'] ?? '' ?>" placeholder="0123 456 789"
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-1 focus:ring-orange-500 outline-none transition" required>
                </div>

                <!-- Destination -->
                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Địa điểm hoạt động <span class="text-red-500">*</span></label>
                    <select name="destination_id" required
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-1 focus:ring-orange-500 outline-none bg-white">
                        <option value="">-- Chọn địa điểm --</option>
                        <?php foreach ($destinations as $dest): ?>
                            <option value="<?= $dest['id'] ?>" <?= (isset($_POST['destination_id']) && $_POST['destination_id'] == $dest['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($dest['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Status -->
                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Trạng thái</label>
                    <select name="status"
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-1 focus:ring-orange-500 outline-none bg-white">
                        <option value="1" selected>Hoạt động</option>
                        <option value="0">Tạm dừng</option>
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
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    Thêm mới
                </button>
            </div>

        </form>
    </div>
</div>

<?php require_once "./views/components/footer.php"; ?>