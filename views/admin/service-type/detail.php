<?php require_once './views/components/header.php'; ?>
<?php require_once './views/components/sidebar.php'; ?>

<div class="ml-54 mt-20 p-10 bg-gray-50 min-h-screen">

    <!-- Page Header -->
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-2xl font-bold text-gray-700 tracking-tight">
            Chi tiết loại dịch vụ
        </h2>

        <a href="index.php?act=service-type"
            class="bg-gray-200 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-lg font-medium transition flex items-center gap-2">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
            Quay lại
        </a>
    </div>

    <!-- Content Card -->
    <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100 space-y-8">

        <!-- ===== THÔNG TIN CƠ BẢN ===== -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Tên -->
            <div class="p-4 border rounded-xl hover:shadow transition">
                <h3 class="text-gray-500 font-medium">Tên Loại Dịch vụ</h3>
                <p class="text-gray-900 text-lg font-semibold">
                    <?= htmlspecialchars($serviceType['name']) ?>
                </p>
            </div>

            <!-- ===== MÔ TẢ ===== -->
            <div class="p-4 border rounded-xl hover:shadow transition">
                <h3 class="text-gray-500 font-medium">Mô tả</h3>
                <p class="text-gray-900 leading-relaxed">
                    <?= nl2br(htmlspecialchars($serviceType['description'])) ?>
                </p>
            </div>

            <!-- Người tạo -->
            <div class="p-4 border rounded-xl hover:shadow transition">
                <h3 class="text-gray-500 font-medium">Người tạo</h3>
                <p class="text-gray-900 font-semibold">
                    <?= $serviceType["creator_name"] ?: 'Không xác định' ?>
                </p>
            </div>

            <!-- Ngày tạo -->
            <div class="p-4 border rounded-xl hover:shadow transition">
                <h3 class="text-gray-500 font-medium">Ngày tạo</h3>
                <p class="text-gray-900 font-semibold">
                    <?= date("d/m/Y H:i", strtotime($serviceType["created_at"])) ?>
                </p>
            </div>

            <div class="p-4 border rounded-xl hover:shadow transition">
                <h3 class="text-gray-500 font-medium">Người sửa gần nhất</h3>
                <p class="text-gray-900 font-semibold">
                    <?= $serviceType["updater_name"] ?: 'Chưa từng sửa' ?>
                </p>
            </div>

            <div class="p-4 border rounded-xl hover:shadow transition">
                <h3 class="text-gray-500 font-medium">Thời gian sửa gần nhất</h3>
                <p class="text-gray-900 font-semibold">
                    <?= $serviceType["updated_at"]
                        ? date("d/m/Y H:i", strtotime($serviceType["updated_at"]))
                        : 'Chưa có cập nhật' ?>
                </p>
            </div>
        </div>

    </div>

</div>

<?php require_once './views/components/footer.php'; ?>