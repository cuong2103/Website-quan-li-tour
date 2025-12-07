<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';
?>

<main class="flex-1 pt-28 overflow-y-auto p-6 bg-gray-50 w-full">

    <div class="w-full mx-auto">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 tracking-tight">Thêm khách hàng mới</h1>
                <p class="text-sm text-gray-500 mt-1">Nhập thông tin chi tiết để tạo hồ sơ khách hàng mới</p>
            </div>
            <a href="<?= BASE_URL . '?act=customers' ?>" class="text-gray-500 hover:text-gray-700 flex items-center text-sm font-medium transition-colors">
                <i class="w-4 h-4 mr-1" data-lucide="arrow-left"></i> Quay lại danh sách
            </a>
        </div>

        <form action="<?= BASE_URL . '?act=customer-create' ?>"
            method="POST"
            enctype="multipart/form-data"
            class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">

            <div class="p-8">
                <!-- Section 1: Thông tin cá nhân -->
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center pb-2 border-b border-gray-100">
                        <i class="w-5 h-5 mr-2 text-blue-600" data-lucide="user"></i>
                        Thông tin cá nhân
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Tên -->
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Họ và tên <span class="text-red-500">*</span></label>
                            <input type="text" name="name" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder-gray-400" placeholder="Nhập họ tên khách hàng" required>
                        </div>

                        <!-- Giới tính -->
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Giới tính</label>
                            <select name="gender" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all bg-white">
                                <option value="male">Nam</option>
                                <option value="female">Nữ</option>
                                <option value="other">Khác</option>
                            </select>
                        </div>

                        <!-- Email -->
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="w-4 h-4 text-gray-400" data-lucide="mail"></i>
                                </div>
                                <input type="email" name="email" class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder-gray-400" placeholder="example@email.com" required>
                            </div>
                        </div>

                        <!-- Số điện thoại -->
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Số điện thoại <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="w-4 h-4 text-gray-400" data-lucide="phone"></i>
                                </div>
                                <input type="text" name="phone" class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder-gray-400" placeholder="0912 345 678" required>
                            </div>
                        </div>

                        <!-- Địa chỉ -->
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Địa chỉ <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="w-4 h-4 text-gray-400" data-lucide="map-pin"></i>
                                </div>
                                <input type="text" name="address" class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder-gray-400" placeholder="Số nhà, đường, phường/xã, quận/huyện..." required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Giấy tờ tùy thân -->
                <div>
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center pb-2 border-b border-gray-100">
                        <i class="w-5 h-5 mr-2 text-blue-600" data-lucide="credit-card"></i>
                        Giấy tờ tùy thân
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- CCCD -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Căn cước công dân (CCCD)</label>
                            <input type="text" name="citizen_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder-gray-400" placeholder="Nhập số CCCD">
                        </div>

                        <!-- Hộ chiếu -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Hộ chiếu (Passport)</label>
                            <input type="text" name="passport" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder-gray-400" placeholder="Nhập số hộ chiếu">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="px-8 py-5 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3">
                <button type="submit" class="px-6 py-2.5 bg-orange-600 hover:bg-orange-700 text-white font-medium rounded-lg shadow-sm hover:shadow transition-all flex items-center">
                    <i class="w-4 h-4 mr-2" data-lucide="save"></i> Lưu khách hàng
                </button>
            </div>

        </form>
    </div>
</main>

<?php
require_once './views/components/footer.php';
?>