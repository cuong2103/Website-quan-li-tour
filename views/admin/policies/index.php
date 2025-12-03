<?php
require_once "./views/components/header.php";
require_once "./views/components/sidebar.php";
?>

<main class="flex-1 mt-28 overflow-y-auto p-6">
    <div class="space-y-6">

        <!-- TIÊU ĐỀ CHÍNH -->
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 mb-2">Quản lý Chính sách</h1>
            <p class="text-gray-500">
                Tạo và quản lý các chính sách áp dụng cho tour (huỷ tour, trẻ em, thanh toán...)
            </p>
        </div>

        <div class="grid grid-cols-3 gap-6">

            <!-- FORM THÊM CHÍNH SÁCH -->
            <div class="xl:col-span-1">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">

                    <h2 class="text-lg font-medium text-gray-900 mb-6">Thêm Chính Sách Mới</h2>

                    <form action="<?= BASE_URL ?>?act=policies-store" method="POST">

                        <!-- Tiêu đề -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Tiêu đề <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="title"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Ví dụ: Chính sách huỷ tour, Chính sách trẻ em...">
                            <?php if (!empty($errors['title'])): ?>
                                <div class="text-red-500"><?= $errors['title'][0] ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Nội dung -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Nội dung <span class="text-red-500">*</span>
                            </label>
                            <textarea name="content" rows="5"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Nhập nội dung chi tiết của chính sách..."></textarea>
                            <?php if (!empty($errors['content'])): ?>
                                <div class="text-red-500"><?= $errors['content'][0] ?></div>
                            <?php endif; ?>

                        </div>

                        <!-- GỢI Ý -->
                        <div class="p-4 bg-blue-50 rounded-lg text-sm text-gray-700 mb-4">
                            <p class="font-medium mb-1">💡 Gợi ý viết chính sách:</p>
                            <ul class="list-disc list-inside space-y-1">
                                <li>Viết ngắn gọn, rõ ràng</li>
                                <li>Chia thành các điểm cụ thể</li>
                                <li>Nêu rõ điều kiện áp dụng</li>
                                <li>Cập nhật thường xuyên khi có thay đổi</li>
                            </ul>
                        </div>

                        <!-- Nút lưu -->
                        <button type="submit"
                            class="w-full bg-orange-500 text-white py-3 rounded-md font-medium hover:bg-orange-600 transition flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Lưu Chính Sách
                        </button>

                    </form>

                </div>
            </div>

            <!-- DANH SÁCH CHÍNH SÁCH -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl border shadow-sm">

                    <div class="px-6 pt-6 pb-3">
                        <h4 class="text-lg font-medium">Danh sách Chính sách (<?= count($policies) ?>)</h4>
                    </div>

                    <div class="px-6 pb-6 space-y-3">
                        <?php foreach ($policies as $poli): ?>
                            <div class="p-4 border rounded-lg hover:shadow-md transition">
                                <div class="flex items-start gap-4">

                                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                        <i data-lucide="file-text" class="w-5 h-5 text-purple-600"></i>
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <div class="flex justify-between">

                                            <div>
                                                <h4 class="font-medium text-gray-900">
                                                    <?= htmlspecialchars($poli['title']) ?>
                                                </h4>

                                                <ul class="text-gray-700 text-sm mt-1 leading-relaxed">
                                                    <?= nl2br(htmlspecialchars($poli['content'])) ?>
                                                </ul>

                                                <p class="text-xs text-gray-400 mt-2">
                                                    Tạo ngày: <?= $poli['created_at'] ?>
                                                </p>
                                            </div>

                                            <!-- ACTION BUTTONS -->
                                            <div class="flex gap-2">
                                                <a href="?act=policies-edit&id=<?= $poli['id'] ?>"
                                                    class="p-2 hover:bg-gray-100 rounded">
                                                    <i data-lucide="square-pen" class="w-4 h-4"></i>
                                                </a>

                                                <a href="?act=policies-detail&id=<?= $poli['id'] ?>"
                                                    class="p-2 hover:bg-gray-100 rounded">
                                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                                </a>

                                                <a href="?act=policies-delete&id=<?= $poli['id'] ?>"
                                                    onclick="return confirm('Bạn có chắc muốn xoá không?')"
                                                    class="p-2 hover:bg-red-50 rounded">
                                                    <i data-lucide="trash-2" class="w-4 h-4 text-red-600"></i>
                                                </a>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                </div>
            </div>

        </div>
    </div>
</main>

<?php require_once "./views/components/footer.php"; ?>