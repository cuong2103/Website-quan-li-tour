<?php require_once './views/components/header.php'; ?>
<?php require_once './views/components/sidebar.php'; ?>

<div class="ml-54 mt-16 p-10">

    <div class="flex justify-between items-center mb-5 mt-2">
        <div>
            <h2 class="text-2xl font-semibold">Chi tiết dịch vụ</h2>
        </div>

        <a href="?act=service"
            class="bg-gray-200 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg font-medium transition flex items-center gap-2">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
            Quay lại
        </a>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-md space-y-4">

        <div>
            <h3 class="font-medium text-gray-700">Tên Dịch vụ:</h3>
            <p class="text-gray-900"><?= $service['name'] ?></p>
        </div>

        <div>
            <h3 class="font-medium text-gray-700">Giá:</h3>
            <p class="text-gray-900"><?= number_format($service['estimated_price']) ?>.00</p>
        </div>
        <div>
            <h3 class="font-medium text-gray-700">Mô tả:</h3>
            <p class="text-gray-900"><?= $service['description'] ?></p>
        </div>

        <div>
            <h3 class="font-medium text-gray-700">Loại Dịch vụ:</h3>
            <p class="text-gray-900"><?= $service['service_type_name'] ?></p>
        </div>

        <div>
            <h3 class="font-medium text-gray-700">Nhà cung cấp:</h3>
            <p class="text-gray-900"><?= $service['supplier_name'] ?></p>
        </div>


        <div>
            <h3 class="font-medium text-gray-700">Người tạo:</h3>
            <p class="text-gray-900"><?= $service['created_by'] ?? '-' ?></p>
        </div>

        <div>
            <h3 class="font-medium text-gray-700">Ngày tạo:</h3>
            <p class="text-gray-900"><?= $service['created_at'] ?? '-' ?></p>
        </div>

        <div>
            <h3 class="font-medium text-gray-700">Cập nhật lần cuối:</h3>
            <p class="text-gray-900"><?= $service['updated_at'] ?? '-' ?></p>
        </div>
    </div>

    <?php require_once './views/components/footer.php'; ?>