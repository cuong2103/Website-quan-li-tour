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
            <form action="<?= BASE_URL . '?act=service-type-store' ?>" method="POST">

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
            <h3 class="text-lg font-semibold mb-4">Danh sách hiện có (<?= count($serviceTypes) ?>)</h3>
            <div class="space-y-4">

                <!-- ITEM -->
                <?php foreach ($serviceTypes as $serviceType) { ?>
                    <div class="border rounded-lg p-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-semibold"><?= $serviceType["name"] ?></h4>
                                <p class="text-gray-500 text-sm"><?= $serviceType["description"] ?></p>
                            </div>
                            <div class="flex gap-2">

                                <!-- Sửa -->
                                <a href="<?= BASE_URL . '?act=service-type-edit&id=' . $serviceType['id'] ?>"
                                class="inline-flex items-center justify-center p-1 text-gray-600 hover:text-orange-500 transition">
                                    <i class="w-5 h-4" data-lucide="square-pen"></i>
                                </a>

                                <!-- Xem chi tiết -->
                                <a href="<?= BASE_URL . '?act=service-type-detail&id=' . $serviceType['id'] ?>"
                                class="inline-flex items-center justify-center p-1 text-gray-600 hover:text-blue-500 transition"">
                                    <i class="w-5 h-4" data-lucide="eye"></i>
                                </a>

                                <!-- Xóa -->
                                <a href="<?= BASE_URL . '?act=service-type-delete&id=' . $serviceType['id'] ?>"
                                    onclick="return confirm('Bạn có chắc muốn xóa không?')"
                                    class="inline-flex items-center justify-center p-1 text-red-600 hover:text-red-700 transition">
                                    <i class="w-5 h-4" data-lucide="trash-2"></i>
                                </a>

                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<?php
require_once './views/components/footer.php';
