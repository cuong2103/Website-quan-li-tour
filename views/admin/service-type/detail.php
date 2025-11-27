<?php require_once './views/components/header.php'; ?>
<?php require_once './views/components/sidebar.php'; ?>

<div class="ml-54 mt-20 p-10 bg-gray-50 min-h-screen">

    <!-- Page Header -->
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-3xl font-bold text-gray-800 tracking-tight">
            Chi tiết Loại Dịch vụ
        </h2>

        <a href="index.php?act=service-type"
           class="text-sm text-gray-600 hover:text-blue-600 flex items-center gap-2 transition">
            <span class="text-xl">←</span> Quay lại
        </a>
    </div>

    <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="p-4 border rounded-xl hover:shadow transition">
                <h3 class="text-gray-500 font-medium">ID</h3>
                <p class="text-gray-900 text-lg font-semibold"><?= $serviceType['id'] ?></p>
            </div>

            <div class="p-4 border rounded-xl hover:shadow transition">
                <h3 class="text-gray-500 font-medium">Tên Loại Dịch vụ</h3>
                <p class="text-gray-900 text-lg font-semibold"><?= $serviceType['name'] ?></p>
            </div>

            <div class="p-4 border rounded-xl hover:shadow transition md:col-span-2">
                <h3 class="text-gray-500 font-medium">Mô tả</h3>
                <p class="text-gray-900"><?= $serviceType['description'] ?></p>
            </div>

            <div class="p-4 border rounded-xl hover:shadow transition">
                <h3 class="text-gray-500 font-medium">Người tạo</h3>
                <p class="text-gray-900 font-semibold">
                    <?= $serviceType["created_by"] ? $serviceType["created_by"] : '-' ?>
                </p>
            </div>

            <div class="p-4 border rounded-xl hover:shadow transition">
                <h3 class="text-gray-500 font-medium">Ngày tạo</h3>
                <p class="text-gray-900 font-semibold">
                    <?= $serviceType["created_at"] ?>
                </p>
            </div>

            <div class="p-4 border rounded-xl hover:shadow transition md:col-span-2">
                <h3 class="text-gray-500 font-medium">Cập nhật lần cuối</h3>
                <p class="text-gray-900 font-semibold">
                    <?= $serviceType["updated_at"] ? $serviceType["updated_at"] : '-' ?>
                </p>
            </div>

        </div>

        <!-- Action Buttons -->
        <div class="flex gap-4 mt-8">

            <a href="index.php?act=service-type-edit&id=<?= $serviceType['id'] ?>"
               class="px-5 py-2 bg-orange-500 text-white rounded-xl shadow hover:bg-orange-600 flex items-center gap-2 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                    <path d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z"/>
                </svg>
                Sửa
            </a>

            <a href="index.php?act=service-type-delete&id=<?= $serviceType['id'] ?>"
               onclick="return confirm('Bạn có chắc muốn xóa không?')"
               class="px-5 py-2 bg-red-500 text-white rounded-xl shadow hover:bg-red-600 flex items-center gap-2 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10 11v6"/>
                    <path d="M14 11v6"/>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/>
                    <path d="M3 6h18"/>
                    <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                </svg>
                Xóa
            </a>

        </div>

    </div>

</div>

<?php require_once './views/components/footer.php'; ?>
