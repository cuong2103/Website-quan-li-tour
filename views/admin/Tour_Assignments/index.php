<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';

function renderAssignmentBadge($status)
{
    switch ($status) {
        case 1:
            return '<span class="px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">Đang hoạt động</span>';
        case 2:
            return '<span class="px-3 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-700">Đã kết thúc</span>';
        case 3:
            return '<span class="px-3 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700">Đã hủy</span>';
        default:
            return '<span class="px-3 py-1 text-xs font-medium rounded-full bg-gray-200 text-gray-600">Không rõ</span>';
    }
}
?>

<main class="mt-28 px-6 pb-20 overflow-auto scrollbar-hide">

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Vận hành Tour</h1>
            <p class="text-sm text-gray-600">Phân công hướng dẫn cho các tour theo booking</p>
        </div>
        <div class="flex space-x-3">
            <a href="<?= BASE_URL . '?act=tour-assignment-create' ?>"
                class="px-5 py-2.5 text-white text-sm font-medium rounded-lg bg-orange-400 hover:bg-orange-500 flex items-center gap-2">
                <i class="w-4 h-4" data-lucide="plus"></i>
                <span>Thêm phân công</span>
            </a>
        </div>
    </div>

    <!-- Table Container -->
    <div class="bg-white p-4 rounded-lg shadow">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="border-b text-gray-800 font-medium">
                    <th class="py-4">Tên booking</th>
                    <th class="py-4">Hướng dẫn viên</th>
                    <th class="py-4">Ngày đi</th>
                    <th class="py-4">Ngày về</th>
                    <th class="py-4">Trạng thái</th>
                    <th class="py-4 text-center">Hành động</th>
                </tr>
            </thead>

            <tbody>
                <?php if (!empty($assignments)): ?>
                    <?php foreach ($assignments as $a): ?>
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="py-4">#<?= $a['booking_id'] ?? $a['booking_id'] ?></td>
                            <td class="py-4">
                                <?= $a['guide_name'] ?? '<span class="text-gray-400 italic">Chưa phân công</span>' ?>
                            </td>
                            <td class="py-4"><?= $a['start_date'] ?></td>
                            <td class="py-4"><?= $a['end_date'] ?></td>
                            <td class="py-4"><?= renderAssignmentBadge($a['status']); ?></td>

                            <!-- Actions -->
                            <td class="py-4 flex justify-center">
                                <div class="flex gap-2 flex-shrink-0">

                                    <!-- Edit -->
                                    <a href="<?= BASE_URL . '?act=tour-assignment-edit&id=' . $a['id'] ?>"
                                        class="inline-flex items-center justify-center p-1 hover:text-orange-500 transition">
                                        <i class="w-4 h-4" data-lucide="square-pen"></i>
                                    </a>

                                    <!-- Delete -->
                                    <a href="<?= BASE_URL . '?act=tour-assignment-delete&id=' . $a['id'] ?>"
                                        onclick="return confirm('Bạn có chắc muốn xóa phân công này không?')"
                                        class="inline-flex items-center justify-center p-1 text-red-600 hover:text-red-800 transition">
                                        <i class="w-4 h-4" data-lucide="trash-2"></i>
                                    </a>

                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="py-6 text-center text-gray-400 italic">
                            Chưa có phân công hướng dẫn viên cho tour nào
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</main>

<!-- Auto submit form filter/search (nếu sau này có thêm filter) -->
<script>
    let timer;
    const form = document.querySelector("form");
    if (form) {
        document.querySelectorAll("form input, form select").forEach(element => {
            element.addEventListener("input", () => {
                clearTimeout(timer);
                timer = setTimeout(() => form.submit(), 600);
            });
        });
    }
</script>

<?php require_once './views/components/footer.php'; ?>