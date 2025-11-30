<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';

// Ưu tiên lấy data từ POST (khi có lỗi validation), nếu không thì từ database
$tourData = !empty($_POST) ? $_POST : $tour;
$dayCount = !empty($_POST['destination_name']) ? count($_POST['destination_name']) : count($itineraries);
?>
<main class="pt-28 px-8 bg-gray-50 min-h-screen overflow-y-auto">
  <div class="max-w-12xl mx-auto">
    <!-- Header của form -->
    <div class="flex items-center justify-between mb-8">
      <div class="flex items-center gap-4">
        <button onclick="history.back()" class="p-2 hover:bg-gray-100 rounded-lg transition">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
        </button>
        <div>
          <h2 class="text-2xl font-bold text-gray-900">Chỉnh sửa Tour</h2>
          <p class="text-sm text-gray-600">Cập nhật thông tin chi tiết về tour</p>
        </div>
      </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 space-y-10">
      <form action="?act=tours-update&id=<?= $tourData['id'] ?>" method="POST">
        <!-- 1. Thông tin cơ bản -->
        <div>
          <h3 class="text-lg font-semibold text-gray-900 mb-6">Thông tin cơ bản</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="col-span-2">
              <label class="block text-sm font-medium text-gray-700 mb-2">Tên tour</label>
              <input type="text" name="name" value="<?= htmlspecialchars($tourData['tour_name'] ?? '') ?>" placeholder="VD: Tour Hà Nội - Sapa 3N2Đ" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
              <?php if (!empty($errors['name'])): ?>
                <div class="text-red-500 text-sm mt-1"><?= $errors['name'][0] ?></div>
              <?php endif; ?>
            </div>

            <div class="col-span-2">
              <label class="block text-sm font-medium text-gray-700 mb-2">Giới thiệu</label>
              <textarea rows="3" name="introduction" placeholder="Mô tả ngắn về tour..." required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"><?= htmlspecialchars($tourData['introduction'] ?? '') ?></textarea>
              <?php if (!empty($errors['introduction'])): ?>
                <div class="text-red-500 text-sm mt-1"><?= $errors['introduction'][0] ?></div>
              <?php endif; ?>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Danh mục</label>
              <select name="category_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">Chọn danh mục</option>
                <?php renderOption($tree, "", $tourData["category_id"] ?? null); ?>
              </select>
              <?php if (!empty($errors['category_id'])): ?>
                <div class="text-red-500 text-sm mt-1"><?= $errors['category_id'][0] ?></div>
              <?php endif; ?>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Trạng thái</label>
              <select name="status" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="active" <?= ($tourData['status'] ?? 'active') == 'active' ? 'selected' : '' ?>>Hoạt động</option>
                <option value="inactive" <?= ($tourData['status'] ?? '') == 'inactive' ? 'selected' : '' ?>>Tạm dừng</option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Giá người lớn (VND)</label>
              <input type="text" name="adult_price" value="<?= htmlspecialchars($tourData['adult_price'] ?? '') ?>" placeholder="4500000" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
              <?php if (!empty($errors['adult_price'])): ?>
                <div class="text-red-500 text-sm mt-1"><?= $errors['adult_price'][0] ?></div>
              <?php endif; ?>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Giá trẻ em (VND)</label>
              <input type="text" name="child_price" value="<?= htmlspecialchars($tourData['child_price'] ?? '') ?>" placeholder="4000000" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
              <?php if (!empty($errors['child_price'])): ?>
                <div class="text-red-500 text-sm mt-1"><?= $errors['child_price'][0] ?></div>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <!-- 2. Lịch trình tour -->
        <!-- 2. Lịch trình tour -->
        <div id="itinerary-section">
          <div class="flex items-center justify-between my-6">
            <h3 class="text-lg font-semibold text-gray-900">Lịch trình tour</h3>
            <button type="button" id="add-day" class="flex items-center gap-2 text-blue-600 hover:text-blue-700 font-medium text-sm">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
              </svg>
              Thêm ngày
            </button>
          </div>

          <?php
          if (!empty($_POST['destination_id'])) {
            for ($i = 0; $i < count($_POST['destination_id']); $i++):
              $dayNum = $i + 1;
          ?>
              <div id="day-<?= $dayNum ?>" class="border border-gray-200 rounded-xl <?= $i > 0 ? 'mt-6' : '' ?> p-6 space-y-5">
                <div class="flex items-center justify-between">
                  <h4 class="font-medium text-gray-900">Ngày <?= $dayNum ?></h4>
                  <?php if ($i > 0): ?>
                    <button type="button" class="remove-day-btn p-2 text-red-600 hover:text-red-700 text-sm font-medium" data-day="<?= $dayNum ?>">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                      </svg>
                    </button>
                  <?php endif; ?>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                  <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Điểm đến</label>
                    <select name="destination_id[]" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                      <option value="">Chọn điểm đến</option>
                      <?php foreach ($destinations as $destination): ?>
                        <option value="<?= $destination['id'] ?>"
                          <?= (isset($_POST['destination_id'][$i]) && $_POST['destination_id'][$i] == $destination['id']) ? 'selected' : '' ?>>
                          <?= htmlspecialchars($destination['name']) ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                    <?php if (!empty($errors['destination_id'][$i])): ?>
                      <div class="text-red-500 text-sm mt-1"><?= $errors['destination_id'][$i] ?></div>
                    <?php endif; ?>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Thời gian đến</label>
                    <input type="text" name="arrival_time[]" value="<?= htmlspecialchars($_POST['arrival_time'][$i] ?? '') ?>" required placeholder="VD: 8:30" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                    <?php if (!empty($errors['arrival_time'][$i])): ?>
                      <div class="text-red-500 text-sm mt-1"><?= $errors['arrival_time'][$i] ?></div>
                    <?php endif; ?>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Thời gian đi</label>
                    <input type="text" name="departure_time[]" value="<?= htmlspecialchars($_POST['departure_time'][$i] ?? '') ?>" required placeholder="VD: 17:00" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                    <?php if (!empty($errors['departure_time'][$i])): ?>
                      <div class="text-red-500 text-sm mt-1"><?= $errors['departure_time'][$i] ?></div>
                    <?php endif; ?>
                  </div>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Mô tả hoạt động</label>
                  <textarea rows="3" name="description[]" required placeholder="Mô tả các hoạt động trong ngày..." class="w-full px-4 py-3 border border-gray-300 rounded-lg"><?= htmlspecialchars($_POST['description'][$i] ?? '') ?></textarea>
                  <?php if (!empty($errors['description'][$i])): ?>
                    <div class="text-red-500 text-sm mt-1"><?= $errors['description'][$i] ?></div>
                  <?php endif; ?>
                </div>
              </div>
            <?php
            endfor;
          } else {
            // Dùng data từ database
            foreach ($itineraries as $i => $itinerary):
              $dayNum = $i + 1;
            ?>
              <div id="day-<?= $dayNum ?>" class="border border-gray-200 rounded-xl <?= $i > 0 ? 'mt-6' : '' ?> p-6 space-y-5">
                <div class="flex items-center justify-between">
                  <h4 class="font-medium text-gray-900">Ngày <?= $dayNum ?></h4>
                  <?php if ($i > 0): ?>
                    <button type="button" class="remove-day-btn p-2 text-red-600 hover:text-red-700 text-sm font-medium" data-day="<?= $dayNum ?>">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                      </svg>
                    </button>
                  <?php endif; ?>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                  <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Điểm đến</label>
                    <select name="destination_id[]" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                      <option value="">Chọn điểm đến</option>
                      <?php foreach ($destinations as $destination): ?>
                        <option value="<?= $destination['id'] ?>"
                          <?= ($itinerary['destination_id'] == $destination['id']) ? 'selected' : '' ?>>
                          <?= htmlspecialchars($destination['name']) ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Thời gian đến</label>
                    <input type="text" name="arrival_time[]" value="<?= htmlspecialchars($itinerary['arrival_time'] ?? '') ?>" required placeholder="VD: 8:30" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Thời gian đi</label>
                    <input type="text" name="departure_time[]" value="<?= htmlspecialchars($itinerary['departure_time'] ?? '') ?>" required placeholder="VD: 17:00" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                  </div>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Mô tả hoạt động</label>
                  <textarea rows="3" name="description[]" required placeholder="Mô tả các hoạt động trong ngày..." class="w-full px-4 py-3 border border-gray-300 rounded-lg"><?= htmlspecialchars($itinerary['description'] ?? '') ?></textarea>
                </div>
              </div>
          <?php
            endforeach;
          }
          ?>
        </div>

        <!-- 3. Chính sách -->
        <div>
          <h3 class="text-lg font-semibold text-gray-900 my-6">Chính sách</h3>
          <div class="space-y-4">
            <?php
            $selectedPolicyIds = !empty($_POST['policy_ids']) ? $_POST['policy_ids'] : $tourPolicyIds;
            foreach ($policies as $policy):
            ?>
              <label class="flex items-start gap-3 cursor-pointer">
                <input type="checkbox" name="policy_ids[]" value="<?= $policy['id'] ?>"
                  <?= in_array($policy['id'], $selectedPolicyIds) ? 'checked' : '' ?>
                  class="mt-1 w-4 h-4 text-blue-600 rounded focus:ring-blue-500">
                <div>
                  <p class="font-medium text-gray-900">Chính sách</p>
                  <p class="text-sm text-gray-600"><?= htmlspecialchars($policy['content']) ?></p>
                </div>
              </label>
            <?php endforeach; ?>
            <?php if (!empty($errors['policy_ids'])): ?>
              <div class="text-red-500 text-sm mt-1"><?= $errors['policy_ids'][0] ?></div>
            <?php endif; ?>
          </div>
        </div>

        <div class="flex fixed bottom-5 right-16 gap-3">
          <button type="button" onclick="history.back()" class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium">
            Hủy
          </button>
          <button type="submit" class="px-6 py-2.5 bg-black text-white rounded-lg hover:bg-gray-900 transition font-medium">
            Cập nhật Tour
          </button>
        </div>
      </form>
    </div>
  </div>
</main>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const addDayBtn = document.getElementById('add-day');
    const itinerarySection = document.getElementById('itinerary-section');

    // Đếm số ngày hiện có
    let dayCount = document.querySelectorAll('[id^="day-"]').length;

    // Tạo option HTML cho destinations
    const destinationOptions = `
      <option value="">Chọn điểm đến</option>
      <?php foreach ($destinations as $destination): ?>
        <option value="<?= $destination['id'] ?>"><?= htmlspecialchars($destination['name']) ?></option>
      <?php endforeach; ?>
    `;

    // Thêm ngày mới
    addDayBtn.addEventListener('click', (e) => {
      e.preventDefault();
      dayCount++;

      const newDayHTML = `
      <div id="day-${dayCount}" class="border border-gray-200 rounded-xl mt-6 p-6 space-y-5">
        <div class="flex items-center justify-between">
          <h4 class="font-medium text-gray-900">Ngày ${dayCount}</h4>
          <button type="button" class="remove-day-btn p-2 text-red-600 hover:text-red-700 text-sm font-medium" data-day="${dayCount}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
          <div class="col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">Điểm đến</label>
            <select name="destination_id[]" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
              ${destinationOptions}
            </select>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Thời gian đến</label>
            <input type="text" name="arrival_time[]" required placeholder="VD: 8:30" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Thời gian đi</label>
            <input type="text" name="departure_time[]" required placeholder="VD: 17:00" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
          </div>
        </div>
        
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Mô tả hoạt động</label>
          <textarea rows="3" name="description[]" required placeholder="Mô tả các hoạt động trong ngày..." class="w-full px-4 py-3 border border-gray-300 rounded-lg"></textarea>
        </div>
      </div>`;

      itinerarySection.insertAdjacentHTML('beforeend', newDayHTML);
    });

    // Xóa ngày với event delegation
    itinerarySection.addEventListener('click', (e) => {
      const removeBtn = e.target.closest('.remove-day-btn');
      if (removeBtn) {
        const dayNum = removeBtn.dataset.day;
        const dayDiv = document.getElementById(`day-${dayNum}`);
        if (dayDiv && dayCount > 1) {
          dayDiv.remove();
          // Không giảm dayCount để tránh trùng ID
        }
      }
    });

  });
</script>

<?php
require_once './views/components/footer.php';
?>