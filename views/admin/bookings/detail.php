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
                                    <?php
                                    $methodLabels = [
                                        'cash' => 'Tiền mặt',
                                        'bank_transfer' => 'Chuyển khoản'
                                    ];
                                    echo $methodLabels[$p['payment_method']] ?? $p['payment_method'];
                                    ?>
                                </p>

                                <p class="text-sm text-gray-700 mt-2 flex items-center gap-1 mb-1">
                                    <i class="w-4 h-4" data-lucide="circle-dollar-sign"></i>
                                    <?php
                                    $typeLabels = [
                                        'deposit' => 'Cọc',
                                        'full_payment' => 'Thanh toán đủ',
                                        'remaining' => 'Thanh toán còn lại',
                                        'refund' => 'Hoàn tiền'
                                    ];
                                    echo $typeLabels[$p['type']] ?? $p['type'];
                                    ?>
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
                            </div>

                            <!-- Status badge -->
                            <?php
                            $statusClass = 'bg-gray-100 text-gray-700';
                            $statusText = 'Không xác định';

                            if ($p['status'] === 'pending') {
                                $statusClass = 'bg-yellow-100 text-yellow-700';
                                $statusText = 'Chờ xử lý';
                            } elseif ($p['status'] === 'completed') {
                                $statusClass = 'bg-green-100 text-green-700';
                                $statusText = 'Thành công';
                            } elseif ($p['status'] === 'failed') {
                                $statusClass = 'bg-red-100 text-red-700';
                                $statusText = 'Thất bại';
                            } elseif ($p['status'] === 'refund') {
                                $statusClass = 'bg-blue-100 text-blue-700';
                                $statusText = 'Hoàn tiền';
                            } elseif ($p['status'] === 'expired') {
                                $statusClass = 'bg-red-100 text-red-700';
                                $statusText = 'Hết hạn';
                            }
                            ?>
                            <span class="px-3 py-1 rounded-lg text-xs font-semibold <?= $statusClass ?>">
                                <?= $statusText ?>
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
                            $statusText = 'Không xác định';

                            if ($c['status'] === 'active') {
                                $statusClass = 'bg-green-200 text-green-700';
                                $statusText = 'Đang hoạt động';
                            } elseif ($c['status'] === 'inactive') {
                                $statusClass = 'bg-red-200 text-red-700';
                                $statusText = 'Ngừng hoạt động';
                            } elseif ($c['status'] === 'expired') {
                                $statusClass = 'bg-yellow-200 text-yellow-700';
                                $statusText = 'Hết hạn';
                            }
                            ?>
                            <span class="px-3 py-1 rounded-lg text-xs font-semibold <?= $statusClass ?>">
                                <?= $statusText ?>
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

    <!-- Tab Xếp phòng -->
    <?php if ($tab == 'room_assignment'): ?>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex justify-between flex-wrap items-center gap-3 mb-4">
                <h2 class="text-base font-semibold text-gray-800">Xếp phòng khách sạn</h2>

                <div class="flex items-center gap-2">
                    <!-- Form Upload Excel -->
                    <form action="<?= BASE_URL ?>?act=booking-import-rooms" method="POST" enctype="multipart/form-data" class="flex items-center gap-2">
                        <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                        <input type="file" name="excel_file" accept=".xlsx, .xls" class="text-sm border border-gray-300 rounded-lg p-1" required>
                        <button type="submit" class="px-3 py-1.5 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-sm font-medium flex items-center gap-1">
                            <i class="w-4 h-4" data-lucide="upload"></i> Upload Excel
                        </button>
                    </form>
                    <a href="<?= BASE_URL ?>?act=booking-export-rooms&booking_id=<?= $booking['id'] ?>"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg text-sm font-medium">
                        <i class="w-4 h-4" data-lucide="download"></i>
                        Export Excel
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th class="px-4 py-3">Khách hàng</th>
                            <th class="px-4 py-3">Số phòng</th>
                            <th class="px-4 py-3">Ghi chú</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($customers)): ?>
                            <?php foreach ($customers as $c): ?>
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-4 py-3 font-medium text-gray-900">
                                        <?= htmlspecialchars($c['name']) ?>
                                    </td>
                                    <td class="px-4 py-3">
                                        <?php if (!empty($c['room_number'])): ?>
                                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-semibold">
                                                <?= htmlspecialchars($c['room_number']) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-gray-400 italic">Chưa xếp</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-4 py-3 text-gray-600">
                                        <?= htmlspecialchars($c['notes'] ?? '') ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="px-4 py-3 text-center text-gray-500 italic">Chưa có khách hàng nào.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>

    <!-- Tab Check-in -->
    <?php if ($tab == 'checkin'): ?>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex justify-between flex-wrap items-center gap-3 mb-4">
                <h2 class="text-base font-semibold text-gray-800">Danh sách Check-in</h2>
            </div>

            <?php if (!empty($checkinLinks)): ?>
                <div class="space-y-4">
                    <?php foreach ($checkinLinks as $link): ?>
                        <div class="p-4 border border-gray-200 rounded-xl bg-gray-50 hover:bg-gray-100">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <h3 class="font-medium text-gray-800 flex items-center gap-2">
                                        <i class="w-5 h-5 text-blue-600" data-lucide="clipboard-check"></i>
                                        <?= htmlspecialchars($link['title']) ?>
                                    </h3>
                                    <?php if (!empty($link['note'])): ?>
                                        <p class="text-sm text-gray-600 mt-1"><?= nl2br(htmlspecialchars($link['note'])) ?></p>
                                    <?php endif; ?>
                                    <p class="text-xs text-gray-500 mt-2">
                                        <i class="w-3 h-3" data-lucide="calendar"></i>
                                        Tạo lúc: <?= date('d/m/Y H:i', strtotime($link['created_at'])) ?>
                                    </p>
                                </div>
                                <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-lg text-sm font-semibold">
                                    <?= $link['checked_count'] ?> người đã check-in
                                </span>
                            </div>

                            <!-- Danh sách khách đã check-in -->
                            <?php
                            $checkedCustomers = $this->checkinModel->getCheckedCustomers($link['id']);
                            if (!empty($checkedCustomers)):
                            ?>
                                <div class="mt-3 pt-3 border-t border-gray-200">
                                    <p class="text-sm font-medium text-gray-700 mb-2">Khách đã check-in:</p>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                        <?php foreach ($checkedCustomers as $customer): ?>
                                            <div class="flex items-center gap-2 text-sm bg-white p-2 rounded-lg">
                                                <i class="w-4 h-4 text-green-600" data-lucide="check-circle"></i>
                                                <span class="font-medium"><?= htmlspecialchars($customer['name']) ?></span>
                                                <span class="text-gray-500 text-xs ml-auto">
                                                    <?= date('H:i', strtotime($customer['checkin_time'])) ?>
                                                </span>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-gray-500 text-sm flex items-center gap-2">
                    <i class="w-4 h-4 text-gray-400" data-lucide="info"></i>
                    Chưa có đợt check-in nào được tạo.
                </p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- Tab Nhật ký -->
    <?php if ($tab == 'journal'): ?>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex justify-between flex-wrap items-center gap-3 mb-4">
                <h2 class="text-base font-semibold text-gray-800">Nhật ký Tour</h2>
            </div>

            <?php if (!empty($journals)): ?>
                <div class="space-y-4">
                    <?php foreach ($journals as $journal): ?>
                        <div class="p-4 border border-gray-200 rounded-xl bg-white hover:bg-gray-50">
                            <div class="flex items-start gap-4">
                                <!-- Thumbnail nếu có -->
                                <?php if (!empty($journal['thumbnail'])): ?>
                                    <div class="flex-shrink-0">
                                        <img src="<?= htmlspecialchars($journal['thumbnail']) ?>"
                                            alt="Journal thumbnail"
                                            class="w-20 h-20 object-cover rounded-lg">
                                    </div>
                                <?php endif; ?>

                                <div class="flex-1">
                                    <!-- Ngày và loại -->
                                    <div class="flex items-center gap-3 mb-2">
                                        <span class="text-sm font-medium text-gray-700 flex items-center gap-1">
                                            <i class="w-4 h-4 text-blue-600" data-lucide="calendar"></i>
                                            <?= date('d/m/Y', strtotime($journal['date'])) ?>
                                        </span>
                                        <?php
                                        $typeLabels = [
                                            'daily' => ['label' => 'Hàng ngày', 'class' => 'bg-blue-100 text-blue-700'],
                                            'incident' => ['label' => 'Sự cố', 'class' => 'bg-red-100 text-red-700'],
                                            'other' => ['label' => 'Khác', 'class' => 'bg-gray-100 text-gray-700']
                                        ];
                                        $typeInfo = $typeLabels[$journal['type']] ?? $typeLabels['other'];
                                        ?>
                                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold <?= $typeInfo['class'] ?>">
                                            <?= $typeInfo['label'] ?>
                                        </span>
                                    </div>

                                    <!-- Nội dung -->
                                    <p class="text-sm text-gray-800 mb-2"><?= nl2br(htmlspecialchars($journal['content'])) ?></p>

                                    <!-- Người tạo -->
                                    <p class="text-xs text-gray-500 flex items-center gap-1">
                                        <i class="w-3 h-3" data-lucide="user"></i>
                                        Ghi bởi: <?= htmlspecialchars($journal['created_by_name'] ?? 'N/A') ?>
                                    </p>
                                </div>
                            </div>

                            <!-- Xem chi tiết nếu có ảnh -->
                            <?php
                            $journalImages = $this->journalModel->getImages($journal['id']);
                            if (count($journalImages) > 1):
                            ?>
                                <div class="mt-3 pt-3 border-t border-gray-200">
                                    <p class="text-xs text-gray-600 flex items-center gap-1">
                                        <i class="w-3 h-3" data-lucide="images"></i>
                                        <?= count($journalImages) ?> ảnh đính kèm
                                    </p>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-gray-500 text-sm flex items-center gap-2">
                    <i class="w-4 h-4 text-gray-400" data-lucide="info"></i>
                    Chưa có nhật ký nào được ghi.
                </p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</main>

<?php require_once './views/components/footer.php'; ?>