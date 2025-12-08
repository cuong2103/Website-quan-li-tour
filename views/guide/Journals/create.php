<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';

$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];
unset($_SESSION['errors'], $_SESSION['old']);
?>

<main class="pt-28 px-6  min-w-7xl mx-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-4 mb-6">
            <button onclick="history.back()" class="p-2 hover:bg-gray-100 rounded-lg transition">
                <i data-lucide="chevron-left" class="w-6 h-6"></i>
            </button>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Viết nhật ký mới</h2>
                <p class="text-sm text-gray-600">Ghi lại hành trình và sự cố trong tour của bạn</p>
            </div>
        </div>
        <button onclick="history.back()"
            class="flex items-center gap-2 text-gray-600 hover:text-gray-900 transition-colors bg-white px-4 py-2 rounded-lg border border-gray-200 shadow-sm hover:bg-gray-50">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Quay lại</span>
        </button>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
        <form action="<?= BASE_URL . '?act=journal-store' ?>"
            method="POST"
            enctype="multipart/form-data"
            class="p-8">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Tour assignment -->
                <div class="col-span-1">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Phân công tour <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select name="tour_assignment_id"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all appearance-none bg-white">
                            <option value="">-- Chọn tour --</option>
                            <?php foreach ($tourAssignments as $ta): ?>
                                <option value="<?= $ta['id'] ?>" <?= (isset($selected_tour_id) && $selected_tour_id == $ta['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($ta['tour_name']) ?> - <?= $ta['booking_code'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-gray-500">
                            <i data-lucide="chevron-down" class="w-4 h-4"></i>
                        </div>
                    </div>
                    <?php if (!empty($errors['tour_assignment_id'])): ?>
                        <p class="text-red-500 text-sm mt-1 flex items-center gap-1">
                            <i data-lucide="alert-circle" class="w-3 h-3"></i>
                            <?= implode(', ', $errors['tour_assignment_id']) ?>
                        </p>
                    <?php endif; ?>
                </div>

                <!-- Date -->
                <div class="col-span-1">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Ngày ghi nhận <span class="text-red-500">*</span></label>
                    <input type="date" name="date"
                        value="<?= $old['date'] ?? $now ?>"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all <?= isset($errors['date']) ? 'border-red-500 ring-1 ring-red-500' : '' ?>">
                    <?php if (!empty($errors['date'])): ?>
                        <p class="text-red-500 text-sm mt-1 flex items-center gap-1">
                            <i data-lucide="alert-circle" class="w-3 h-3"></i>
                            <?= implode(', ', $errors['date']) ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Type -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Loại nhật ký <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-2 gap-4">
                    <label class="cursor-pointer">
                        <input type="radio" name="type" value="daily" class="peer sr-only" <?= ($old['type'] ?? '') == 'daily' ? 'checked' : '' ?>>
                        <div class="p-4 rounded-lg border border-gray-200 peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:bg-gray-50 transition-all flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                                <i data-lucide="book-open" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <span class="block font-medium text-gray-900">Nhật ký ngày</span>
                                <span class="block text-xs text-gray-500">Ghi lại hoạt động hàng ngày</span>
                            </div>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="type" value="incident" class="peer sr-only" <?= ($old['type'] ?? '') == 'incident' ? 'checked' : '' ?>>
                        <div class="p-4 rounded-lg border border-gray-200 peer-checked:border-red-500 peer-checked:bg-red-50 hover:bg-gray-50 transition-all flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center">
                                <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <span class="block font-medium text-gray-900">Sự cố</span>
                                <span class="block text-xs text-gray-500">Báo cáo vấn đề phát sinh</span>
                            </div>
                        </div>
                    </label>
                </div>
                <?php if (!empty($errors['type'])): ?>
                    <p class="text-red-500 text-sm mt-1 flex items-center gap-1">
                        <i data-lucide="alert-circle" class="w-3 h-3"></i>
                        <?= implode(', ', $errors['type']) ?>
                    </p>
                <?php endif; ?>
            </div>

            <!-- Content -->
            <div class="mb-8">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nội dung chi tiết <span class="text-red-500">*</span></label>
                <textarea name="content" rows="6"
                    placeholder="Mô tả chi tiết về hoạt động hoặc sự cố..."
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all resize-y <?= isset($errors['content']) ? 'border-red-500 ring-1 ring-red-500' : '' ?>"><?= htmlspecialchars($old['content'] ?? 'Xe đón khách tại khách sạn. Kiểm tra quân số đủ, hành lý đầy đủ.') ?></textarea>
                <?php if (!empty($errors['content'])): ?>
                    <p class="text-red-500 text-sm mt-1 flex items-center gap-1">
                        <i data-lucide="alert-circle" class="w-3 h-3"></i>
                        <?= implode(', ', $errors['content']) ?>
                    </p>
                <?php endif; ?>
            </div>

            <!-- Upload ảnh -->
            <div class="mb-8">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Hình ảnh đính kèm</label>

                <div class="relative group">
                    <input type="file"
                        name="images[]"
                        id="newImages"
                        multiple
                        accept="image/*"
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">

                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center group-hover:border-blue-500 group-hover:bg-blue-50 transition-all duration-200">
                        <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform">
                            <i data-lucide="upload-cloud" class="w-6 h-6"></i>
                        </div>
                        <p class="text-sm font-medium text-gray-900">Click để tải ảnh lên hoặc kéo thả vào đây</p>
                        <p class="text-xs text-gray-500 mt-1">PNG, JPG, GIF (Tối đa 5MB)</p>
                    </div>
                </div>

                <!-- Preview ảnh -->
                <div id="previewNew" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 mt-4"></div>
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-100">
                <button onclick="history.back()"
                    class="px-6 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-4 focus:ring-gray-100 transition-all">
                    Hủy bỏ
                </button>
                <button type="submit"
                    class="px-6 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-100 shadow-lg shadow-blue-500/30 transition-all flex items-center gap-2">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    Lưu nhật ký
                </button>
            </div>

        </form>
    </div>
</main>

<script>
    document.getElementById('newImages').addEventListener('change', function(e) {
        let preview = document.getElementById('previewNew');
        preview.innerHTML = "";

        let files = Array.from(e.target.files);
        let dt = new DataTransfer();

        files.forEach((file, index) => {
            let reader = new FileReader();
            reader.onload = function(ev) {
                let wrap = document.createElement("div");
                wrap.className = "relative group aspect-square rounded-xl overflow-hidden border border-gray-200 shadow-sm";

                let img = document.createElement("img");
                img.src = ev.target.result;
                img.className = "w-full h-full object-cover transition-transform group-hover:scale-105";

                let btn = document.createElement("button");
                btn.innerHTML = '<i data-lucide="x" class="w-3 h-3"></i>';
                btn.type = "button";
                btn.className =
                    "absolute top-2 right-2 bg-white/90 text-red-500 w-6 h-6 flex items-center justify-center rounded-full shadow-sm hover:bg-red-500 hover:text-white transition-all opacity-0 group-hover:opacity-100";

                btn.onclick = function() {
                    wrap.remove();
                    files.splice(index, 1);

                    let newDT = new DataTransfer();
                    files.forEach(f => newDT.items.add(f));
                    document.getElementById('newImages').files = newDT.files;
                };

                wrap.appendChild(img);
                wrap.appendChild(btn);
                preview.appendChild(wrap);
            };
            reader.readAsDataURL(file);
        });
    });
</script>

<?php
require_once './views/components/footer.php';
?>