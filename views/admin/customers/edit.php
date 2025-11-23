<?php
require_once "./views/components/header.php";
require_once "./views/components/sidebar.php";
?>
<main class="flex-1 mt-28 overflow-y-auto p-6">

    <!-- Tiêu đề -->
    <h1 class="text-2xl font-semibold text-gray-800 mb-6">Cập nhật khách hàng</h1>

    <div class="bg-white shadow-sm rounded-lg p-6 max-w-2xl mx-auto">

        <!-- Hiển thị lỗi -->
        <?php if (!empty($err)): ?>
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
                <?= $err ?>
            </div>
        <?php endif; ?>

        <!-- Form update -->
        <form action="?act=customer-update&id=<?= $customer['id'] ?>" method="POST" class="space-y-5">

            <!-- Tên khách hàng -->
            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700">Tên khách hàng *</label>
                <input
                    type="text"
                    name="name"
                    value="<?= $customer['name'] ?>"
                    class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <!-- Email -->
            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700">Email *</label>
                <input
                    type="email"
                    name="email"
                    value="<?= $customer['email'] ?>"
                    class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <!-- Số điện thoại -->
            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700">Số điện thoại *</label>
                <input
                    type="text"
                    name="phone"
                    value="<?= $customer['phone'] ?>"
                    class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <!-- Địa chỉ -->
            <div>
                <label class="block mb-1 text-sm font-medium text-gray-700">Địa chỉ *</label>
                <input
                    type="text"
                    name="address"
                    value="<?= $customer['address'] ?>"
                    class="w-full border border-gray-300 px-4 py-2 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <!-- Nút -->
            <div class="flex justify-end gap-3 pt-4">
                <a href="?act=customers" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg">Hủy</a>
                <button type="submit" class="px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg">Cập nhật</button>
            </div>
        </form>
    </div>

</main>
<?php require_once "./views/components/footer.php"; ?>