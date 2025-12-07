<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';
?>

<main class="pt-28 px-6 pb-20 overflow-auto scrollbar-hide">
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center gap-4 mb-6">
            <button onclick="history.back()" class="p-2 hover:bg-gray-100 rounded-lg transition">
                <i data-lucide="chevron-left" class="w-6 h-6"></i>
            </button>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Tour của tôi</h2>
                <p class="text-sm text-gray-600">Quản lý các tour được phân công</p>
            </div>
        </div>
    </div>

    <div class="bg-white p-4 rounded-lg shadow">
        <table class="w-full text-left">
            <thead>
                <tr class="border-b">
                    <th class="py-5">Tour</th>
                    <th class="py-5">Mã booking</th>
                    <th class="py-5">Ngày viết</th>
                    <th class="py-5">Nội dung</th>
                    <th class="py-5">Hành động</th>
                </tr>
            </thead>

            <tbody>
                <?php if (!empty($journals)): ?>
                    <?php foreach ($journals as $j): ?>
                        <tr class="border-b text-sm hover:bg-gray-50">
                            <td class="py-5"><?= htmlspecialchars($j['tour_name']) ?></td>
                            <td class="py-5"><?= htmlspecialchars($j['booking_code']) ?></td>
                            <td class="py-5"><?= htmlspecialchars($j['date']) ?></td>
                            <td class="py-5"><?= nl2br(htmlspecialchars($j['content'])) ?></td>
                            <td class="py-5">
                                <div class="flex gap-1 flex-shrink-0">
                                    <?php if ($j['tour_status'] != '1'): ?>
                                        <a href="<?= BASE_URL . '?act=journal-edit&id=' . $j['id'] ?>"
                                            class="inline-flex items-center justify-center gap-1 px-1 text-blue-500 hover:text-blue-700">
                                            <i class="w-4 h-4" data-lucide="square-pen"></i>
                                        </a>
                                    <?php endif; ?>

                                    <a href="<?= BASE_URL . '?act=journal-detail&id=' . $j['id'] ?>"
                                        class="inline-flex items-center justify-center gap-1 px-1 text-gray-500 hover:text-gray-700">
                                        <i class="w-4 h-4" data-lucide="eye"></i>
                                    </a>

                                    <a href="<?= BASE_URL . '?act=journal-delete&id=' . $j['id'] ?>"
                                        onclick="return confirm('Bạn có chắc muốn xoá không?')"
                                        class="inline-flex items-center justify-center gap-1 px-1 text-red-600 hover:text-red-700">
                                        <i class="w-4 h-4" data-lucide="trash-2"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="py-4 text-center text-gray-500">Chưa có nhật ký</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<?php require_once './views/components/footer.php'; ?>