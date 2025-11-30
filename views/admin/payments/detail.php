<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';
?>

<main class="mt-28 px-6 pb-20">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-xl font-semibold">Chi tiết thanh toán #<?= $payment['id'] ?></h1>

        <a href="<?= BASE_URL ?>?act=booking-detail&id=<?= $payment['booking_id'] ?>&tab=payments"
            class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 text-sm">
            Quay lại Booking
        </a>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 space-y-4">

        <div>
            <p class="text-gray-500 text-sm">Phương thức</p>
            <p class="font-medium"><?= $payment['payment_method'] ?></p>
        </div>

        <div>
            <p class="text-gray-500 text-sm">Loại thanh toán</p>
            <p class="font-medium"><?= $payment['type'] ?></p>
        </div>

        <div>
            <p class="text-gray-500 text-sm">Số tiền</p>
            <p class="font-medium text-green-600">
                <?= number_format($payment['amount'], 0, ',', '.') ?> đ
            </p>
        </div>

        <div>
            <p class="text-gray-500 text-sm">Ngày thanh toán</p>
            <p class="font-medium"><?= $payment['payment_date'] ?></p>
        </div>

        <div>
            <p class="text-gray-500 text-sm">Trạng thái</p>
            <p class="font-medium"><?= $payment['status'] ?></p>
        </div>

        <div>
            <p class="text-gray-500 text-sm">Ghi chú</p>
            <p class="font-medium whitespace-pre-line"><?= $payment['notes'] ?></p>
        </div>

        <div class="pt-4 flex gap-3">
            <a href="<?= BASE_URL ?>?act=payment-edit&id=<?= $payment['id'] ?>"
                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Sửa
            </a>

            <a href="<?= BASE_URL ?>?act=payment-delete&id=<?= $payment['id'] ?>"
                onclick="return confirm('Bạn chắc muốn xóa?')"
                class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                Xóa
            </a>
        </div>

    </div>

</main>

<?php require_once './views/components/footer.php'; ?>
