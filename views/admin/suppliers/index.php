<?php
require_once "./views/components/header.php";
require_once "./views/components/sidebar.php";
?>

<div class="ml-54 mt-28 p-6">

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-semibold text-gray-800">Quản lý nhà cung cấp</h2>
            <p class="text-gray-500 text-sm">Danh sách đối tác và nhà cung cấp dịch vụ</p>
        </div>
        <a href="?act=supplier-create"
            class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg font-medium transition flex items-center gap-2 text-sm shadow-sm">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Thêm nhà cung cấp
        </a>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 text-gray-700 font-medium border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4">Nhà cung cấp</th>
                    <th class="px-6 py-4">Liên hệ</th>
                    <th class="px-6 py-4">Địa điểm</th>
                    <th class="px-6 py-4 text-center">Trạng thái</th>
                    <th class="px-6 py-4 text-center">Hành động</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php foreach ($suppliers as $supplier): ?>
                    <tr class="hover:bg-gray-50 transition">
                        <!-- Tên -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center shrink-0">
                                    <i data-lucide="building-2" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900"><?= htmlspecialchars($supplier['name']) ?></div>
                                </div>
                            </div>
                        </td>

                        <!-- Liên hệ -->
                        <td class="px-6 py-4">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2 text-gray-600">
                                    <i data-lucide="mail" class="w-3.5 h-3.5"></i>
                                    <?= htmlspecialchars($supplier['email']) ?>
                                </div>
                                <div class="flex items-center gap-2 text-gray-600">
                                    <i data-lucide="phone" class="w-3.5 h-3.5"></i>
                                    <?= htmlspecialchars($supplier['phone']) ?>
                                </div>
                            </div>
                        </td>

                        <!-- Địa điểm -->
                        <td class="px-6 py-4 text-gray-700">
                            <div class="flex items-center gap-2">
                                <i data-lucide="map-pin" class="w-4 h-4 text-gray-400"></i>
                                <?= htmlspecialchars($supplier['destination_name'] ?? 'Chưa cập nhật') ?>
                            </div>
                        </td>

                        <!-- Trạng thái -->
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                <?= $supplier['status'] == 1 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                                <?= $supplier['status'] == 1 ? 'Hoạt động' : 'Ngừng hoạt động' ?>
                            </span>
                        </td>

                        <!-- Hành động -->
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-3">
                                <a href="?act=supplier-edit&id=<?= $supplier['id'] ?>"
                                    class="text-white-500 hover:text-blue-600 transition" title="Sửa">
                                    <i data-lucide="square-pen" class="w-5 h-4.5 mr-2"></i>
                                </a>
                                <a href="?act=supplier-detail&id=<?= $supplier['id'] ?>"
                                    class="text-white-500 hover:text-orange-600 transition" title="Chi tiết">
                                    <i data-lucide="eye" class="w-5 h4.5 mr-2"></i>
                                </a>
                                <a href="?act=supplier-delete&id=<?= $supplier['id'] ?>"
                                    onclick="return confirm('Bạn có chắc muốn xoá nhà cung cấp này không?')"
                                    class="text-red-500 hover:text-red-600 transition" title="Xóa">
                                    <i data-lucide="trash-2" class="w-5 h4.5"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php if (empty($suppliers)): ?>
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                    <i data-lucide="inbox" class="w-8 h-8"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900">Chưa có dữ liệu</h3>
                <p class="text-gray-500 mt-1">Chưa có nhà cung cấp nào được tạo.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once "./views/components/footer.php"; ?>