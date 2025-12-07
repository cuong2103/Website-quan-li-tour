<?php require_once "./views/components/header.php"; ?>
<?php require_once "./views/components/sidebar.php"; ?>

<main class="flex-1 pt-28 overflow-y-auto p-6">
    <div class="space-y-6 max-w-7xl mx-auto">

        <!-- Header + Nút quay lại -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <button onclick="history.back()" class="p-2 hover:bg-gray-100 rounded-lg transition">
                    <i class="w-6 h-6" data-lucide="chevron-left"></i>
                </button>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Chi tiết Chính sách</h2>
                    <p class="text-sm text-gray-600">Thông tin chi tiết về chính sách</p>
                </div>
            </div>

            <a href="?act=policies" class="flex items-center gap-2 text-gray-500 hover:text-gray-700 text-sm font-medium transition-colors">
                Quay lại <i data-lucide="move-right"></i>
            </a>
        </div>

        <div class="grid grid-cols-4 gap-6">

            <!-- Thông tin chính -->
            <div class="col-span-4 space-y-6">

                <!-- Card thông tin chính sách -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-start gap-5">
                            <div class="w-20 h-20 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i data-lucide="file-text" class="w-10 h-10 text-blue-600"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-2xl font-bold text-gray-900"><?= htmlspecialchars($policy['title']) ?></h3>
                                <div class="flex items-center gap-3 mt-2">
                                    <span class="text-sm text-gray-500">ID: #<?= $policy['id'] ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 space-y-6">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Nội dung</label>
                            <div class="mt-2 prose prose-slate max-w-none">
                                <?= $policy['content'] ?>
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
                            <span class="ml-2 font-medium text-gray-900"><?= htmlspecialchars($policy['created_by']) ?></span>
                        </div>
                        <div>
                            <span class="text-gray-500">Ngày tạo:</span>
                            <span class="ml-2 font-medium text-gray-900">
                                <?php
                                $createdDate = new DateTime($policy['created_at']);
                                echo $createdDate->format('H:i d/m/Y');
                                ?>
                            </span>
                        </div>
                        <div>
                            <span class="text-gray-500">Cập nhật lần cuối:</span>
                            <span class="ml-2 font-medium text-gray-900">
                                <?php
                                if ($policy['updated_at']) {
                                    $updatedDate = new DateTime($policy['updated_at']);
                                    echo $updatedDate->format('H:i d/m/Y');
                                } else {
                                    echo 'Chưa cập nhật';
                                }
                                ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>

<?php require_once "./views/components/footer.php"; ?>