<?php
require_once "./views/components/header.php";
require_once "./views/components/sidebar.php";
?>

<main class="flex-1 mt-28 overflow-y-auto p-6">
    <div class="space-y-6">
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <button onclick="history.back()" class="p-2 hover:bg-gray-100 rounded-lg transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Nhà Cung Cấp</h2>
                    <p class="text-sm text-gray-600">Thông tin về nhà cung cấp</p>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-3 gap-6">
            <div class="xl:col-span-1">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">

                    <h2 class="text-lg font-medium text-gray-900 mb-6">Thêm Nhà Cung Cấp Mới</h2>

                    <form action="<?= BASE_URL ?>?act=supplier-create" method="POST">
                        <!-- Tên nhà cung cấp -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tên nhà cung cấp <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="<?= $_POST['name'] ?? '' ?>"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Ví dụ: Công ty ABC, Nhà hàng A"><?php if (!empty($errors['name'])): ?>
                                <div class="text-red-500"><?= $errors['name'][0] ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                            <input type="text" name="email" value="<?= $_POST['email'] ?? '' ?>"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Ví dụ: abc123@gmail.com"><?php if (!empty($errors['email'])): ?>
                                <div class="text-red-500"><?= $errors['email'][0] ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Số điện thoại <span class="text-red-500">*</span></label>
                            <input type="text" name="phone" value="<?= $_POST['phone'] ?? '' ?>"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Ví dụ: 0123456789"><?php if (!empty($errors['phone'])): ?>
                                <div class="text-red-500"><?= $errors['phone'][0] ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Trạng Thái <span class="text-red-500">*</span></label>
                            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="1" <?= (isset($_POST['status']) && $_POST['status'] === '1') ? 'selected' : '' ?>>Hoạt động</option>
                                <option value="0" <?= (isset($_POST['status']) && $_POST['status'] === '0') ? 'selected' : '' ?>>Ngừng hoạt động</option>
                            </select>
                            <?php if (!empty($errors['status'])): ?>
                                <div class="text-red-500"><?= $errors['status'][0] ?></div>
                            <?php endif; ?>
                        </div>


                        <!-- Địa điểm -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Chọn địa điểm</label>
                            <select name="destination_id"
                                class="w-full px-3 py-2  border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Chọn địa điểm</option>
                                <?php foreach ($destinations as $destination): ?>
                                    <option value="<?= $destination['id'] ?>" <?= (isset($_POST['destination_id']) && $_POST['destination_id'] == $destination['id']) ? 'selected' : '' ?>><?= $destination['name'] ?></option>
                                <?php endforeach; ?>

                            </select>
                            <p class="text-xs text-gray-500 mt-1">Địa điểm</p>
                            <?php if (!empty($errors['destination_id'])): ?>
                                <div class="text-red-500"><?= $errors['destination_id'][0] ?></div>
                            <?php endif; ?>

                        </div>


                        <!-- Nút lưu -->
                        <button type="submit"
                            class="w-full bg-orange-400 text-white py-3 rounded-md font-medium hover:bg-orange-500 transition flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Lưu Nhà Cung Cấp
                        </button>
                    </form>

                </div>
            </div>
            <div class="lg:col-span-2">
                <div class="bg-card text-card-foreground flex flex-col gap-6 rounded-xl border">
                    <div class=" grid auto-rows-min items-start gap-1.5 px-6 pt-6 pb-6">
                        <h4 class="leading-none">Danh sách Nhà cung cấp (<?= count($suppliers) ?>)</h4>
                    </div>
                    <div class="px-6 pb-6">
                        <div class="space-y-3">
                            <?php foreach ($suppliers as $supplier): ?>
                                <div class="p-4 border rounded-lg hover:shadow-md transition-shadow border-gray-200">
                                    <div class="flex items-start gap-4">
                                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0"><i class="w-4 h-4" data-lucide="building-2"></i></div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-start justify-between gap-3">
                                                <div>
                                                    <h4 class="text-gray-900"><?= $supplier['name'] ?></h4>
                                                    <div class="mt-1">
                                                        <span class="inline-flex items-center justify-center rounded-md border px-2 py-0.5 text-xs font-medium w-fit whitespace-nowrap shrink-0 <?= $supplier['status'] == 1 ? 'bg-green-100 text-green-800 border-green-200' : 'bg-gray-100 text-gray-800 border-gray-300' ?>">
                                                            <?= $supplier['status'] == 1 ? 'Hoạt động' : 'Tạm dừng' ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-3 space-y-1 text-sm">
                                                <div class="flex items-center gap-2 text-gray-600"><i class="w-4 h-4" data-lucide="mail"></i>
                                                    <path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7"></path>
                                                    <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                                                    </svg><?= ($supplier['email']) ?>
                                                </div>
                                                <div class="flex items-center gap-2 text-gray-600"><i class="w-4 h-4" data-lucide="phone"></i><?= ($supplier['phone']) ?></div>
                                            </div>
                                            <div class="flex items-center gap-4 mt-2 text-xs text-gray-500"><span>📍 <?= ($supplier['destination_id']) ?></span><span>🔧 <?= ($supplier['created_by']) ?> dịch vụ</span></div>
                                        </div>
                                        <div class="flex gap-2 flex-shrink-0">
                                            <a href="?act=supplier-edit&id=<?= $supplier['id'] ?>" class="inline-flex items-center justify-center  disabled:opacity-50 gap-1.5 px-3 "><i class="w-4 h-4" data-lucide="square-pen"></i></a>
                                            <a href="?act=supplier-detail&id=<?= $supplier['id'] ?>"

                                                class="inline-flex items-center justify-center gap-1.5 px-3 has-[>svg]:px-2.5">

                                                <!-- Icon con mắt -->
                                                <i class="w-4 h-4" data-lucide="eye"></i>

                                            </a>

                                            <a href="?act=supplier-delete&id=<?= $supplier['id'] ?>" onclick="return confirm('Bạn có chắc muốn xoá không?')" class="inline-flex items-center justify-center gap-1.5 px-3 has-[&gt;svg]:px-2.5"><span class="text-red-600"><i class="w-4 h-4" data-lucide="trash-2"></i></span></a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once "./views/components/footer.php"; ?>