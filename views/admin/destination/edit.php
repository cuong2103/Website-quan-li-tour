<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';
?>

<main class="pt-28 px-6 pb-20">

    <h1 class="text-2xl font-bold mb-6">Chỉnh sửa địa điểm</h1>

    <form action="<?= BASE_URL . '?act=destination-update' ?>"
        method="POST"
        enctype="multipart/form-data"
        class="bg-white p-6 rounded-lg shadow">

        <input type="hidden" name="id" value="<?= $destination['id'] ?>">

        <!-- Danh mục -->
        <div class="mb-4">
            <label class="block font-medium mb-1">Danh mục</label>
            <select name="category_id"
                class="border rounded-lg w-full p-2 <?= isset($_SESSION['errors']['category_id']) ? 'border-red-500' : '' ?>">
                <option value="">-- Chọn danh mục --</option>
                <?php renderOption($tree, '', $destination['category_id']); ?>
            </select>
            <?php if (!empty($_SESSION['errors']['category_id'])): ?>
                <p class="text-red-500 text-sm mt-1"><?= implode(', ', $_SESSION['errors']['category_id']) ?></p>
            <?php endif; ?>
        </div>

        <!-- Tên -->
        <div class="mb-4">
            <label class="block font-medium mb-1">Tên địa điểm</label>
            <input type="text" name="name"
                class="border w-full p-2 rounded-lg <?= isset($_SESSION['errors']['name']) ? 'border-red-500' : '' ?>"
                value="<?= htmlspecialchars($_SESSION['old']['name'] ?? $destination['name']) ?>">
            <?php if (!empty($_SESSION['errors']['name'])): ?>
                <p class="text-red-500 text-sm mt-1"><?= implode(', ', $_SESSION['errors']['name']) ?></p>
            <?php endif; ?>
        </div>

        <!-- Địa chỉ -->
        <div class="mb-4">
            <label class="block font-medium mb-1">Các điểm đến</label>
            <textarea name="locations" rows="5" class="border w-full p-2 rounded-lg"><?= htmlspecialchars($destination['locations']) ?></textarea>
        </div>

        <!-- Mô tả -->
        <div class="mb-4">
            <label class="block font-medium mb-1">Mô tả</label>
            <textarea name="description" rows="4" class="border w-full p-2 rounded-lg "><?= htmlspecialchars($destination['description']) ?> </textarea>
        </div>

        <!-- Ảnh cũ -->
        <div class="mb-4">
            <label class="block font-medium mb-2">Ảnh hiện có</label>
            <div class="flex flex-wrap gap-4">
                <?php foreach ($images as $img): ?>
                    <div class="relative w-32 h-24 group">
                        <img src="<?= BASE_URL . 'uploads/destinations_image/' . $img['image_url'] ?>"
                            class="w-full h-full object-cover rounded-lg border">
                        <a href="<?= BASE_URL . '?act=destination-delete-image&id=' . $img['id'] ?>"
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
            <a href="<?= BASE_URL . '?act=destination' ?>" class="px-6 py-2 border rounded-lg hover:bg-gray-50">
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
unset($_SESSION['errors']);
unset($_SESSION['old']);
require_once './views/components/footer.php';
?>