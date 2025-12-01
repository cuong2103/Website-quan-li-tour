<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';

$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? $journal; // Nếu không có old, lấy dữ liệu journal cũ
unset($_SESSION['errors'], $_SESSION['old']);
?>

<main class="mt-28 px-6 pb-20">

    <h1 class="text-2xl font-bold mb-6">Chỉnh sửa nhật ký</h1>

    <form action="<?= BASE_URL . '?act=journal-update' ?>"
        method="POST"
        enctype="multipart/form-data"
        class="bg-white p-6 rounded-lg shadow">

        <input type="hidden" name="id" value="<?= $journal['id'] ?>">

        <!-- Phân công tour -->
        <div class="mb-4">
            <label class="block font-medium mb-1">Phân công tour *</label>
            <select name="tour_assignment_id" class="border rounded-lg w-full p-2">
                <option value="">-- Chọn tour --</option>
                <?php foreach ($tourAssignments as $ta): ?>
                    <option value="<?= $ta['id'] ?>"
                        <?= isset($old['tour_assignment_id']) && $old['tour_assignment_id'] == $ta['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($ta['tour_name']) ?> - <?= $ta['booking_code'] ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <?php if (!empty($errors['tour_assignment_id'])): ?>
                <p class="text-red-500 text-sm mt-1"><?= implode(', ', $errors['tour_assignment_id']) ?></p>
            <?php endif; ?>
        </div>

        <!-- Ngày -->
        <div class="mb-4">
            <label class="block font-medium mb-1">Ngày *</label>
            <input type="date" name="date"
                value="<?= htmlspecialchars($old['date'] ?? $journal['date']) ?>"
                class="border w-full p-2 rounded-lg <?= isset($errors['date']) ? 'border-red-500' : '' ?>">
            <?php if (!empty($errors['date'])): ?>
                <p class="text-red-500 text-sm mt-1"><?= implode(', ', $errors['date']) ?></p>
            <?php endif; ?>
        </div>

        <!-- Loại -->
        <div class="mb-4">
            <label class="block font-medium mb-1">Loại *</label>
            <select name="type" class="border rounded-lg w-full p-2 <?= isset($errors['type']) ? 'border-red-500' : '' ?>">
                <option value="">-- Chọn loại --</option>
                <option value="daily" <?= ($old['type'] ?? $journal['type']) == 'daily' ? 'selected' : '' ?>>Nhật ký ngày</option>
                <option value="incident" <?= ($old['type'] ?? $journal['type']) == 'incident' ? 'selected' : '' ?>>Sự cố</option>
            </select>
            <?php if (!empty($errors['type'])): ?>
                <p class="text-red-500 text-sm mt-1"><?= implode(', ', $errors['type']) ?></p>
            <?php endif; ?>
        </div>

        <!-- Nội dung -->
        <div class="mb-4">
            <label class="block font-medium mb-1">Nội dung *</label>
            <textarea name="content" rows="5"
                class="border w-full p-2 rounded-lg <?= isset($errors['content']) ? 'border-red-500' : '' ?>"><?= htmlspecialchars($old['content'] ?? $journal['content']) ?></textarea>
            <?php if (!empty($errors['content'])): ?>
                <p class="text-red-500 text-sm mt-1"><?= implode(', ', $errors['content']) ?></p>
            <?php endif; ?>
        </div>

        <!-- Ảnh cũ -->
        <div class="mb-4">
            <label class="block font-medium mb-2">Ảnh hiện có</label>
            <div class="flex flex-wrap gap-4">
                <?php foreach ($images as $img): ?>
                    <div class="relative w-32 h-24 group">
                        <img src="<?= BASE_URL . 'uploads/journals/' . $img['image_url'] ?>?>"
                            class="w-full h-full object-cover rounded-lg border">
                        <a href="<?= BASE_URL . '?act=journal-images-delete&id=' . $img['id'] ?>"
                            onclick="return confirm('Xóa ảnh này?')"
                            class="absolute -top-2 -right-2 bg-red-600 text-white w-6 h-6 flex items-center justify-center rounded-full opacity-90 hover:bg-red-700">
                            ×
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Upload ảnh mới -->
        <div class="mb-4">
            <label class="block font-medium mb-1">Thêm ảnh mới</label>
            <input type="file" name="images[]" id="newImages" multiple accept="image/*"
                class="border p-2 rounded-lg w-full">
        </div>

        <!-- Preview ảnh mới -->
        <div id="previewNew" class="flex flex-wrap gap-3"></div>

        <!-- Nút -->
        <div class="flex gap-3 mt-6">
            <button class="px-6 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700">
                Cập nhật
            </button>
            <a href="<?= BASE_URL . '?act=journal' ?>" class="px-6 py-2 border rounded-lg hover:bg-gray-50">
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
                btn.className = "absolute -top-2 -right-2 bg-red-600 text-white w-6 h-6 flex items-center justify-center rounded-full";
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