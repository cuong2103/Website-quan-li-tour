<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';
?>

<div class="container mx-auto p-6">
    <h2 class="text-2xl font-bold mb-6">Sửa dịch vụ</h2>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?= $_SESSION['error'] ?>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['success'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <?= $_SESSION['success'] ?>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <form action="?act=service-update" method="POST" method="POST" class="bg-white shadow-md rounded px-10 pt-10 ml-54 mt-20 p-18">
        <input type="hidden" name="id" value="<?= $service['id'] ?>">

        <div class="mb-4 ">
            <label for="name" class="block text-gray-700 font-bold mb-2">Tên dịch vụ:</label>
            <input type="text" name="name" id="name" value="<?= $service['name'] ?>" required
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>

        <div class="mb-4">
            <label for="description" class="block text-gray-700 font-bold mb-2">Mô tả:</label>
            <textarea name="description" id="description" rows="4"
                      class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"><?= htmlspecialchars($service['description']) ?></textarea>
        </div>

        <div class="mb-4">
            <label for="service_type_id" class="block text-gray-700 font-bold mb-2">Loại dịch vụ:</label>
            <select name="service_type_id" id="service_type_id" required
                    class="shadow border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline">
                <option value="">-- Chọn loại dịch vụ --</option>
                <?php foreach ($serviceTypes as $type): ?>
                    <option value="<?= $type['id'] ?>" <?= $type['id'] == $service['service_type_id'] ? 'selected' : '' ?>>
                        <?= $type['name'] ?>
                    </option>
                <?php endforeach; ?> 
            </select>
        </div>

        <div class="mb-4">
            <label for="supplier_id" class="block text-gray-700 font-bold mb-2">Nhà cung cấp:</label>
            <select name="supplier_id" id="supplier_id" 
                    class="shadow border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:shadow-outline">
                <option value="">-- Chọn nhà cung cấp --</option>
                <?php foreach ($suppliers as $supplier): ?>
                    <option value="<?= $supplier['id'] ?>" <?= $supplier['id'] == $service['supplier_id'] ? 'selected' : '' ?>>
                        <?= $supplier['name'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-6">
            <label for="price" class="block text-gray-700 font-bold mb-2">Giá:</label>
            <input type="number" name="price" id="price" value="<?= rtrim(rtrim($service['price'], '0'), '.') ?>"required
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>

        <div class="flex items-center justify-between">
            <button type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Cập nhật dịch vụ
            </button>
            <a href="?act=service"
               class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                Hủy
            </a>
        </div>
    </form>
</div>

<?php require_once './views/components/footer.php'; ?>
