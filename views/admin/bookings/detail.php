<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';
?>

<main class="pt-28 px-6 pb-20 text-gray-700">

    <!-- Tiêu đề + nút -->
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-semibold">Chi tiết Booking <?= $booking['booking_code'] ?></h1>
        <a href="<?= BASE_URL . '?act=bookings' ?>"
            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg text-sm">
            Quay lại
        </a>
    </div>


    <!-- Thông tin chung -->
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 mb-6">
        <h2 class="font-medium mb-4 text-gray-800">Thông tin chung</h2>

        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">

            <div>
                <p class="text-gray-500">Tour</p>
                <p class="font-medium">
                    <?= htmlspecialchars($booking['tour_name']) ?>
                </p>
            </div>

            <div>
                <p class="text-gray-500">Ngày đi</p>
                <p class="font-medium"><?= $booking['start_date'] ?></p>
            </div>

            <div>
                <p class="text-gray-500">Ngày về</p>
                <p class="font-medium"><?= $booking['end_date'] ?></p>
            </div>

            <div>
                <p class="text-gray-500">Số lượng</p>
                <p class="font-medium"><?= $booking['adult_count'] ?> NL, <?= $booking['child_count'] ?> TE</p>
            </div>

            <div>
                <p class="text-gray-500">Tiền dịch vụ</p>
                <p class="font-medium text-purple-600">
                    <?= number_format($booking['service_amount'] ?? 0, 0, ',', '.') ?>đ
                </p>
            </div>

            <div>
                <p class="text-gray-500">Tổng tiền</p>
                <p class="font-medium text-green-600">
                    <?= number_format($booking['total_amount'], 0, ',', '.') ?>đ
                </p>
            </div>

            <div>
                <p class="text-gray-500">Còn lại</p>
                <p class="font-medium <?= $remaining > 0 ? 'text-red-600' : 'text-green-600' ?>">
                    <?= number_format($remaining, 0, ',', '.') ?>đ
                </p>
            </div>

            <div>
                <p class="text-gray-500">Yêu cầu đặc biệt</p>
                <p class="font-medium break-words">
                    <?= nl2br(htmlspecialchars($booking['special_requests'] ?? '')) ?>
                </p>
            </div>

            <div>
                <p class="text-gray-500">Trạng thái</p>
                <p class="font-medium">
                    <?php
                    $statusArr = [
                        'pending' => 'Chưa thanh toán',
                        'deposited' => 'Đã cọc',
                        'paid' => 'Đã thanh toán đủ',
                        'cancelled' => 'Đã hủy',
                        'completed' => 'Hoàn thành Tour'
                    ];
                    echo $statusArr[$booking['status']] ?? 'Không xác định';
                    ?>
                </p>
            </div>
        </div>
    </div>

    <!-- Các tab -->
    <div class="flex flex-wrap gap-3 mb-6">
        <?php
        $tabs = [
            'customers' => ['icon' => 'users', 'label' => 'Khách hàng'],
            'services'  => ['icon' => 'concierge-bell', 'label' => 'Dịch vụ'],
            'payments'  => ['icon' => 'credit-card', 'label' => 'Thanh toán'],
            'contracts' => ['icon' => 'file-text', 'label' => 'Hợp đồng'],
            'itinerary' => ['icon' => 'map-pin', 'label' => 'Lịch trình'],
            'room_assignment' => ['icon' => 'bed-double', 'label' => 'Xếp phòng'],
            'checkin' => ['icon' => 'clipboard-check', 'label' => 'Check-in'],
            'journal' => ['icon' => 'book-open', 'label' => 'Nhật ký'],
        ];
        ?>
        <?php foreach ($tabs as $key => $t): ?>
            <a href="<?= BASE_URL . '?act=booking-detail&id=' . $booking['id'] . '&tab=' . $key ?>"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium
           <?= $tab === $key
                ? 'bg-gray-900 text-white'
                : 'bg-gray-100 hover:bg-gray-200 text-gray-700'
            ?>">
                <i class="w-4 h-4" data-lucide="<?= $t['icon'] ?>"></i>
                <?= $t['label'] ?>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- Tab Khách hàng -->
    <?php
    switch ($tab) {
        case 'customers':
            require_once './views/admin/bookings/tabs/customers.php';
            break;
        case 'services':
            require_once './views/admin/bookings/tabs/services.php';
            break;
        case 'payments':
            require_once './views/admin/bookings/tabs/payments.php';
            break;
        case 'contracts':
            require_once './views/admin/bookings/tabs/contracts.php';
            break;
        case 'itinerary':
            require_once './views/admin/bookings/tabs/itinerary.php';
            break;
        case 'room_assignment':
            require_once './views/admin/bookings/tabs/room_assignment.php';
            break;
        case 'checkin':
            require_once './views/admin/bookings/tabs/checkin.php';
            break;
        case 'journal':
            require_once './views/admin/bookings/tabs/journal.php';
            break;
    }
    ?>

</main>

<?php require_once './views/components/footer.php'; ?>