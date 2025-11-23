<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';
?>

<main class="mt-28 px-6 pb-20">
    <h1 class="text-2xl font-bold mb-6">Chi tiết Booking</h1>

    <!-- Nút quay lại -->
    <div class="flex justify-end mt-4 mb-4">
        <a href="<?= BASE_URL . '?act=bookings' ?>"
            class="px-6 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">Quay lại</a>
    </div>

    <div class="bg-white p-6 rounded-lg shadow space-y-3">

        <p><b>Tour:</b> <?= htmlspecialchars($booking['tour_name']) ?></p>
        <p><b>Ngày khởi hành:</b> <?= $booking['start_date'] ?></p>
        <p><b>Ngày kết thúc:</b> <?= $booking['end_date'] ?></p>
        <p><b>Người lớn:</b> <?= $booking['adult_count'] ?></p>
        <p><b>Trẻ em:</b> <?= $booking['child_count'] ?></p>
        <p><b>Tổng tiền:</b> <?= number_format($booking['total_amount'], 0, ',', '.') ?> VNĐ</p>
        <p><b>Tiền cọc:</b> <?= number_format($booking['deposit_amount'], 0, ',', '.') ?> VNĐ</p>
        <p><b>Tiền còn lại:</b> <?= number_format($booking['remaining_amount'], 0, ',', '.') ?> VNĐ</p>
        <p><b>Trạng thái:</b>
            <?php
            $statusArr = [1 => 'Pending', 2 => 'Deposited', 3 => 'Completed', 4 => 'Cancelled'];
            echo $statusArr[$booking['status']];
            ?>
        </p>
        <p><b>Yêu cầu đặc biệt:</b> <?= nl2br(htmlspecialchars($booking['special_requests'])) ?></p>

        <p><b>Danh sách khách hàng:</b></p>
        <ul class="list-disc ml-6">
            <?php foreach ($booking['customers'] as $c): ?>
                <li><?= htmlspecialchars($c['name']) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</main>

<?php
require_once './views/components/footer.php';
?>