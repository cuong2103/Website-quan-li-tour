<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';
?>

<main class="pt-28 p-6 min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Nhân viên đang nghỉ phép</h1>
            <p class="text-gray-600 mt-1">Danh sách các nhân viên hiện đang trong thời gian nghỉ phép</p>
        </div>
        <a href="?act=user" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition-colors flex items-center gap-2">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
            Quay lại
        </a>
    </div>

    <?php if (empty($users)): ?>
        <!-- Empty State -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <i data-lucide="alert-circle" class="mx-auto h-16 w-16 text-gray-400 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Không có nhân viên nào đang nghỉ phép</h3>
            <p class="text-gray-500">Tất cả nhân viên hiện đang hoạt động</p>
        </div>
    <?php else: ?>
        <!-- Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nhân viên
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ngày bắt đầu
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ngày kết thúc
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Số ngày nghỉ
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Trạng thái
                            </th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Hành động
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($users as $user):
                            $start = new DateTime($user['leave_start']);
                            $end = new DateTime($user['leave_end']);
                            $today = new DateTime();
                            $diff = $start->diff($end);
                            $days = $diff->days + 1;

                            // Kiểm tra trạng thái
                            // Kiểm tra trạng thái
                            $today = new DateTime();
                            $today->setTime(0, 0, 0);

                            $start = new DateTime($user['leave_start']);
                            $start->setTime(0, 0, 0);

                            $end = new DateTime($user['leave_end']);
                            $end->setTime(0, 0, 0);

                            $isActive = ($today >= $start && $today <= $end);
                            $isPast = ($today > $end);
                            $isFuture = ($today < $start);
                        ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center overflow-hidden">
                                            <?php if (!empty($user['avatar'])): ?>
                                                <img src="/uploads/avatar/<?= $user['avatar'] ?>" class="w-full h-full object-cover">
                                            <?php else: ?>
                                                <span class="text-lg font-bold text-blue-600"><?= strtoupper(substr($user['fullname'], 0, 1)) ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900"><?= htmlspecialchars($user['fullname']) ?></p>
                                            <p class="text-sm text-gray-500"><?= htmlspecialchars($user['email']) ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="calendar" class="w-4 h-4 text-gray-400"></i>
                                        <span class="text-gray-900"><?= date('d/m/Y', strtotime($user['leave_start'])) ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="calendar" class="w-4 h-4 text-gray-400"></i>
                                        <span class="text-gray-900"><?= date('d/m/Y', strtotime($user['leave_end'])) ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-gray-900 font-medium"><?= $days ?> ngày</span>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if ($isActive): ?>
                                        <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                            Đang nghỉ
                                        </span>
                                    <?php elseif ($isPast): ?>
                                        <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">
                                            Đã hết hạn
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                            Sắp nghỉ
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="?act=user-detail&id=<?= $user['id'] ?>"
                                            class="p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                            title="Xem chi tiết">
                                            <i data-lucide="eye" class="w-5 h-5"></i>
                                        </a>
                                        <a href="?act=user-end-leave&id=<?= $user['id'] ?>"
                                            onclick="return confirm('Bạn có chắc muốn kết thúc nghỉ phép cho <?= htmlspecialchars($user['fullname']) ?>?')"
                                            class="p-2 text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                            title="Kết thúc nghỉ phép">
                                            <i data-lucide="x" class="w-5 h-5"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Summary -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-center gap-3">
                <i data-lucide="alert-circle" class="w-5 h-5 text-blue-600"></i>
                <p class="text-sm text-blue-800">
                    <strong>Tổng cộng:</strong> <?= count($users) ?> nhân viên đang/sắp nghỉ phép
                </p>
            </div>
        </div>
    <?php endif; ?>
</main>

<?php require_once './views/components/footer.php'; ?>