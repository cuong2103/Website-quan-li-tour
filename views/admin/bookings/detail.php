<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';
?>

<main class="mt-28 px-6 pb-20 text-gray-700">

    <!-- Tiêu đề + nút -->
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-semibold">Chi tiết Booking #<?= $booking['id'] ?></h1>
        <a href="<?= BASE_URL . '?act=bookings' ?>"
            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg text-sm">
            Quay lại
        </a>
    </div>

    <!-- Tour Subtitle -->
    <p class="text-sm text-gray-500 mb-4">
        <?= htmlspecialchars($booking['tour_name']) ?>
    </p>

    <!-- Thông tin chung -->
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 mb-6">
        <h2 class="font-medium mb-4 text-gray-800">Thông tin chung</h2>

        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
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
                <p class="text-gray-500">Tổng tiền</p>
                <p class="font-medium text-green-600">
                    <?= number_format($booking['total_amount'], 0, ',', '.') ?>đ
                </p>
            </div>

            <div>
                <p class="text-gray-500">Trạng thái</p>
                <p class="font-medium">
                    <?php
                    $statusArr = [
                        1 => 'Chưa thanh toán',
                        2 => 'Đã cọc',
                        3 => 'Đã thanh toán đủ',
                        4 => 'Đã hủy',
                        5 => 'Hoàn thành Tour'
                    ];
                    echo $statusArr[$booking['status']];
                    ?>
                </p>
            </div>

            <div>
                <p class="text-gray-500">Yêu cầu đặc biệt</p>
                <p class="font-medium">
                    <?= nl2br(htmlspecialchars($booking['special_requests'])) ?>
                </p>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="flex gap-3 mb-6">

        <a href="<?= BASE_URL . '?act=booking-detail&id=' . $booking['id'] . '&tab=customers' ?>"
            class="px-4 py-2 rounded-lg text-sm <?= ($tab == 'customers' ? 'bg-gray-900 text-white' : 'bg-gray-100 hover:bg-gray-200') ?>">
            Khách hàng
        </a>

        <a href="<?= BASE_URL . '?act=booking-detail&id=' . $booking['id'] . '&tab=services' ?>"
            class="px-4 py-2 rounded-lg text-sm <?= ($tab == 'services' ? 'bg-gray-900 text-white' : 'bg-gray-100 hover:bg-gray-200') ?>">
            Dịch vụ
        </a>

        <a href="<?= BASE_URL . '?act=booking-detail&id=' . $booking['id'] . '&tab=payments' ?>"
            class="px-4 py-2 rounded-lg text-sm <?= ($tab == 'payments' ? 'bg-gray-900 text-white' : 'bg-gray-100 hover:bg-gray-200') ?>">
            Thanh toán
        </a>

        <a href="<?= BASE_URL . '?act=booking-detail&id=' . $booking['id'] . '&tab=contracts' ?>"
            class="px-4 py-2 rounded-lg text-sm <?= ($tab == 'contracts' ? 'bg-gray-900 text-white' : 'bg-gray-100 hover:bg-gray-200') ?>">
            Hợp đồng
        </a>

    </div>

    <!-- TAB: KHÁCH HÀNG -->
    <?php if ($tab == 'customers'): ?>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">

            <div class="flex justify-between items-center mb-4">
                <h2 class="text-base font-semibold">Danh sách khách hàng</h2>
                <a href="<?= BASE_URL . '?act=booking-add-customer&id=' . $booking['id'] ?>"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg">
                    Thêm khách hàng
                </a>
            </div>

            <div class="space-y-4">
                <?php foreach ($booking['customers'] as $c): ?>
                    <div class="flex items-center justify-between p-4 border border-gray-100 rounded-xl hover:bg-gray-50">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                                <i class="fa-solid fa-user text-lg"></i>
                            </div>

                            <div>
                                <p class="font-medium text-gray-800">
                                    <?= htmlspecialchars($c['name']) ?>

                                    <?php if ($c['is_representative'] == 1): ?>
                                        <span class="ml-2 text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded">
                                            Người đại diện
                                        </span>
                                    <?php endif; ?>
                                </p>

                                <p class="text-sm text-gray-500">
                                    <?= $c['phone'] ?> • <?= $c['email'] ?>
                                </p>
                            </div>
                        </div>

                        <a href="<?= BASE_URL . '?act=customer-detail&id=' . $c['id'] ?>"
                            class="text-sm text-blue-600 hover:underline">
                            Xem chi tiết
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>
    <?php endif; ?>


    <!-- TAB: DỊCH VỤ -->
    <?php if ($tab == 'services'): ?>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-base font-semibold mb-3">Dịch vụ đi kèm</h2>

            <?php if (!empty($bookingServices)): ?>
                <ul class="list-disc ml-6 text-gray-700">
                    <?php foreach ($bookingServices as $s): ?>
                        <li><?= htmlspecialchars($s['name']) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="text-gray-500">Chưa có dịch vụ nào.</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>



    <!-- TAB: THANH TOÁN -->
    <?php if ($tab == 'payments'): ?>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-base font-semibold mb-3">Lịch sử thanh toán</h2>
            <?php if (!empty($booking['payments'])): ?>
                <?php foreach ($booking['payments'] as $p): ?>
                    <div class="p-4 border rounded mb-2">
                        <p>Số tiền: <?= number_format($p['amount'], 0, ',', '.') ?>đ</p>
                        <p>Ngày: <?= $p['payment_date'] ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-gray-500">Chưa có thanh toán.</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>


    <!-- TAB: HỢP ĐỒNG -->
    <?php if ($tab == 'contracts'): ?>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-base font-semibold mb-3">Hợp đồng tour</h2>

            <?php if (!empty($booking['contract'])): ?>
                <a href="<?= $booking['contract']['file_url'] ?>" class="text-blue-600 underline">
                    Tải hợp đồng
                </a>
            <?php else: ?>
                <p class="text-gray-500">Chưa có hợp đồng.</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

</main>

<?php require_once './views/components/footer.php'; ?>