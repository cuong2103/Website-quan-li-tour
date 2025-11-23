<?php
require_once "./views/components/header.php";
require_once "./views/components/sidebar.php";
?>

<!-- Nội dung chính -->
<main class="flex-1 ml-8 mt-28 p-6 bg-gray-100">

    <h1 class="text-2xl font-semibold text-gray-800 mb-6">Chi tiết Nhà cung cấp</h1>

    <div class="bg-white shadow rounded p-6">
        <table class="min-w-full divide-y divide-gray-200">
            <tbody class="divide-y divide-gray-100">

                <tr>
                    <th class="px-4 py-3 text-gray-600 font-medium w-1/4">ID</th>
                    <td class="px-4 py-3"><?= $supplier['id'] ?></td>
                </tr>

                <tr>
                    <th class="px-4 py-3 text-gray-600 font-medium">Tên nhà cung cấp</th>
                    <td class="px-4 py-3"><?= htmlspecialchars($supplier['name']) ?></td>
                </tr>

                <tr>
                    <th class="px-4 py-3 text-gray-600 font-medium">Email</th>
                    <td class="px-4 py-3"><?= htmlspecialchars($supplier['email']) ?></td>
                </tr>

                <tr>
                    <th class="px-4 py-3 text-gray-600 font-medium">Số điện thoại</th>
                    <td class="px-4 py-3"><?= htmlspecialchars($supplier['phone']) ?></td>
                </tr>

                <tr>
                    <th class="px-4 py-3 text-gray-600 font-medium">Điểm đến</th>
                    <td class="px-4 py-3"><?= htmlspecialchars($supplier['destination_id'] ?? 'Chưa xác định') ?></td>
                </tr>

                <tr>
                    <th class="px-4 py-3 text-gray-600 font-medium">Người tạo</th>
                    <td class="px-4 py-3"><?= htmlspecialchars($supplier['created_by']) ?></td>
                </tr>

                <tr>
                    <th class="px-4 py-3 text-gray-600 font-medium">Ngày tạo</th>
                    <td class="px-4 py-3"><?= $supplier['created_at'] ?></td>
                </tr>

                <tr>
                    <th class="px-4 py-3 text-gray-600 font-medium">Ngày cập nhật</th>
                    <td class="px-4 py-3"><?= $supplier['updated_at'] ?></td>
                </tr>

            </tbody>
        </table>
    </div>

    <!-- Nút quay lại -->
    <a href="?act=suppliers"
        class="inline-block mt-6 bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
        Quay lại
    </a>

</main>

<?php require_once "./views/components/footer.php"; ?>