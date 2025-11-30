<?php
require './views/components/header.php';
require './views/components/sidebar.php';

// tabs
$status_tab = $_GET['status'] ?? 'upcoming';
$tabs = [
    'upcoming'  => ['label' => 'Sắp đi', 'count' => $upcomingCount ?? 0],
    'ongoing'   => ['label' => 'Đang đi', 'count' => $ongoingCount ?? 0],
    'completed' => ['label' => 'Đã hoàn thành', 'count' => $completedCount ?? 0]
];
?>

<main class="mt-28 px-6 pb-20 text-gray-700">
    <div class="mb-6">
        <h1 class="text-2xl font-bold">Tour của tôi</h1>
        <p class="text-gray-500 text-sm">Quản lý các tour được phân công</p>
    </div>

    <!-- tab filter -->
    <div class="flex gap-2 mb-6">
        <?php foreach ($tabs as $key => $t): ?>
            <a href="<?= BASE_URL . '?act=guide-tour-assignments&status=' . $key ?>"
                class="px-4 py-2 rounded-lg text-sm font-medium border
               <?= $status_tab === $key ? 'bg-gray-200 border-gray-400' : 'bg-white border-gray-200 hover:bg-gray-100' ?>">
                <?= $t['label'] ?> (<?= $t['count'] ?>)
            </a>
        <?php endforeach; ?>
    </div>

    <!-- table tour -->
    <div class="bg-white border shadow rounded-xl p-6">
        <?php if (!empty($assignments)): ?>
            <table class="w-full text-sm text-gray-700">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3 text-left">Tên Tour</th>
                        <th class="p-3 text-left">Ngày đi</th>
                        <th class="p-3 text-left">Ngày về</th>
                        <th class="p-3 text-left">Số khách</th>
                        <th class="p-3 text-left">Trạng thái</th>
                        <th class="p-3 text-left">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($assignments as $a): ?>
                        <tr class="border-t hover:bg-gray-50">
                            <td class="p-3"><?= htmlspecialchars($a['tour_name']) ?></td>
                            <td class="p-3"><?= htmlspecialchars($a['start_date']) ?></td>
                            <td class="p-3"><?= htmlspecialchars($a['end_date']) ?></td>
                            <td class="p-3"><?= htmlspecialchars($a['total_customers']) ?></td>
                            <td class="p-3">
                                <?php
                                $statusLabel = [
                                    'upcoming' => 'Sắp đi',
                                    'ongoing' => 'Đang đi',
                                    'completed' => 'Đã hoàn thành'
                                ][$a['status']];
                                $statusClass = [
                                    'upcoming' => 'bg-yellow-100 text-yellow-800',
                                    'ongoing' => 'bg-green-100 text-green-800',
                                    'completed' => 'bg-blue-200 text-gray-700'
                                ][$a['status']];
                                ?>
                                <span class="px-2 py-1 text-xs rounded-full <?= $statusClass ?>">
                                    <?= $statusLabel ?>
                                </span>
                            </td>
                            <td class="p-3 flex gap-2">
                                <a href="<?= BASE_URL . '?act=guide-tour-assignments-detail&id=' . $a['id'] ?>"
                                    class="text-blue-600 hover:text-blue-800 flex-1">
                                    Xem chi tiết
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-gray-500">Chưa có tour nào trong danh sách.</p>
        <?php endif; ?>
    </div>
</main>

<?php require './views/components/footer.php'; ?>