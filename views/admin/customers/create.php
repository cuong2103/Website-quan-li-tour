<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';
?>

<main class="mt-28 px-6 pb-20">

    <h1 class="text-2xl font-bold mb-6">Thêm khách hàng mới</h1>

    <form action="<?= BASE_URL . '?act=customer-create' ?>"
        method="POST"
        enctype="multipart/form-data"
        class="bg-white p-6 rounded-lg shadow">

        <?php  ?>
        <!-- Tên -->
        <div class="mb-4">
            <label class="block font-medium mb-1">Tên khách hàng</label>
            <input type="text" name="name" class="border w-full p-2 rounded-lg" required>
        </div>

        <!-- Email -->
        <div class="mb-4">
            <label class="block font-medium mb-1">Email</label>
            <input type="text" name="email" class="border w-full p-2 rounded-lg" required>
        </div>
        <!-- Số điện thoại -->
        <div class="mb-4">
            <label class="block font-medium mb-1">Số điện thoại</label>
            <input type="text" name="phone" class="border w-full p-2 rounded-lg" required>
        </div>
        <!-- Địa chỉ -->
        <div class="mb-4">
            <label class="block font-medium mb-1">Địa chỉ</label>
            <input type="text" name="address" class="border w-full p-2 rounded-lg" required>
        </div>
        <!-- Hộ chiếu -->
        <div class="mb-4">
            <label class="block font-medium mb-1">Hộ chiếu</label>
            <input type="text" name="passport" class="border w-full p-2 rounded-lg" required>
        </div>
        <!-- Giới tính -->
        <div class="mb-4">
            <label class="block font-medium mb-1">Giới tính</label>
            <input type="text" name="gender" class="border w-full p-2 rounded-lg" required>
        </div>






        <!-- Nút -->
        <div class="flex gap-3 mt-6">
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Thêm mới
            </button>
            <a href="<?= BASE_URL . '?act=customers' ?>" class="px-6 py-2 border rounded-lg hover:bg-gray-50">
                Hủy
            </a>
        </div>

    </form>
</main>



<?php
require_once './views/components/footer.php';
?>