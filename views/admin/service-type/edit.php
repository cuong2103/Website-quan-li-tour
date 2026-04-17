<?php require_once './views/components/header.php'; ?>
<?php require_once './views/components/sidebar.php'; ?>

<div class="ml-54 mt-16 p-10">

    <div class="flex items-center justify-between mb-8">
        <h2 class="text-3xl font-bold text-gray-800 tracking-tight">
            Sửa loại dịch vụ
        </h2>

        <button onclick="history.back()" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition text-sm font-medium text-gray-700">Quay lại</button>
    </div>

    <form action="index.php?act=service-type-update" method="POST" class="bg-white p-6 rounded-xl shadow-md">

        <input type="hidden" name="id" value="<?= $serviceType['id'] ?>">

        <label class="block mb-2 font-medium">Tên loại dịch vụ <span class="text-red-500">*</span></label>
        <input type="text" name="name" value="<?= htmlspecialchars($data['name'] ?? '') ?>"
            class="w-full border rounded-lg p-2 mb-1 <?= !empty($errors['name']) ? 'border-red-500' : '' ?>" />
        <?php if (!empty($errors['name'])): ?>
            <p class="text-red-500 text-sm mt-1 mb-3"><?= is_array($errors['name']) ? $errors['name'][0] : $errors['name'] ?></p>
        <?php endif; ?>

        <label class="block mb-2 mt-4 font-medium">Mô tả</label>
        <textarea name="description" rows="3"
            class="w-full border rounded-lg p-2 mb-1 <?= !empty($errors['description']) ? 'border-red-500' : '' ?>"><?= htmlspecialchars($data['description'] ?? '') ?></textarea>
        <?php if (!empty($errors['description'])): ?>
            <p class="text-red-500 text-sm mt-1 mb-3"><?= is_array($errors['description']) ? $errors['description'][0] : $errors['description'] ?></p>
        <?php endif; ?>
        <br> <br>
        <!-- submit -->
        <button type="submit"
            class="w-full bg-orange-500 text-white py-2 rounded-lg hover:bg-orange-550 transition">
            Lưu thay đổi
        </button>
    </form>
</div>

<?php require_once './views/components/footer.php'; ?>