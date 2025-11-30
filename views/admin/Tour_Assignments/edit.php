<?php require_once './views/components/header.php'; ?>
<?php require_once './views/components/sidebar.php'; ?>

<main class="mt-28 px-6 pb-20">

    <h1 class="text-xl font-semibold mb-6 flex items-center gap-2">
        <i data-lucide="pencil"></i> Sửa phân công hướng dẫn viên
    </h1>

    <form action="<?= BASE_URL ?>?act=tour-assignment-update&id=<?= $assignment['id'] ?>"
        method="POST"
        class="bg-white p-6 rounded-xl shadow space-y-5">

        <input type="hidden" name="id" value="<?= $assignment['id'] ?>">

        <!-- Booking -->
        <div>
            <label class="font-medium">Booking</label>
            <select id="bookingSelect" name="booking_id" class="w-full p-3 border rounded-lg bg-gray-100" readonly>
                <option value="<?= $assignment['booking_id'] ?>">
                    #<?= $assignment['booking_id'] ?> - <?= $assignment['tour_name'] ?>
                </option>
            </select>
        </div>

        <!-- Guide -->
        <div>
            <label class="font-medium">Hướng dẫn viên</label>
            <select name="guide_id" class="w-full p-3 border rounded-lg">
                <?php foreach ($guides as $g): ?>
                    <option value="<?= $g['id'] ?>"
                        <?= $g['id'] == $assignment['guide_id'] ? 'selected' : '' ?>>
                        <?= $g['fullname'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Submit -->
        <button class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 flex items-center gap-2">
            <i data-lucide="save"></i> Cập nhật
        </button>

    </form>

</main>

<?php require_once './views/components/footer.php'; ?>