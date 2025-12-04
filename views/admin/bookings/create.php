<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';
?>

<main class="mt-28 px-6 pb-24 text-gray-700">

    <!-- Header Title -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Tạo Booking mới</h1>
            <p class="text-sm text-gray-500 mt-1">Điền thông tin để tạo mới một booking.</p>
        </div>
        <a href="<?= BASE_URL . '?act=bookings' ?>"
            class="px-4 py-2 bg-white border border-gray-300 hover:bg-gray-50 rounded-lg text-sm font-medium transition shadow-sm">
            Quay lại
        </a>
    </div>

    <!-- FORM -->
    <form method="POST" action="<?= BASE_URL . '?act=booking-store' ?>" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
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
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-100 focus:border-blue-400 outline-none transition"
                            required>
                            <option value="">-- Chọn Tour --</option>
                            <?php foreach ($tours as $t): ?>
                                <option value="<?= $t['id'] ?>"
                                    data-adult="<?= $t['adult_price'] ?>"
                                    data-child="<?= $t['child_price'] ?>"
                                    data-duration="<?= $t['duration_days'] ?>"
                                    <?= (isset($_GET['tour_id']) && $_GET['tour_id'] == $t['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($t['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-1.5 text-sm font-medium text-gray-700">Ngày khởi hành <span class="text-red-500">*</span></label>
                            <input type="date" name="start_date"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-100 focus:border-blue-400 outline-none transition" required>
                        </div>

                        <div>
                            <label class="block mb-1.5 text-sm font-medium text-gray-700">Ngày kết thúc <span class="text-red-500">*</span></label>
                            <input type="date" name="end_date"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-100 focus:border-blue-400 outline-none transition" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card: Thông tin người đại diện -->
            <div class="bg-white shadow-sm rounded-xl p-6 border border-gray-100">
                <div class="flex items-center gap-2 mb-4">
                    <div class="p-2 bg-green-50 rounded-lg text-green-600">
                        <i data-lucide="user" class="w-5 h-5"></i>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-800">Thông tin người đại diện</h2>
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-1.5 text-sm font-medium text-gray-700">Họ và tên <span class="text-red-500">*</span></label>
                            <input type="text" name="rep_name" placeholder="Nguyễn Văn A"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-green-100 focus:border-green-400 outline-none transition" required>
                        </div>
                        <div>
                            <label class="block mb-1.5 text-sm font-medium text-gray-700">Số điện thoại <span class="text-red-500">*</span></label>
                            <input type="text" name="rep_phone" placeholder="0912345678"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-green-100 focus:border-green-400 outline-none transition" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-1.5 text-sm font-medium text-gray-700">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="rep_email" placeholder="email@example.com"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-green-100 focus:border-green-400 outline-none transition" required>
                        </div>
                        <div>
                            <label class="block mb-1.5 text-sm font-medium text-gray-700">Giới tính</label>
                            <select name="rep_gender"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-green-100 focus:border-green-400 outline-none transition">
                                <option value="male">Nam</option>
                                <option value="female">Nữ</option>
                                <option value="other">Khác</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-1.5 text-sm font-medium text-gray-700">Hộ chiếu (Passport)</label>
                            <input type="text" name="rep_passport" placeholder="Số hộ chiếu..."
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-green-100 focus:border-green-400 outline-none transition">
                        </div>
                        <div>
                            <label class="block mb-1.5 text-sm font-medium text-gray-700">CMND/CCCD</label>
                            <input type="text" name="rep_citizen_id" placeholder="Số CMND/CCCD..."
                                class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-green-100 focus:border-green-400 outline-none transition">
                        </div>
                    </div>
                    <div>
                        <label class="block mb-1.5 text-sm font-medium text-gray-700">Địa chỉ</label>
                        <input type="text" name="rep_address" placeholder="Địa chỉ liên hệ..."
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-green-100 focus:border-green-400 outline-none transition">
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
                        // Check if service is in selected tour
                        $isChecked = false;
                        $defaultQty = 1;
                        $defaultPrice = $sv['estimated_price'];

                        if (!empty($selectedTourServices)) {
                            foreach ($selectedTourServices as $ts) {
                                if ($ts['service_id'] == $sv['id']) {
                                    $isChecked = true;
                                    $defaultQty = $ts['default_quantity'] ?? 1;
                                    break;
                                }
                            }
                        }
                        ?>
                        <div class="service-item p-3 border border-gray-200 rounded-lg hover:bg-purple-50 transition">
                            <label class="flex items-center gap-3 cursor-pointer mb-2">
                                <input type="checkbox" name="services[]" value="<?= $sv['id'] ?>"
                                    class="w-4 h-4 text-purple-600 rounded border-gray-300 focus:ring-purple-500 service-checkbox"
                                    data-id="<?= $sv['id'] ?>"
                                    <?= $isChecked ? 'checked' : '' ?>>
                                <span class="text-sm font-medium text-gray-700"><?= htmlspecialchars($sv['name']) ?></span>
                            </label>

                            <div class="grid grid-cols-2 gap-2 pl-7 <?= $isChecked ? '' : 'hidden' ?>" id="service-inputs-<?= $sv['id'] ?>">
                                <div>
                                    <label class="text-xs text-gray-500">Giá (VNĐ)</label>
                                    <input type="number" name="service_prices[<?= $sv['id'] ?>]"
                                        value="<?= $defaultPrice ?>"
                                        class="w-full border border-gray-300 rounded px-2 py-1 text-sm outline-none focus:border-purple-400">
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500">Số lượng</label>
                                    <input type="number" name="service_quantities[<?= $sv['id'] ?>]"
                                        value="<?= $defaultQty ?>" min="1"
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
                    placeholder="Nhập yêu cầu đặc biệt của khách hàng..."></textarea>
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
                            <input type="number" id="adultCount" name="adult_count" value="1" min="1"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-100 focus:border-orange-400 outline-none transition text-center font-medium">
                        </div>
                        <div>
                            <label class="block mb-1.5 text-sm font-medium text-gray-700">Trẻ em</label>
                            <input type="number" id="childCount" name="child_count" value="0" min="0"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-100 focus:border-orange-400 outline-none transition text-center font-medium">
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
                            <input type="text" id="totalAmountDisplay" value="0" readonly
                                class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-3 text-xl font-bold text-orange-600 text-right outline-none">
                            <input type="hidden" name="total_amount" id="totalAmountInput">
                        </div>
                    </div>

                    <div>
                        <label class="block mb-1.5 text-sm font-medium text-gray-700">Trạng thái</label>
                        <select name="status"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-100 focus:border-blue-400 outline-none transition">
                            <option value="1">Chờ thanh toán</option>
                            <option value="2">Đã cọc</option>
                            <option value="3">Đã thanh toán đủ</option>
                        </select>
                    </div>

                    <div class="flex fixed bottom-5 right-16 gap-3">
                        <button class="px-6 py-2.5 bg-black text-white rounded-lg hover:bg-gray-900 transition font-medium">
                            Tạo booking
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </form>

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
            document.querySelectorAll('.service-checkbox:checked').forEach(cb => {
                const id = cb.dataset.id;
                const priceInput = document.querySelector(`input[name="service_prices[${id}]"]`);
                const qtyInput = document.querySelector(`input[name="service_quantities[${id}]"]`);

                const price = Number(priceInput.value) || 0;
                const qty = Number(qtyInput.value) || 1;

                serviceTotal += price * qty;
            });

            // 3. Tổng cộng
            const grandTotal = tourTotal + serviceTotal;

            // Update UI
            totalServicePriceDisplay.value = formatCurrency(serviceTotal);
            totalAmountDisplay.value = formatCurrency(grandTotal);
            totalAmountInput.value = grandTotal;
        }

        [tourSelect, adultCount, childCount].forEach(el => {
            el.addEventListener("input", updatePrice);
        });
        // Cần lắng nghe change ở checkbox và input ở các trường giá/số lượng
        document.querySelectorAll('.service-checkbox').forEach(cb => {
            cb.addEventListener('change', function() {
                const inputsDiv = document.getElementById(`service-inputs-${this.dataset.id}`);
                if (this.checked) {
                    inputsDiv.classList.remove('hidden');
                } else {
                    inputsDiv.classList.add('hidden');
                }
                updatePrice(); // Tính lại tiền khi check/uncheck
            });
        });

        // Lắng nghe sự thay đổi giá/số lượng của dịch vụ
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
        // Tính end_date khi chọn tour (không reload)
        tourSelect.addEventListener('change', function() {
            calculateEndDate();
            updatePrice(); // Cập nhật giá
        });
        startDateInput.addEventListener('change', calculateEndDate);
        // Run on load
        calculateEndDate();

        // Trigger updatePrice on load to calculate initial total
        updatePrice();
    </script>

</main>

<?php require_once './views/components/footer.php'; ?>