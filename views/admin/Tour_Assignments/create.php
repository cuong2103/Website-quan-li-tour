<?php require_once './views/components/header.php'; ?>
<?php require_once './views/components/sidebar.php'; ?>

<main class="mt-28 px-6 pb-20">

    <h1 class="text-xl font-semibold mb-6 flex items-center gap-2">
        <i data-lucide="plus"></i> Tạo phân công hướng dẫn viên
    </h1>

    <form action="<?= BASE_URL ?>?act=tour-assignment-store" method="POST"
          class="bg-white p-6 rounded-xl shadow space-y-4">

        <div>
            <label class="font-medium">Booking</label>
            <select name="booking_id" class="w-full p-3 border rounded-lg">
                <?php foreach ($bookings as $b): ?>
                <option value="<?= $b['id'] ?>">#<?= $b['id'] ?> - <?= $b['tour_name'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label class="font-medium">Hướng dẫn viên</label>
            <select name="guide_id" class="w-full p-3 border rounded-lg">
            <option value="">Chưa phân công</option>
                <?php foreach ($guides as $g): ?>
                <option value="<?= $g['id'] ?>"><?= $g['fullname'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <button class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 flex items-center gap-2">
            <i data-lucide="save"></i> Lưu phân công
        </button>

    </form>

</main>

<?php
require_once './views/components/footer.php';
?>
