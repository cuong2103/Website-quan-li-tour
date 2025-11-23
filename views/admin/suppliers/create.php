<?php
require_once "./views/components/header.php";
require_once "./views/components/sidebar.php";
?>

<main class="flex-1 ml-8 mt-28 p-6 bg-gray-100">

    <h1 class="text-2xl font-semibold text-gray-800 mb-6">Thêm Nhà cung cấp mới</h1>

    <div class="bg-white shadow rounded p-6 max-w-2xl">

        <?php if (!empty($err)): ?>
            <p class="mb-4 px-4 py-2 bg-red-100 text-red-700 rounded">
                <?= $err ?>
            </p>
        <?php endif; ?>

        <form action="?act=supplier-create" method="POST" class="space-y-5">

            <!-- Tên -->
            <div>
                <label class="block text-gray-700 font-medium mb-1">Tên nhà cung cấp</label>
                <input type="text" name="name"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-blue-200"
                    required>
            </div>

            <!-- Email -->
            <div>
                <label class="block text-gray-700 font-medium mb-1">Email</label>
                <input type="email" name="email"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-blue-200"
                    required>
            </div>

            <!-- Phone -->
            <div>
                <label class="block text-gray-700 font-medium mb-1">Số điện thoại</label>
                <input type="text" name="phone"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-blue-200"
                    required>
            </div>

            <!-- Destination -->
            <div>
                <label class="block text-gray-700 font-medium mb-1">Điểm đến</label>
                <select name="destination_id"
                    class="w-full border border-gray-300 rounded px-3 py-2 bg-white focus:ring focus:ring-blue-200"
                    required>
                    <option value="">-- Chọn điểm đến --</option>
                    <?php foreach ($destinations as $dest): ?>
                        <option value="<?= $dest['id'] ?>">
                            <?= htmlspecialchars($dest['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Created by -->
            <div>
                <label class="block text-gray-700 font-medium mb-1">Người tạo</label>
                <input type="text" name="created_by"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-blue-200"
                    required>
            </div>

            <!-- Buttons -->
            <div class="flex items-center space-x-3 pt-3">
                <button type="submit"
                    class="bg-orange-600 text-white px-4 py-2 rounded hover:bg-orange-600">
                    Thêm mới
                </button>
                <a href="?act=supplier-list"
                    class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                    Hủy
                </a>
            </div>

        </form>
    </div>

</main>

<?php require_once "./views/components/footer.php"; ?>