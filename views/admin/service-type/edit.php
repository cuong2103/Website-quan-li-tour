<?php require_once './views/components/header.php'; ?>
<?php require_once './views/components/sidebar.php'; ?>

<div class="ml-54 mt-16 p-10">

    <div class="flex items-center justify-between mb-8">
        <h2 class="text-3xl font-bold text-gray-800 tracking-tight">
            Sửa loại dịch vụ
        </h2>

        <a href="index.php?act=service-type"
            class="bg-gray-200 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg font-medium transition flex items-center gap-2">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
            Quay lại
        </a>
    </div>

    <form action="index.php?act=service-type-update" method="POST" class="bg-white p-6 rounded-xl shadow-md">

        <input type="hidden" name="id" value="<?= $serviceType['id'] ?>">

        <label class="block mb-2 font-medium">Tên loại dịch vụ</label>
        <input type="text" name="name" value="<?= $serviceType['name'] ?>"
            class="w-full border rounded-lg p-2 mb-4" />

        <label class="block mb-2 font-medium">Mô tả</label>
        <textarea name="description" rows="3"
            class="w-full border rounded-lg p-2 mb-4"><?= $serviceType['description'] ?></textarea>
        <br> <br>
        <!-- submit -->
        <button type="submit"
            class="w-full bg-orange-500 text-white py-2 rounded-lg hover:bg-orange-550 transition">
            Lưu thay đổi
        </button>
    </form>
</div>

<?php require_once './views/components/footer.php'; ?>