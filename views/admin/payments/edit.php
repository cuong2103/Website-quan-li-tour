<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';
?>

<main class="mt-28 px-6 pb-20 text-gray-700">

    <h1 class="text-xl font-semibold mb-6">Sửa thanh toán #<?= $payment['id'] ?></h1>

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

    <form action="<?= BASE_URL ?>?act=payment-update" method="POST"
        class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 space-y-4">

        <input type="hidden" name="id" value="<?= $payment['id'] ?>">

        <div>
            <label class="text-sm font-medium">Phương thức thanh toán</label>
            <select name="payment_method" class="w-full px-3 py-2 border rounded-lg mt-1">
                <option value="cash" <?= $payment['payment_method'] == 'cash' ? 'selected' : '' ?>>Tiền mặt</option>
                <option value="bank_transfer" <?= $payment['payment_method'] == 'bank_transfer' ? 'selected' : '' ?>>Chuyển khoản</option>
            </select>
        </div>

        <div>
            <label class="text-sm font-medium">Loại thanh toán</label>
            <select name="type" class="w-full px-3 py-2 border rounded-lg mt-1">
                <option value="deposit" <?= $payment['type'] == 'deposit' ? 'selected' : '' ?>>Cọc</option>
                <option value="full_payment" <?= $payment['type'] == 'full_payment' ? 'selected' : '' ?>>Thanh toán đủ</option>
                <option value="remaining" <?= $payment['type'] == 'remaining' ? 'selected' : '' ?>>Thanh toán còn lại</option>
                <option value="refund" <?= $payment['type'] == 'refund' ? 'selected' : '' ?>>Hoàn tiền</option>
            </select>
        </div>

        <div>
            <label class="text-sm font-medium">Số tiền</label>
            <input type="number" name="amount" value="<?= $payment['amount'] ?>" class="w-full px-3 py-2 border rounded-lg mt-1"
                required>
        </div>

        <div>
            <label class="text-sm font-medium">Ngày thanh toán</label>
            <input type="datetime-local" name="payment_date"
                value="<?= date('Y-m-d\TH:i', strtotime($payment['payment_date'])) ?>"
                class="w-full px-3 py-2 border rounded-lg mt-1">
        </div>

        <div>
            <label class="text-sm font-medium">Trạng thái</label>
            <select name="status" class="w-full px-3 py-2 border rounded-lg mt-1">
                <option value="pending" <?= $payment['status'] == 'pending' ? 'selected' : '' ?>>Chờ xử lý</option>
                <option value="completed" <?= $payment['status'] == 'completed' ? 'selected' : '' ?>>Thành công</option>
                <option value="failed" <?= $payment['status'] == 'failed' ? 'selected' : '' ?>>Thất bại</option>
            </select>
        </div>



        <div class="pt-3 flex gap-3">
            <button class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Cập nhật</button>
            <a href="<?= BASE_URL ?>?act=payment-detail&id=<?= $payment['id'] ?>"
                class="px-6 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">
                Hủy
            </a>
        </div>
    </form>

</main>

<?php require_once './views/components/footer.php'; ?>