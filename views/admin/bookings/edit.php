<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';
?>

<main class="mt-28 px-6 pb-20">
    <h1 class="text-2xl font-bold mb-6">Chỉnh sửa Booking</h1>

    <form action="<?= BASE_URL . '?act=booking-update' ?>" method="POST" class="bg-white p-6 rounded-lg shadow">

        <input type="hidden" name="id" value="<?= $booking['id'] ?>">

        <!-- Chọn Tour -->
        <div class="mb-4">
            <label class="block mb-1">Chọn Tour *</label>
            <select name="tour_id" id="tourSelect" class="border px-3 py-2 rounded w-full" required>
                <option value="">-- Chọn Tour --</option>
                <?php foreach ($tours as $t): ?>
                    <option value="<?= $t['id'] ?>" 
                        data-adult="<?= $t['adult_price'] ?>" 
                        data-child="<?= $t['child_price'] ?>"
                        <?= $t['id']==$booking['tour_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($t['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Ngày -->
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label>Ngày khởi hành</label>
                <input type="date" name="start_date" class="border w-full p-2 rounded" 
                       value="<?= $booking['start_date'] ?>" required>
            </div>
            <div>
                <label>Ngày kết thúc</label>
                <input type="date" name="end_date" class="border w-full p-2 rounded" 
                       value="<?= $booking['end_date'] ?>" required>
            </div>
        </div>

        <!-- Số lượng -->
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label>Người lớn</label>
                <input type="number" name="adult_count" id="adultCount" value="<?= $booking['adult_count'] ?>" min="0"
                       class="border w-full p-2 rounded">
            </div>
            <div>
                <label>Trẻ em</label>
                <input type="number" name="child_count" id="childCount" value="<?= $booking['child_count'] ?>" min="0"
                       class="border w-full p-2 rounded">
            </div>
        </div>

        <!-- Tổng tiền -->
        <div class="mb-4">
            <label>Tổng tiền</label>
            <input type="text" name="total_amount" id="totalAmount" value="<?= $booking['total_amount'] ?>" 
                   class="border w-full p-2 rounded bg-gray-100" readonly>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label>Tiền cọc</label>
                <input type="number" name="deposit_amount" value="<?= $booking['deposit_amount'] ?>" class="border w-full p-2 rounded">
            </div>
            <div>
                <label>Tiền còn lại</label>
                <input type="number" name="remaining_amount" value="<?= $booking['remaining_amount'] ?>" class="border w-full p-2 rounded">
            </div>
        </div>

        <div class="mb-4">
            <label>Trạng thái</label>
            <select name="status" class="border w-full p-2 rounded">
                <option value="1" <?= $booking['status']==1 ? 'selected' : '' ?>>Pending</option>
                <option value="2" <?= $booking['status']==2 ? 'selected' : '' ?>>Deposited</option>
                <option value="3" <?= $booking['status']==3 ? 'selected' : '' ?>>Completed</option>
                <option value="4" <?= $booking['status']==4 ? 'selected' : '' ?>>Cancelled</option>
            </select>
        </div>

        <!-- Yêu cầu đặc biệt -->
        <div class="mb-4">
            <label>Yêu cầu đặc biệt</label>
            <textarea name="special_requests" class="border w-full p-2 rounded"><?= $booking['special_requests'] ?></textarea>
        </div>

        <!-- Danh sách khách hàng -->
        <div class="mb-4">
            <label>Chọn khách hàng</label>
            <div class="border p-3 rounded max-h-60 overflow-y-auto">
                <?php 
                $selectedCustomers = array_column($booking['customers'], 'id'); 
                foreach ($customers as $c): ?>
                    <label class="flex justify-between items-center mb-2">
                        <span><?= htmlspecialchars($c['name']) ?></span>
                        <input type="checkbox" name="customers[]" value="<?= $c['id'] ?>"
                            <?= in_array($c['id'], $selectedCustomers) ? 'checked' : '' ?>>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Cập nhật Booking</button>
    </form>
</main>

<script>
function format(n){ return n.toLocaleString("vi-VN"); }

document.querySelectorAll("#tourSelect, #adultCount, #childCount").forEach(el => {
    el.addEventListener("input", updatePrice);
});

function updatePrice() {
    let tour = document.querySelector("#tourSelect");
    if (!tour.value) return;

    let adultPrice = parseInt(tour.selectedOptions[0].dataset.adult);
    let childPrice = parseInt(tour.selectedOptions[0].dataset.child);
    let adult = parseInt(document.getElementById("adultCount").value) || 0;
    let child = parseInt(document.getElementById("childCount").value) || 0;

    let total = adult * adultPrice + child * childPrice;
    document.getElementById("totalAmount").value = total;
}
</script>

<?php
require_once './views/components/footer.php';
?>
