<?php
require './views/components/header.php';
require './views/components/sidebar.php';

$tabs = [
    'customers' => ['label' => 'Danh sách khách hàng', 'icon' => 'users'],
    'rooms'     => ['label' => 'Xem phòng', 'icon' => 'bed-double'],
    'checkin'   => ['label' => 'Check-in', 'icon' => 'check-circle'],
    'journals'  => ['label' => 'Nhật ký tour', 'icon' => 'book-open'],
    'itinerary' => ['label' => 'Lịch trình chi tiết', 'icon' => 'map'],
    'info'      => ['label' => 'Thông tin & Yêu cầu', 'icon' => 'info'],
    'services'  => ['label' => 'Dịch vụ kèm theo', 'icon' => 'package']
];
?>

<main class="pt-28 px-6 pb-20 text-gray-700">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Chi tiết Tour: <?= htmlspecialchars($assignment['tour_name']) ?></h1>
        <button onclick="history.back()"
            class="flex items-center gap-2 bg-gray-700 text-white px-4 py-2 rounded-lg hover:bg-black text-sm">
            <i data-lucide="arrow-left"></i> Quay lại
        </button>
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
            <a href="<?= BASE_URL . '?act=guide-tour-assignments-detail&id=' . $assignmentId
                            . '&tab=' . $key ?>"
                class="flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-t-lg
               <?= $tab === $key ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-gray-200' ?>">
                <i data-lucide="<?= $t['icon'] ?>" class="w-4 h-4"></i>
                <?= $t['label'] ?>
            </a>
        <?php endforeach; ?>
    </div>
    <!-- tab customers -->
    <?php switch ($tab) {
        case 'customers':
            require './views/guide/tour-assignments/tabs/customers.php';
            break;
        case 'rooms':
            require './views/guide/tour-assignments/tabs/rooms.php';
            break;
        case 'checkin':
            require './views/guide/tour-assignments/tabs/checkin.php';
            break;
        case 'journals':
            require './views/guide/tour-assignments/tabs/journals.php';
            break;
        case 'itinerary':
            require './views/guide/tour-assignments/tabs/itinerary.php';
            break;
        case 'info':
            require './views/guide/tour-assignments/tabs/info.php';
            break;
        case 'services':
            require './views/guide/tour-assignments/tabs/services.php';
            break;
        default:
            break;
    } ?>
</main>

<?php require './views/components/footer.php'; ?>