<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';

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

    <div class="bg-white p-4 rounded-lg shadow">
        <table class="w-full text-left">
            <thead>
                <tr class="border-b">
                    <th class="py-3">STT</th>
                    <th>Tour</th>
                    <th>Ngày đi</th>
                    <th>Ngày về</th>
                    <th>Số lượng</th>
                    <th>Tổng tiền</th>
                    <th>Hành động</th>
                </tr>
            </thead>

            <tbody>
                <?php if (!empty($bookings)): ?>
                    <?php foreach ($bookings as $b): ?>
                        <tr class="border-b text-sm">
                            <td class="py-2"><?= $b['id'] ?></td>
                            <td><?= $b['tour_name'] ?></td>
                            <td><?= $b['start_date'] ?></td>
                            <td><?= $b['end_date'] ?></td>
                            <td><?= $b['adult_count'] ?> Người lớn, <?= $b['child_count'] ?> Trẻ em</td>
                            <td><?= number_format($b['total_amount']) ?>đ</td>
                            <td>
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