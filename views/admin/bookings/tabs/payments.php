<?php
// Kiểm tra xem có được phép thêm thanh toán không
$canAddPayment = !in_array($booking['status'], ['paid', 'completed', 'cancelled']);
?>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">

            <div class="flex justify-between flex-wrap items-center gap-3 mb-4">
                <h2 class="text-base font-semibold text-gray-800">Lịch sử thanh toán</h2>
                
                <?php if ($canAddPayment): ?>
                    <a href="<?= BASE_URL ?>?act=payment-create&booking_id=<?= $booking['id'] ?>"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium">
                        <i class="w-4 h-4" data-lucide="plus"></i>
                        Thêm thanh toán
                    </a>
                <?php else: ?>
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-500 rounded-lg text-sm font-medium">
                        <i class="w-4 h-4" data-lucide="lock"></i>
                        <?php
                            if ($booking['status'] === 'paid') echo 'Đã thanh toán đủ';
                            elseif ($booking['status'] === 'completed') echo 'Tour đã hoàn thành';
                            elseif ($booking['status'] === 'cancelled') echo 'Booking đã hủy';
                        ?>
                    </div>
                <?php endif; ?>
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
                                        'payment' => 'Thanh toán',
                                        'refund' => 'Hoàn tiền'
                                    ];
                                    echo $typeLabels[$p['type']] ?? $p['type'];
                                    ?>
                                </p>

                                <p class="text-sm text-gray-700 mt-1 flex items-center gap-1 mb-1">
                                    <i class="w-4 h-4" data-lucide="banknote"></i>
                                    Số tiền:
                                    <span class="font-semibold <?= $p['type'] === 'refund' ? 'text-red-600' : 'text-green-600' ?>">
                                        <?= number_format($p['amount'], 0, ',', '.') ?>đ
                                    </span>
                                </p>

                                <p class="text-sm text-gray-500 mt-1 flex items-center gap-1 mb-1">
                                    <i class="w-4 h-4" data-lucide="calendar"></i>
                                    Ngày: <?= date('d/m/Y', strtotime($p['payment_date'])) ?>
                                </p>
                            </div>

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