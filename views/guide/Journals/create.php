<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';

$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];
unset($_SESSION['errors'], $_SESSION['old']);
?>

<main class="mt-28 px-6 pb-20">

    <h1 class="text-2xl font-bold mb-6">Viết nhật ký mới</h1>

    <form action="<?= BASE_URL . '?act=journal-store' ?>"
        method="POST"
        enctype="multipart/form-data"
        class="bg-white p-6 rounded-lg shadow">

        <!-- Tour assignment -->
        <div class="mb-4">
            <label class="block font-medium mb-1">Phân công tour *</label>
            <select name="tour_assignment_id" class="border rounded-lg w-full p-2">
                <option value="">-- Chọn tour --</option>
                <?php foreach ($tourAssignments as $ta): ?>
                    <option value="<?= $ta['id'] ?>">
                        <?= htmlspecialchars($ta['tour_name']) ?> - <?= $ta['booking_code'] ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <?php if (!empty($errors['tour_assignment_id'])): ?>
                <p class="text-red-500 text-sm mt-1"><?= implode(', ', $errors['tour_assignment_id']) ?></p>
            <?php endif; ?>
        </div>

        <!-- Date -->
        <div class="mb-4">
            <label class="block font-medium mb-1">Ngày *</label>
            <input type="date" name="date"
                value="<?= $old['date'] ?? '' ?>"
                class="border w-full p-2 rounded-lg <?= isset($errors['date']) ? 'border-red-500' : '' ?>">

            <?php if (!empty($errors['date'])): ?>
                <p class="text-red-500 text-sm mt-1"><?= implode(', ', $errors['date']) ?></p>
            <?php endif; ?>
        </div>

        <!-- Type -->
        <div class="mb-4">
            <label class="block font-medium mb-1">Loại *</label>
            <select name="type" class="border rounded-lg w-full p-2 <?= isset($errors['type']) ? 'border-red-500' : '' ?>">
                <option value="">-- Chọn loại --</option>
                <option value="daily" <?= ($old['type'] ?? '') == 'daily' ? 'selected' : '' ?>>Nhật ký ngày</option>
                <option value="incident" <?= ($old['type'] ?? '') == 'incident' ? 'selected' : '' ?>>Sự cố</option>
            </select>

            <?php if (!empty($errors['type'])): ?>
                <p class="text-red-500 text-sm mt-1"><?= implode(', ', $errors['type']) ?></p>
            <?php endif; ?>
        </div>

        <!-- Content -->
        <div class="mb-4">
            <label class="block font-medium mb-1">Nội dung *</label>
            <textarea name="content" rows="5"
                class="border w-full p-2 rounded-lg <?= isset($errors['content']) ? 'border-red-500' : '' ?>"><?= htmlspecialchars($old['content'] ?? '') ?></textarea>

            <?php if (!empty($errors['content'])): ?>
                <p class="text-red-500 text-sm mt-1"><?= implode(', ', $errors['content']) ?></p>
            <?php endif; ?>
        </div>

        <!-- Upload ảnh -->
        <div class="mb-4">
            <label class="block font-medium mb-1">Ảnh đính kèm</label>
            <input type="file"
                name="images[]"
                id="newImages"
                multiple
                accept="image/*"
                class="border p-2 rounded-lg w-full">
        </div>

        <!-- Preview ảnh -->
        <div id="previewNew" class="flex flex-wrap gap-3"></div>

        <!-- Buttons -->
        <div class="flex gap-3 mt-6">
            <button class="px-6 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700">
                Lưu
            </button>
            <a href="<?= BASE_URL . '?act=journal' ?>"
                class="px-6 py-2 border rounded-lg hover:bg-gray-50">
                Hủy
            </a>
        </div>

    </form>
</main>

<script>
    document.getElementById('newImages').addEventListener('change', function(e) {
        let preview = document.getElementById('previewNew');
        preview.innerHTML = "";

        let files = Array.from(e.target.files);
        let dt = new DataTransfer();

        files.forEach((file, index) => {
            let reader = new FileReader();
            reader.onload = function(ev) {
                let wrap = document.createElement("div");
                wrap.classList.add("relative", "w-28", "h-24");

                let img = document.createElement("img");
                img.src = ev.target.result;
                img.classList.add("w-full", "h-full", "object-cover", "rounded-lg", "border");

                let btn = document.createElement("button");
                btn.innerText = "×";
                btn.type = "button";
                btn.className =
                    "absolute -top-2 -right-2 bg-red-600 text-white w-6 h-6 flex items-center justify-center rounded-full";

                btn.onclick = function() {
                    wrap.remove();
                    files.splice(index, 1);

                    let newDT = new DataTransfer();
                    files.forEach(f => newDT.items.add(f));
                    document.getElementById('newImages').files = newDT.files;
                };

                wrap.appendChild(img);
                wrap.appendChild(btn);
                preview.appendChild(wrap);
            };
            reader.readAsDataURL(file);
        });
    });
</script>

<?php
require_once './views/components/footer.php';
?>