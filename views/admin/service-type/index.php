<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';
?>
<div class="p-6">

    <!-- Tiêu đề -->
    <h2 class="text-2xl font-semibold mb-2">Quản lý Loại Dịch vụ</h2>
    <p class="text-gray-500 mb-6">
        Quản lý các danh mục dịch vụ như Khách sạn, Vận chuyển, Nhà hàng...
    </p>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Form thêm loại dịch vụ -->
        <div class="bg-white rounded-xl shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Thêm Loại Dịch vụ</h3>
            <form action="<?= BASE_URL . '?act=service-type-store'?>" method="POST">
                <!-- kiểm tra dữ liệu vào post -->
                <label class="block mb-2 font-medium">Tên loại dịch vụ <span class="text-red-500">*</span></label>
                <input type="text" name="name" class="w-full border rounded-lg p-2 mb-4" required='vui lòng nhập đủ thông tin'
                    placeholder="Ví dụ: Khách sạn, Vận chuyển..." />

                <label class="block mb-2 font-medium">Mô tả</label>
                <textarea name="description" rows="3"
                    class="w-full border rounded-lg p-2 mb-4" required='vui lòng nhập đủ thông tin'
                    placeholder="Mô tả chi tiết về loại dịch vụ này..." ></textarea>

                <button type="submit" class="w-full bg-orange-500 text-white py-2 rounded-lg hover:bg-orange-600 transition"> + Lưu</button> 
            </form>
        </div>

        <!-- Danh sách loại dịch vụ -->
        <div class="bg-white rounded-xl shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Danh sách hiện có (<?=  count($serviceTypes) ?>)</h3>
            <div class="space-y-4">

                <!-- ITEM -->
                 <?php  foreach( $serviceTypes as $serviceType) { ?>
                <div class="border rounded-lg p-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <h4 class="font-semibold"><?= $serviceType["name"] ?></h4>
                            <p class="text-gray-500 text-sm"><?= $serviceType["description"] ?></p>
                        </div>
                        <div class="flex gap-3 text-gray-600">
                            <!-- Xem chi tiết -->
                            <a href="?act=service-type-detail&id=<?= $serviceType['id'] ?>">
                                <button type="button">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye-icon lucide-eye"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/><circle cx="12" cy="12" r="3"/></svg></button>
                            </a>

                            <!-- Sửa -->
                            <a href="?act=service-type-edit&id=<?= $serviceType['id'] ?>">
                                <button type="button" >
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-square-pen-icon lucide-square-pen"><path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z"/></svg></button>
                            </a>

                            <!-- Xóa -->
                            <a href="?act=service-type-delete&id=<?= $serviceType['id'] ?>" onclick="return confirm('Bạn có chắc muốn xóa không?')">
                                <button type="button">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="red" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash2-icon lucide-trash-2"><path d="M10 11v6"/><path d="M14 11v6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>
                            </a>
                        </div>

                    </div>
                </div>
            <?php } ; ?>

            </div>
        </div>

    </div>

</div>









<?php
require_once './views/components/footer.php';