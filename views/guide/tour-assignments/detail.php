<?php
require './views/components/header.php';
require './views/components/sidebar.php';

$tabs = [
    'customers' => ['label' => 'Danh sách khách hàng', 'icon' => 'users'],
    'checkin'   => ['label' => 'Check-in', 'icon' => 'check-circle'],
    'journals'  => ['label' => 'Nhật ký tour', 'icon' => 'book-open'],
    'itinerary' => ['label' => 'Lịch trình chi tiết', 'icon' => 'map'],
    'info'      => ['label' => 'Thông tin & Yêu cầu', 'icon' => 'info'],
    'services'  => ['label' => 'Dịch vụ kèm theo', 'icon' => 'package']
];
?>

<main class="mt-28 px-6 pb-20 text-gray-700">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Chi tiết Tour: <?= htmlspecialchars($assignment['tour_name']) ?></h1>
        <a href="<?= BASE_URL . '?act=guide-tour-assignments' ?>"
            class="flex items-center gap-2 bg-gray-700 text-white px-4 py-2 rounded-lg hover:bg-black text-sm">
            <i data-lucide="arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="bg-white border shadow-md rounded-xl p-6 mb-8">
        <div class="grid grid-cols-5 gap-6 text-sm">
            <div>
                <div class="text-gray-500 text-sm">Ngày khởi hành</div>
                <div class="font-semibold text-lg"><?= date('Y-m-d', strtotime($assignment['start_date'])) ?></div>
            </div>
            <div>
                <div class="text-gray-500 text-sm">Ngày kết thúc</div>
                <div class="font-semibold text-lg"><?= date('Y-m-d', strtotime($assignment['end_date'])) ?></div>
            </div>
            <div>
                <div class="text-gray-500 text-sm">Số lượng khách</div>
                <div class="font-semibold text-lg flex items-center gap-1">
                    <i data-lucide="users" class="w-4"></i>
                    <?= htmlspecialchars($assignment['total_customers'] ?? 0) ?> người
                </div>
            </div>
            <div>
                <div class="text-gray-500 text-sm">Trạng thái</div>
                <span class="px-3 py-1 text-xs rounded-full <?= $assignment['status_color'] ?>">
                    <?= htmlspecialchars($assignment['status_text']) ?>
                </span>
            </div>
            <div>
                <div class="text-gray-500 text-sm">Mã Booking</div>
                <div class="font-semibold text-lg"><?= htmlspecialchars($assignment['booking_code']) ?></div>
            </div>
        </div>
    </div>

    <!-- tabs -->
    <div class="flex gap-3 border-b mb-4 pb-2">
        <?php foreach ($tabs as $key => $t): ?>
            <a href="<?= BASE_URL . '?act=guide-tour-assignments-detail&id=' . $assignment['id'] . '&tab=' . $key ?>"
                class="flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-t-lg
               <?= $tab === $key ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-gray-200' ?>">
                <i data-lucide="<?= $t['icon'] ?>" class="w-4 h-4"></i>
                <?= $t['label'] ?>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- tab customers -->
    <?php if ($tab === 'customers'): ?>
        <div class="bg-white border shadow rounded-xl p-5">
            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3 text-left">STT</th>
                        <th class="p-3 text-left">Tên khách hàng</th>
                        <th class="p-3 text-left">Số điện thoại</th>
                        <th class="p-3 text-left">Email</th>
                        <th class="p-3 text-left">Ghi chú</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($customers as $i => $c): ?>
                        <tr class="border-t">
                            <td class="p-3"><?= $i + 1 ?></td>
                            <td class="p-3"><?= htmlspecialchars($c['name']) ?></td>
                            <td class="p-3"><?= htmlspecialchars($c['phone']) ?></td>
                            <td class="p-3"><?= htmlspecialchars($c['email']) ?></td>
                            <td class="p-3"><?= htmlspecialchars($c['notes'] ?? '-') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <!-- tab checkin -->
    <?php if ($tab === 'checkin'): ?>
        <div class="bg-white border shadow rounded-xl p-5">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-semibold text-lg">Danh sách Check-in</h3>
                <div class="flex gap-2">
                    <a href="<?= BASE_URL . '?act=guide-tour-assignments-export-checkin&id=' . $assignment['id'] ?>"
                        class="bg-green-600 text-white px-3 py-2 rounded-lg text-sm hover:bg-green-700 flex items-center gap-2">
                        <i data-lucide="file-spreadsheet" class="w-4 h-4"></i>
                        Tải Excel Xếp phòng
                    </a>
                </div>
            </div>

            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3 text-left">STT</th>
                        <th class="p-3 text-left">Tên khách hàng</th>
                        <th class="p-3 text-left">Số điện thoại</th>
                        <th class="p-3 text-left">Trạng thái</th>
                        <th class="p-3 text-left">Thời gian</th>
                        <th class="p-3 text-left">Phòng</th>
                        <th class="p-3 text-left">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($customers)): ?>
                        <?php foreach ($customers as $i => $c): ?>
                            <tr class="border-t">
                                <td class="p-3"><?= $i + 1 ?></td>
                                <td class="p-3 font-medium"><?= htmlspecialchars($c['name']) ?></td>
                                <td class="p-3"><?= htmlspecialchars($c['phone']) ?></td>
                                <td class="p-3">
                                    <?php if ($c['checkin_count'] > 0): ?>
                                        <span class="text-green-600 font-medium flex items-center gap-1">
                                            <i data-lucide="check" class="w-3 h-3"></i> Đã check-in
                                        </span>
                                    <?php else: ?>
                                        <span class="text-gray-400">Chưa check-in</span>
                                    <?php endif; ?>
                                </td>
                                <td class="p-3 text-gray-600">
                                    <?= !empty($c['latest_checkin_time']) ? date('H:i d/m', strtotime($c['latest_checkin_time'])) : '-' ?>
                                </td>
                                <td class="p-3 text-gray-600"><?= htmlspecialchars($c['room'] ?? '-') ?></td>
                                <td class="p-3">
                                    <?php if ($c['checkin_count'] == 0): ?>
                                        <form action="<?= BASE_URL . '?act=guide-tour-assignments-checkin' ?>" method="POST">
                                            <input type="hidden" name="assignment_id" value="<?= $assignment['id'] ?>">
                                            <input type="hidden" name="customer_id" value="<?= $c['id'] ?>">
                                            <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded text-xs hover:bg-blue-700">
                                                Check-in
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <form action="<?= BASE_URL . '?act=guide-tour-assignments-checkin-destroy' ?>" method="POST">
                                            <input type="hidden" name="assignment_id" value="<?= $assignment['id'] ?>">
                                            <input type="hidden" name="checkin_id" value="<?= $c['latest_checkin_id'] ?>">
                                            <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded text-xs hover:bg-red-700">
                                                Hủy
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="p-4 text-center text-gray-500">Chưa có khách hàng.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <!-- tab journals -->
    <?php if ($tab === 'journals'): ?>
        <div class="bg-white border shadow rounded-xl p-5">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-semibold text-lg">Nhật ký tour</h3>
                <a href="<?= BASE_URL . '?act=journal-create&tour_assignment_id=' . $assignment['id'] ?>"
                    class="bg-orange-600 text-white px-3 py-2 rounded-lg text-sm hover:bg-orange-700 flex items-center gap-2">
                    <i data-lucide="pen-tool" class="w-4 h-4"></i>
                    Viết nhật ký mới
                </a>
            </div>

            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3 text-left">Ngày</th>
                        <th class="p-3 text-left">Loại</th>
                        <th class="p-3 text-left">Nội dung</th>
                        <th class="p-3 text-left">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($journals)): ?>
                        <?php foreach ($journals as $j): ?>
                            <tr class="border-t hover:bg-gray-50">
                                <td class="p-3"><?= date('d/m/Y', strtotime($j['date'])) ?></td>
                                <td class="p-3">
                                    <?php if ($j['type'] == 'incident'): ?>
                                        <span class="text-red-600 font-medium flex items-center gap-1">
                                            <i data-lucide="alert-triangle" class="w-3 h-3"></i> Sự cố
                                        </span>
                                    <?php else: ?>
                                        <span class="text-blue-600 font-medium flex items-center gap-1">
                                            <i data-lucide="book-open" class="w-3 h-3"></i> Nhật ký ngày
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="p-3 max-w-md truncate"><?= htmlspecialchars($j['content']) ?></td>
                                <td class="p-3">
                                    <div class="flex gap-2">
                                        <a href="<?= BASE_URL . '?act=journal-detail&id=' . $j['id'] ?>"
                                            class="text-gray-600 hover:bg-gray-100 p-1 rounded transition-colors" title="Xem chi tiết">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                        </a>
                                        <a href="<?= BASE_URL . '?act=journal-edit&id=' . $j['id'] ?>"
                                            class="text-blue-600 hover:bg-blue-50 p-1 rounded transition-colors" title="Sửa">
                                            <i data-lucide="edit-2" class="w-4 h-4"></i>
                                        </a>
                                        <a href="<?= BASE_URL . '?act=journal-delete&id=' . $j['id'] ?>"
                                            onclick="return confirm('Bạn có chắc muốn xóa nhật ký này?')"
                                            class="text-red-600 hover:bg-red-50 p-1 rounded transition-colors" title="Xóa">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="p-4 text-center text-gray-500">Chưa có nhật ký nào.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <!-- tab itinerary -->
    <?php if ($tab === 'itinerary'): ?>
        <div class="bg-white border shadow rounded-xl p-5">
            <?php if (!empty($itinerary_days)): ?>
                <?php foreach ($itinerary_days as $day => $items): ?>
                    <h3 class="font-semibold text-lg mb-3">Ngày <?= $day ?></h3>
                    <div class="border-l ml-2 pl-4 space-y-4">
                        <?php foreach ($items as $row): ?>
                            <div>
                                <div class="font-medium"><?= htmlspecialchars($row['destination_name']) ?></div>
                                <div class="text-xs text-gray-500"><?= $row['arrival_time'] ?> → <?= $row['departure_time'] ?></div>
                                <div class="text-sm mt-1"><?= htmlspecialchars($row['description']) ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <hr class="my-4">
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-gray-500">Chưa có lịch trình chi tiết.</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- tab info -->
    <?php if ($tab === 'info'): ?>
        <div class="bg-white border shadow rounded-xl p-5">
            <h3 class="font-semibold text-lg mb-3">Yêu cầu đặc biệt</h3>
            <p class="text-sm text-gray-700 mb-6">
                <?= htmlspecialchars($assignment['special_requests'] ?? 'Không có yêu cầu đặc biệt.') ?>
            </p>
        </div>
    <?php endif; ?>

    <!-- tab services -->
    <?php if ($tab === 'services'): ?>
        <div class="bg-white border shadow rounded-xl p-5">
            <?php if (!empty($services)): ?>
                <ul class="list-disc ml-5 text-sm text-gray-700">
                    <?php foreach ($services as $s): ?>
                        <li><?= htmlspecialchars($s['service_name']) ?>
                            <?php if (!empty($s['quantity'])): ?> (Số lượng: <?= $s['quantity'] ?>)<?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="text-gray-500">Chưa có dịch vụ kèm theo.</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

</main>

<!-- Modal Upload Excel -->
<div id="uploadRoomModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl p-6 w-full max-w-md mx-4">
        <h3 class="text-lg font-semibold mb-4">Upload danh sách xếp phòng</h3>
        <form action="<?= BASE_URL . '?act=guide-tour-assignments-import-room' ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="assignment_id" value="<?= $assignment['id'] ?>">

            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Chọn file Excel (.xlsx, .xls)</label>
                <input type="file" name="file" accept=".xlsx, .xls" required
                    class="w-full border rounded-lg p-2">
                <p class="text-xs text-gray-500 mt-1">File cần có cột: Tên khách hàng, Số điện thoại (để khớp), Phòng (cột I hoặc cột 9).</p>
            </div>

            <div class="flex gap-3 justify-end">
                <button type="button" onclick="document.getElementById('uploadRoomModal').classList.add('hidden')"
                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg text-sm">
                    Hủy
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm">
                    Upload
                </button>
            </div>
        </form>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function(event) {
        let scrollpos = sessionStorage.getItem('checkin_scroll_pos');
        if (scrollpos) window.scrollTo(0, scrollpos);
    });

    window.onbeforeunload = function(e) {
        sessionStorage.setItem('checkin_scroll_pos', window.scrollY);
    };
</script>
<?php require './views/components/footer.php'; ?>