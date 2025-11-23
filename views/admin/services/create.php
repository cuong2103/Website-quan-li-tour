<?php require_once './views/components/header.php'; ?>
<?php require_once './views/components/sidebar.php'; ?>

<div class="ml-58 mt-16 p-11 bg-gray-50 min-h-screen">
    <div class="flex items-center gap-4 mb-8">
        <a href="?act=service" class="text-gray-600 hover:text-orange-600">
            <i data-lucide="arrow-left" class="w-6 h-6"></i>
        </a>
        <h2 class="text-3xl font-bold text-gray-900">Thêm Dịch vụ Mới</h2>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 max-w-4xl">
        <form action="?act=service-store" method="POST">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Tên dịch vụ -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tên dịch vụ *</label>
                    <input type="text" name="name" required 
                           class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition">
                </div>

                <!-- Giá -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Giá dịch vụ (₫) *</label>
                    <input type="number" name="price" min="0" required 
                           class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none">
                </div>

                <!-- Loại dịch vụ -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Loại dịch vụ *</label>
                    <select name="service_type_id" required 
                            class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        <option value="">-- Chọn loại --</option>
                        <?php foreach($serviceTypes as $type): ?>
                            <option value="<?= $type['id'] ?>"><?= $type['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Nhà cung cấp -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nhà cung cấp *</label>
                    <select name="supplier_id" required 
                            class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        <option value="">-- Chọn nhà cung cấp --</option>
                        <?php foreach($suppliers as $supplier): ?>
                            <option value="<?= $supplier['id'] ?>"><?= $supplier['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Mô tả -->
                <div class="md:col-span-2 mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mô tả dịch vụ</label>
                    <textarea name="description" rows="4" 
                              class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 resize-none"></textarea>
                </div>
            </div>

            <!-- Nút hành động -->
            <div class="mt-10 flex justify-end gap-4 border-t pt-6">
                <a href="?act=service" 
                   class="px-6 py-3 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium transition">
                    Hủy bỏ
                </a>
                <button type="submit" 
                        class="px-8 py-3 bg-orange-500 text-white rounded-lg hover:bg-orange-600 font-medium transition flex items-center gap-2">
                    <i data-lucide="plus" class="w-5 h-5"></i>
                    Thêm dịch vụ
                </button>
            </div>
        </form>
    </div>
</div>

<script>lucide.createIcons();</script>
<?php require_once './views/components/footer.php'; ?>
