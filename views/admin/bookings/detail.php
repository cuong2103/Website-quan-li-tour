<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';
?>

<main class="mt-28 px-6 pb-20 text-gray-700">

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

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">

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
                <p class="text-gray-500">Tổng tiền</p>
                <p class="font-medium text-green-600">
                    <?= number_format($booking['total_amount'], 0, ',', '.') ?>đ
                </p>
            </div>


            <div>
                <p class="text-gray-500">Còn lại</p>
                <?php
                $totalPaid = (new BookingModel())->getTotalPaid($booking['id']);
                $remaining = $booking['total_amount'] - $totalPaid;
                ?>
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
                        1 => 'Chưa thanh toán',
                        2 => 'Đã cọc',
                        3 => 'Đã thanh toán đủ',
                        4 => 'Đã hủy',
                        5 => 'Hoàn thành Tour'
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
    <?php if ($tab == 'customers'): ?>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex justify-between flex-wrap items-center gap-3 mb-4">
                <h2 class="text-base font-semibold text-gray-800">Danh sách khách hàng</h2>

                <div class="flex items-center gap-2">
                    <!-- Form Upload Excel -->
                    <form action="<?= BASE_URL ?>?act=booking-upload-customers" method="POST" enctype="multipart/form-data" class="flex items-center gap-2">
                        <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                        <input type="file" name="file" accept=".xlsx, .xls" class="text-sm border border-gray-300 rounded-lg p-1" required>
                        <button type="submit" class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium flex items-center gap-1">
                            <i class="w-4 h-4" data-lucide="upload"></i> Upload Excel
                        </button>
                    </form>
                    <a href="<?= BASE_URL ?>?act=booking-export-customers&booking_id=<?= $booking['id'] ?>"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg text-sm font-medium">
                        <i class="w-4 h-4" data-lucide="download"></i>
                        Export Excel
                    </a>
                </div>
            </div>

            <?php if (!empty($customers)): ?>
                <div class="space-y-3">
                    <?php foreach ($customers as $c): ?>
                        <div class="p-4 border border-gray-100 rounded-xl bg-white shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-4 hover:bg-gray-50">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                                    <i data-lucide="user" class="w-5 h-5"></i>
                                </div>
                                <div class="flex flex-col">
                                    <p class="font-medium text-gray-800 mb-1">
                                        <?= htmlspecialchars($c['name']) ?>
                                        <?php if ($c['is_representative']): ?>
                                            <span class="ml-2 px-2 py-0.5 bg-green-100 text-green-700 text-xs rounded-full">Người đại diện</span>
                                        <?php endif; ?>
                                    </p>
                                    <div class="flex items-center gap-3 text-sm text-gray-500">
                                        <span class="flex items-center gap-1"><i class="w-3 h-3" data-lucide="phone"></i> <?= htmlspecialchars($c['phone']) ?></span>
                                        <span class="flex items-center gap-1"><i class="w-3 h-3" data-lucide="mail"></i> <?= htmlspecialchars($c['email']) ?></span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 text-gray-600">
                                <a href="<?= BASE_URL ?>?act=customer-edit&id=<?= $c['id'] ?>" class="p-1 hover:text-blue-600">
                                    <i class="w-4 h-4" data-lucide="square-pen"></i>
                                </a>
                                <a href="<?= BASE_URL ?>?act=booking-remove-customer&booking_id=<?= $booking['id'] ?>&customer_id=<?= $c['id'] ?>"
                                    onclick="return confirm('Xóa khách này khỏi booking?')"
                                    class="p-1 hover:text-red-600 text-red-500">
                                    <i class="w-4 h-4" data-lucide="trash-2"></i>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-gray-500 text-sm">Chưa có khách hàng nào.</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- Tab Dịch vụ -->
    <?php if ($tab == 'services'): ?>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex justify-between flex-wrap items-center gap-3 mb-4">
                <h2 class="text-base font-semibold text-gray-800">Danh sách dịch vụ</h2>

                <div class="flex items-center gap-2">
                    <a href="<?= BASE_URL . '?act=booking-edit&id=' . $booking['id']  ?>"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg text-sm font-medium">
                        <i class="w-4 h-4" data-lucide="square-pen"></i>
                        Chỉnh sửa dịch vụ
                    </a>
                </div>
            </div>
            <?php if (!empty($bookingServices)): ?>
                <ul class="space-y-2 text-gray-800 text-sm">
                    <?php foreach ($bookingServices as $s): ?>
                        <li class="flex items-center justify-between bg-gray-50 border border-gray-100 rounded-lg px-4 py-3">
                            <div class="flex items-center gap-3">
                                <i class="w-5 h-5 text-blue-600" data-lucide="check-circle"></i>
                                <div>
                                    <p class="font-medium"><?= htmlspecialchars($s['name']) ?></p>
                                    <span class="font-semibold text-gray-700">
                                        <?= number_format(($s['current_price'] ?? 0) * $s['quantity'], 0, ',', '.') ?>đ
                                    </span>
                                </div>
                            </div>
                            <span class="font-semibold text-gray-700">
                                <?= number_format($s['current_price'] * $s['quantity'], 0, ',', '.') ?>đ
                            </span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="text-gray-500 text-sm flex items-center gap-2">
                    <i class="w-4 h-4 text-gray-400" data-lucide="info"></i>
                    Chưa có dịch vụ nào.
                </p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- Tab Thanh toán -->
    <?php if ($tab == 'payments'): ?>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">

            <div class="flex justify-between flex-wrap items-center gap-3 mb-4">
                <h2 class="text-base font-semibold text-gray-800">Lịch sử thanh toán</h2>
                <a href="<?= BASE_URL ?>?act=payment-create&booking_id=<?= $booking['id'] ?>"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium">
                    <i class="w-4 h-4" data-lucide="plus"></i>
                    Thêm thanh toán
                </a>
            </div>

            <?php if (!empty($bookingPayments)): ?>
                <div class="space-y-3">
                    <?php foreach ($bookingPayments as $p): ?>
                        <div class="p-4 border border-gray-100 rounded-xl bg-white shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-4 hover:bg-gray-50">

                            <div class="flex flex-col flex-1">
                                <p class="font-medium text-gray-800 flex items-center gap-1 mb-1">
                                    <i class="w-4 h-4" data-lucide="wallet"></i>
                                    <?= htmlspecialchars($p['payment_method']) ?>
                                </p>

                                <p class="text-sm text-gray-700 mt-2 flex items-center gap-1 mb-1">
                                    <i class="w-4 h-4" data-lucide="circle-dollar-sign"></i>
                                    <?= htmlspecialchars($p['type']) ?>
                                </p>

                                <p class="text-sm text-gray-700 mt-1 flex items-center gap-1 mb-1">
                                    <i class="w-4 h-4" data-lucide="banknote"></i>
                                    Số tiền:
                                    <span class="font-semibold text-green-600"><?= number_format($p['amount'], 0, ',', '.') ?>đ</span>
                                </p>

                                <p class="text-sm text-gray-500 mt-1 flex items-center gap-1 mb-1">
                                    <i class="w-4 h-4" data-lucide="calendar"></i>
                                    Ngày: <?= date('Y-m-d', strtotime($p['payment_date'])) ?>
                                </p>

                                <p class="text-sm text-gray-500 mt-1 flex items-center gap-1 mb-1">
                                    <i class="w-4 h-4" data-lucide="file-edit"></i>
                                    Ghi chú: <?= !empty($p['notes']) ? htmlspecialchars($p['notes']) : '—' ?>
                                </p>
                            </div>

                            <!-- Status badge -->
                            <?php
                            $statusClass = 'bg-gray-100 text-gray-700';

                            if ($p['status'] === 'pending') $statusClass = 'bg-yellow-100 text-yellow-700';
                            elseif ($p['status'] === 'success') $statusClass = 'bg-green-100 text-green-700';
                            elseif ($p['status'] === 'failed') $statusClass = 'bg-red-100 text-red-700';
                            elseif ($p['status'] === 'refund') $statusClass = 'bg-blue-100 text-blue-700';
                            elseif ($p['status'] === 'expired') $statusClass = 'bg-red-100 text-red-700';
                            ?>
                            <span class="px-3 py-1 rounded-lg text-xs font-semibold <?= $statusClass ?>">
                                <?= htmlspecialchars($p['status']) ?>
                            </span>

                            <!-- Action buttons -->
                            <div class="flex items-center gap-2 text-gray-600">
                                <a href="<?= BASE_URL ?>?act=payment-edit&id=<?= $p['id'] ?>" class="p-1 hover:text-blue-600">
                                    <i class="w-4 h-4" data-lucide="square-pen"></i>
                                </a>

                                <a href="<?= BASE_URL ?>?act=payment-detail&id=<?= $p['id'] ?>" class="p-1 hover:text-blue-600">
                                    <i class="w-4 h-4" data-lucide="eye"></i>
                                </a>

                                <a href="<?= BASE_URL ?>?act=payment-delete&id=<?= $p['id'] ?>&booking_id=<?= $booking['id'] ?>"
                                    onclick="return confirm('Bạn có chắc muốn xóa thanh toán này?');"
                                    class="p-1 hover:text-red-600 text-red-500">
                                    <i class="w-4 h-4" data-lucide="trash-2"></i>
                                </a>
                            </div>

                        </div>
                    <?php endforeach; ?>
                </div>

            <?php else: ?>
                <p class="text-gray-500 text-sm">Chưa có thanh toán nào.</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- Tab Hợp đồng -->
    <?php if ($tab == 'contracts'): ?>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">

            <div class="flex justify-between flex-wrap items-center gap-3 mb-4">
                <h2 class="text-base font-semibold text-gray-800">Hợp đồng booking</h2>
                <a href="<?= BASE_URL ?>?act=contract-create&booking_id=<?= $booking['id'] ?>"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium">
                    <i class="w-4 h-4" data-lucide="file-plus"></i>
                    Tạo hợp đồng
                </a>
            </div>

            <?php if (!empty($bookingContracts)): ?>
                <div class="space-y-3">
                    <?php foreach ($bookingContracts as $c): ?>
                        <div class="p-4 border border-gray-100 rounded-xl bg-white shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-4 hover:bg-gray-50">

                            <!-- Thông tin -->
                            <div class="flex flex-col flex-1 gap-2">

                                <p class="font-medium text-gray-800 flex items-center gap-1">
                                    <i class="w-4 h-4 text-blue-600" data-lucide="file-text"></i>
                                    <?= htmlspecialchars($c['contract_name']) ?>
                                </p>

                                <p class="text-sm text-gray-500 flex items-center gap-1">
                                    <i class="w-4 h-4 text-green-600" data-lucide="calendar-check"></i>
                                    Hiệu lực: <?= !empty($c['effective_date']) ? date('Y-m-d', strtotime($c['effective_date'])) : '—' ?>
                                </p>

                                <p class="text-sm text-gray-500 flex items-center gap-1">
                                    <i class="w-4 h-4 text-red-600" data-lucide="calendar-x"></i>
                                    Hết hạn: <?= !empty($c['expiry_date']) ? date('Y-m-d', strtotime($c['expiry_date'])) : '—' ?>
                                </p>

                                <p class="text-sm text-gray-500 flex items-center gap-1">
                                    <i class="w-4 h-4 text-purple-600" data-lucide="pen-tool"></i>
                                    Người ký: <?= htmlspecialchars($_SESSION['currentUser']['fullname']) ?>
                                </p>

                                <p class="text-sm">
                                    <?php if (!empty($c['file_url'])): ?>
                                        <a href="<?= htmlspecialchars($c['file_url']) ?>"
                                            target="_blank"
                                            class="text-blue-600 underline inline-flex items-center gap-1 bg-blue-50 border border-blue-100 px-2 py-1 rounded-lg text-xs font-medium hover:bg-blue-100">
                                            <i class="w-4 h-4" data-lucide="download"></i>
                                            <?= htmlspecialchars($c['file_name'] ?? 'Tải file') ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-gray-400 flex items-center gap-1">
                                            <i class="w-4 h-4" data-lucide="file-off"></i>Không có file
                                        </span>
                                    <?php endif; ?>
                                </p>
                            </div>

                            <!-- Màu trạng thái -->
                            <?php
                            $statusClass = 'bg-gray-100 text-gray-700';
                            if ($c['status'] === 'active') $statusClass = 'bg-green-200 text-green-700';
                            elseif ($c['status'] === 'inactive') $statusClass = 'bg-red-200 text-red-700';
                            elseif ($c['status'] === 'expired') $statusClass = 'bg-yellow-200 text-yellow-700';
                            ?>
                            <span class="px-3 py-1 rounded-lg text-xs font-semibold <?= $statusClass ?>">
                                <?= htmlspecialchars($c['status'] ?? '—') ?>
                            </span>

                            <!-- nút -->
                            <div class="flex items-center gap-2 text-gray-600">
                                <a href="<?= BASE_URL ?>?act=contract-edit&id=<?= $c['id'] ?>" class="p-1 hover:text-blue-600">
                                    <i class="w-4 h-4" data-lucide="square-pen"></i>
                                </a>

                                <a href="<?= BASE_URL ?>?act=contract-detail&id=<?= $c['id'] ?>" class="p-1 hover:text-blue-600">
                                    <i class="w-4 h-4" data-lucide="eye"></i>
                                </a>

                                <a href="<?= BASE_URL ?>?act=contract-delete&id=<?= $c['id'] ?>"
                                    onclick="return confirm('Bạn có chắc muốn xóa hợp đồng này?');"
                                    class="p-1 hover:text-red-600 text-red-500">
                                    <i class="w-4 h-4" data-lucide="trash-2"></i>
                                </a>
                            </div>

                        </div>
                    <?php endforeach; ?>
                </div>

            <?php else: ?>
                <p class="text-gray-500 text-sm">Chưa có hợp đồng nào.</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</main>

<?php require_once './views/components/footer.php'; ?>