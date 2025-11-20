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

    <h1 class="text-2xl font-bold mb-6">Chi tiết địa điểm: <?= $destination['name'] ?></h1>

    <!-- Thông tin cơ bản -->
    <div class="bg-white p-6 rounded-lg shadow mb-6 space-y-2">
        <p><strong>Quốc gia:</strong> <?= $destination['country_name'] ?></p>
        <p><strong>Danh mục:</strong> <?= $destination['category_name'] ?></p>
        <p><strong>Địa chỉ:</strong> <?= $destination['address'] ?></p>
        <p><strong>Mô tả:</strong> <?= nl2br($destination['description']) ?></p>
        <p><strong>Người tạo:</strong> <?= $destination['created_by'] ?></p>
        <p><strong>Ngày tạo:</strong> <?= $destination['created_at'] ?></p>
        <p><strong>Cập nhật lần cuối:</strong> <?= $destination['updated_at'] ?></p>
    </div>

    <!-- Ảnh địa điểm -->
    <?php if(!empty($images)): ?>
    <div class="bg-white p-6 rounded-lg shadow mb-6">
        <h2 class="text-lg font-semibold mb-2">Ảnh địa điểm</h2>
        <div class="flex flex-wrap gap-4">
            <?php foreach($images as $img): ?>
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
        <?php if(!empty($tours)): ?>
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
                    <?php foreach($tours as $tour): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 py-2 border"><?= $tour['name'] ?></td>
                            <td class="px-3 py-2 border"><?= $tour['adult_price'] ?> đ</td>
                            <td class="px-3 py-2 border"><?= $tour['child_price'] ?> đ</td>
                            <td class="px-3 py-2 border"><?= $tour['introduction'] ?></td>
                            <td class="px-3 py-2 border"><?= $tour['created_at'] ?></td>
                            <td class="px-3 py-2 border"><?= $tour['updated_at'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
            <p>Chưa có tour liên quan.</p>
        <?php endif; ?>
    </div>

    <!-- Nhà cung cấp & dịch vụ -->
    <div class="bg-white p-6 rounded-lg shadow mb-6">
        <h2 class="text-lg font-semibold mb-2">Nhà cung cấp & dịch vụ</h2>
        <?php if(!empty($suppliers)): ?>
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-300">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-3 py-2 border">Nhà cung cấp</th>
                        <th class="px-3 py-2 border">Email</th>
                        <th class="px-3 py-2 border">Điện thoại</th>
                        <th class="px-3 py-2 border">Dịch vụ</th>
                        <th class="px-3 py-2 border">Loại</th>
                        <th class="px-3 py-2 border">Giá</th>
                        <th class="px-3 py-2 border">Tạo</th>
                        <th class="px-3 py-2 border">Cập nhật</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($suppliers as $s): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 py-2 border"><?= $s['supplier_name'] ?></td>
                            <td class="px-3 py-2 border"><?= $s['email'] ?></td>
                            <td class="px-3 py-2 border"><?= $s['phone'] ?></td>
                            <td class="px-3 py-2 border"><?= $s['service_name'] ?></td>
                            <td class="px-3 py-2 border"><?= $s['type'] ?></td>
                            <td class="px-3 py-2 border"><?= $s['price'] ?> đ</td>
                            <td class="px-3 py-2 border"><?= $s['created_at'] ?></td>
                            <td class="px-3 py-2 border"><?= $s['updated_at'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
            <p>Chưa có nhà cung cấp hoặc dịch vụ.</p>
        <?php endif; ?>
    </div>

</main>

<?php
require_once './views/components/footer.php';
?>
