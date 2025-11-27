<?php require_once './views/components/header.php'; ?>
<?php require_once './views/components/sidebar.php'; ?>

<main class="mt-28 px-6 pb-20">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-xl font-semibold">Cập nhật hợp đồng</h1>

        <a href="<?= BASE_URL ?>?act=booking-detail&id=<?= $contract['booking_id'] ?>&tab=contracts"
            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg text-sm">
            Quay lại
        </a>
    </div>


    <form action="<?= BASE_URL . '?act=contract-update' ?>" method="POST"
        enctype="multipart/form-data" class="space-y-4">

        <input type="hidden" name="id" value="<?= $contract['id'] ?>">

        <div>
            <label class="block text-sm mb-1">Tên hợp đồng</label>
            <input type="text" name="contract_name" value="<?= $contract['contract_name'] ?>" required class="border p-2 w-full rounded">
        </div>

        <div class="grid grid-cols-3 gap-4">
            <div class="mb-4">
                <label for="signing_date" class="block text-sm font-medium text-gray-700">Ngày ký</label>
                <input type="date" id="signing_date" name="signing_date"
                    value="<?= !empty($contract['signing_date']) ? date('Y-m-d', strtotime($contract['signing_date'])) : '' ?>"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>

            <div class="mb-4">
                <label for="effective_date" class="block text-sm font-medium text-gray-700">Ngày hiệu lực</label>
                <input type="date" id="effective_date" name="effective_date"
                    value="<?= !empty($contract['effective_date']) ? date('Y-m-d', strtotime($contract['effective_date'])) : '' ?>"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>

            <div class="mb-4">
                <label for="expiry_date" class="block text-sm font-medium text-gray-700">Ngày hết hạn</label>
                <input type="date" id="expiry_date" name="expiry_date"
                    value="<?= !empty($contract['expiry_date']) ? date('Y-m-d', strtotime($contract['expiry_date'])) : '' ?>"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>

        </div>

        <div>
            <label class="block text-sm mb-1">Người ký</label>
            <input type="number" name="signer_id" value="<?= $contract['signer_id'] ?>" class="border p-2 w-full rounded">
        </div>

        <div>
            <label class="block text-sm mb-1">Khách hàng</label>
            <input type="number" name="customer_id" value="<?= $contract['customer_id'] ?>" class="border p-2 w-full rounded">
        </div>

        <div>
            <label class="block text-sm mb-1">File hợp đồng hiện tại</label>
            <?php if ($contract['file_url']): ?>
                <a href="<?= $contract['file_url'] ?>" target="_blank" class="text-blue-600 underline">
                    Tải về
                </a>
            <?php else: ?>
                <p class="text-gray-400 text-sm">Chưa có file</p>
            <?php endif; ?>
        </div>

        <div>
            <label class="block text-sm mb-1">Upload file mới (nếu thay)</label>
            <input type="file" name="file_upload" accept=".pdf,.doc,.docx" class="border p-2 w-full rounded">
        </div>

        <div>
            <label class="block text-sm mb-1">Trạng thái</label>
            <select name="status" class="border p-2 w-full rounded">
                <option value="active" <?= $contract['status'] == 'active' ? 'selected' : '' ?>>Đang hiệu lực</option>
                <option value="expired" <?= $contract['status'] == 'expired' ? 'selected' : '' ?>>Hết hạn</option>
                <option value="draft" <?= $contract['status'] == 'draft' ? 'selected' : '' ?>>Nháp</option>
            </select>
        </div>

        <div>
            <label class="block text-sm mb-1">Ghi chú</label>
            <textarea name="notes" class="border p-2 w-full rounded"><?= $contract['notes'] ?></textarea>
        </div>

        <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">
            Cập nhật
        </button>

    </form>

</main>

<?php require_once './views/components/footer.php'; ?>