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

        <a href="?act=customer-create" class="px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-md flex items-center gap-2">
            <span class="text-lg font-bold">+</span> Thêm khách hàng
        </a>
    </div>

    <!-- Ô tìm kiếm -->
    <div class="mb-6">
        <form method="GET">
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
            Danh sách khách hàng (5)
        </h2>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="text-gray-600 bg-gray-100 border-b">
                        <th class="px-4 py-3">Khách hàng</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Số điện thoại</th>
                        <th class="px-4 py-3">Địa chỉ</th>
                        <th class="px-4 py-3">Giới tính</th>
                        <th class="px-4 py-3">Hộ chiếu</th>
                        <th class="px-4 py-3 text-center">Hành động</th>
                    </tr>
                </thead>

                <tbody class="text-gray-700">
                    <?php foreach ($listCustomers as $cus): ?>
                        <!-- 1 hàng -->
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-3 flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xl font-semibold">

                                </div>
                                <?= $cus['name'] ?>
                            </td>
                            <td class="px-4 py-3"><?= $cus['email'] ?></td>
                            <td class="px-4 py-3"><?= $cus['phone'] ?></td>
                            <td class="px-4 py-3"><?= $cus['address'] ?></td>
                            <td class="px-4 py-3"><?= $cus['gender'] ?></td>
                            <td class="px-4 py-3"><?= $cus['passport'] ?></td>
                            <td class="px-4 py-3 text-center">

                                <!-- Xem -->
                                <a href="?act=customer-edit&id=<?= $cus['id'] ?>" class="text-gray-600 hover:text-blue-600 mx-1 inline-block">
                                    <i class="w-4 h-4" data-lucide="square-pen"></i>
                                </a>

                                <!-- Sửa -->
                                <a href="?act=customer-detail&id=<?= $cus['id'] ?>" class="text-gray-600 hover:text-blue-600 mx-1 inline-block">
                                    <i class="w-4 h-4" data-lucide="eye"></i>
                                </a>

                                <!-- Xóa -->
                                <a href="?act=customer-delete&id=<?= $cus['id'] ?>" class="text-gray-600 hover:text-blue-600 mx-1 inline-block">
                                    <i class="w-4 h-4" data-lucide="trash-2"></i>
                                </a>

                            </td>
                        </tr>

                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <script>
        let timer;
        const form = document.querySelector("form");

        document.querySelectorAll("form input, form select").forEach(element => {
            element.addEventListener("input", () => {
                console.log("1");
                clearTimeout(timer);
                timer = setTimeout(() => form.submit(), 600);
            });
        });
    </script>
</main>
<?php require_once "./views/components/footer.php"; ?>