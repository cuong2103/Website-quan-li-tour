<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';
// Lấy số ngày từ POST hoặc mặc định là 1

$dayCount = !empty($_POST['destination_id']) ? count($_POST['destination_id']) : 1;
?>
<main class="pt-28 px-8 bg-gray-50 min-h-screen overflow-y-auto">
  <div class="max-w-12xl mx-auto">
    <!-- Header của form -->
    <div class="flex items-center justify-between mb-8">
      <div class="flex items-center gap-4">
        <button onclick="history.back()" class="p-2 hover:bg-gray-100 rounded-lg transition">
          <i data-lucide="arrow-left" class="w-6 h-6"></i>
        </button>
        <div>
          <h2 class="text-2xl font-bold text-gray-900">Tạo Tour mới</h2>
          <p class="text-sm text-gray-600">Nhập thông tin chi tiết về tour</p>
        </div>
      </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 space-y-10">
      <form action="?act=tours-store" method="POST">
        <!-- 1. Thông tin cơ bản -->
        <div>
          <h3 class="text-lg font-semibold text-gray-900 mb-6">Thông tin cơ bản</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="col-span-2">
              <label class="block text-sm font-medium text-gray-700 mb-2">Tên tour</label>
              <input type="text" name="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" placeholder="VD: Tour Hà Nội - Sapa 3N2Đ" class="w-full px-4 py-3 border <?= !empty($errors['name']) ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-transparent' ?> rounded-lg focus:ring-2 outline-none">
              <?php if (!empty($errors['name'])): ?>
                <div class="text-red-500 text-sm mt-1"><?= $errors['name'][0] ?></div>
              <?php endif; ?>
            </div>

            <div class="col-span-2">
              <label class="block text-sm font-medium text-gray-700 mb-2">Giới thiệu</label>
              <textarea rows="3" name="introduction" placeholder="Mô tả ngắn về tour..." class="w-full px-4 py-3 border <?= !empty($errors['introduction']) ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-transparent' ?> rounded-lg focus:ring-2 outline-none resize-none"><?= htmlspecialchars($_POST['introduction'] ?? '') ?></textarea>
              <?php if (!empty($errors['introduction'])): ?>
                <div class="text-red-500 text-sm mt-1"><?= $errors['introduction'][0] ?></div>
              <?php endif; ?>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Danh mục</label>
              <select name="category_id" class="w-full px-4 py-3 border <?= !empty($errors['category_id']) ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-transparent' ?> rounded-lg focus:ring-2 outline-none">
                <option value="">Chọn danh mục</option>
                <?php renderOption($tree, "", $_POST["category_id"] ?? null); ?>
              </select>
              <?php if (!empty($errors['category_id'])): ?>
                <div class="text-red-500 text-sm mt-1"><?= $errors['category_id'][0] ?></div>
              <?php endif; ?>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Số ngày</label>
              <input type="number" name="duration_days" value="<?= htmlspecialchars($_POST['duration_days'] ?? '1') ?>" placeholder="4" min="1" class="w-full px-4 py-3 border <?= !empty($errors['duration_days']) ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-transparent' ?> rounded-lg focus:ring-2 outline-none">
              <?php if (!empty($errors['duration_days'])): ?>
                <div class="text-red-500 text-sm mt-1"><?= $errors['duration_days'][0] ?></div>
              <?php endif; ?>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Giá người lớn (VND)</label>
              <input type="text" name="adult_price" value="<?= htmlspecialchars($_POST['adult_price'] ?? '') ?>" placeholder="4500000" class="w-full px-4 py-3 border <?= !empty($errors['adult_price']) ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-transparent' ?> rounded-lg focus:ring-2 outline-none">
              <?php if (!empty($errors['adult_price'])): ?>
                <div class="text-red-500 text-sm mt-1"><?= $errors['adult_price'][0] ?></div>
              <?php endif; ?>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Giá trẻ em (VND)</label>
              <input type="text" name="child_price" value="<?= htmlspecialchars($_POST['child_price'] ?? '') ?>" placeholder="4000000" class="w-full px-4 py-3 border <?= !empty($errors['child_price']) ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-blue-500 focus:border-transparent' ?> rounded-lg focus:ring-2 outline-none">
              <?php if (!empty($errors['child_price'])): ?>
                <div class="text-red-500 text-sm mt-1"><?= $errors['child_price'][0] ?></div>
              <?php endif; ?>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Trạng thái</label>
              <select name="status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="active" <?= ($_POST['status'] ?? 'active') == 'active' ? 'selected' : '' ?>>Hoạt động</option>
                <option value="inactive" <?= ($_POST['status'] ?? '') == 'inactive' ? 'selected' : '' ?>>Tạm dừng</option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Loại tour</label>
              <div class="flex items-center gap-2 mt-3">
                <input type="checkbox" id="is_fixed" name="is_fixed" value="1" <?= isset($_POST['is_fixed']) ? 'checked' : '' ?> class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500">
                <label for="is_fixed" class="text-gray-700">Tour cố định (Có dịch vụ đi kèm)</label>
              </div>
            </div>

          </div>
        </div>

        <!-- Services Section (Hidden by default, shown if is_fixed is checked) -->
        <div id="services-section" class="hidden">
          <h3 class="text-lg font-semibold text-gray-900 mb-6">Dịch vụ đi kèm</h3>

          <input id="searchService" type="text" placeholder="Tìm dịch vụ..."
            class="w-full border border-gray-300 rounded-lg px-4 py-2 mb-3 text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-400 outline-none transition">

          <div id="serviceList" class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-60 overflow-y-auto">
            <?php foreach ($services as $service): ?>
              <div class="service-item p-3 border border-gray-200 rounded-lg hover:bg-blue-50 transition">
                <label class="flex justify-between items-center gap-3 cursor-pointer">
                  <div class="flex items-center gap-2">
                    <input type="checkbox" name="service_ids[]" value="<?= $service['id'] ?>"
                      <?= in_array($service['id'], $_POST['service_ids'] ?? []) ? 'checked' : '' ?>
                      class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500">
                    <span class="text-sm font-medium text-gray-700"><?= htmlspecialchars($service['name']) ?></span>
                  </div>
                  <p class="text-sm text-gray-500 mt-1"><?= number_format($service['estimated_price']) ?> VND</p>
                </label>
              </div>
            <?php endforeach; ?>
          </div>
          <p id="noServiceResult" class="hidden text-sm text-gray-500 italic mt-2 text-center">Không tìm thấy dịch vụ phù hợp.</p>

          <?php if (!empty($errors['service_ids'])): ?>
            <div class="text-red-500 text-sm mt-1"><?= $errors['service_ids'][0] ?></div>
          <?php endif; ?>
        </div>

        <!-- 2. Lịch trình tour -->
        <div id="itinerary-section">
          <div class="flex items-center justify-between my-6">
            <h3 class="text-lg font-semibold text-gray-900">Lịch trình tour</h3>
            <button type="button" id="add-day" class="flex items-center gap-2 text-blue-600 hover:text-blue-700 font-medium text-sm">
              <i data-lucide="plus" class="w-5 h-5"></i>
              Thêm ngày
            </button>
          </div>

          <?php for ($i = 0; $i < $dayCount; $i++):
            $dayNum = $i + 1;
          ?>
            <div id="day-<?= $dayNum ?>" class="border border-gray-200 rounded-xl <?= $i > 0 ? 'mt-6' : '' ?> p-6 space-y-5">
              <div class="flex items-center justify-between">
                <h4 class="font-medium text-gray-900">Ngày <?= $dayNum ?></h4>
                <?php if ($i > 0): ?>
                  <button type="button" class="remove-day-btn p-2 text-red-600 hover:text-red-700 text-sm font-medium" data-day="<?= $dayNum ?>">
                    <i data-lucide="x" class="w-5 h-5"></i>
                  </button>
                <?php endif; ?>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="col-span-2">
                  <label class="block text-sm font-medium text-gray-700 mb-2">Điểm đến</label>
                  <select name="destination_id[]" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Chọn điểm đến</option>
                    <?php foreach ($destinations as $destination): ?>
                      <option value="<?= $destination['id'] ?>"
                        <?= (isset($_POST['destination_id'][$i]) && $_POST['destination_id'][$i] == $destination['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($destination['name']) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                  <?php if (!empty($errors['destination_id'][$i])): ?>
                    <div class="text-red-500 text-sm mt-1"><?= $errors['destination_id'][$i][0] ?></div>
                  <?php endif; ?>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Thời gian đến</label>
                  <input type="time" name="arrival_time[]" value="<?= htmlspecialchars($_POST['arrival_time'][$i] ??  '') ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                  <?php if (!empty($errors['arrival_time'][$i])): ?>
                    <div class="text-red-500 text-sm mt-1"><?= $errors['arrival_time'][$i][0] ?></div>
                  <?php endif; ?>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Thời gian đi</label>
                  <input type="time" name="departure_time[]" value="<?= htmlspecialchars($_POST['departure_time'][$i]   ?? '') ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                  <?php if (!empty($errors['departure_time'][$i])): ?>
                    <div class="text-red-500 text-sm mt-1"><?= $errors['departure_time'][$i][0] ?></div>
                  <?php endif; ?>
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Mô tả hoạt động</label>
                <textarea name="description[]" placeholder="Mô tả các hoạt động trong ngày..." class="w-full h-48 px-4 py-3 border border-gray-300 rounded-lg"><?= htmlspecialchars($_POST['description'][$i] ?? '') ?></textarea>
                <?php if (!empty($errors['description'][$i])): ?>
                  <div class="text-red-500 text-sm mt-1"><?= $errors['description'][$i][0] ?></div>
                <?php endif; ?>
              </div>
            </div>
          <?php endfor; ?>
        </div>

        <!-- 3. Chính sách -->
        <div>
          <h3 class="text-lg font-semibold text-gray-900 my-6">Chính sách</h3>
          <div class="space-y-4">
            <?php foreach ($policies as $policy): ?>
              <label class="flex items-start gap-3 cursor-pointer">
                <input type="checkbox" name="policy_ids[]" value="<?= $policy['id'] ?>"
                  <?= in_array($policy['id'], $_POST['policy_ids'] ?? []) ? 'checked' : '' ?>
                  class="mt-1 w-4 h-4 text-blue-600 rounded focus:ring-blue-500">
                <div>
                  <p class="font-medium text-gray-900"><?= htmlspecialchars($policy['title']) ?></p>
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
            Lưu Tour
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
    const durationInput = document.querySelector('input[name="duration_days"]');

    // Tạo option HTML cho destinations
    const destinationOptions = `
      <option value="">Chọn điểm đến</option>
      <?php foreach ($destinations as $destination): ?>
        <option value="<?= $destination['id'] ?>"><?= htmlspecialchars($destination['name']) ?></option>
      <?php endforeach; ?>
    `;

    // Hàm tạo HTML cho một ngày
    function createDayHTML(dayNum, showRemoveBtn = true) {
      return `
      <div id="day-${dayNum}" class="border border-gray-200 rounded-xl ${dayNum > 1 ? 'mt-6' : ''} p-6 space-y-5">
        <div class="flex items-center justify-between">
          <h4 class="font-medium text-gray-900">Ngày ${dayNum}</h4>
          ${showRemoveBtn ? `
          <button type="button" class="remove-day-btn p-2 text-red-600 hover:text-red-700 text-sm font-medium" data-day="${dayNum}">
            <i data-lucide="x" class="w-5 h-5"></i>
          </button>
          ` : ''}
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
          <div class="col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">Điểm đến</label>
            <select name="destination_id[]" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
              ${destinationOptions}
            </select>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Thời gian đến</label>
            <input type="time" name="arrival_time[]" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Thời gian đi</label>
            <input type="time" name="departure_time[]" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
          </div>
        </div>
        
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Mô tả hoạt động</label>
          <textarea rows="3" name="description[]" placeholder="Mô tả các hoạt động trong ngày..." class="w-full h-48 px-4 py-3 border border-gray-300 rounded-lg"></textarea>
        </div>
      </div>`;
    }

    // Hàm cập nhật các ngày theo số lượng
    function updateDays(targetCount) {
      if (targetCount < 1) targetCount = 1;
      if (targetCount > 30) targetCount = 30; // Giới hạn tối đa 30 ngày

      const existingDays = document.querySelectorAll('[id^="day-"]');
      const currentCount = existingDays.length;

      if (targetCount > currentCount) {
        // Thêm ngày mới
        for (let i = currentCount + 1; i <= targetCount; i++) {
          itinerarySection.insertAdjacentHTML('beforeend', createDayHTML(i, i > 1));
          lucide.createIcons(); // Render Lucide icons cho ngày mới
        }
      } else if (targetCount < currentCount) {
        // Xóa các ngày thừa (từ cuối lên)
        for (let i = currentCount; i > targetCount; i--) {
          const dayDiv = document.getElementById(`day-${i}`);
          if (dayDiv) dayDiv.remove();
        }
      }
    }

    // Lắng nghe sự kiện thay đổi số ngày
    if (durationInput) {
      durationInput.addEventListener('input', (e) => {
        const value = parseInt(e.target.value) || 0;
        updateDays(value);
      });

      // Khởi tạo khi load trang nếu đã có giá trị
      const initialValue = parseInt(durationInput.value) || 0;
      if (initialValue > 0) {
        updateDays(initialValue);
      }
    }

    // Thêm ngày mới (nút thủ công)
    addDayBtn.addEventListener('click', (e) => {
      e.preventDefault();
      const currentCount = document.querySelectorAll('[id^="day-"]').length;
      const newCount = currentCount + 1;

      // Cập nhật input số ngày
      if (durationInput) {
        durationInput.value = newCount;
      }

      itinerarySection.insertAdjacentHTML('beforeend', createDayHTML(newCount, newCount > 1));
      lucide.createIcons();
    });

    // Xóa ngày với event delegation
    itinerarySection.addEventListener('click', (e) => {
      const removeBtn = e.target.closest('.remove-day-btn');
      if (removeBtn) {
        const dayNum = parseInt(removeBtn.dataset.day);
        const allDays = document.querySelectorAll('[id^="day-"]');

        if (allDays.length > 1) {
          // Xóa ngày được click
          const dayDiv = document.getElementById(`day-${dayNum}`);
          if (dayDiv) dayDiv.remove();

          // Đánh lại số thứ tự cho các ngày còn lại
          const remainingDays = document.querySelectorAll('[id^="day-"]');
          remainingDays.forEach((day, index) => {
            const newNum = index + 1;
            day.id = `day-${newNum}`;
            day.querySelector('h4').textContent = `Ngày ${newNum}`;

            // Cập nhật data-day cho nút xóa
            const removeBtn = day.querySelector('.remove-day-btn');
            if (removeBtn) {
              removeBtn.dataset.day = newNum;
            }

            // Ẩn nút xóa cho ngày đầu tiên
            if (newNum === 1 && removeBtn) {
              removeBtn.remove();
            }
          });

          // Cập nhật input số ngày
          if (durationInput) {
            durationInput.value = remainingDays.length;
          }
        }
      }
    });

    // Toggle Services Section
    const isFixedCheckbox = document.getElementById('is_fixed');
    const servicesSection = document.getElementById('services-section');

    function toggleServices() {
      if (isFixedCheckbox.checked) {
        servicesSection.classList.remove('hidden');
      } else {
        servicesSection.classList.add('hidden');
      }
    }

    if (isFixedCheckbox) {
      isFixedCheckbox.addEventListener('change', toggleServices);
      // Run on load to set initial state
      toggleServices();
    }

    // Search Services
    const searchService = document.getElementById("searchService");
    const serviceItems = document.querySelectorAll(".service-item");
    const noServiceResult = document.getElementById("noServiceResult");

    if (searchService) {
      searchService.addEventListener("keyup", function() {
        const keyword = this.value.toLowerCase();
        let count = 0;
        serviceItems.forEach(item => {
          const text = item.innerText.toLowerCase();
          if (text.includes(keyword)) {
            item.style.display = "block";
            count++;
          } else {
            item.style.display = "none";
          }
        });
        noServiceResult.classList.toggle("hidden", count > 0);
      });

      // Prevent submit on enter
      searchService.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') e.preventDefault();
      });
    }
  });
</script>

<?php
unset($_SESSION['old']);
unset($_SESSION['validate_errors']);
require_once './views/components/footer.php';
?>