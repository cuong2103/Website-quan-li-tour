<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';
?>

<main class="mt-28 px-6 pb-20 overflow-auto scrollbar-hide space-y-6">
    <div class="text-xl font-semibold">
        Chào mừng trở lại, <?= htmlspecialchars($_SESSION['currentUser']['fullname'] ?? '') ?>!
    </div>

    <!-- tour hiện tại -->
    <div class="bg-blue-50 rounded-2xl border border-blue-100 p-5">
        <div class="flex justify-between items-center">
            <h3 class="font-semibold flex items-center gap-2 text-blue-900">
                <i data-lucide="map-pin"></i> Tour hiện tại
            </h3>
            <span class="bg-green-500 text-white text-xs px-3 py-1 rounded-full">Đang diễn ra</span>
        </div>

        <?php if (!empty($currentTours)): ?>
            <?php foreach ($currentTours as $t): ?>
                <div class="bg-white rounded-xl p-5 mt-4 shadow-sm border">
                    <div class="text-lg font-semibold text-gray-700">
                        <?= htmlspecialchars($t['tour_name'] ?? '') ?>
                    </div>
                    <div class="text-xs text-gray-500">Mã Booking: <?= $t['booking_code'] ?></div>

                    <div class="grid grid-cols-3 gap-4 mt-4 text-sm">
                        <div>
                            <p class="text-gray-500 text-xs">Thời gian</p>
                            <p class="font-medium">
                                <?= date('d/m/Y', strtotime($t['start_date'])) ?>
                                →
                                <?= date('d/m/Y', strtotime($t['end_date'])) ?>
                            </p>
                        </div>

                        <div>
                            <p class="text-gray-500 text-xs">Số khách</p>
                            <p class="font-medium"><?= $t['guest_count'] ?? '—' ?> khách</p>
                        </div>

                        <div>
                            <p class="text-gray-500 text-xs">Tiến độ</p>
                            <p class="font-medium">
                                Ngày <?= $t['current_day'] ?> / <?= $t['total_days'] ?>
                            </p>
                        </div>
                    </div>

                    <div class="flex justify-between items-center mt-5 gap-3">
                        <a href="<?= BASE_URL . '?act=checkin&assignment_id=' . $t['assignment_id'] ?>"
                            class="flex-[2] bg-blue-500 text-white text-xs px-4 py-2 rounded-lg hover:bg-blue-600 items-center justify-center flex gap-1">
                            <i data-lucide="user-check"></i> Check-in
                        </a>

                        <a href="<?= BASE_URL . '?act=guide-tour-assignments-detail&id=' . $t['assignment_id'] ?>"
                            class="flex-[6] bg-blue-500 text-white text-xs px-4 py-2 rounded-lg hover:bg-blue-600 items-center justify-center flex gap-1">
                            <i data-lucide="eye"></i> Xem chi tiết tour
                        </a>

                        <a href="<?= BASE_URL . '?act=journal-create&tour_assignment_id=' . $t['assignment_id'] ?>"
                            class="flex-[2] bg-blue-500 text-white text-xs px-4 py-2 rounded-lg hover:bg-blue-600 items-center justify-center flex gap-1">
                            <i data-lucide="clipboard"></i> Viết nhật ký
                        </a>

                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-sm text-gray-500 mt-3">Không có tour nào đang diễn ra</p>
        <?php endif; ?>
    </div>

    <!-- tour sắp tới -->
    <div class="bg-orange-50 border border-orange-200 rounded-2xl p-5">
        <h3 class="font-semibold flex items-center gap-2 text-orange-800">
            <i data-lucide="clock"></i> Tour sắp tới
        </h3>

        <?php if (!empty($upcomingTours)): ?>
            <div class="mt-4 space-y-3">
                <?php foreach ($upcomingTours as $u): ?>
                    <div class="bg-white border rounded-xl p-4 shadow-sm flex justify-between items-center">
                        <div>
                            <p class="font-medium text-gray-700"><?= htmlspecialchars($u['tour_name']) ?></p>
                            <p class="text-xs text-gray-500">Booking ID: <?= $u['booking_code'] ?></p>
                            <p class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                                <i data-lucide="calendar"></i>
                                <?= $u['start_date'] ?>
                                → <?= $u['end_date'] ?>
                            </p>
                        </div>

                        <a href="<?= BASE_URL . '?act=guide-tour-assignments-detail&id=' . $u['assignment_id'] ?>"
                            class="bg-gray-900 text-white text-xs px-3 py-1 rounded-lg hover:bg-black flex items-center gap-1">
                            <i data-lucide="eye"></i> Xem chi tiết
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-sm text-gray-500 mt-3">Chưa có tour sắp tới</p>
        <?php endif; ?>
    </div>

    <!-- thống kê -->
    <div class="grid grid-cols-3 gap-4">
        <div class="border rounded-xl p-4 shadow-sm bg-white">
            <p class="text-sm text-gray-600 flex items-center gap-1">
                <i data-lucide="book"></i> Nhật ký đã viết
            </p>
            <p class="text-2xl font-bold mt-1"><?= $journalCount ?? 0 ?></p>
        </div>

        <div class="border rounded-xl p-4 shadow-sm bg-white">
            <p class="text-sm text-gray-600 flex items-center gap-1">
                <i data-lucide="bell"></i> Thông báo mới
            </p>
            <p class="text-2xl font-bold text-red-500 mt-1"><?= $notificationCount ?? 0 ?></p>
        </div>

        <div class="border rounded-xl p-4 shadow-sm bg-white">
            <p class="text-sm text-gray-600 flex items-center gap-1">
                <i data-lucide="calendar"></i> Tổng tour tháng này
            </p>
            <p class="text-2xl font-bold mt-1">
                <?= count($currentTours) + count($upcomingTours) ?>
            </p>
        </div>
    </div>

</main>

<?php
require_once './views/components/footer.php';
?>