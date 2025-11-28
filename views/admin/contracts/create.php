<?php require_once './views/components/header.php'; ?>
<?php require_once './views/components/sidebar.php'; ?>

<main class="mt-28 px-6 pb-20">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-xl font-semibold">Tạo hợp đồng</h1>

        <a href="<?= BASE_URL ?>?act=booking-detail&id=<?= $booking_id ?>&tab=contracts"
            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg text-sm">
            Quay lại
        </a>
    </div>
    <form action="<?= BASE_URL . '?act=contract-store' ?>"
        method="POST"
        enctype="multipart/form-data"
        class="space-y-4">

        <input type="hidden" name="booking_id" value="<?= $_GET['booking_id'] ?? '' ?>">

        <div>
            <label class="block text-sm mb-1">Tên hợp đồng</label>
            <input type="text" name="contract_name" required class="border p-2 w-full rounded">
        </div>

        <div>
            <label class="block text-sm mb-1">Người ký</label>
            <select name="signer_id" class="border p-2 w-full rounded">
                <option value="<?= $_SESSION['currentUser']['id'] ?>" selected>
                    <?= $_SESSION['currentUser']['fullname'] ?>
                </option>
            </select>
        </div>

        <div>
            <label>Ngày ký</label>
            <input type="date" name="signing_date" class="border p-2 w-full rounded">
        </div>

        <div>
            <label>Ngày hiệu lực</label>
            <input type="date" name="effective_date" class="border p-2 w-full rounded">
        </div>

        <div>
            <label>Ngày hết hạn</label>
            <input type="date" name="expiry_date" class="border p-2 w-full rounded">
        </div>

        <div>
            <label class="block text-sm mb-1">Khách hàng ký</label>
            <select name="customer_id" class="border p-2 w-full rounded" required>
                <option value="">-- Chọn khách hàng --</option>
                <?php foreach ($bookingCustomers as $c): ?>
                    <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Trạng thái -->
        <div>
            <label class="block text-sm mb-1">Trạng thái hợp đồng</label>
            <select name="status" class="border p-2 w-full rounded" required>
                <option value="active">Đang hiệu lực</option>
                <option value="inactive">Chấm dứt</option>
                <option value="expired">Hết hạn</option>
            </select>
        </div>

        <div>
            <label class="block text-sm mb-1">Upload file hợp đồng</label>
            <input type="file" name="file_upload" accept=".pdf,.doc,.docx" required class="border p-2 w-full rounded">
        </div>

        <div>
            <label class="block text-sm mb-1">Ghi chú</label>
            <textarea name="notes" class="border p-2 w-full rounded"></textarea>
        </div>

        <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">
            Lưu hợp đồng
        </button>

    </form>


</main>

<?php require_once './views/components/footer.php'; ?>