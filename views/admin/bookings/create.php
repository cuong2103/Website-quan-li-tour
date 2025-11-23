<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';
?>

<main class="mt-28 px-6 pb-20">

    <h1 class="text-2xl font-bold mb-6">Tạo Booking mới</h1>

    <form method="POST" action="<?= BASE_URL . '?act=booking-store' ?>" class="bg-white p-6 rounded-lg shadow">

<h1 class="text-xl font-bold mb-4">Tạo Booking mới</h1>

<div class="mb-4">
    <label class="block mb-1">Chọn Tour *</label>
    <select name="tour_id" id="tourSelect" class="border px-3 py-2 rounded w-full" required>
        <option value="">-- Chọn Tour --</option>
        <?php foreach ($tours as $t): ?>
            <option value="<?= $t['id'] ?>" data-adult="<?= $t['adult_price'] ?>" data-child="<?= $t['child_price'] ?>">
                <?= htmlspecialchars($t['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

<div class="grid grid-cols-2 gap-4 mb-4">
    <div>
        <label>Ngày khởi hành</label>
        <input type="date" name="start_date" class="border w-full p-2 rounded" required>
    </div>
    <div>
        <label>Ngày kết thúc</label>
        <input type="date" name="end_date" class="border w-full p-2 rounded" required>
    </div>
</div>

<div class="grid grid-cols-2 gap-4 mb-4">
    <div>
        <label>Người lớn</label>
        <input type="number" name="adult_count" id="adultCount" value="1" min="0" class="border w-full p-2 rounded">
    </div>
    <div>
        <label>Trẻ em</label>
        <input type="number" name="child_count" id="childCount" value="0" min="0" class="border w-full p-2 rounded">
    </div>
</div>

<div class="mb-4">
    <label>Tổng tiền</label>
    <input type="text" name="total_amount" id="totalAmount" class="border w-full p-2 rounded bg-gray-100" readonly>
</div>

<div class="mb-4">
    <label>Tiền cọc</label>
    <input type="number" name="deposit_amount" class="border w-full p-2 rounded" value="0">
</div>

<div class="mb-4">
    <label>Tiền còn lại</label>
    <input type="number" name="remaining_amount" class="border w-full p-2 rounded" value="0">
</div>

<div class="mb-4">
    <label>Yêu cầu đặc biệt</label>
    <textarea name="special_requests" class="border w-full p-2 rounded"></textarea>
</div>

<div class="mb-4">
    <label>Chọn khách hàng</label>
    <div class="border p-3 rounded max-h-60 overflow-y-auto">
        <?php foreach ($customers as $c): ?>
            <label class="flex justify-between items-center mb-2">
                <span><?= htmlspecialchars($c['name']) ?></span>
                <input type="checkbox" name="customers[]" value="<?= $c['id'] ?>">
            </label>
        <?php endforeach; ?>
    </div>
</div>

<button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Tạo Booking</button>
</form>

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

</main>

<script>
function format(n){ return n.toLocaleString("vi-VN"); }

// Cập nhật tổng tiền
document.querySelectorAll("#tourSelect, #adultCount, #childCount").forEach(el => {
    el.addEventListener("input", updatePrice);
});
function updatePrice() {
    let tour = document.querySelector("#tourSelect");
    let adult = parseInt(document.getElementById("adultCount").value);
    let child = parseInt(document.getElementById("childCount").value);
    if (!tour.value) return;

    let adultPrice = parseInt(tour.selectedOptions[0].dataset.adult);
    let childPrice = parseInt(tour.selectedOptions[0].dataset.child);

    let total = adult * adultPrice + child * childPrice;
    document.getElementById("totalAmount").value = total;

    updateSummary();
}

// Cập nhật danh sách đại diện
document.querySelectorAll(".customerCheck").forEach(chk => {
    chk.addEventListener("change", () => {
        let rep = document.getElementById("representative");
        rep.innerHTML = "<option value=''>-- Chọn --</option>";
        document.querySelectorAll(".customerCheck:checked").forEach(c => {
            rep.innerHTML += `<option value="${c.value}">Khách ID: ${c.value}</option>`;
        });
        updateSummary();
    });
});

// Update summary
function updateSummary() {
    let tour = document.querySelector("#tourSelect");
    document.getElementById("sumTour").innerText = tour.value ? tour.selectedOptions[0].text : '';
    document.getElementById("sumAdult").innerText = document.getElementById("adultCount").value;
    document.getElementById("sumChild").innerText = document.getElementById("childCount").value;
    document.getElementById("sumTotal").innerText = format(parseInt(document.getElementById("totalAmount").value || 0)) + " VNĐ";

    let list = document.getElementById("sumCustomers");
    list.innerHTML = '';
    document.querySelectorAll(".customerCheck:checked").forEach(c => {
        let name = c.parentElement.querySelector(".font-semibold").innerText;
        list.innerHTML += `<li>${name}</li>`;
    });

    document.getElementById("sumRep").innerText =
        document.querySelector("#representative option:checked")?.text || '';
}

// Khởi tạo summary khi load trang
updateSummary();
</script>

<?php
require_once './views/components/footer.php';
?>
