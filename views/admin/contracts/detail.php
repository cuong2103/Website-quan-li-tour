<?php require_once './views/components/header.php'; ?>
<?php require_once './views/components/sidebar.php'; ?>

<main class="pt-28 px-6 pb-20 text-gray-700">

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
            <p class="font-semibold">Mã Booking:</p>
            <p class="font-bold text-blue-600"><?= $contract['booking_code'] ?? 'N/A' ?></p>
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
            <p class="font-semibold">Ngày hiệu lực:</p>
            <p><?= !empty($contract['effective_date']) ? date('Y-m-d', strtotime($contract['effective_date'])) : '' ?></p>
        </div>

        <div>
            <p class="font-semibold">Ngày hết hạn:</p>
            <p><?= !empty($contract['expiry_date']) ? date('Y-m-d', strtotime($contract['expiry_date'])) : '' ?></p>
        </div>

        <div>
            <p class="font-semibold mb-1">Trạng thái:</p>
            <?php
            $statusLabels = [
                'active' => 'Đang hoạt động',
                'inactive' => 'Ngừng hoạt động',
                'expired' => 'Hết hạn'
            ];
            $statusText = $statusLabels[$contract['status']] ?? $contract['status'];

            $statusClass = 'bg-gray-100 text-gray-700';
            if ($contract['status'] === 'active') $statusClass = 'bg-green-100 text-green-700';
            elseif ($contract['status'] === 'inactive') $statusClass = 'bg-red-100 text-red-700';
            elseif ($contract['status'] === 'expired') $statusClass = 'bg-yellow-100 text-yellow-700';
            ?>
            <span class="px-3 py-1 <?= $statusClass ?> rounded">
                <?= $statusText ?>
            </span>
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