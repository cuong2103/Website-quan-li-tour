<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';
?>

<main class="mt-28 px-6 pb-20">

    <!-- Nút quay lại -->
    <div class="flex justify-end mt-4">
        <a href="<?= BASE_URL . '?act=destination' ?>"
            class="px-6 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">Quay lại</a>
    </div>

    <h1 class="text-2xl font-bold mb-6">Chi tiết địa điểm: <?= htmlspecialchars($destination['name'] ?? 'Chưa có') ?></h1>

    <div class="bg-white p-6 rounded-lg shadow mb-6 space-y-2">
        <p><strong>Danh mục:</strong> <?= htmlspecialchars($destination['category_name'] ?? 'Chưa có') ?></p>
        <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($destination['address'] ?? 'Chưa có') ?></p>
        <p><strong>Mô tả:</strong> <?= nl2br(htmlspecialchars($destination['description'] ?? 'Chưa có')) ?></p>
        <p><strong>Người tạo:</strong> <?= htmlspecialchars($destination['created_by'] ?? 'Chưa có') ?></p>
        <p><strong>Ngày tạo:</strong> <?= htmlspecialchars($destination['created_at'] ?? 'Chưa có') ?></p>
        <p><strong>Cập nhật lần cuối:</strong> <?= htmlspecialchars($destination['updated_at'] ?? 'Chưa có') ?></p>
    </div>

    <!-- Ảnh địa điểm -->
    <?php if (!empty($images)): ?>
        <div class="bg-white p-6 rounded-lg shadow mb-6">
            <h2 class="text-lg font-semibold mb-2">Ảnh địa điểm</h2>
            <div class="flex flex-wrap gap-4">
                <?php foreach ($images as $img): ?>
                    <div class="w-32 h-24 relative">
                        <img src="<?= BASE_URL . 'uploads/destinations_image/' . $img['image_url'] ?>"
                            class="w-full h-full object-cover rounded-lg border">
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Tour liên quan -->
    <div class="bg-white p-6 rounded-lg shadow mb-6">
        <h2 class="text-lg font-semibold mb-2">Tour liên quan</h2>
        <?php if (!empty($relatedTours)): ?>
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-300">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-3 py-2 border">Tên tour</th>
                            <th class="px-3 py-2 border">Giá người lớn</th>
                            <th class="px-3 py-2 border">Giá trẻ em</th>
                            <th class="px-3 py-2 border">Giới thiệu</th>
                            <th class="px-3 py-2 border">Ngày tạo</th>
                            <th class="px-3 py-2 border">Cập nhật</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($relatedTours as $tour): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-2 border"><?= htmlspecialchars($tour['name']) ?></td>
                                <td class="px-3 py-2 border"><?= number_format($tour['adult_price'] ?? 0) ?> đ</td>
                                <td class="px-3 py-2 border"><?= number_format($tour['child_price'] ?? 0) ?> đ</td>
                                <td class="px-3 py-2 border"><?= htmlspecialchars($tour['introduction'] ?? '') ?></td>
                                <td class="px-3 py-2 border"><?= isset($tour['created_at']) ? date('d/m/Y', strtotime($tour['created_at'])) : '' ?></td>
                                <td class="px-3 py-2 border"><?= isset($tour['updated_at']) ? date('d/m/Y', strtotime($tour['updated_at'])) : '' ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>Chưa có tour liên quan.</p>
        <?php endif; ?>
    </div>


</main>

<?php
require_once './views/components/footer.php';
?>