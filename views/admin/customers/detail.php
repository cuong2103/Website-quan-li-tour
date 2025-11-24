<?php
require_once "./views/components/header.php";
require_once "./views/components/sidebar.php";
?>
<main class="flex-1 mt-28 overflow-y-auto p-6">

    <!-- Tiêu đề -->
    <h1 class="text-2xl font-semibold text-gray-800 mb-6">Chi tiết khách hàng</h1>

    <!-- Container bảng thông tin -->
    <div class="bg-white shadow-sm rounded-lg p-6 max-w-2xl mx-auto">

        <!-- Bảng thông tin giống form -->
        <div class="space-y-5">

            <!-- Tên khách hàng -->
            <div class="flex items-center gap-4">
                <label class="w-40 font-medium text-gray-700">Tên khách hàng:</label>
                <span class="text-gray-800"><?= $customer['name'] ?></span>
            </div>

            <!-- Email -->
            <div class="flex items-center gap-4">
                <label class="w-40 font-medium text-gray-700">Email:</label>
                <span class="text-gray-800"><?= $customer['email'] ?></span>
            </div>

            <!-- Số điện thoại -->
            <div class="flex items-center gap-4">
                <label class="w-40 font-medium text-gray-700">Số điện thoại:</label>
                <span class="text-gray-800"><?= $customer['phone'] ?></span>
            </div>

            <!-- Địa chỉ -->
            <div class="flex items-center gap-4">
                <label class="w-40 font-medium text-gray-700">Địa chỉ:</label>
                <span class="text-gray-800"><?= $customer['address'] ?></span>
            </div>

            <!-- Giới tính -->
            <div class="flex items-center gap-4">
                <label class="w-40 font-medium text-gray-700">Giới tính:</label>
                <span class="text-gray-800"><?= $customer['gender'] ?></span>
            </div>
            <!-- Hộ chiếu -->
            <div class="flex items-center gap-4">
                <label class="w-40 font-medium text-gray-700">Hộ chiếu:</label>
                <span class="text-gray-800"><?= $customer['passport'] ?></span>
            </div>

        </div>
        <!-- Người tạo -->
        <div class="flex items-center gap-4">
            <label class="w-40 font-medium text-gray-700">Người tạo:</label>
            <span class="text-gray-800"><?= $customer['created_by'] ?></span>
        </div>

        <!-- Ngày tạo -->
        <div class="flex items-center gap-4">
            <label class="w-40 font-medium text-gray-700">Ngày tạo:</label>
            <span class="text-gray-800"><?= $customer['created_at'] ?></span>
        </div>

        <!-- Ngày cập nhật -->
        <div class="flex items-center gap-4">
            <label class="w-40 font-medium text-gray-700">Cập nhật lần cuối:</label>
            <span class="text-gray-800"><?= $customer['updated_at'] ?></span>
        </div>


        <!-- Nút quay về -->
        <div class="flex justify-end gap-3 pt-6">
            <a href="?act=customers" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg">Quay lại</a>
        </div>

    </div>

</main>
<?php require_once "./views/components/footer.php"; ?>