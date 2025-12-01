<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';
?>

<main class="mt-28 px-6 pb-20">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Chi tiết nhật ký</h1>
            <p class="text-sm text-gray-600">Thông tin chi tiết của nhật ký</p>
        </div>
        <div class="flex space-x-3">
            <?php if ($tour['tour_status'] != '1'): ?>
                <a href="<?= BASE_URL . '?act=journal-edit&id=' . $journal['id'] ?>"
                    class="px-5 py-2.5 text-white text-sm font-medium rounded-lg bg-blue-500 hover:bg-blue-600 flex items-center space-x-2">
                    <i data-lucide="square-pen" class="w-4 h-4"></i>
                    <span>Chỉnh sửa</span>
                </a>
            <?php endif; ?>
            <a href="<?= BASE_URL . '?act=journal' ?>"
                class="px-5 py-2.5 border rounded-lg hover:bg-gray-50 text-sm font-medium">Quay lại</a>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow space-y-4">
        <p><strong>Tour:</strong> <?= htmlspecialchars($tour['tour_name']) ?> (<?= htmlspecialchars($tour['booking_code']) ?>)</p>
        <p><strong>Ngày viết:</strong> <?= htmlspecialchars($journal['date']) ?></p>
        <p><strong>Loại:</strong> <?= htmlspecialchars($journal['type']) ?></p>
        <p><strong>Nội dung:</strong></p>
        <div class="border p-4 rounded-lg bg-gray-50"><?= nl2br(htmlspecialchars($journal['content'])) ?></div>

        <?php if (!empty($images)): ?>
            <div>
                <p><strong>Ảnh đính kèm:</strong></p>
                <div class="flex flex-wrap gap-3 mt-2">
                    <?php foreach ($images as $img): ?>
                        <div class="w-32 h-24">
                            <img src="<?= BASE_URL . 'uploads/journals/' . $img['image_url'] ?>?>" class="w-full h-full object-cover rounded-lg border">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php require_once './views/components/footer.php'; ?>