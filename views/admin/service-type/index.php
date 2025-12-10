<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';
?>
<main class="w-full flex-1 pt-28 overflow-y-auto p-6 bg-gray-50 ">

    <div class="w-full mx-auto grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- FORM THÊM LOẠI DỊCH VỤ -->
        <div class="bg-white rounded-xl shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Thêm loại dịch vụ</h3>

            <form action="<?= BASE_URL . '?act=service-type-store' ?>" method="POST">

                <!-- Tên loại dịch vụ -->
                <label class="block mb-2 font-medium">Tên loại dịch vụ <span class="text-red-500">*</span></label>
                <input
                    type="text"
                    name="name"
                    value="<?= htmlspecialchars($data['name'] ?? '') ?>"
                    class="w-full border rounded-lg p-2 mb-1 <?= !empty($errors['name']) ? 'border-red-500' : '' ?>"
                    placeholder="Ví dụ: Khách sạn, Vận chuyển..." />

                <?php if (!empty($errors['name'])): ?>
                    <p class="text-red-500 text-sm mt-1"><?= $errors['name'][0] ?></p>
                <?php endif; ?>


                <!-- Mô tả -->
                <label class="block mt-4 mb-2 font-medium">Mô tả</label>
                <textarea
                    name="description"
                    rows="3"
                    class="w-full border rounded-lg p-2 mb-1 <?= !empty($errors['description']) ? 'border-red-500' : '' ?>"
                    placeholder="Mô tả chi tiết về loại dịch vụ này..."><?= htmlspecialchars($data['description'] ?? '') ?></textarea>

                <?php if (!empty($errors['description'])): ?>
                    <p class="text-red-500 text-sm mt-1"><?= $errors['description'][0] ?></p>
                <?php endif; ?>

                <button
                    type="submit"
                    class="mt-4 w-full bg-orange-500 text-white py-2 rounded-lg hover:bg-orange-600 transition">
                    + Lưu
                </button>
            </form>
        </div>

        <!-- DANH SÁCH LOẠI DỊCH VỤ -->
        <div class="bg-white rounded-xl shadow p-6">

            <?php $serviceTypes = $serviceTypes ?? []; ?>

            <h3 class="text-lg font-semibold mb-4">
                Danh sách hiện có (<?= count($serviceTypes) ?>)
            </h3>

            <div class="space-y-4">

                <?php if (empty($serviceTypes)): ?>
                    <p class="text-gray-500">Chưa có loại dịch vụ nào.</p>
                <?php else: ?>

                    <?php foreach ($serviceTypes as $serviceType): ?>
                        <div class="border rounded-lg p-4 bg-gray-50">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="font-semibold"><?= htmlspecialchars($serviceType["name"]) ?></h4>
                                    <p class="text-gray-500 text-sm"><?= htmlspecialchars($serviceType["description"] ?? '') ?></p>
                                </div>

                                <!-- ACTION BUTTONS -->
                                <div class="flex gap-2">

                                    <!-- Sửa -->
                                    <a href="<?= BASE_URL . '?act=service-type-edit&id=' . $serviceType['id'] ?>"
                                        class="p-1 text-gray-600 hover:text-orange-500 transition">
                                        <i class="w-5 h-4" data-lucide="square-pen"></i>
                                    </a>

                                    <!-- Xem chi tiết -->
                                    <a href="<?= BASE_URL . '?act=service-type-detail&id=' . $serviceType['id'] ?>"
                                        class="p-1 text-gray-600 hover:text-blue-500 transition">
                                        <i class="w-5 h-4" data-lucide="eye"></i>
                                    </a>

                                    <!-- Xóa -->
                                    <a href="<?= BASE_URL . '?act=service-type-delete&id=' . $serviceType['id'] ?>"
                                        onclick="return confirm('Bạn có chắc muốn xóa không?')"
                                        class="p-1 text-red-600 hover:text-red-700 transition">
                                        <i class="w-5 h-4" data-lucide="trash-2"></i>
                                    </a>

                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                <?php endif; ?>

            </div>
        </div>

    </div>

</main>

<?php require_once './views/components/footer.php'; ?>