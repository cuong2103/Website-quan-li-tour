<?php require_once './views/components/header.php'; ?>
<?php require_once './views/components/sidebar.php'; ?>

<?php
// Map đơn vị tính
$units = [
    'person' => 'Người',
    'room' => 'Phòng',
    'vehicle' => 'Chuyến',
    'day' => 'Ngày',
    'meal' => 'Suất ăn'
];
?>

<div class="ml-54 pt-28 p-6">

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-semibold text-gray-800">Chi tiết dịch vụ</h2>
            <p class="text-gray-500 text-sm">Thông tin chi tiết và lịch sử cập nhật</p>
        </div>

        <div class="flex gap-3">
            <a href="?act=service"
                class="px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition flex items-center gap-2 text-sm font-medium">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Quay lại
            </a>
            <a href="?act=service-edit&id=<?= $service['id'] ?>"
                class="px-4 py-2 rounded-lg bg-orange-500 text-white hover:bg-orange-600 transition flex items-center gap-2 text-sm font-medium shadow-sm">
                <i data-lucide="pencil" class="w-4 h-4"></i>
                Chỉnh sửa
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Cột trái: Thông tin chính -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Card Thông tin cơ bản -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center gap-2">
                    <i data-lucide="info" class="w-5 h-5 text-blue-500"></i>
                    <h3 class="font-semibold text-gray-800">Thông tin cơ bản</h3>
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Tên dịch vụ</label>
                            <div class="text-base font-semibold text-gray-900"><?= $service['name'] ?></div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Loại dịch vụ</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-gray-100 text-gray-700">
                                <?= $service['service_type_name'] ?>
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Giá dự kiến</label>
                            <div class="text-base font-bold text-orange-600"><?= number_format($service['estimated_price']) ?> ₫</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Đơn vị tính</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-700">
                                <?= $units[$service['unit'] ?? 'person'] ?? 'Người' ?>
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Nhà cung cấp</label>
                            <div class="flex items-center gap-2 text-gray-900 font-medium">
                                <i data-lucide="building-2" class="w-4 h-4 text-gray-400"></i>
                                <?= $service['supplier_name'] ?>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-2">Mô tả chi tiết</label>
                        <div class="p-4 bg-gray-50 rounded-lg text-gray-700 leading-relaxed border border-gray-100">
                            <?= nl2br($service['description']) ?>
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
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide">Người tạo</label>
                            <div class="font-medium text-gray-900 text-sm"><?= $service['creator_name'] ?? 'N/A' ?></div>
                            <div class="text-xs text-gray-400 mt-0.5"><?= $service['created_at'] ?></div>
                        </div>
                    </div>

                    <div class="border-t border-gray-100"></div>

                    <!-- Người cập nhật -->
                    <div class="flex items-start gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 uppercase tracking-wide">Cập nhật lần cuối</label>
                            <div class="font-medium text-gray-900 text-sm"><?= $service['updater_name'] ?? 'N/A' ?></div>
                            <div class="text-xs text-gray-400 mt-0.5"><?= $service['updated_at'] ?? 'Chưa cập nhật' ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php require_once './views/components/footer.php'; ?>