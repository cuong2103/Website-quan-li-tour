<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';
?>

<main class="mt-28 px-6 pb-24 text-gray-700">

    <!-- Header Title -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Cập nhật Booking</h1>
            <p class="text-sm text-gray-500 mt-1">Chỉnh sửa thông tin booking.</p>
        </div>
        <a href="<?= BASE_URL . '?act=bookings' ?>"
            class="px-4 py-2 bg-white border border-gray-300 hover:bg-gray-50 rounded-lg text-sm font-medium transition shadow-sm">
            Quay lại
        </a>
    </div>

    <!-- FORM -->
    <form method="POST" action="<?= BASE_URL . '?act=booking-update' ?>" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <input type="hidden" name="id" value="<?= $booking['id'] ?>">

        <!-- LEFT COLUMN (2/3) -->
        <div class="lg:col-span-3 space-y-6">

            <!-- Card: Thông tin Tour -->
            <div class="bg-white shadow-sm rounded-xl p-6 border border-gray-100">
                <div class="flex items-center gap-2 mb-4">
                    <div class="p-2 bg-blue-50 rounded-lg text-blue-600">
                        <i data-lucide="map" class="w-5 h-5"></i>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-800">Thông tin Tour</h2>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block mb-1.5 text-sm font-medium text-gray-700">Chọn Tour <span class="text-red-500">*</span></label>
                        <select name="tour_id" id="tourSelect"
                            class="w-full border <?= isset($_SESSION['validate_errors']['tour_id']) ? 'border-red-500' : 'border-gray-300' ?> rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-100 focus:border-blue-400 outline-none transition">
                            <option value="">-- Chọn Tour --</option>
                            <?php foreach ($tours as $t): ?>
                                <option value="<?= $t['id'] ?>"
                                    data-adult="<?= $t['adult_price'] ?>"
                                    data-child="<?= $t['child_price'] ?>"
                                    data-duration="<?= $t['duration_days'] ?>"
                                    <?= $t['id'] == (old('tour_id') ?: $booking['tour_id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($t['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($_SESSION['validate_errors']['tour_id'])): ?>
                            <p class="text-sm text-red-500 mt-1"><?= is_array($_SESSION['validate_errors']['tour_id']) ? $_SESSION['validate_errors']['tour_id'][0] : $_SESSION['validate_errors']['tour_id'] ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-1.5 text-sm font-medium text-gray-700">Ngày khởi hành <span class="text-red-500">*</span></label>
                            <input type="date" name="start_date"
                                value="<?= old('start_date') ?: $booking['start_date'] ?>"
                                class="w-full border <?= isset($_SESSION['validate_errors']['start_date']) ? 'border-red-500' : 'border-gray-300' ?> rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-100 focus:border-blue-400 outline-none transition">
                            <?php if (isset($_SESSION['validate_errors']['start_date'])): ?>
                                <p class="text-sm text-red-500 mt-1"><?= is_array($_SESSION['validate_errors']['start_date']) ? $_SESSION['validate_errors']['start_date'][0] : $_SESSION['validate_errors']['start_date'] ?></p>
                            <?php endif; ?>
                        </div>

                        <div>
                            <label class="block mb-1.5 text-sm font-medium text-gray-700">Ngày kết thúc <span class="text-red-500">*</span></label>
                            <input type="date" name="end_date"
                                value="<?= old('end_date') ?: $booking['end_date'] ?>"
                                class="w-full border <?= isset($_SESSION['validate_errors']['end_date']) ? 'border-red-500' : 'border-gray-300' ?> rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-100 focus:border-blue-400 outline-none transition">
                            <?php if (isset($_SESSION['validate_errors']['end_date'])): ?>
                                <p class="text-sm text-red-500 mt-1"><?= is_array($_SESSION['validate_errors']['end_date']) ? $_SESSION['validate_errors']['end_date'][0] : $_SESSION['validate_errors']['end_date'] ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card: Khách hàng (Chỉ hiển thị danh sách để chọn người đại diện, logic thêm khách chi tiết ở trang detail) -->
            <div class="bg-white shadow-sm rounded-xl p-6 border border-gray-100">
                <div class="flex items-center gap-2 mb-4">
                    <div class="p-2 bg-green-50 rounded-lg text-green-600">
                        <i data-lucide="users" class="w-5 h-5"></i>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-800">Khách hàng & Người đại diện</h2>
                </div>

                <div class="space-y-4">
                    <label class="block mb-1.5 text-sm font-medium text-gray-700">Danh sách khách hàng trong booking này</label>
                    <div class="max-h-60 overflow-y-auto border border-gray-200 rounded-lg p-3">
                        <?php if (empty($booking['customers'])): ?>
                            <p class="text-sm text-gray-500 italic">Chưa có khách hàng nào.</p>
                        <?php else: ?>
                            <?php foreach ($booking['customers'] as $c): ?>
                                <div class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded border-b last:border-0 border-gray-100">
                                    <input type="checkbox" name="customers[]" value="<?= $c['id'] ?>" checked class="customerCheck w-4 h-4 text-green-600 rounded border-gray-300 focus:ring-green-500">
                                    <div>
                                        <p class="text-sm font-medium text-gray-800"><?= htmlspecialchars($c['name']) ?></p>
                                        <p class="text-xs text-gray-500"><?= htmlspecialchars($c['phone']) ?> - <?= htmlspecialchars($c['email']) ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label class="block mb-1.5 text-sm font-medium text-gray-700">Người đại diện <span class="text-red-500">*</span></label>
                        <select name="is_representative" id="representative"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-green-100 focus:border-green-400 outline-none transition" required>
                            <option value="">-- Chọn người đại diện --</option>
                            <?php foreach ($booking['customers'] as $c): ?>
                                <option value="<?= $c['id'] ?>" <?= ($c['id'] == $booking['is_representative']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($c['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Card: Dịch vụ -->
            <div class="bg-white shadow-sm rounded-xl p-6 border border-gray-100">
                <div class="flex items-center gap-2 mb-4">
                    <div class="p-2 bg-purple-50 rounded-lg text-purple-600">
                        <i data-lucide="concierge-bell" class="w-5 h-5"></i>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-800">Dịch vụ đi kèm</h2>
                </div>

                <input id="searchService" type="text" placeholder="Tìm dịch vụ..."
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 mb-3 text-sm focus:ring-2 focus:ring-purple-100 focus:border-purple-400 outline-none transition">

                <div id="serviceList" class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-60 overflow-y-auto">
                    <?php foreach ($services as $sv): ?>
                        <?php
                        $isSelected = false;
                        $currentPrice = $sv['estimated_price'];
                        $currentQuantity = 1;

                        foreach ($selectedServices as $ss) {
                            if ($ss['id'] == $sv['id']) {
                                $isSelected = true;
                                $currentPrice = $ss['current_price'];
                                $currentQuantity = $ss['quantity'];
                                break;
                            }
                        }
                        ?>
                        <div class="service-item p-3 border border-gray-200 rounded-lg hover:bg-purple-50 transition">
                            <label class="flex items-center gap-3 cursor-pointer mb-2">
                                <input type="checkbox" name="services[]" value="<?= $sv['id'] ?>"
                                    class="w-4 h-4 text-purple-600 rounded border-gray-300 focus:ring-purple-500 service-checkbox"
                                    data-id="<?= $sv['id'] ?>"
                                    data-unit="<?= $sv['unit'] ?? 'person' ?>"
                                    <?= $isSelected ? 'checked' : '' ?>>
                                <span class="text-sm font-medium text-gray-700">
                                    <?= htmlspecialchars($sv['name']) ?>
                                    <span class="text-xs text-gray-500">
                                        (<?php
                                            $unitMap = ['person' => 'Người', 'room' => 'Phòng', 'vehicle' => 'Xe', 'day' => 'Ngày', 'meal' => 'Suất ăn'];
                                            echo $unitMap[$sv['unit'] ?? 'person'] ?? 'Người';
                                            ?>)
                                    </span>
                                </span>
                            </label>

                            <div class="grid grid-cols-2 gap-2 pl-7 <?= $isSelected ? '' : 'hidden' ?>" id="service-inputs-<?= $sv['id'] ?>">
                                <div>
                                    <label class="text-xs text-gray-500">Giá (VNĐ)</label>
                                    <input type="number" name="service_prices[<?= $sv['id'] ?>]"
                                        value="<?= $currentPrice ?>"
                                        class="w-full border border-gray-300 rounded px-2 py-1 text-sm outline-none focus:border-purple-400">
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500">Số lượng</label>
                                    <input type="number" name="service_quantities[<?= $sv['id'] ?>]"
                                        value="<?= $currentQuantity ?>" min="1"
                                        class="w-full border border-gray-300 rounded px-2 py-1 text-sm outline-none focus:border-purple-400">
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <p id="noServiceResult" class="hidden text-sm text-gray-500 italic mt-2 text-center">Không tìm thấy dịch vụ phù hợp.</p>
            </div>

            <!-- Card: Ghi chú -->
            <div class="bg-white shadow-sm rounded-xl p-6 border border-gray-100">
                <div class="flex items-center gap-2 mb-4">
                    <div class="p-2 bg-gray-100 rounded-lg text-gray-600">
                        <i data-lucide="file-text" class="w-5 h-5"></i>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-800">Ghi chú</h2>
                </div>
                <textarea name="special_requests"
                    class="w-full border border-gray-300 rounded-lg p-3 h-28 resize-none focus:ring-2 focus:ring-gray-200 focus:border-gray-400 outline-none transition"
                    placeholder="Nhập yêu cầu đặc biệt của khách hàng..."><?= htmlspecialchars($booking['special_requests']) ?></textarea>
            </div>

            <!-- Card: Chi phí & Thanh toán -->
            <div class="bg-white shadow-sm rounded-xl p-6 border border-gray-100 sticky top-28">
                <div class="flex items-center gap-2 mb-4">
                    <div class="p-2 bg-orange-50 rounded-lg text-orange-600">
                        <i data-lucide="credit-card" class="w-5 h-5"></i>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-800">Chi phí & Thanh toán</h2>
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-1.5 text-sm font-medium text-gray-700">Người lớn</label>
                            <input type="number" id="adultCount" name="adult_count"
                                value="<?= old('adult_count') ?: $booking['adult_count'] ?>" min="1"
                                class="w-full border <?= isset($_SESSION['validate_errors']['adult_count']) ? 'border-red-500' : 'border-gray-300' ?> rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-100 focus:border-orange-400 outline-none transition text-center font-medium">
                            <?php if (isset($_SESSION['validate_errors']['adult_count'])): ?>
                                <p class="text-sm text-red-500 mt-1"><?= is_array($_SESSION['validate_errors']['adult_count']) ? $_SESSION['validate_errors']['adult_count'][0] : $_SESSION['validate_errors']['adult_count'] ?></p>
                            <?php endif; ?>
                        </div>
                        <div>
                            <label class="block mb-1.5 text-sm font-medium text-gray-700">Trẻ em</label>
                            <input type="number" id="childCount" name="child_count"
                                value="<?= old('child_count') ?: $booking['child_count'] ?>" min="0"
                                class="w-full border <?= isset($_SESSION['validate_errors']['child_count']) ? 'border-red-500' : 'border-gray-300' ?> rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-100 focus:border-orange-400 outline-none transition text-center font-medium">
                            <?php if (isset($_SESSION['validate_errors']['child_count'])): ?>
                                <p class="text-sm text-red-500 mt-1"><?= is_array($_SESSION['validate_errors']['child_count']) ? $_SESSION['validate_errors']['child_count'][0] : $_SESSION['validate_errors']['child_count'] ?></p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-dashed border-gray-200">
                        <label class="block mb-1.5 text-sm font-medium text-gray-700">Tổng tiền dịch vụ</label>
                        <div class="relative">
                            <input type="text" id="totalServicePriceDisplay" value="0" readonly
                                class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-3 text-lg font-bold text-purple-600 text-right outline-none">
                        </div>
                    </div>

                    <div class="pt-4 border-t border-dashed border-gray-200">
                        <label class="block mb-1.5 text-sm font-medium text-gray-700">Tổng tiền dự kiến</label>
                        <div class="relative">
                            <input type="text" id="totalAmountDisplay" value="<?= number_format($booking['total_amount'], 0, ',', '.') ?>" readonly
                                class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-3 text-xl font-bold text-orange-600 text-right outline-none">
                            <input type="hidden" name="total_amount" id="totalAmountInput" value="<?= $booking['total_amount'] ?>">
                        </div>
                    </div>

                    <div>
                        <label class="block mb-1.5 text-sm font-medium text-gray-700">Trạng thái</label>
                        <select name="status"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-100 focus:border-blue-400 outline-none transition">
                            <option value="pending">Chờ thanh toán</option>
                            <option value="deposited">Đã cọc</option>
                            <option value="paid">Đã thanh toán đủ</option>
                        </select>
                    </div>

                    <div class="flex fixed bottom-5 right-16 gap-3">
                        <button class="px-6 py-2.5 bg-black text-white rounded-lg hover:bg-gray-900 transition font-medium">
                            Cập nhật Booking
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </form>

    <!-- SCRIPT -->
    <script>
        // --- LOGIC TÍNH TIỀN ---
        const tourSelect = document.getElementById("tourSelect");
        const adultCount = document.getElementById("adultCount");
        const childCount = document.getElementById("childCount");
        const totalAmountDisplay = document.getElementById("totalAmountDisplay");
        const totalAmountInput = document.getElementById("totalAmountInput");
        const totalServicePriceDisplay = document.getElementById("totalServicePriceDisplay");

        function formatCurrency(amount) {
            return new Intl.NumberFormat('vi-VN').format(amount);
        }

        function updatePrice() {
            // 1. Tính tiền Tour
            let tourTotal = 0;
            if (tourSelect.value) {
                const option = tourSelect.selectedOptions[0];
                const adultPrice = Number(option.dataset.adult) || 0;
                const childPrice = Number(option.dataset.child) || 0;
                const adults = Number(adultCount.value) || 0;
                const children = Number(childCount.value) || 0;
                tourTotal = (adults * adultPrice) + (children * childPrice);
            }

            // 2. Tính tiền Dịch vụ
            let serviceTotal = 0;
            const totalPeople = (Number(adultCount.value) || 0) + (Number(childCount.value) || 0);

            document.querySelectorAll('.service-checkbox:checked').forEach(cb => {
                const id = cb.dataset.id;
                const unit = cb.dataset.unit || 'person'; // Lấy đơn vị tính
                const priceInput = document.querySelector(`input[name="service_prices[${id}]"]`);
                const qtyInput = document.querySelector(`input[name="service_quantities[${id}]"]`);
                const price = Number(priceInput.value) || 0;
                const qty = Number(qtyInput.value) || 1;
                // Tính theo đơn vị
                if (unit === 'person') {
                    // Dịch vụ tính theo người: nhân với tổng số người
                    serviceTotal += price * qty * totalPeople;
                } else {
                    // Các đơn vị khác: không nhân với số người
                    serviceTotal += price * qty;
                }
            });

            // 3. Tổng cộng
            const grandTotal = tourTotal + serviceTotal;

            // Update UI
            totalServicePriceDisplay.value = formatCurrency(serviceTotal);
            totalAmountDisplay.value = formatCurrency(grandTotal);
            totalAmountInput.value = grandTotal;
        }

        [tourSelect, adultCount, childCount].forEach(el => {
            if (el) el.addEventListener("input", updatePrice);
        });

        // Event Listeners cho Services
        document.querySelectorAll('.service-checkbox').forEach(cb => {
            cb.addEventListener('change', function() {
                const inputsDiv = document.getElementById(`service-inputs-${this.dataset.id}`);
                if (this.checked) {
                    inputsDiv.classList.remove('hidden');
                } else {
                    inputsDiv.classList.add('hidden');
                }
                updatePrice();
            });
        });

        document.querySelectorAll('input[name^="service_prices"], input[name^="service_quantities"]').forEach(input => {
            input.addEventListener('input', updatePrice);
        });


        // --- LOGIC TÌM DỊCH VỤ ---
        const searchService = document.getElementById("searchService");
        const serviceItems = document.querySelectorAll(".service-item");
        const noServiceResult = document.getElementById("noServiceResult");

        searchService.addEventListener("keyup", function() {
            const keyword = this.value.toLowerCase();
            let count = 0;
            serviceItems.forEach(item => {
                const text = item.innerText.toLowerCase();
                if (text.includes(keyword)) {
                    item.style.display = "flex";
                    count++;
                } else {
                    item.style.display = "none";
                }
            });
            noServiceResult.classList.toggle("hidden", count > 0);
        });

        // Ngăn submit form khi nhấn Enter ở ô tìm kiếm
        document.querySelectorAll('input[type="text"]').forEach(input => {
            input.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') e.preventDefault();
            });
        });
        updatePrice();

        // --- LOGIC TÍNH NGÀY KẾT THÚC ---
        const startDateInput = document.querySelector('input[name="start_date"]');
        const endDateInput = document.querySelector('input[name="end_date"]');

        function calculateEndDate() {
            const startDateVal = startDateInput.value;
            const selectedOption = tourSelect.selectedOptions[0];
            const duration = selectedOption ? parseInt(selectedOption.dataset.duration) || 0 : 0;

            if (startDateVal && duration > 0) {
                const start = new Date(startDateVal);
                const end = new Date(start);
                end.setDate(start.getDate() + (duration - 1));
                const yyyy = end.getFullYear();
                const mm = String(end.getMonth() + 1).padStart(2, '0');
                const dd = String(end.getDate()).padStart(2, '0');
                endDateInput.value = `${yyyy}-${mm}-${dd}`;
                // Lock input
                endDateInput.readOnly = true;
                endDateInput.classList.add('bg-gray-100', 'cursor-not-allowed');
            } else {
                endDateInput.readOnly = false;
                endDateInput.classList.remove('bg-gray-100', 'cursor-not-allowed');
            }
        }
        // Tính end_date khi chọn tour hoặc thay đổi start_date
        tourSelect.addEventListener('change', calculateEndDate);
        startDateInput.addEventListener('change', calculateEndDate);
        // Run on load
        calculateEndDate();

        document.querySelectorAll(".customerCheck").forEach(chk => {
            chk.addEventListener("change", () => {
                let rep = document.getElementById("representative");
                let currentRep = rep.value;
                rep.innerHTML = `<option value="">-- Chọn người đại diện --</option>`;

                let checked = document.querySelectorAll(".customerCheck:checked");

                checked.forEach(c => {
                    let nameContainer = c.parentElement.querySelector("p.font-medium"); // Updated selector
                    let name = nameContainer ? nameContainer.innerText : "Khách hàng";
                    rep.innerHTML += `<option value="${c.value}">${name}</option>`;
                });

                if ([...checked].some(c => c.value == currentRep)) {
                    rep.value = currentRep;
                } else if (checked.length === 1) {
                    rep.value = checked[0].value;
                }
            });
        });
    </script>
</main>
<?php 
// Xóa session errors và old data sau khi đã hiển thị
unset($_SESSION['validate_errors']);
unset($_SESSION['old']);
require_once './views/components/footer.php'; 
?>