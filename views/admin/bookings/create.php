<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';
?>

<main class="mt-28 px-6 pb-24 text-gray-700">

    <!-- Header Title -->
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-semibold">Tạo Booking mới</h1>
        <a href="<?= BASE_URL . '?act=bookings' ?>"
            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg text-sm">
            Quay lại
        </a>
    </div>

    <!-- FORM -->
    <form method="POST" action="<?= BASE_URL . '?act=booking-store' ?>" class="space-y-6">

        <!-- Card: Chọn Tour -->
        <div class="bg-white shadow-sm rounded-xl p-6 border border-gray-100">
            <h2 class="text-lg font-medium mb-4">Thông tin tour</h2>

            <div class="mb-4">
                <label class="block mb-1 font-medium">Chọn Tour *</label>
                <select name="tour_id" id="tourSelect"
                    class="border px-3 py-2 rounded-lg w-full focus:ring focus:ring-blue-200"
                    required>
                    <option value="">-- Chọn Tour --</option>
                    <?php foreach ($tours as $t): ?>
                        <option value="<?= $t['id'] ?>"
                            data-adult="<?= $t['adult_price'] ?>"
                            data-child="<?= $t['child_price'] ?>">
                            <?= htmlspecialchars($t['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="font-medium">Ngày khởi hành</label>
                    <input type="date" name="start_date"
                        class="border w-full p-2 rounded-lg focus:ring focus:ring-blue-200" required>
                </div>

                <div>
                    <label class="font-medium">Ngày kết thúc</label>
                    <input type="date" name="end_date"
                        class="border w-full p-2 rounded-lg focus:ring focus:ring-blue-200" required>
                </div>
            </div>
        </div>

        <!-- Card: Số lượng & Giá tiền -->
        <div class="bg-white shadow-sm rounded-xl p-6 border border-gray-100">
            <h2 class="text-lg font-medium mb-4">Thông tin chi phí</h2>

            <div class="grid md:grid-cols-2 gap-4">

                <div>
                    <label class="font-medium">Người lớn</label>
                    <input type="number" id="adultCount" name="adult_count" value="1" min="0"
                        class="border w-full p-2 rounded-lg focus:ring focus:ring-blue-200">
                </div>

                <div>
                    <label class="font-medium">Trẻ em</label>
                    <input type="number" id="childCount" name="child_count" value="0" min="0"
                        class="border w-full p-2 rounded-lg focus:ring focus:ring-blue-200">
                </div>

                <div>
                    <label class="font-medium">Tổng tiền</label>
                    <input type="text" id="totalAmount" name="total_amount"
                        class="border w-full p-2 rounded-lg bg-gray-100 font-semibold text-green-600"
                        readonly>
                </div>

                <div>
                    <label class="font-medium">Tiền cọc</label>
                    <input type="number" id="depositAmount" name="deposit_amount"
                        class="border w-full p-2 rounded-lg focus:ring focus:ring-blue-200"
                        value="0">
                </div>

                <div>
                    <label class="font-medium">Tiền còn lại</label>
                    <input type="number" id="remainingAmount" name="remaining_amount"
                        class="border w-full p-2 rounded-lg bg-gray-100"
                        readonly>
                </div>
            </div>

            <div class="mt-4">
                <label class="font-medium block mb-1">Trạng thái</label>
                <select name="status"
                    class="border w-full p-2 rounded-lg focus:ring focus:ring-blue-200">
                    <option value="1">Chờ thanh toán</option>
                    <option value="2">Đã cọc</option>
                    <option value="3">Đã thanh toán đủ</option>
                    <option value="4">Đã hủy</option>
                    <option value="5">Hoàn thành Tour</option>
                </select>
            </div>
        </div>

        <!-- Card: Ghi chú -->
        <div class="bg-white shadow-sm rounded-xl p-6 border border-gray-100">
            <h2 class="text-lg font-medium mb-4">Ghi chú</h2>
            <textarea name="special_requests"
                class="border w-full p-3 rounded-lg h-28 resize-none focus:ring focus:ring-blue-200"
                placeholder="Nhập yêu cầu đặc biệt..."></textarea>
        </div>

        <!-- Card: Chọn khách hàng -->
        <div class="bg-white shadow-sm rounded-xl p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-medium">Khách hàng</h2>
                <span class="text-sm text-gray-500">
                    Chọn khách và đặt 1 người làm đại diện
                </span>
            </div>

            <div class="border p-3 rounded-xl max-h-60 overflow-y-auto bg-gray-50">
                <?php foreach ($customers as $c): ?>
                    <label class="flex justify-between items-center bg-white p-3 rounded-lg border mb-2 hover:bg-gray-50">
                        <span class="font-medium"><?= htmlspecialchars($c['name']) ?></span>
                        <input type="checkbox" class="customerCheck"
                            name="customers[]" value="<?= $c['id'] ?>">
                    </label>
                <?php endforeach; ?>
            </div>

            <div class="mt-4">
                <label class="font-medium">Khách đại diện</label>
                <select id="representative" name="is_representative"
                    class="border w-full p-2 rounded-lg mt-1">
                    <option value="">-- Chọn --</option>
                </select>
            </div>
        </div>

        <!-- Card: Chọn dịch vụ -->
        <div class="bg-white shadow-sm rounded-xl p-6 border border-gray-100">
            <h2 class="text-lg font-medium mb-4">Dịch vụ đi kèm</h2>

            <div class="border p-3 rounded-xl max-h-60 overflow-y-auto bg-gray-50">
                <?php foreach ($services as $sv): ?>
                    <label class="flex justify-between items-center bg-white p-3 rounded-lg border mb-2 hover:bg-gray-50">
                        <span class="font-medium"><?= htmlspecialchars($sv['name']) ?></span>
                        <input type="checkbox" name="services[]" value="<?= $sv['id'] ?>">
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Submit button -->
        <div class="flex fixed bottom-5 right-16 gap-3">
            <button class="px-6 py-2.5 bg-black text-white rounded-lg hover:bg-gray-900 transition font-medium">
                Thêm booking mới
            </button>
        </div>

    </form>

    <!-- SCRIPT -->
    <script>
        function format(n) {
            return n.toLocaleString("vi-VN");
        }

        document.querySelectorAll("#tourSelect, #adultCount, #childCount").forEach(el => {
            el.addEventListener("input", updatePrice);
        });

        function updatePrice() {
            let tour = document.querySelector("#tourSelect");
            if (!tour.value) return;

            let adult = Number(adultCount.value);
            let child = Number(childCount.value);

            let adultPrice = Number(tour.selectedOptions[0].dataset.adult);
            let childPrice = Number(tour.selectedOptions[0].dataset.child);

            let total = adult * adultPrice + child * childPrice;
            totalAmount.value = total;
            updateRemaining();
        }

        depositAmount.addEventListener("input", updateRemaining);

        function updateRemaining() {
            let total = Number(totalAmount.value || 0);
            let deposit = Number(depositAmount.value || 0);
            remainingAmount.value = Math.max(total - deposit, 0);
        }

        document.querySelectorAll(".customerCheck").forEach(chk => {
            chk.addEventListener("change", () => {
                representative.innerHTML = `<option value="">-- Chọn --</option>`;
                document.querySelectorAll(".customerCheck:checked").forEach(c => {
                    representative.innerHTML +=
                        `<option value="${c.value}">${c.parentElement.querySelector("span").innerText}</option>`;
                });
            });
        });
    </script>

</main>

<?php require_once './views/components/footer.php'; ?>