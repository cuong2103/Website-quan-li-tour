<?php
require_once "./views/components/header.php";
require_once "./views/components/sidebar.php";
?>

<main class="flex-1 pt-28 overflow-y-auto p-6">
    <div class="space-y-6">

        <!-- TIÊU ĐỀ -->
        <div class="flex items-center gap-4">
            <button onclick="history.back()" class="p-2 hover:bg-gray-100 rounded-lg transition">
                <i data-lucide="chevron-left" class="w-6 h-6"></i>
            </button>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Chỉnh sửa Chính sách</h2>
                <p class="text-sm text-gray-600">Cập nhật thông tin của chính sách đang áp dụng cho tour.</p>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-8">

            <!-- FORM CHỈNH SỬA (BÊN TRÁI) -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 col-span-1">

                <form action="<?= BASE_URL ?>?act=policy-update&id=<?= $policy['id'] ?>" method="POST">

                    <!-- Tiêu đề -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Tiêu đề <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="text"
                            name="title"

                            placeholder="Nhập tiêu đề chính sách..."

                            value="<?= htmlspecialchars($policy['title'] ?? $_POST['title'] ?? '') ?>"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md 
                           focus:outline-none focus:ring-2 focus:ring-blue-500">

                        <?php if (!empty($errors['title'])): ?>
                            <div class="text-red-500 text-sm mt-1"><?= $errors['title'][0] ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Nội dung -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Nội dung <span class="text-red-500">*</span>
                        </label>

                        <textarea
                            name="content"
                            rows="6"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md 
                           focus:outline-none focus:ring-2 focus:ring-blue-500"><?= htmlspecialchars($policy['content'] ?? $_POST['content'] ?? '') ?></textarea>

                        <?php if (!empty($errors['content'])): ?>
                            <div class="text-red-500 text-sm mt-1"><?= $errors['content'][0] ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Gợi ý -->
                    <div class="p-4 bg-blue-50 rounded-lg text-sm text-gray-700 mb-4">
                        <p class="font-medium mb-1">💡 Gợi ý:</p>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Nên trình bày nội dung theo từng điểm</li>
                            <li>Diễn đạt rõ ràng và dễ hiểu</li>
                            <li>Cập nhật khi có thay đổi mới</li>
                        </ul>
                    </div>

                    <!-- Nút -->
                    <div class="flex items-center gap-3">
                        <a href="<?= BASE_URL ?>?act=policies"
                            class="px-4 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-md transition-colors">
                            Quay lại
                        </a>

                        <button type="submit"
                            class="px-6 py-3 bg-orange-500 text-white rounded-md hover:bg-orange-600 transition flex items-center gap-2">
                            <i data-lucide="save" class="w-5 h-5"></i>
                            Cập nhật Chính sách
                        </button>
                    </div>

                </form>

            </div>

            <!-- DANH SÁCH (BÊN PHẢI) -->
            <div class="bg-white rounded-xl border shadow-sm col-span-2">

                <div class="px-6 pt-6 pb-3">
                    <h4 class="text-lg font-medium">Danh sách Chính sách (<?= count($policies) ?>)</h4>
                </div>

                <div class="px-6 pb-6 space-y-3">
                    <?php foreach ($policies as $poli): ?>
                        <div class="p-4 border rounded-lg hover:shadow-md transition">
                            <div class="flex items-start gap-4">

                                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i data-lucide="file-text" class="w-5 h-5 text-purple-600"></i>
                                </div>

                                <div class="flex-1 min-w-0">
                                    <h4 class="font-medium text-gray-900">
                                        <?= htmlspecialchars($poli['title']) ?>
                                    </h4>

                                    <div class="text-gray-700 text-sm mt-1 leading-relaxed">
                                        <?= nl2br(htmlspecialchars($poli['content'])) ?>
                                    </div>

                                    <p class="text-xs text-gray-400 mt-2">
                                        Tạo ngày: <?= $poli['created_at'] ?>
                                    </p>
                                </div>

                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

            </div>

        </div>

    </div>
</main>

<?php require_once "./views/components/footer.php"; ?>