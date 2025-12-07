<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';

$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];
unset($_SESSION['errors'], $_SESSION['old']);
?>

<main class="pt-28 px-6 pb-20 text-gray-700">
    
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-semibold">Xin Nghỉ Phép</h1>
        <a href="<?= BASE_URL ?>?act=guide-leave"
            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg text-sm">
            Quay lại
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-2xl">
        
        <form action="<?= BASE_URL ?>?act=guide-leave-store" method="POST">
            
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">
                    Từ ngày <span class="text-red-500">*</span>
                </label>
                <input type="date" 
                    name="leave_start" 
                    value="<?= $old['leave_start'] ?? '' ?>"
                    min="<?= date('Y-m-d') ?>"
                    class="w-full px-4 py-2 border rounded-lg <?= isset($errors['leave_start']) ? 'border-red-500' : 'border-gray-300' ?>"
                    required>
                <?php if (isset($errors['leave_start'])): ?>
                    <p class="text-red-500 text-sm mt-1"><?= $errors['leave_start'] ?></p>
                <?php endif; ?>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">
                    Đến ngày <span class="text-red-500">*</span>
                </label>
                <input type="date" 
                    name="leave_end" 
                    value="<?= $old['leave_end'] ?? '' ?>"
                    min="<?= date('Y-m-d') ?>"
                    class="w-full px-4 py-2 border rounded-lg <?= isset($errors['leave_end']) ? 'border-red-500' : 'border-gray-300' ?>"
                    required>
                <?php if (isset($errors['leave_end'])): ?>
                    <p class="text-red-500 text-sm mt-1"><?= $errors['leave_end'] ?></p>
                <?php endif; ?>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium mb-2">
                    Lý do xin nghỉ <span class="text-red-500">*</span>
                </label>
                <textarea 
                    name="leave_reason" 
                    rows="4"
                    placeholder="Vui lòng nêu rõ lý do xin nghỉ (tối thiểu 10 ký tự)..."
                    class="w-full px-4 py-2 border rounded-lg <?= isset($errors['leave_reason']) ? 'border-red-500' : 'border-gray-300' ?>"
                    required><?= $old['leave_reason'] ?? '' ?></textarea>
                <?php if (isset($errors['leave_reason'])): ?>
                    <p class="text-red-500 text-sm mt-1"><?= $errors['leave_reason'] ?></p>
                <?php endif; ?>
                <p class="text-xs text-gray-500 mt-1">Lý do cần ít nhất 10 ký tự</p>
            </div>

            <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg mb-6">
                <p class="text-blue-800 text-sm">
                    <i class="w-4 h-4 inline" data-lucide="info"></i>
                    Đơn xin nghỉ sẽ được gửi đến admin để duyệt. Bạn sẽ nhận được thông báo khi đơn được xử lý.
                </p>
            </div>

            <div class="flex gap-2">
                <button type="submit"
                    class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium">
                    Gửi đơn xin nghỉ
                </button>
                <a href="<?= BASE_URL ?>?act=guide-leave"
                    class="px-6 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg text-sm font-medium">
                    Hủy
                </a>
            </div>

        </form>

    </div>

</main>

<?php require_once './views/components/footer.php'; ?>
