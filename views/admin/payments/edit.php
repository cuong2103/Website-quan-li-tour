<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';
?>

<main class="mt-28 px-6 pb-20 text-gray-700">

    <h1 class="text-xl font-semibold mb-6">Sửa thanh toán #<?= $payment['id'] ?></h1>

    <form action="<?= BASE_URL ?>?act=payment-update" method="POST"
        class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 space-y-4">

        <input type="hidden" name="id" value="<?= $payment['id'] ?>">

        <div>
            <label class="text-sm font-medium">Phương thức thanh toán</label>
            <select name="payment_method" class="w-full px-3 py-2 border rounded-lg mt-1">
                <option value="Cash" <?= $payment['payment_method'] == 'Cash' ? 'selected' : '' ?>>Tiền mặt</option>
                <option value="BankTransfer" <?= $payment['payment_method'] == 'BankTransfer' ? 'selected' : '' ?>>Chuyển khoản</option>
            </select>
        </div>

        <div>
            <label class="text-sm font-medium">Loại thanh toán</label>
            <select name="type" class="w-full px-3 py-2 border rounded-lg mt-1">
                <option value="Deposit" <?= $payment['type'] == 'Deposit' ? 'selected' : '' ?>>Cọc</option>
                <option value="Remaining" <?= $payment['type'] == 'Remaining' ? 'selected' : '' ?>>Còn lại</option>
                <option value="Refund" <?= $payment['type'] == 'Refund' ? 'selected' : '' ?>>Hoàn tiền</option>
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
                <option value="success" <?= $payment['status'] == 'success' ? 'selected' : '' ?>>Thành công</option>
                <option value="failed" <?= $payment['status'] == 'failed' ? 'selected' : '' ?>>Thất bại</option>
                <option value="refund" <?= $payment['status'] == 'refund' ? 'selected' : '' ?>>Hoàn tiền</option>
            </select>
        </div>

        <div>
            <label class="text-sm font-medium">Ghi chú</label>
            <textarea name="notes" class="w-full px-3 py-2 border rounded-lg mt-1" rows="4"><?= $payment['notes'] ?></textarea>
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
