<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';
?>

<main class="mt-28 px-6 pb-20">
    <h1 class="text-2xl font-bold mb-6">Chỉnh sửa Booking</h1>

    <form action="<?= BASE_URL . '?act=booking-update' ?>" method="POST"
        class="bg-white p-8 rounded-2xl shadow-lg border border-gray-200 space-y-6">

        <input type="hidden" name="id" value="<?= $booking['id'] ?>">

        <!-- Chọn Tour -->
        <div>
            <label class="block font-semibold mb-1 text-gray-700">Chọn Tour *</label>
            <select name="tour_id" id="tourSelect"
                class="border border-gray-300 px-3 py-2 rounded-lg w-full focus:ring-2 focus:ring-blue-400"
                required>
                <option value="">-- Chọn Tour --</option>
                <?php foreach ($tours as $t): ?>
                    <option value="<?= $t['id'] ?>"
                        data-adult="<?= $t['adult_price'] ?>"
                        data-child="<?= $t['child_price'] ?>"
                        <?= $t['id'] == $booking['tour_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($t['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Ngày -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="font-semibold text-gray-700">Ngày khởi hành</label>
                <input type="date" name="start_date"
                    class="border border-gray-300 w-full p-2 rounded-lg focus:ring-2 focus:ring-blue-400"
                    value="<?= $booking['start_date'] ?>" required>
            </div>
            <div>
                <label class="font-semibold text-gray-700">Ngày kết thúc</label>
                <input type="date" name="end_date"
                    class="border border-gray-300 w-full p-2 rounded-lg focus:ring-2 focus:ring-blue-400"
                    value="<?= $booking['end_date'] ?>" required>
            </div>
        </div>

        <!-- Số lượng -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="font-semibold text-gray-700">Người lớn</label>
                <input type="number" name="adult_count" id="adultCount"
                    class="border border-gray-300 w-full p-2 rounded-lg focus:ring-2 focus:ring-blue-400"
                    min="0" value="<?= $booking['adult_count'] ?>">
            </div>
            <div>
                <label class="font-semibold text-gray-700">Trẻ em</label>
                <input type="number" name="child_count" id="childCount"
                    class="border border-gray-300 w-full p-2 rounded-lg focus:ring-2 focus:ring-blue-400"
                    min="0" value="<?= $booking['child_count'] ?>">
            </div>
        </div>

        <!-- Tổng tiền -->
        <div>
            <label class="font-semibold text-gray-700">Tổng tiền</label>
            <input type="text" name="total_amount" id="totalAmount"
                class="border border-gray-300 w-full p-2 rounded-lg bg-gray-100 font-semibold"
                value="<?= $booking['total_amount'] ?>" readonly>
        </div>

        <!-- Tiền cọc -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="font-semibold text-gray-700">Tiền cọc</label>
                <input type="number" name="deposit_amount" id="depositAmount"
                    class="border border-gray-300 w-full p-2 rounded-lg focus:ring-2 focus:ring-blue-400"
                    value="<?= $booking['deposit_amount'] ?>">
            </div>
            <div>
                <label class="font-semibold text-gray-700">Tiền còn lại</label>
                <input type="number" name="remaining_amount" id="remainingAmount"
                    class="border border-gray-300 w-full p-2 rounded-lg bg-gray-100 font-semibold"
                    value="<?= $booking['remaining_amount'] ?>" readonly>
            </div>
        </div>

        <!-- Trạng thái -->
        <div>
            <label class="font-semibold text-gray-700">Trạng thái</label>
            <select name="status"
                class="border border-gray-300 w-full p-2 rounded-lg focus:ring-2 focus:ring-blue-400">
                <option value="1" <?= $booking['status'] == 1 ? 'selected' : '' ?>>Chờ thanh toán</option>
                <option value="2" <?= $booking['status'] == 2 ? 'selected' : '' ?>>Đã cọc</option>
                <option value="3" <?= $booking['status'] == 3 ? 'selected' : '' ?>>Đã thanh toán đủ</option>
                <option value="4" <?= $booking['status'] == 4 ? 'selected' : '' ?>>Đã hủy</option>
                <option value="5" <?= $booking['status'] == 5 ? 'selected' : '' ?>>Hoàn thành</option>
            </select>
        </div>

        <!-- Special request -->
        <div>
            <label class="font-semibold text-gray-700">Yêu cầu đặc biệt</label>
            <textarea name="special_requests"
                class="border border-gray-300 w-full p-3 rounded-lg focus:ring-2 focus:ring-blue-400"
                rows="3"><?= $booking['special_requests'] ?></textarea>
        </div>

        <!-- Danh sách khách hàng -->
        <div>
            <label class="font-semibold text-gray-700">Chọn khách hàng</label>
            <div  id="chon-khach-hang" class="border rounded-xl p-4 bg-gray-50 max-h-64 overflow-y-auto">

                <?php $selectedCustomers = array_column($booking['customers'], 'id'); ?>

                <?php foreach ($customers as $c): ?>
                    <label class="flex justify-between items-center p-2 bg-white rounded-lg mb-2 shadow-sm hover:bg-gray-100">
                        <span class="font-medium"><?= htmlspecialchars($c['name']) ?></span>
                        <input type="checkbox" class="customerCheck"
                            name="customers[]"
                            value="<?= $c['id'] ?>"
                            <?= in_array($c['id'], $selectedCustomers) ? 'checked' : '' ?>>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Người đại diện -->
        <div>
            <label class="font-semibold text-gray-700">Khách đại diện</label>
            <select name="is_representative" id="representative"
                class="border w-full p-2 rounded-lg bg-white focus:ring-2 focus:ring-blue-400" required>
                <option value="">-- Chọn --</option>
                <?php foreach ($booking['customers'] as $c): ?>
                    <option value="<?= $c['id'] ?>" <?= ($c['id'] == $booking['is_representative']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Service -->
        <div>
            <label class="font-semibold text-gray-700">Dịch vụ thêm</label>
            <div class="grid grid-cols-2 gap-2 mt-2">
                <?php $selectedServices = array_column($booking['services'], 'service_id'); ?>

                <?php foreach ($services as $service): ?>
                    <label class="flex items-center gap-2 bg-white p-2 rounded-lg shadow-sm hover:bg-gray-100">
                        <input type="checkbox"
                            name="services[]"
                            value="<?= $service['id'] ?>"
                            <?= in_array($service['id'], $selectedServices) ? 'checked' : '' ?>>
                        <span><?= htmlspecialchars($service['name']) ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="flex fixed bottom-5 right-16 gap-3">
            <button class="px-6 py-2.5 bg-black text-white rounded-lg hover:bg-gray-900 transition font-medium">
                Cập nhật
            </button>
        </div>

    </form>

</main>

<script>
    function updatePrice() {
        let tour = document.querySelector("#tourSelect");
        if (!tour.value) return;

        let adultPrice = parseInt(tour.selectedOptions[0].dataset.adult);
        let childPrice = parseInt(tour.selectedOptions[0].dataset.child);

        let adult = parseInt(document.getElementById("adultCount").value) || 0;
        let child = parseInt(document.getElementById("childCount").value) || 0;

        let total = adult * adultPrice + child * childPrice;
        document.getElementById("totalAmount").value = total;

        updateRemaining();
    }

    function updateRemaining() {
        let total = Number(document.getElementById("totalAmount").value || 0);
        let deposit = Number(document.getElementById("depositAmount").value || 0);
        let remain = total - deposit;

        document.getElementById("remainingAmount").value = remain >= 0 ? remain : 0;
    }

    document.querySelectorAll("#tourSelect, #adultCount, #childCount").forEach(el => {
        el.addEventListener("input", updatePrice);
    });

    document.getElementById("depositAmount").addEventListener("input", updateRemaining);

    /* ------------------ Cập nhật lựa chọn người đại diện ------------------ */
    document.querySelectorAll(".customerCheck").forEach(chk => {
        chk.addEventListener("change", () => {
            let rep = document.getElementById("representative");
            rep.innerHTML = `<option value="">-- Chọn --</option>`;

            let checked = document.querySelectorAll(".customerCheck:checked");

            checked.forEach(c => {
                let name = c.parentElement.querySelector("span").innerText;
                rep.innerHTML += `<option value="${c.value}">${name}</option>`;
            });

            if (checked.length === 1) {
                rep.value = checked[0].value;
            }
        });
    });
</script>

<?php
require_once './views/components/footer.php';
?>