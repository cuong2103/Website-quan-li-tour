<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';
?>

<main class="mt-28 px-6 pb-20 text-gray-700">

    <h1 class="text-xl font-semibold mb-6">Thêm thanh toán</h1>

    <form action="<?= BASE_URL ?>?act=payment-store" method="POST"
        class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 space-y-4">

        <input type="hidden" name="booking_id" value="<?= $_GET['booking_id'] ?>">

        <div>
            <label class="text-sm font-medium">Phương thức thanh toán</label>
            <select name="payment_method" class="w-full px-3 py-2 border rounded-lg mt-1">
                <option value="Cash">Tiền mặt</option>
                <option value="BankTransfer">Chuyển khoản</option>
            </select>
        </div>

        <div>
            <label class="text-sm font-medium">Loại thanh toán</label>
            <select name="type" class="w-full px-3 py-2 border rounded-lg mt-1">
                <option value="Deposit">Cọc</option>
                <option value="Remaining">Còn lại</option>
                <option value="Refund">Hoàn tiền</option>
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

        <div>
            <label class="text-sm font-medium">Trạng thái</label>
            <select name="status" class="w-full px-3 py-2 border rounded-lg mt-1">
                <option value="pending">Chờ xử lý</option>
                <option value="success">Thành công</option>
                <option value="failed">Thất bại</option>
                <option value="refund">Hoàn tiền</option>
            </select>
        </div>

        <div>
            <label class="text-sm font-medium">Ghi chú</label>
            <textarea name="notes" class="w-full px-3 py-2 border rounded-lg mt-1" rows="4"></textarea>
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
