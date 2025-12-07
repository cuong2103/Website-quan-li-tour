<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';
?>

<main class="pt-28 px-6 pb-20 text-gray-700">
    <div class="w-full mx-auto bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <h2 class="text-xl font-semibold mb-6 text-gray-800">Phân công Hướng dẫn viên</h2>

        <div class="mb-6 p-4 bg-blue-50 rounded-lg">
            <p class="text-sm text-blue-800 font-medium">Booking: <?= $booking['booking_code'] ?></p>
            <p class="text-sm text-blue-600">Tour: <?= $booking['tour_name'] ?></p>
            <p class="text-sm text-blue-600">Ngày đi: <?= date('d/m/Y', strtotime($booking['start_date'])) ?></p>
        </div>

        <form action="<?= BASE_URL ?>?act=tour-assignment-store" method="POST">
            <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Chọn Hướng dẫn viên</label>
                <select name="guide_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">-- Chọn HDV --</option>
                    <?php foreach ($guides as $g): ?>
                        <option value="<?= $g['id'] ?>" <?= !empty($assignment) && $assignment['guide_id'] == $g['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($g['fullname']) ?> - <?= htmlspecialchars($g['phone']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Trạng thái</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="assigned">Đã phân công</option>
                    <option value="in_progress">Đang thực hiện</option>
                    <option value="completefixd">Đã hoàn thành</option>
                    <option value="cancelled">Đã hủy</option>
                </select>
            </div>

            <div class="flex items-center justify-end gap-3">
                <a href="<?= BASE_URL ?>?act=bookings" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg text-sm font-medium">Hủy</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium">
                    Lưu phân công
                </button>
            </div>
        </form>
    </div>
</main>

<?php require_once './views/components/footer.php'; ?>