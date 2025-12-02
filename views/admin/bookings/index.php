<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';
function renderStatusBadge($status)
{
    switch ($status) {
        case 1: // Chờ thanh toán
            return '<span class="px-3 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-700">Chờ thanh toán</span>';

        case 2: // Đã cọc
            return '<span class="px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700">Đã cọc</span>';

        case 3: // Đã thanh toán đủ
            return '<span class="px-3 py-1 text-xs font-medium rounded-full bg-sky-100 text-sky-700">Đã thanh toán đủ</span>';

        case 4: // Đã hủy
            return '<span class="px-3 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700">Đã hủy</span>';

        case 5: // Hoàn thành Tour
            return '<span class="px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">Hoàn thành Tour</span>';

        default:
            return '<span class="px-3 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-700">Không rõ</span>';
    }
}

?>

<main class="mt-28 px-6 pb-20 overflow-auto scrollbar-hide">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Quản lí booking</h1>
            <p class="text-sm text-gray-600">Danh sách các booking</p>
        </div>
        <div class="flex space-x-3">
            <a href="<?= BASE_URL . '?act=booking-create' ?>"
                class="px-5 py-2.5 text-white text-sm font-medium rounded-lg bg-orange-400 hover:bg-orange-500 flex items-center space-x-2">
                <span>+ Tạo booking</span>
            </a>
        </div>
    </div>

    <?php if (Message::get('errors')): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-medium">Booking của bạn đã thanh toán. Không thể xóa</strong>
            <span class="block sm:inline"><?= Message::get('errors') ?></span>
        </div>
    <?php endif; ?>

    <div class="bg-white p-4 rounded-lg shadow">
        <table class="w-full text-left">
            <thead>
                <tr class="border-b">
                    <th class="py-5">Tour</th>
                    <th class="py-5">Ngày đi</th>
                    <th class="py-5">Ngày về</th>
                    <th class="py-5">Số lượng</th>
                    <th class="py-5">Tổng tiền</th>
                    <th class="py-5">Trạng thái</th>
                    <th class="py-5">Hành động</th>
                </tr>
            </thead>

            <tbody>
                <?php if (!empty($bookings)): ?>
                    <?php foreach ($bookings as $b): ?>
                        <tr class="border-b text-sm">
                            <td class="py-5"><?= $b['tour_name'] ?></td>
                            <td class="py-5"><?= $b['start_date'] ?></td>
                            <td class="py-5"><?= $b['end_date'] ?></td>
                            <td class="py-5"><?= $b['adult_count'] ?> NL, <?= $b['child_count'] ?> TE</td>
                            <td class="py-5"><?= number_format($b['total_amount']) ?>đ</td>
                            <td class="py-5"><?= renderStatusBadge($b['status']); ?></td>
                            <td class="py-5">
                                <div class="flex gap-0 flex-shrink-0">
                                    <a href="<?= BASE_URL . '?act=booking-edit&id=' . $b['id']  ?>" class="inline-flex items-center justify-center  disabled:opacity-50 gap-1.5 px-1 "><i class="w-4 h-4" data-lucide="square-pen"></i></a>
                                    <a href="<?= BASE_URL . '?act=booking-detail&id=' . $b['id']  ?>"

                                        class="inline-flex items-center justify-center gap-1.5 px-1 has-[>svg]:px-2.5">

                                        <!-- Icon con mắt -->
                                        <i class="w-4 h-4" data-lucide="eye"></i>

                                    </a>

                                    <a href="<?= BASE_URL . '?act=booking-delete&id=' . $b['id']  ?>" onclick="return confirm('Bạn có chắc muốn xoá không?')" class="inline-flex items-center justify-center gap-1.5 px-1 has-[&gt;svg]:px-2.5"><span class="text-red-600"><i class="w-4 h-4" data-lucide="trash-2"></i></span></a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="py-4 text-center">Chưa có booking</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<!-- Form thay  -->
<script>
    let timer;
    const form = document.querySelector("form");

    document.querySelectorAll("form input, form select").forEach(element => {
        element.addEventListener("input", () => {
            console.log("1");
            clearTimeout(timer);
            timer = setTimeout(() => form.submit(), 600);
        });
    });
</script>
<?php
require_once './views/components/footer.php';
?>