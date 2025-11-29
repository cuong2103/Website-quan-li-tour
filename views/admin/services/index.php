<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';
?>

<div class="ml-54 mt-28 p-6">

    <!-- Header trang -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-semibold">Quản lý Dịch Vụ</h2>
            <p class="text-gray-500 text-sm">Danh sách dịch vụ đang cung cấp</p>
        </div>
        <button onclick="window.location.href='?act=service-create'"
            class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-2 rounded-lg font-medium transition flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Thêm Dịch vụ mới
        </button>
    </div>

    <!-- Bộ lọc realtime -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
        <div class="flex flex-wrap items-center gap-4">
            <input type="hidden" id="filter-act" value="service">

            <!-- Search -->
            <input id="filter-keyword" type="text" placeholder="Tìm theo tên dịch vụ..."
                value="<?= $_GET['keyword'] ?? '' ?>"
                class="flex-1 min-w-60 border rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">

            <!-- Loại dịch vụ -->
            <select id="filter-type" class="border rounded-lg px-4 py-2 text-sm">
                <option value="">Tất cả loại</option>
                <?php foreach ($serviceTypes as $type): ?>
                    <option value="<?= $type['id'] ?>" <?= (($_GET['service_type_id'] ?? '') == $type['id']) ? 'selected' : '' ?>>
                        <?= $type['name'] ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <!-- Nhà cung cấp -->
            <select id="filter-supplier" class="border rounded-lg px-4 py-2 text-sm">
                <option value="">Tất cả NCC</option>
                <?php foreach ($suppliers as $s): ?>
                    <option value="<?= $s['id'] ?>" <?= (($_GET['supplier_id'] ?? '') == $s['id']) ? 'selected' : '' ?>>
                        <?= $s['name'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <!-- Danh sách dịch vụ -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden" id="service-table">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="text-left px-6 py-4 font-medium text-gray-700">Tên Dịch vụ</th>
                    <th class="px-6 py-4 font-medium text-gray-700">Loại</th>
                    <th class="px-6 py-4 font-medium text-gray-700">Nhà cung cấp</th>
                    <th class="px-6 py-4 font-medium text-gray-700">Giá</th>
                    <th class="px-6 py-4 font-medium text-gray-700 text-center">Hành động</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200">
                <?php foreach ($services as $service): ?>
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
                                if ($service["service_type_name"] == 'Khách sạn') echo 'bg-blue-100 text-blue-700';
                                elseif ($service["service_type_name"] == 'Vận chuyển') echo 'bg-purple-100 text-purple-700';
                                elseif ($service["service_type_name"] == 'Ăn uống') echo 'bg-cyan-100 text-cyan-700';
                                elseif ($service["service_type_name"] == 'Vé tham quan') echo 'bg-green-100 text-green-700';
                                else echo 'bg-gray-100 text-gray-700';
                            ?>">
                                <?= $service["service_type_name"] ?>
                            </span>
                        </td>

                        <!-- Nhà cung cấp -->
                        <td class="px-6 py-5 text-center text-gray-700">
                            <?= $service["supplier_name"] ?>
                        </td>

                        <!-- Giá -->
                        <td class="px-6 py-5 text-center font-medium text-gray-700">
                            <?= number_format($service["estimated_price"] ?? 0) . ' VNĐ' ?>
                        </td>

                        <!-- Hành động -->
                        <td class="px-6 py-5 text-center">
                            <div class="flex justify-center gap-3">

                                <a href="?act=service-edit&id=<?= $service['id'] ?>"
                                    class="text-gray-700 hover:text-blue-600">
                                    <i class="w-5 h-4" data-lucide="square-pen"></i>
                                </a>

                                <a href="?act=service-detail&id=<?= $service['id'] ?>"
                                    class="text-gray-700 hover:text-orange-600">
                                    <i class="w-5 h-4" data-lucide="eye"></i>
                                </a>

                                <a href="?act=service-delete&id=<?= $service['id'] ?>"
                                    onclick="return confirm('Bạn có chắc muốn xoá không?')"
                                    class="text-red-600 hover:text-red-700">
                                    <i class="w-5 h-4" data-lucide="trash-2"></i>
                                </a>

                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Footer -->
        <div class="px-6 py-4 bg-gray-50 border-t text-sm text-gray-600">
            Danh sách dịch vụ hiện có (<?=  count($services) ?>)
        </div>
    </div>
</div>

<script>
let timer = null;

function autoFilter() {
    clearTimeout(timer);
    timer = setTimeout(() => {
        const keyword = document.getElementById('filter-keyword').value;
        const type = document.getElementById('filter-type').value;
        const supplier = document.getElementById('filter-supplier').value;

        const params = new URLSearchParams({
            act: "service",
            keyword: keyword,
            service_type_id: type,
            supplier_id: supplier
        });

        window.location.href = "?" + params.toString();
    }, 600);
}

// Gắn event realtime
document.getElementById("filter-keyword").addEventListener("input", autoFilter);
document.getElementById("filter-type").addEventListener("change", autoFilter);
document.getElementById("filter-supplier").addEventListener("change", autoFilter);
</script>

<?php
require_once './views/components/footer.php';
?>
