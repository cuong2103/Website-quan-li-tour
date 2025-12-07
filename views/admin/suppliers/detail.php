<?php require_once "./views/components/header.php"; ?>
<?php require_once "./views/components/sidebar.php"; ?>

<div class="ml-54 pt-28 p-6">

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-semibold text-gray-800">Chi tiết Nhà Cung Cấp</h2>
            <p class="text-gray-500 text-sm">Thông tin chi tiết và lịch sử hoạt động</p>
        </div>

        <div class="flex gap-3">
            <a href="?act=suppliers"
                class="px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition flex items-center gap-2 text-sm font-medium">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Quay lại
            </a>
            <a href="?act=supplier-edit&id=<?= $supplier['id'] ?>"
                class="px-4 py-2 rounded-lg bg-orange-500 text-white hover:bg-orange-600 transition flex items-center gap-2 text-sm font-medium shadow-sm">
                <i data-lucide="pencil" class="w-4 h-4"></i>
                Chỉnh sửa
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Cột trái: Thông tin chính -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Card Thông tin -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center gap-2">
                    <i data-lucide="building-2" class="w-5 h-5 text-blue-500"></i>
                    <h3 class="font-semibold text-gray-800">Thông tin chung</h3>
                </div>
                <div class="p-6">
                    <div class="flex items-start gap-6 mb-6">
                        <div class="w-20 h-20 bg-purple-100 rounded-xl flex items-center justify-center shrink-0">
                            <i data-lucide="building" class="w-10 h-10 text-purple-600"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900"><?= htmlspecialchars($supplier['name']) ?></h3>
                            <div class="flex items-center gap-3 mt-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $supplier['status'] == 1 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                                    <?= $supplier['status'] == 1 ? 'Đang hoạt động' : 'Ngừng hoạt động' ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Email liên hệ</label>
                            <div class="mt-1 text-gray-900 flex items-center gap-2">
                                <i data-lucide="mail" class="w-4 h-4 text-gray-400"></i>
                                <?= htmlspecialchars($supplier['email']) ?>
                            </div>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-500">Số điện thoại</label>
                            <div class="mt-1 text-gray-900 flex items-center gap-2">
                                <i data-lucide="phone" class="w-4 h-4 text-gray-400"></i>
                                <?= htmlspecialchars($supplier['phone']) ?>
                            </div>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-500">Địa điểm</label>
                            <div class="mt-1 text-gray-900 flex items-center gap-2">
                                <i data-lucide="map-pin" class="w-4 h-4 text-gray-400"></i>
                                <?= htmlspecialchars($supplier['destination_name'] ?? 'Chưa cập nhật') ?>
                            </div>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-500">Tổng dịch vụ</label>
                            <div class="mt-1 text-gray-900 font-semibold text-lg flex items-center gap-2">
                                <i data-lucide="package" class="w-4 h-4 text-gray-400"></i>
                                <?= count($services) ?> dịch vụ
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cột phải: Thông tin hệ thống -->
        <div class="space-y-6">
            <!-- Card System Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center gap-2">
                    <i data-lucide="activity" class="w-5 h-5 text-purple-500"></i>
                    <h3 class="font-semibold text-gray-800">Thông tin hệ thống</h3>
                </div>
                <div class="p-6 space-y-6">
                    <!-- Người tạo -->
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 font-bold text-sm">
                            <?= substr($supplier['creator_name'] ?? 'U', 0, 1) ?>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide">Người tạo</label>
                            <div class="font-medium text-gray-900 text-sm"><?= $supplier['creator_name'] ?? 'N/A' ?></div>
                            <div class="text-xs text-gray-400 mt-0.5"><?= $supplier['created_at'] ?></div>
                        </div>
                    </div>

                    <div class="border-t border-gray-100"></div>

                    <!-- Người cập nhật -->
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 font-bold text-sm">
                            <?= substr($supplier['updater_name'] ?? 'U', 0, 1) ?>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide">Cập nhật lần cuối</label>
                            <div class="font-medium text-gray-900 text-sm"><?= $supplier['updater_name'] ?? 'N/A' ?></div>
                            <div class="text-xs text-gray-400 mt-0.5"><?= $supplier['updated_at'] ?? 'Chưa cập nhật' ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php require_once "./views/components/footer.php"; ?>