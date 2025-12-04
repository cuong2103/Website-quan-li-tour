<?php
// Kiểm tra xem có phải là request AJAX không
$isAjax = isset($_GET['ajax']) && $_GET['ajax'] == 1;

// Chỉ include header và sidebar nếu KHÔNG phải là AJAX
if (!$isAjax) {
    require_once './views/components/header.php';
    require_once './views/components/sidebar.php';
}

// Hàm helper (chỉ định nghĩa nếu chưa tồn tại để tránh lỗi)
if (!function_exists('renderStatusBadge')) {
    function renderStatusBadge($status)
    {
        switch ($status) {
            case 'pending': // Chờ thanh toán
                return '<span class="px-3 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-700">Chờ thanh toán</span>';

            case 'deposited': // Đã cọc
                return '<span class="px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700">Đã cọc</span>';

            case 'paid': // Đã thanh toán đủ
                return '<span class="px-3 py-1 text-xs font-medium rounded-full bg-sky-100 text-sky-700">Đã thanh toán đủ</span>';

            case 'cancelled': // Đã hủy
                return '<span class="px-3 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700">Đã hủy</span>';

            case 'completed': // Hoàn thành Tour
                return '<span class="px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">Hoàn thành Tour</span>';

            default:
                return '<span class="px-3 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-700">Không rõ</span>';
        }
    }
}
?>

<?php if (!$isAjax): ?>
    <main class="mt-28 px-6 pb-20 overflow-auto scrollbar-hide">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Quản lí booking</h1>
                <p class="text-sm text-gray-600">Danh sách các booking</p>
            </div>
            <div class="flex space-x-3">
                <a href="<?= BASE_URL . '?act=booking-create' ?>"
                    class="px-5 py-2.5 text-white text-sm font-medium rounded-lg bg-orange-400 hover:bg-orange-500 flex items-center space-x-2 shadow-sm transition-all">
                    <span>+ Tạo booking</span>
                </a>
            </div>
        </div>

        <!-- Filter Toolbar -->
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6">
            <form id="filter-form" class="flex flex-col md:flex-row gap-4 items-end">
                <!-- FIX: Thêm input hidden act để không bị redirect về dashboard -->
                <input type="hidden" name="act" value="bookings">

                <!-- Keyword Search -->
                <div class="flex-1 w-full">
                    <label class="block text-xs font-medium text-gray-500 mb-1.5 uppercase tracking-wider">Tìm kiếm</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="w-4 h-4 text-gray-400 group-focus-within:text-blue-500 transition-colors" data-lucide="search"></i>
                        </div>
                        <input type="text" name="keyword" value="<?= htmlspecialchars($filters['keyword'] ?? '') ?>"
                            class="pl-10 w-full rounded-lg border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all text-sm py-2.5"
                            placeholder="Tên tour ">
                    </div>
                </div>

                <!-- Status Filter -->
                <div class="w-full md:w-48">
                    <label class="block text-xs font-medium text-gray-500 mb-1.5 uppercase tracking-wider">Trạng thái</label>
                    <div class="relative">
                        <select name="status" class="w-full appearance-none rounded-lg border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all text-sm py-2.5 pl-3 pr-8 cursor-pointer">
                            <option value="">Tất cả trạng thái</option>
                            <option value="pending" <?= ($filters['status'] ?? '') === 'pending' ? 'selected' : '' ?>>Chờ thanh toán</option>
                            <option value="deposited" <?= ($filters['status'] ?? '') === 'deposited' ? 'selected' : '' ?>>Đã cọc</option>
                            <option value="paid" <?= ($filters['status'] ?? '') === 'paid' ? 'selected' : '' ?>>Đã thanh toán đủ</option>
                            <option value="completed" <?= ($filters['status'] ?? '') === 'completed' ? 'selected' : '' ?>>Hoàn thành Tour</option>
                            <option value="cancelled" <?= ($filters['status'] ?? '') === 'cancelled' ? 'selected' : '' ?>>Đã hủy</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                            <i class="w-4 h-4 text-gray-400" data-lucide="chevron-down"></i>
                        </div>
                    </div>
                </div>

                <!-- Date Range -->
                <div class="flex gap-2 w-full md:w-auto">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5 uppercase tracking-wider">Từ ngày</label>
                        <input type="date" name="date_from" value="<?= htmlspecialchars($filters['date_from'] ?? '') ?>"
                            class="w-full rounded-lg border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all text-sm py-2.5">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1.5 uppercase tracking-wider">Đến ngày</label>
                        <input type="date" name="date_to" value="<?= htmlspecialchars($filters['date_to'] ?? '') ?>"
                            class="w-full rounded-lg border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all text-sm py-2.5">
                    </div>
                </div>
            </form>
        </div>

        <!-- Table Container -->
        <div id="booking-table-container" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden transition-opacity duration-200">
        <?php endif; // End if !$isAjax 
        ?>

        <!-- PHẦN BẢNG DỮ LIỆU (Luôn hiển thị) -->
        <table class="w-full text-left">
            <thead>
                <tr class="border-b bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                    <th class="py-4 px-4 font-medium">Tour</th>
                    <th class="py-4 px-4 font-medium">Ngày đi</th>
                    <th class="py-4 px-4 font-medium">Ngày về</th>
                    <th class="py-4 px-4 font-medium">Số lượng</th>
                    <th class="py-4 px-4 font-medium">Tổng tiền</th>
                    <th class="py-4 px-4 font-medium">Trạng thái</th>
                    <th class="py-4 px-4 font-medium">HDV</th>
                    <th class="py-4 px-4 font-medium text-center">Hành động</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
                <?php if (!empty($bookings)): ?>
                    <?php foreach ($bookings as $b): ?>
                        <tr class="hover:bg-gray-50 transition-colors text-sm text-gray-700">
                            <td class="py-4 px-4 font-medium text-gray-900"><?= $b['tour_name'] ?></td>
                            <td class="py-4 px-4"><?= date('d/m/Y', strtotime($b['start_date'])) ?></td>
                            <td class="py-4 px-4"><?= date('d/m/Y', strtotime($b['end_date'])) ?></td>
                            <td class="py-4 px-4">
                                <span class="inline-flex items-center gap-1">
                                    <i class="w-3 h-3 text-gray-400" data-lucide="users"></i>
                                    <?= $b['adult_count'] ?>L, <?= $b['child_count'] ?>N
                                </span>
                            </td>
                            <td class="py-4 px-4 font-medium text-blue-600"><?= number_format($b['total_amount']) ?>đ</td>

                            <td class="py-4 px-4"><?= renderStatusBadge($b['status']); ?></td>
                            <td class="py-4 px-4">
                                <?php if (!empty($b['guide_name'])): ?>
                                    <div class="flex items-center gap-2">
                                        <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-purple-50 border border-purple-100">
                                            <i class="w-3.5 h-3.5 text-purple-600" data-lucide="user"></i>
                                            <span class="text-xs font-medium text-purple-700"><?= htmlspecialchars($b['guide_name']) ?></span>
                                        </div>
                                        <a href="<?= BASE_URL ?>?act=tour-assignment-edit&booking_id=<?= $b['id'] ?>"
                                            class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-md transition-colors"
                                            title="Chỉnh sửa phân công">
                                            <i class="w-3.5 h-3.5" data-lucide="edit-3"></i>
                                        </a>
                                    </div>
                                <?php else: ?>
                                    <a href="<?= BASE_URL . '?act=tour-assignment-create&booking_id=' . $b['id'] ?>"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors border border-blue-200">
                                        <i class="w-3.5 h-3.5" data-lucide="plus-circle"></i>
                                        <span>Phân công</span>
                                    </a>
                                <?php endif; ?>
                            </td>
                            <td class="py-4 px-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="<?= BASE_URL . '?act=booking-edit&id=' . $b['id']  ?>"
                                        class="p-1.5 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-md transition-colors"
                                        title="Sửa">
                                        <i class="w-4 h-4" data-lucide="square-pen"></i>
                                    </a>
                                    <a href="<?= BASE_URL . '?act=booking-detail&id=' . $b['id']  ?>"
                                        class="p-1.5 text-gray-500 hover:text-green-600 hover:bg-green-50 rounded-md transition-colors"
                                        title="Chi tiết">
                                        <i class="w-4 h-4" data-lucide="eye"></i>
                                    </a>
                                    <a href="<?= BASE_URL . '?act=booking-delete&id=' . $b['id']  ?>"
                                        onclick="return confirm('Bạn có chắc muốn xoá không?')"
                                        class="p-1.5 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-md transition-colors"
                                        title="Xóa">
                                        <i class="w-4 h-4" data-lucide="trash-2"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <i class="w-8 h-8 text-gray-300 mb-2" data-lucide="inbox"></i>
                                <p>Không tìm thấy booking nào phù hợp</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <?php if (!$isAjax): ?>
        </div> <!-- End Table Container -->
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('filter-form');
            const tableContainer = document.getElementById('booking-table-container');
            let timeout = null;

            // Hàm gọi AJAX
            function fetchBookings() {
                const formData = new FormData(form);
                const params = new URLSearchParams(formData);
                // act đã có trong form, nhưng ajax cần thêm thủ công
                params.append('ajax', '1');

                // Hiển thị loading nhẹ
                tableContainer.style.opacity = '0.6';

                fetch(`<?= BASE_URL ?>?${params.toString()}`)
                    .then(response => response.text())
                    .then(html => {
                        tableContainer.innerHTML = html;
                        tableContainer.style.opacity = '1';
                        // Re-initialize Lucide icons
                        if (typeof lucide !== 'undefined') {
                            lucide.createIcons();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        tableContainer.style.opacity = '1';
                    });
            }

            // Lắng nghe sự kiện input/change trên tất cả các trường trong form
            const inputs = form.querySelectorAll('input, select');
            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    clearTimeout(timeout);
                    timeout = setTimeout(fetchBookings, 500); // Debounce 500ms
                });
            });
        });
    </script>
    <?php
            require_once './views/components/footer.php';
    ?>
<?php endif; ?>