<?php
require_once "./views/components/header.php";
require_once "./views/components/sidebar.php";
?>
<main class="flex-1 mt-28 overflow-y-auto p-6">

    <!-- Tiêu đề + nút thêm khách hàng -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Quản lý khách hàng</h1>
            <p class="text-sm text-gray-500">Danh sách khách hàng và lịch sử booking</p>
        </div>

        <div class="flex items-center gap-2">
            <!-- Nút tải xuống Excel -->
            <a href="?act=customer-export" class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-md flex items-center gap-2">
                <i class="w-4 h-4" data-lucide="download"></i> Tải xuống Excel
            </a>

            <!-- Nút tải lên Excel -->
            <button onclick="document.getElementById('importModal').classList.remove('hidden')" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-md flex items-center gap-2">
                <i class="w-4 h-4" data-lucide="upload"></i> Tải lên Excel
            </button>

            <!-- Nút thêm khách hàng -->
            <a href="?act=customer-create" class="px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-md flex items-center gap-2">
                <span class="text-lg font-bold">+</span> Thêm khách hàng
            </a>
        </div>
    </div>

    <!-- Ô tìm kiếm -->
    <div class="mb-6">
        <form method="GET" id="searchForm">
            <input type="hidden" name="act" value="customers">

            <input
                type="text" name="search" placeholder="Search..." value="<?= $_GET["search"] ?? "" ?>"
                placeholder="Tìm kiếm khách hàng..."
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none">

        </form>
    </div>

    <!-- Bảng danh sách khách hàng -->
    <div class="bg-white shadow-sm rounded-lg p-4">
        <h2 class="text-lg font-medium text-gray-700 mb-4">
            Danh sách khách hàng (<?= count($listCustomers) ?>)
        </h2>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Khách hàng</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Số điện thoại</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Địa chỉ</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Giới tính</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Hộ chiếu</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">CCCD</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-center">Hành động</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($listCustomers as $cus): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xl font-semibold">
                                    <?= strtoupper(substr($cus['name'], 0, 1)) ?>
                                </div>
                                <div class="truncate max-w-xs"><?= $cus['name'] ?></div>
                            </td>
                            <td class="px-6 py-4 truncate max-w-xs"><?= $cus['email'] ?></td>
                            <td class="px-6 py-4"><?= $cus['phone'] ?></td>
                            <td class="px-6 py-4 truncate max-w-xs"><?= $cus['address'] ?></td>
                            <td class="px-6 py-4">
                                <?php
                                $genderClass = 'bg-gray-100 text-gray-600';
                                $genderLabel = 'Khác';
                                if ($cus['gender'] === 'male') {
                                    $genderClass = 'bg-blue-50 text-blue-700 border border-blue-100';
                                    $genderLabel = 'Nam';
                                } elseif ($cus['gender'] === 'female') {
                                    $genderClass = 'bg-pink-50 text-pink-700 border border-pink-100';
                                    $genderLabel = 'Nữ';
                                }
                                ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $genderClass ?>">
                                    <?= $genderLabel ?>
                                </span>
                            </td>
                            <td class="px-6 py-4"><?= $cus['passport'] ?></td>
                            <td class="px-6 py-4"><?= $cus['citizen_id'] ?? '' ?></td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <!-- Sửa -->
                                    <a href="?act=customer-edit&id=<?= $cus['id'] ?>" class="text-white-600 hover:text-indigo-900 mr-2">
                                        <i class="w-5 h-4" data-lucide="square-pen"></i>
                                    </a>
                                    <!-- Xem -->
                                    <a href="?act=customer-detail&id=<?= $cus['id'] ?>" class="text-white-600 hover:text-indigo-900 mr-2">
                                        <i class="w-5 h-4" data-lucide="eye"></i>
                                    </a>
                                    <!-- Xóa -->
                                    <a href="?act=customer-delete&id=<?= $cus['id'] ?>" onclick="return confirm('Bạn có chắc muốn xóa khách hàng này?')" class="text-red-600 hover:text-red-900">
                                        <i class="w-5 h-4" data-lucide="trash-2"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Upload Excel -->
    <div id="importModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Tải lên danh sách khách hàng</h3>
                <button onclick="document.getElementById('importModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                    <i class="w-5 h-5" data-lucide="x"></i>
                </button>
            </div>

            <form action="?act=customer-import" method="POST" enctype="multipart/form-data">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Chọn file Excel</label>
                    <input type="file" name="file" accept=".xlsx,.xls" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
                    <p class="text-xs text-gray-500 mt-1">Định dạng: .xlsx hoặc .xls</p>
                </div>

                <div class="mb-4 p-3 bg-blue-50 rounded-lg">
                    <p class="text-sm text-gray-700 mb-2"><strong>Định dạng file Excel:</strong></p>
                    <p class="text-xs text-gray-600">Cột 1: STT</p>
                    <p class="text-xs text-gray-600">Cột 2: Họ tên</p>
                    <p class="text-xs text-gray-600">Cột 3: Email</p>
                    <p class="text-xs text-gray-600">Cột 4: Số điện thoại</p>
                    <p class="text-xs text-gray-600">Cột 5: Địa chỉ</p>
                    <p class="text-xs text-gray-600">Cột 6: Giới tính (Nam/Nữ/Khác)</p>
                    <p class="text-xs text-gray-600">Cột 7: Hộ chiếu</p>
                    <p class="text-xs text-gray-600">Cột 8: Căn cước công dân (CCCD)</p>
                </div>

                <div class="mb-4">
                    <a href="?act=customer-export" class="text-sm text-blue-600 hover:underline flex items-center gap-1">
                        <i class="w-4 h-4" data-lucide="download"></i>
                        Tải file mẫu (danh sách hiện tại)
                    </a>
                </div>

                <div class="flex gap-2">
                    <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')"
                        class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">
                        Hủy
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-md">
                        Tải lên
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let timer;
        const searchForm = document.getElementById("searchForm");

        if (searchForm) {
            searchForm.querySelectorAll("input, select").forEach(element => {
                element.addEventListener("input", () => {
                    clearTimeout(timer);
                    timer = setTimeout(() => searchForm.submit(), 500);
                });
            });
        }
    </script>
</main>
<?php require_once "./views/components/footer.php"; ?>