<?php
require_once "./views/components/header.php";
require_once "./views/components/sidebar.php";
?>

<main class="p-6 bg-gray-100 min-h-screen mt-28">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold text-gray-800">Quản lý Nhà cung cấp</h1>
            <a href="?act=supplier-add&id=" class="bg-orange-500 text-white px-4 py-2 rounded-md hover:bg-orange-600">Thêm NCC</a>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-700">Danh sách nhà cung cấp (4)</h2>
                <!-- Search bar if needed, but not in main focus -->
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nhà cung cấp</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Loại</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sđt</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số dịch vụ</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hành động</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($suppliers as $sup): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= $sup['name'] ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $sup['destination_id'] ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $sup['email'] ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $sup['phone'] ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $sup['created_by'] ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">

                                    <?= $sup['status'] == 1 ? '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Hoạt động</span>' : '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Tạm dừng</span>' ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <a href="?act=supplier-detail&id=<?= $sup['id'] ?>" class="text-gray-400 hover:text-gray-600 mr-2">👁️</a>
                                    <a href="?act=supplier-edit&id=<?= $sup['id'] ?>" class="text-gray-400 hover:text-gray-600 mr-2">✏️</a>
                                    <a href="?act=supplier-delete&id=<?= $sup['id'] ?>" class="text-gray-400 hover:text-gray-600" onclick="return confirm('Bạn có chắc muốn xoá không?')">🗑️</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>


                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php
require_once "./views/components/footer.php";
?>