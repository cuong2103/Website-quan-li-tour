<?php 
require_once './views/components/header.php';
require_once './views/components/sidebar.php';
?>

<div class=" mt-28 p-6">
    <!-- Tiêu đề trang -->
    <!-- Bộ lọc -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
        <div class="flex flex-wrap items-center gap-4">
            <div class="flex-1 min-w-64">
                <input type="text" placeholder="Tìm theo tên dịch vụ..." 
                       class="w-full border rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
            </div>
            <select class="flex-1 border rounded-lg px-4 py-2 text-sm">
                <option>Tất cả loại</option>
            </select>
            <select class="border flex-1 rounded-lg px-4 py-2 text-sm">
                <option>Tất cả NCC</option>
            </select>
            <select class="border flex-1 rounded-lg px-4 py-2 text-sm">
                <option>Tất cả</option>
            </select>
        </div>
    </div>

    <!-- Bảng danh sách dịch vụ -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-30 border-b">
                <tr>
                    <th class="text-left px-6 py-4 font-medium text-gray-700">Tên Dịch vụ</th>
                    <th class="text-left px-6 py-4 font-medium text-gray-700">Loại Dịch vụ</th>
                    <th class="text-left px-6 py-4 font-medium text-gray-700">Nhà cung cấp</th>
                    <th class="text-right px-6 py-4 font-medium text-gray-700">Giá</th>
                    <th class="text-center px-6 py-4 font-medium text-gray-700">Hành động</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <div class="font-medium"><?= $service["name"] ?></div>
                        <div class="text-sm text-gray-500"><?= $service["description"] ?></div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-medium"><?= $service["service_type_name"] ?></span>
                    </td>
                    <td class="px-6 py-4"><?= $service["supplier_name"] ?></td>
                    <td class="px-6 py-4 text-center"><?= number_format($service["price"]) ?>.00</td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center gap-3">
                            <a href="index.php?act=service" >
                             ← Quay lại
                            </a>
                            <!-- Sửa -->
                            <a href="?act=service-edit&id=<?= $service['id'] ?>" class="text-gray-600 hover:text-green-600">
                                <i data-lucide="square-pen" class="w-5 h-5"></i>
                            </a>
                            <!-- Xóa -->
                            <a href="?act=service-delete&id=<?= $service['id'] ?>" 
                               onclick="return confirm('Xóa dịch vụ này?')" 
                               class="text-red-300 hover:text-red-500">
                                <i data-lucide="trash-2" class="w-5 h-5"></i>
                            </a>
                            
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
                   
        <!-- Footer bảng -->
        <div class="px-6 py-4 bg-gray-50 border-t text-sm text-gray-600">
            Hiển thị 4 trong tổng số 64 dịch vụ
        </div>
    </div>
</div>

<!-- Khởi tạo Lucide icons (chỉ thêm 1 dòng này ở cuối trang) -->
<script>lucide.createIcons();</script>

<?php 
require_once './views/components/footer.php';
?>