<?php require_once './views/components/header.php'; ?>
<?php require_once './views/components/sidebar.php'; ?>

<div  class="ml-54 mt-16 p-10">

    <h2 class="text-2xl font-semibold mb-4">Chi tiết Loại Dịch vụ</h2>

    <div class="bg-white p-6 rounded-xl shadow-md space-y-4">

        <div>
            <h3 class="font-medium text-gray-700">ID:</h3>
            <p class="text-gray-900"><?= $serviceType['id'] ?></p>
        </div>
        <!-- id -->
        <div>
            <h3 class="font-medium text-gray-700">Tên Loại Dịch vụ:</h3>
            <p class="text-gray-900"><?= $serviceType['name'] ?></p>
        </div>
        <!-- Tên loại dịch vụ -->
        <div>
            <h3 class="font-medium text-gray-700">Mô tả:</h3>
            <p class="text-gray-900"><?= $serviceType['description'] ?></p>
        </div>
        <!-- mô tả dịch vụ -->
        <div>
            <h3 class="font-medium text-gray-700">Người Tạo:</h3>
            <p class="text-grey-900">
                <?= $serviceType["created_by"] ? $serviceType["created_by"] : '-' ?>
            </p>
        </div>
        <!-- người tạo -->
         <div>
            <h3 class="font-medium text-gray-700">Ngày Tạo:</h3>
            <p class="text-grey-900">
                <?= $serviceType["created_at"]?>
            </p>
        </div>
        <!-- ngày tạo -->
         <div>
            <h3 class="font-medium text-gray-700">Cập Nhập Lần Cuối</h3>
            <p class="text-grey-900">
                <?= $serviceType["updated_at"] ? $serviceType["updated_at"] : '-' ?>
            </p>
        </div>
        <div class="flex gap-3 mt-4">
            <a href="index.php?act=service-type" >
                ← Quay lại
            </a>

            <a href="index.php?act=edit&id=<?= $serviceType['id'] ?>" >
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-square-pen-icon lucide-square-pen"><path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z"/></svg>
            </a>

            <a href="index.php?act=delete&id=<?= $serviceType['id'] ?>" onclick="return confirm('Bạn có chắc muốn xóa không?')" >
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="red" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash2-icon lucide-trash-2"><path d="M10 11v6"/><path d="M14 11v6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
            </a>
        </div>

    </div>

</div>

<?php require_once './views/components/footer.php'; ?>
