<?php require_once "./views/components/header.php"; ?>
<?php require_once "./views/components/sidebar.php"; ?>

<main class="flex-1 mt-28 overflow-y-auto p-6">
    <div class="space-y-6 max-w-7xl mx-auto">

        <!-- Header + Nút quay lại -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <button onclick="history.back()" class="p-2 hover:bg-gray-100 rounded-lg transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Chi tiết Nhà cung cấp</h2>
                    <p class="text-sm text-gray-600">Thông tin chi tiết về nhà cung cấp</p>
                </div>
            </div>

            <div class="flex gap-3">
                <a href="#" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                    <i data-lucide="square-pen" class="w-4 h-4"></i>
                    Chỉnh sửa
                </a>
            </div>
        </div>

        <div class="grid grid-cols-4  gap-6">

            <!-- Thông tin chính -->
            <div class=" col-span-4 space-y-6">

                <!-- Card thông tin nhà cung cấp -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-start gap-5">
                            <div class="w-20 h-20 bg-purple-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i data-lucide="building-2" class="w-10 h-10 text-purple-600"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-2xl font-bold text-gray-900"><?= htmlspecialchars($supplier['name']) ?></h3>
                                <div class="flex items-center gap-3 mt-2">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium <?= $supplier['status'] == 1 ? 'bg-green-100 text-green-800 border-green-200' : 'bg-gray-100 text-gray-800 border-gray-300' ?>">
                                        <?= $supplier['status'] == 1 ? 'Hoạt động' : 'Tạm dừng' ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Email</label>
                                <p class="mt-1 text-gray-900 flex items-center gap-2">
                                    <i data-lucide="mail" class="w-4 h-4 text-gray-400"></i>
                                    <?= htmlspecialchars($supplier['email']) ?>
                                </p>
                            </div>

                            <div>
                                <label class="text-sm font-medium text-gray-500">Số điện thoại</label>
                                <p class="mt-1 text-gray-900 flex items-center gap-2">
                                    <i data-lucide="phone" class="w-4 h-4 text-gray-400"></i>
                                    <?= htmlspecialchars($supplier['phone']) ?>
                                </p>
                            </div>

                            <div>
                                <label class="text-sm font-medium text-gray-500">Địa điểm</label>
                                <p class="mt-1 text-gray-900 flex items-center gap-2">
                                    <i data-lucide="map-pin" class="w-4 h-4 text-gray-400"></i>
                                    <?= htmlspecialchars($supplier['destination_name']) ?>
                                </p>
                            </div>

                            <div>
                                <label class="text-sm font-medium text-gray-500">Tổng dịch vụ đã cung cấp</label>
                                <p class="mt-1 text-gray-900 font-mono"><?= count($services) ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Thông tin hệ thống -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h4 class="font-medium text-gray-900">Thông tin hệ thống</h4>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                        <div>
                            <span class="text-gray-500">Người tạo:</span>
                            <span class="ml-2 font-medium text-gray-900"><?= htmlspecialchars($createdBy['fullname'] ?? '') ?></span>
                        </div>
                        <div>
                            <span class="text-gray-500">Ngày tạo:</span>
                            <span class="ml-2 font-medium text-gray-900"><?= htmlspecialchars($supplier['created_at'] ?? '') ?></span>
                        </div>
                        <div>
                            <span class="text-gray-500">Người cập nhật gần nhất:</span>
                            <span class="ml-2 font-medium text-gray-900"><?= htmlspecialchars($updatedBy['fullname'] ?? '') ?></span>
                        </div>
                        <div>
                            <span class="text-gray-500">Cập nhật lần cuối:</span>
                            <span class="ml-2 font-medium text-gray-900"><?= htmlspecialchars($supplier['updated_at'] ?? '') ?></span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    </div>
</main>

<?php require_once "./views/components/footer.php"; ?>