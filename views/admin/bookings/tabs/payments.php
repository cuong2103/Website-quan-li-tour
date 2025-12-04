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