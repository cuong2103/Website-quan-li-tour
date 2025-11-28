<?php require_once './views/components/header.php'; ?>
<?php require_once './views/components/sidebar.php'; ?>

<main class="mt-28 px-6 pb-20 text-gray-700">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-semibold">
            Chi tiết hợp đồng #<?= $contract['id'] ?>
        </h1>

        <a href="<?= BASE_URL ?>?act=booking-detail&id=<?= $booking_id ?>&tab=contracts"
            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg text-sm">
            Quay lại
        </a>
    </div>

    <div class="bg-white rounded shadow p-6 grid grid-cols-2 gap-6">

        <div>
            <p class="font-semibold">Tên hợp đồng:</p>
            <p><?= $contract['contract_name'] ?></p>
        </div>

        <div>
            <p class="font-semibold">Booking:</p>
            <p><?= $contract['booking_id'] ?></p>
        </div>

        <div>
            <p class="font-semibold">Người ký (Admin):</p>
            <p><?= $_SESSION['currentUser']['fullname'] ?></p>
        </div>

        <div>
            <p class="font-semibold">Khách hàng ký:</p>
            <p><?= htmlspecialchars($contract['customer_name'] ?? 'Không xác định') ?></p>
        </div>


        <div>
            <p class="font-semibold">Ngày ký:</p>
            <p><?= !empty($contract['signing_date']) ? date('Y-m-d', strtotime($contract['signing_date'])) : '' ?></p>
        </div>

        <div>
            <p class="font-semibold">Ngày hiệu lực:</p>
            <p><?= !empty($contract['effective_date']) ? date('Y-m-d', strtotime($contract['effective_date'])) : '' ?></p>
        </div>

        <div>
            <p class="font-semibold">Ngày hết hạn:</p>
            <p><?= !empty($contract['expiry_date']) ? date('Y-m-d', strtotime($contract['expiry_date'])) : '' ?></p>
        </div>


        <div>
            <p class="font-semibold">Trạng thái:</p>
            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded">
                <?= $contract['status'] ?>
            </span>
        </div>


        <div class="col-span-2">
            <p class="font-semibold">Ghi chú:</p>
            <p><?= $contract['notes'] ?: 'Không có' ?></p>
        </div>

        <div class="col-span-2">
            <p class="font-semibold">File hợp đồng:</p>
            <?php if ($contract['file_url']): ?>
                <a href="<?= $contract['file_url'] ?>"
                    class="text-blue-600 underline" target="_blank">
                    Tải xuống để xem
                </a>
            <?php else: ?>
                <p>Không có file</p>
            <?php endif; ?>
        </div>

    </div>

</main>