<?php 
require_once './views/components/header.php';
require_once './views/components/sidebar.php';
?>

<div class="ml-54 mt-28 p-6">
    <!-- Tiêu đề trang -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-semibold">Quản lý Dịch Vụ</h2>
            <p class="text-gray-500 text-sm">Quản lý các dịch vụ cung cấp cho tour</p>
        </div>
        <button onclick="window.location.href='?act=service-create'" 
                class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-2 rounded-lg font-medium transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Thêm Dịch vụ mới
        </button>
    </div>

    <!-- Bộ lọc -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
    <form action="" method="GET" class="flex flex-wrap items-center gap-4">
        <input type="hidden" name="act" value="service">

        <!-- Tìm theo tên dịch vụ -->
        <div class="flex-1 min-w-64">
            <input type="text" name="keyword" placeholder="Tìm theo tên dịch vụ..." 
                   value="<?= $_GET['keyword'] ?? '' ?>" 
                   class="w-full border rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
        </div>

        <!-- Loại dịch vụ -->
        <select name="service_type_id" class="flex-1 border rounded-lg px-4 py-2 text-sm">
            <option value="">Tất cả loại</option>
            <?php foreach($serviceTypes as $type): ?>
                <option value="<?= $type['id'] ?>" <?= (isset($_GET['service_type_id']) && $_GET['service_type_id']==$type['id']) ? 'selected' : '' ?>>
                    <?= $type['name'] ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- Nhà cung cấp -->
        <select name="supplier_id" class="flex-1 border rounded-lg px-4 py-2 text-sm">
            <option value="">Tất cả NCC</option>
            <?php foreach($suppliers as $supplier): ?>
                <option value="<?= $supplier['id'] ?>" <?= (isset($_GET['supplier_id']) && $_GET['supplier_id']==$supplier['id']) ? 'selected' : '' ?>>
                    <?= $supplier['name'] ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
            Lọc
        </button>
    </form>
</div>


    <!-- Bảng danh sách dịch vụ -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="text-left px-6 py-4 font-medium text-gray-700">Tên Dịch vụ</th>
                    <th class=" px-6 py-4 font-medium text-gray-700">Loại Dịch vụ</th>
                    <th class=" px-6 py-4 font-medium text-gray-700">Nhà cung cấp</th>
                    <th class=" px-6 py-4 font-medium text-gray-700">Giá</th>
                    <th class=" px-6 py-4 font-medium text-gray-700">Hành động</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php foreach($services as $service): ?>
                <tr class="hover:bg-gray-50 transition">
                    <!-- Tên + mô tả -->
                    <td class="px-6 py-5">
                        <div class="font-semibold text-gray-900"><?= htmlspecialchars($service["name"]) ?></div>
                        <div class="text-sm text-gray-500 mt-1"><?= htmlspecialchars($service["description"]) ?></div>
                    </td>

                    <!-- Loại dịch vụ -->
                    <td class="px-6 py-5 text-center">
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium
                            <?php
                                if($service["service_type_name"] == 'Khách sạn') echo 'bg-blue-100 text-blue-700';
                                elseif($service["service_type_name"] == 'Vận chuyển') echo 'bg-purple-100 text-purple-700';
                                elseif($service["service_type_name"] == 'Ăn uống') echo 'bg-cyan-100 text-cyan-700';
                                else echo 'bg-gray-100 text-gray-700';
                            ?>">
                            <?= $service["service_type_name"] ?>
                        </span>
                    </td>

                    <!-- Nhà cung cấp -->
                    <td class="px-6 py-5 text-gray-700 text-center">
                        <?= $service["supplier_name"] ?>
                    </td>

                    <!-- Giá -->
                    <td class="px-6 py-5 text-center font-medium text-gray-900">
                        <?= number_format($service["price"]) ?>
                    </td>

                    <!-- Hành động – căn giữa hoàn hảo -->
                    <td class="px-6 py-5">
                        <div class="flex justify-center items-center gap-5">
                            <!-- Xem -->
                            <a href="<?= BASE_URL ?>?act=service-detail&id=<?= $service['id'] ?>" 
                               class="text-gray-500 hover:text-blue-600 transition">
                                <i data-lucide="eye"></i>
                            </a>

                            <!-- Sửa -->
                            <a href="?act=service-edit&id=<?= $service['id'] ?>" 
                               class="text-gray-500 hover:text-green-600 transition">
                                <i data-lucide="square-pen"></i>
                            </a>

                            <!-- Xóa -->
                            <a href="?act=service-delete&id=<?= $service['id'] ?>" 
                               onclick="return confirm('Bạn có chắc chắn muốn xóa dịch vụ này?')"
                               class="text-gray-500 hover:text-red-600 transition">
                                <i data-lucide="trash-2"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
                   
        <!-- Footer bảng -->
        <div class="px-6 py-4 bg-gray-50 border-t text-sm text-gray-600">
            Các Dịch Vụ Hiện Có
        </div>
    </div>
</div>

<?php 
require_once './views/components/footer.php';
?>