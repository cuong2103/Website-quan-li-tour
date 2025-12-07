<?php
require_once "./views/components/header.php";
require_once "./views/components/sidebar.php";
?>
<main class="flex-1 pt-28 overflow-y-auto p-6 bg-gray-50 w-full">

    <div class="w-full mx-auto">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 tracking-tight">Chi tiết khách hàng</h1>
                <p class="text-sm text-gray-500 mt-1">Xem thông tin chi tiết và lịch sử hoạt động</p>
            </div>
            <a href="?act=customers" class="text-gray-500 hover:text-gray-700 flex items-center text-sm font-medium transition-colors">
                <i class="w-4 h-4 mr-1" data-lucide="arrow-left"></i> Quay lại danh sách
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Profile Card -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 text-center border-b border-gray-100 bg-gradient-to-b from-blue-50 to-white">
                        <div class="w-24 h-24 mx-auto bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white text-3xl font-bold shadow-lg mb-4">
                            <?= strtoupper(substr($customer['name'], 0, 1)) ?>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800"><?= htmlspecialchars($customer['name']) ?></h2>
                        <p class="text-gray-500 text-sm mt-1">Khách hàng</p>

                        <div class="mt-4 flex justify-center">
                            <?php
                            $genderClass = 'bg-gray-100 text-gray-600';
                            $genderLabel = 'Khác';
                            if ($customer['gender'] === 'male') {
                                $genderClass = 'bg-blue-50 text-blue-700 border border-blue-100';
                                $genderLabel = 'Nam';
                            } elseif ($customer['gender'] === 'female') {
                                $genderClass = 'bg-pink-50 text-pink-700 border border-pink-100';
                                $genderLabel = 'Nữ';
                            }
                            ?>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium <?= $genderClass ?>">
                                <?= $genderLabel ?>
                            </span>
                        </div>
                    </div>

                    <div class="p-6">
                        <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Thông tin liên hệ</h3>
                        <ul class="space-y-4">
                            <li class="flex items-start">
                                <i class="w-5 h-5 text-gray-400 mr-3 mt-0.5" data-lucide="mail"></i>
                                <div>
                                    <span class="block text-xs text-gray-500">Email</span>
                                    <span class="text-sm text-gray-800 font-medium break-all"><?= htmlspecialchars($customer['email']) ?></span>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <i class="w-5 h-5 text-gray-400 mr-3 mt-0.5" data-lucide="phone"></i>
                                <div>
                                    <span class="block text-xs text-gray-500">Số điện thoại</span>
                                    <span class="text-sm text-gray-800 font-medium"><?= htmlspecialchars($customer['phone']) ?></span>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <i class="w-5 h-5 text-gray-400 mr-3 mt-0.5" data-lucide="map-pin"></i>
                                <div>
                                    <span class="block text-xs text-gray-500">Địa chỉ</span>
                                    <span class="text-sm text-gray-800 font-medium"><?= htmlspecialchars($customer['address']) ?></span>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <div class="p-4 bg-gray-50 text-center border-t border-gray-100">
                        <a href="?act=customer-edit&id=<?= $customer['id'] ?>" class="inline-flex items-center justify-center w-full px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 hover:text-orange-600 transition-colors shadow-sm">
                            <i class="w-4 h-4 mr-2" data-lucide="pencil"></i> Chỉnh sửa hồ sơ
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right Column: Details & History -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Identity Info -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                        <i class="w-5 h-5 mr-2 text-blue-600" data-lucide="credit-card"></i>
                        Giấy tờ tùy thân
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="p-4 bg-gray-50 rounded-lg border border-gray-100">
                            <span class="block text-xs text-gray-500 mb-1">Căn cước công dân (CCCD)</span>
                            <div class="font-mono text-gray-800 font-medium text-lg">
                                <?= !empty($customer['citizen_id']) ? htmlspecialchars($customer['citizen_id']) : '<span class="text-gray-400 italic text-sm">Chưa cập nhật</span>' ?>
                            </div>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-lg border border-gray-100">
                            <span class="block text-xs text-gray-500 mb-1">Hộ chiếu (Passport)</span>
                            <div class="font-mono text-gray-800 font-medium text-lg">
                                <?= !empty($customer['passport']) ? htmlspecialchars($customer['passport']) : '<span class="text-gray-400 italic text-sm">Chưa cập nhật</span>' ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- System Info -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                        <i class="w-5 h-5 mr-2 text-blue-600" data-lucide="info"></i>
                        Thông tin hệ thống
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-8">
                        <div class="flex justify-between items-center py-2 border-b border-gray-50">
                            <span class="text-sm text-gray-500">Người tạo</span>
                            <span class="text-sm font-medium text-gray-800"><?= htmlspecialchars($customer['creator_name'] ?? 'Không rõ') ?></span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-50">
                            <span class="text-sm text-gray-500">Ngày tạo</span>
                            <span class="text-sm font-medium text-gray-800"><?= date('H:i d/m/Y', strtotime($customer['created_at'])) ?></span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-50">
                            <span class="text-sm text-gray-500">Người cập nhật</span>
                            <span class="text-sm font-medium text-gray-800"><?= htmlspecialchars($customer['updater_name'] ?? 'Chưa cập nhật') ?></span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-50">
                            <span class="text-sm text-gray-500">Cập nhật lần cuối</span>
                            <span class="text-sm font-medium text-gray-800">
                                <?= $customer['updated_at'] ? date('H:i d/m/Y', strtotime($customer['updated_at'])) : 'Chưa cập nhật' ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</main>
<?php require_once "./views/components/footer.php"; ?>