<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';
?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<main class="mt-28 px-6 pb-20 overflow-auto scrollbar-hide">

    <!-- Ti ÊU ĐỀ + NÚT TẠO -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Quản lí địa điểm</h1>
            <p class="text-sm text-gray-600">Danh sách các điểm đến du lịch</p>
        </div>
        <div class="flex space-x-3">
            <button class="px-5 py-2.5  text-white text-sm font-medium rounded-lg bg-orange-400 hover:bg-orange-500 flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Thêm địa điểm</span>
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <?php foreach ($listDestination as $item): ?>

            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition">

                <!-- Icon + Tên địa điểm -->
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center">

                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M16.6667 8.33341C16.6667 12.4942 12.0509 16.8276 10.5009 18.1659C10.3565 18.2745 10.1807 18.3332 10 18.3332C9.81938 18.3332 9.6436 18.2745 9.49921 18.1659C7.94921 16.8276 3.33337 12.4942 3.33337 8.33341C3.33337 6.5653 4.03575 4.86961 5.286 3.61937C6.53624 2.36913 8.23193 1.66675 10 1.66675C11.7682 1.66675 13.4638 2.36913 14.7141 3.61937C15.9643 4.86961 16.6667 6.5653 16.6667 8.33341Z" stroke="#155DFC" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M10 10.8333C11.3807 10.8333 12.5 9.71396 12.5 8.33325C12.5 6.95254 11.3807 5.83325 10 5.83325C8.61929 5.83325 7.5 6.95254 7.5 8.33325C7.5 9.71396 8.61929 10.8333 10 10.8333Z" stroke="#155DFC" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>

                    </div>

                    <div>
                        <h2 class="text-lg font-semibold"><?= htmlspecialchars($item['name']) ?></h2>
                        <p class="text-gray-500 text-sm">
                            <?= htmlspecialchars($item['country_name'] ?? 'Không có') ?>
                        </p>
                    </div>
                </div>

                <!-- Danh mục -->
                <div class="mt-4">
                    <p class="text-gray-500 text-sm">Danh mục:</p>
                    <span class="inline-block bg-gray-100 text-gray-700 px-3 py-1 rounded-lg text-xs">
                        <?= htmlspecialchars($item['category_name'] ?? 'Không có') ?>
                    </span>
                </div>

                <!-- Số tour -->
                <div class="mt-4">
                    <p class="text-gray-500 text-sm">Số tour:</p>
                    <p class="text-lg font-semibold"><?= $item['tour_count'] ?? 0 ?></p>
                </div>

                <!-- Buttons -->
                <div class="flex justify-between items-center mt-5">

                    <a href=""
                        class="flex items-center gap-1 px-4 py-2 border rounded-lg hover:bg-gray-50 text-sm">
                        <i class="bi bi-pencil"></i> Sửa
                    </a>

                    <a href=""
                        class="text-blue-600 text-sm hover:underline">
                        Xem chi tiết
                    </a>

                </div>

            </div>

        <?php endforeach; ?>

    </div>
</main>
<?php
require_once './views/components/footer.php';
?>