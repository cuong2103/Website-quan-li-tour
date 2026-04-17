<?php
require_once "./views/components/header.php";
require_once "./views/components/sidebar.php";
?>

<main class="flex-1 pt-28 overflow-y-auto p-6">
    <div class="space-y-6">

        <!-- TIÊU ĐỀ CHÍNH -->
        <div class="flex items-center justify-between mb-2">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Quản lý Chính sách</h2>
                <p class="text-sm text-gray-600">Tạo và quản lý các chính sách áp dụng cho tour (huỷ tour, trẻ em, thanh toán...)</p>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-6">

            <!-- FORM THÊM CHÍNH SÁCH -->
            <div class="xl:col-span-1">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">

                    <h2 class="text-lg font-medium text-gray-900 mb-6">Thêm Chính Sách Mới</h2>

                    <form action="<?= BASE_URL ?>?act=policy-store" method="POST">

                        <!-- Tiêu đề -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Tiêu đề <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="title" value="<?= htmlspecialchars($old['title'] ?? '') ?>"
                                class="w-full px-3 py-2 border <?= !empty($errors['title']) ? 'border-red-500 bg-red-50' : 'border-gray-300' ?> rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Ví dụ: Chính sách huỷ tour, Chính sách trẻ em...">
                            <?php if (!empty($errors['title'])): ?>
                                <div class="text-red-500 text-sm mt-1"><?= is_array($errors['title']) ? $errors['title'][0] : $errors['title'] ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Nội dung -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Nội dung <span class="text-red-500">*</span>
                            </label>
                            <textarea name="content" rows="5"
                                class="w-full px-3 py-2 border <?= !empty($errors['content']) ? 'border-red-500 bg-red-50' : 'border-gray-300' ?> rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Nhập nội dung chi tiết của chính sách..."><?= htmlspecialchars($old['content'] ?? '') ?></textarea>
                            <?php if (!empty($errors['content'])): ?>
                                <div class="text-red-500 text-sm mt-1"><?= is_array($errors['content']) ? $errors['content'][0] : $errors['content'] ?></div>
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
                            <i data-lucide="plus"></i>
                            Lưu Chính Sách
                        </button>

                    </form>

                </div>
            </div>

            <!-- DANH SÁCH CHÍNH SÁCH -->
            <div class="col-span-2">
                <div class="bg-white rounded-xl border shadow-sm">

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
                                            <?= htmlspecialchars($poli['title'] ?? '') ?>
                                        </h4>

                                        <div class="text-gray-700 text-sm mt-1 leading-relaxed">
                                            <?= nl2br(htmlspecialchars($poli['content'] ?? '')) ?>
                                        </div>

                                        <p class="text-xs text-gray-400 mt-2">
                                            Tạo ngày: <?= $poli['created_at'] ?>
                                        </p>
                                    </div>

                                    <!-- ACTION BUTTONS -->
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="?act=policy-edit&id=<?= $poli['id'] ?>"
                                            class="flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors"
                                            title="Sửa">
                                            <i class="w-4 h-4" data-lucide="square-pen"></i>
                                            <span class="text-xs font-semibold">Sửa</span>
                                        </a>
                                        <a href="?act=policy-detail&id=<?= $poli['id'] ?>"
                                            class="flex items-center gap-1.5 px-3 py-1.5 bg-green-50 text-green-600 rounded-lg hover:bg-green-100 transition-colors"
                                            title="Chi tiết">
                                            <i class="w-4 h-4" data-lucide="eye"></i>
                                            <span class="text-xs font-semibold">Chi tiết</span>
                                        </a>
                                        <a href="?act=policy-delete&id=<?= $poli['id'] ?>"
                                            onclick="return confirm('Bạn có chắc muốn xoá không?')"
                                            class="flex items-center gap-1.5 px-3 py-1.5 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors"
                                            title="Xóa">
                                            <i class="w-4 h-4" data-lucide="trash-2"></i>
                                            <span class="text-xs font-semibold">Xóa</span>
                                        </a>
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