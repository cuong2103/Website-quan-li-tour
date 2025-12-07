<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';
?>

<main class="pt-28 px-6 pb-20 text-gray-700">

    <h1 class="text-xl font-semibold mb-6">Sửa thanh toán</h1>

    <?php if (isset($_SESSION['payment_errors'])): ?>
        <div class="bg-red-100 border border-red-300 text-red-500 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Lỗi!</strong>
            <ul class="list-disc list-inside">
                <?php foreach ($_SESSION['payment_errors'] as $error): ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php unset($_SESSION['payment_errors']); ?>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>?act=payment-update" method="POST" enctype="multipart/form-data"
        class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 space-y-4">

        <input type="hidden" name="id" value="<?= $payment['id'] ?>">

        <!-- Thông tin số tiền -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-4 rounded-lg border border-blue-200">
            <h3 class="font-semibold text-gray-800 mb-3 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
                Thông tin thanh toán
            </h3>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <p class="text-xs text-gray-600 mb-1">Tổng tiền booking</p>
                    <p class="text-lg font-bold text-gray-800"><?= number_format($booking['total_amount'], 0, ',', '.') ?>đ</p>
                </div>
                <div>
                    <p class="text-xs text-gray-600 mb-1">Đã thanh toán (chưa tính payment này)</p>
                    <p class="text-lg font-bold text-green-600"><?= number_format($totalPaid, 0, ',', '.') ?>đ</p>
                </div>
                <div>
                    <p class="text-xs text-gray-600 mb-1">Còn lại</p>
                    <p class="text-lg font-bold <?= $remaining > 0 ? 'text-red-600' : 'text-green-600' ?>"><?= number_format($remaining, 0, ',', '.') ?>đ</p>
                </div>
            </div>
        </div>

        <div>
            <label class="text-sm font-medium">Phương thức thanh toán <span class="text-red-500">*</span></label>
            <select name="payment_method" id="paymentMethod" class="w-full px-3 py-2 border rounded-lg mt-1" required>
                <option value="cash" <?= $payment['payment_method'] == 'cash' ? 'selected' : '' ?>>Tiền mặt</option>
                <option value="bank_transfer" <?= $payment['payment_method'] == 'bank_transfer' ? 'selected' : '' ?>>Chuyển khoản</option>
            </select>
        </div>

        <!-- Mã giao dịch -->
        <div id="transactionCodeField" style="display: <?= $payment['payment_method'] == 'bank_transfer' ? 'block' : 'none' ?>;">
            <label class="text-sm font-medium">Mã giao dịch <span class="text-red-500">*</span></label>
            <input type="text" name="transaction_code" value="<?= $payment['transaction_code'] ?? '' ?>"
                class="w-full px-3 py-2 border rounded-lg mt-1" placeholder="Nhập mã giao dịch...">
        </div>

        <div>
            <label class="text-sm font-medium">Loại thanh toán <span class="text-red-500">*</span></label>
            <select name="type" class="w-full px-3 py-2 border rounded-lg mt-1" required>
                <option value="deposit" <?= $payment['type'] == 'deposit' ? 'selected' : '' ?>>Cọc</option>
                <option value="full_payment" <?= $payment['type'] == 'full_payment' ? 'selected' : '' ?>>Thanh toán đủ</option>
                <option value="remaining" <?= $payment['type'] == 'remaining' ? 'selected' : '' ?>>Thanh toán còn lại</option>
                <option value="refund" <?= $payment['type'] == 'refund' ? 'selected' : '' ?>>Hoàn tiền</option>
            </select>
        </div>

        <div>
            <label class="text-sm font-medium">Số tiền <span class="text-red-500">*</span></label>
            <input type="number" name="amount" value="<?= $payment['amount'] ?>" max="<?= $remaining ?>"
                class="w-full px-3 py-2 border rounded-lg mt-1" required>
            <p class="text-xs text-gray-500 mt-1">Tối đa: <?= number_format($remaining, 0, ',', '.') ?>đ</p>
        </div>

        <!-- File phiếu thu -->
        <div>
            <label class="text-sm font-medium">Phiếu thu / Ảnh chứng từ</label>
            <?php if ($payment['receipt_file']): ?>
                <div class="mb-2 p-3 bg-blue-50 rounded border border-blue-200 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-700"><?= $payment['receipt_file'] ?></span>
                    </div>
                    <a href="uploads/receipts/<?= $payment['receipt_file'] ?>" download
                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">
                        Tải xuống
                    </a>
                </div>
            <?php endif; ?>
            <input type="file" name="receipt_file" id="receiptFile" accept="image/*,.pdf"
                class="w-full px-3 py-2 border rounded-lg mt-1">
            <p class="text-xs text-gray-500 mt-1">Chấp nhận: JPG, PNG, PDF (tối đa 5MB). Để trống nếu không muốn thay đổi.</p>

            <!-- Preview ảnh -->
            <div id="imagePreview" class="mt-3 hidden">
                <div class="relative inline-block">
                    <img id="previewImg" src="" alt="Preview" class="max-w-xs max-h-48 rounded border">
                    <button type="button" id="removeImage"
                        class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1.5 hover:bg-red-600 shadow-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Preview PDF -->
            <div id="pdfPreview" class="mt-3 hidden">
                <div class="flex items-center gap-2 p-3 bg-gray-50 rounded border">
                    <svg class="w-8 h-8 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 18h12V6h-4V2H4v16zm-2 1V0h12l4 4v16H2v-1z"></path>
                    </svg>
                    <div class="flex-1">
                        <p class="text-sm font-medium" id="pdfName"></p>
                        <p class="text-xs text-gray-500">File PDF</p>
                    </div>
                    <button type="button" id="removePdf"
                        class="text-red-500 hover:text-red-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div>
            <label class="text-sm font-medium">Ngày thanh toán</label>
            <input type="date" name="payment_date" value="<?= date('Y-m-d', strtotime($payment['payment_date'])) ?>"
                class="w-full px-3 py-2 border rounded-lg mt-1">
        </div>

        <div class="pt-3 flex gap-3">
            <button class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Cập nhật</button>
            <a href="<?= BASE_URL ?>?act=booking-detail&id=<?= $payment['booking_id'] ?>&tab=payments"
                class="px-6 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">
                Hủy
            </a>
        </div>
    </form>

    <script>
        // Toggle mã giao dịch
        const paymentMethod = document.getElementById('paymentMethod');
        const transactionCodeField = document.getElementById('transactionCodeField');

        paymentMethod.addEventListener('change', function() {
            if (this.value === 'bank_transfer') {
                transactionCodeField.style.display = 'block';
                transactionCodeField.querySelector('input').required = true;
            } else {
                transactionCodeField.style.display = 'none';
                transactionCodeField.querySelector('input').required = false;
            }
        });

        if (paymentMethod.value === 'bank_transfer') {
            transactionCodeField.querySelector('input').required = true;
        }

        // Preview file
        const receiptFile = document.getElementById('receiptFile');
        const imagePreview = document.getElementById('imagePreview');
        const pdfPreview = document.getElementById('pdfPreview');
        const previewImg = document.getElementById('previewImg');
        const pdfName = document.getElementById('pdfName');
        const removeImage = document.getElementById('removeImage');
        const removePdf = document.getElementById('removePdf');

        receiptFile.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                imagePreview.classList.add('hidden');
                pdfPreview.classList.add('hidden');

                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImg.src = e.target.result;
                        imagePreview.classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                } else if (file.type === 'application/pdf') {
                    pdfName.textContent = file.name;
                    pdfPreview.classList.remove('hidden');
                }
            }
        });

        removeImage.addEventListener('click', function() {
            receiptFile.value = '';
            imagePreview.classList.add('hidden');
        });

        removePdf.addEventListener('click', function() {
            receiptFile.value = '';
            pdfPreview.classList.add('hidden');
        });
    </script>

</main>

<?php require_once './views/components/footer.php'; ?>