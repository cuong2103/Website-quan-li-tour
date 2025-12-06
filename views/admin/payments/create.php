<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';
?>

<main class="mt-28 px-6 pb-20 text-gray-700">

    <h1 class="text-xl font-semibold mb-6">Thêm thanh toán</h1>

    <?php if (Message::get('errors')): ?>
        <div class="bg-red-100 border border-red-300 text-red-500 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Bạn đã nhập quá số tiền của booking. Vui lòng nhập lại!</strong>
            <span class="block sm:inline"><?= Message::get('errors') ?></span>
        </div>
    <?php endif; ?>

    <?php if (Message::get('success')): ?>
        <div class="bg-green-100 border border-green-300 text-green-500 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Thành công!</strong>
            <span class="block sm:inline"><?= Message::get('success') ?></span>
        </div>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>?act=payment-store" method="POST"
        class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 space-y-4">

        <input type="hidden" name="booking_id" value="<?= $_GET['booking_id'] ?>">
<!-- 
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
        </div> -->

        <div>
            <label class="text-sm font-medium">Phương thức thanh toán</label>
            <select name="payment_method" class="w-full px-3 py-2 border rounded-lg mt-1">
                <option value="cash">Tiền mặt</option>
                <option value="bank_transfer">Chuyển khoản</option>
            </select>
        </div>

        <div>
            <label class="text-sm font-medium">Loại thanh toán</label>
            <select name="type" class="w-full px-3 py-2 border rounded-lg mt-1">
                <option value="deposit">Cọc</option>
                <option value="full_payment">Thanh toán đủ</option>
                <option value="remaining">Thanh toán còn lại</option>
                <option value="refund">Hoàn tiền</option>
            </select>
        </div>

        <div>
            <label class="text-sm font-medium">Số tiền</label>
            <input type="number" name="amount" class="w-full px-3 py-2 border rounded-lg mt-1" required>
        </div>

        <div>
            <label class="text-sm font-medium">Ngày thanh toán</label>
            <input type="datetime-local" name="payment_date" class="w-full px-3 py-2 border rounded-lg mt-1"
                value="<?= date('Y-m-d\TH:i') ?>">
        </div>

        <div class="pt-3 flex gap-3">
            <button class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Lưu</button>
            <a href="<?= BASE_URL ?>?act=booking-detail&id=<?= $_GET['booking_id'] ?>&tab=payments"
                class="px-6 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">
                Hủy
            </a>
        </div>
    </form>

</main>

<?php require_once './views/components/footer.php'; ?>