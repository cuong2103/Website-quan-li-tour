<?php
require './views/components/header.php';
require './views/components/sidebar.php';

// Kiểm tra tour có đang diễn ra không
$today = date('Y-m-d');
$canCheckinNow = ($today >= $checkinLink['start_date'] && $today <= $checkinLink['end_date']);
?>

<main class="mt-28 px-6 pb-20 text-gray-700">

  <div class="flex justify-between items-center mb-6">
    <div>
      <h1 class="text-2xl font-bold">Chi tiết Check-in: <?= htmlspecialchars($checkinLink['title']) ?></h1>
      <p class="text-sm text-gray-600 mt-1">Tour: <?= htmlspecialchars($checkinLink['tour_name']) ?></p>
    </div>
    <a href="<?= BASE_URL . '?act=guide-tour-assignments-detail&id=' . $assignmentId . '&tab=checkin' ?>"
      class="flex items-center gap-2 bg-gray-700 text-white px-4 py-2 rounded-lg hover:bg-black text-sm">
      <i data-lucide="arrow-left"></i> Quay lại
    </a>
  </div>


  <?php if (!$canCheckinNow): ?>
    <div class="mb-4 p-4 rounded-lg <?= $today < $checkinLink['start_date'] ? 'bg-yellow-50 border border-yellow-200' : 'bg-gray-50 border border-gray-200' ?>">
      <div class="flex items-center gap-2">
        <i data-lucide="<?= $today < $checkinLink['start_date'] ? 'clock' : 'check-circle' ?>" class="w-5 h-5 <?= $today < $checkinLink['start_date'] ? 'text-yellow-600' : 'text-gray-600' ?>"></i>
        <span class="font-medium <?= $today < $checkinLink['start_date'] ? 'text-yellow-800' : 'text-gray-700' ?>">
          <?php if ($today < $checkinLink['start_date']): ?>
            Chưa đến thời gian khởi hành! Tour bắt đầu từ <?= date('d/m/Y', strtotime($checkinLink['start_date'])) ?>
          <?php else: ?>
            Tour đã kết thúc từ ngày <?= date('d/m/Y', strtotime($checkinLink['end_date'])) ?>
          <?php endif; ?>
        </span>
      </div>
    </div>
  <?php endif; ?>

  <!-- Bảng danh sách khách hàng -->
  <div class=" bg-white border shadow rounded-xl p-5">
    <div class="flex justify-between items-center mb-4">
      <h3 class=" inline font-semibold text-lg mb-4">Danh sách khách hàng</h3>
      <a href="<?= BASE_URL . '?act=checkin-export&id=' . $assignmentId ?>"
        class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700 flex items-center gap-2">
        <i data-lucide="file-spreadsheet" class="w-4 h-4"></i>
        Xuất Excel
      </a>
    </div>

    <form action="<?= BASE_URL . '?act=guide-checkin-batch-update' ?>" method="POST" id="checkin-form">
      <input type="hidden" name="link_id" value="<?= $linkId ?>">
      <input type="hidden" name="assignment_id" value="<?= $assignmentId ?>">

      <table class="w-full text-sm">
        <thead class="bg-gray-100">
          <tr>
            <th class="p-3 text-left">STT</th>
            <th class="p-3 text-left">Tên khách hàng</th>
            <th class="p-3 text-left">Số điện thoại</th>
            <th class="p-3 text-left">Email</th>
            <th class="p-3 text-left">Số phòng</th>
            <th class="p-3 text-left">Trạng thái</th>
            <th class="p-3 text-left">Thời gian check-in</th>
            <th class="p-3 text-center">Check-in</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($customers)): ?>
            <?php foreach ($customers as $i => $customer): ?>
              <?php $isCheckedIn = !empty($customer['checkin_id']); ?>
              <tr class="border-t hover:bg-gray-50">
                <td class="p-3"><?= $i + 1 ?></td>
                <td class="p-3 font-medium"><?= htmlspecialchars($customer['name']) ?></td>
                <td class="p-3"><?= htmlspecialchars($customer['phone']) ?></td>
                <td class="p-3 text-gray-600"><?= htmlspecialchars($customer['email'] ?? '-') ?></td>
                <td class="p-3 text-gray-600"><?= htmlspecialchars($customer['room_number'] ?? '-') ?></td>
                <td class="p-3">
                  <?php if ($isCheckedIn): ?>
                    <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-medium flex items-center gap-1 w-fit">
                      <i data-lucide="check-circle" class="w-3 h-3"></i> Đã check-in
                    </span>
                  <?php else: ?>
                    <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded text-xs font-medium">Chưa check-in</span>
                  <?php endif; ?>
                </td>
                <td class="p-3 text-gray-600">
                  <?= $customer['checkin_time'] ? date('H:i d/m/Y', strtotime($customer['checkin_time'])) : '-' ?>
                </td>
                <td class="p-3 text-center">
                  <?php if ($canCheckinNow): ?>
                    <!-- Toggle Switch -->
                    <label class="relative inline-flex items-center cursor-pointer">
                      <input type="checkbox"
                        name="checked_customers[]"
                        value="<?= $customer['id'] ?>"
                        class="sr-only peer checkin-toggle"
                        <?= $isCheckedIn ? 'checked' : '' ?>>
                      <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                    </label>
                  <?php else: ?>
                    <!-- Disabled Toggle -->
                    <label class="relative inline-flex items-center cursor-not-allowed opacity-50">
                      <input type="checkbox"
                        disabled
                        class="sr-only peer"
                        <?= $isCheckedIn ? 'checked' : '' ?>>
                      <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                    </label>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="8" class="p-8 text-center text-gray-500">
                <i data-lucide="user-x" class="w-12 h-12 text-gray-300 mx-auto mb-2"></i>
                <p>Không có khách hàng nào trong tour này</p>
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>

      <?php if (!empty($customers) && $canCheckinNow): ?>
        <!-- Submit Button -->
        <div class="mt-6 flex justify-end gap-3">
          <button type="button" id="select-all-btn" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 flex items-center gap-2 text-sm">
            <i data-lucide="check-square" class="w-4 h-4"></i>
            Chọn tất cả
          </button>
          <button type="button" id="deselect-all-btn" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 flex items-center gap-2 text-sm">
            <i data-lucide="square" class="w-4 h-4"></i>
            Bỏ chọn tất cả
          </button>
          <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2 font-medium">
            <i data-lucide="save" class="w-4 h-4"></i>
            Lưu thay đổi
          </button>
        </div>
      <?php endif; ?>
    </form>
  </div>

</main>

<script>
  document.addEventListener("DOMContentLoaded", function(event) {

    // Select/Deselect all buttons
    const selectAllBtn = document.getElementById('select-all-btn');
    const deselectAllBtn = document.getElementById('deselect-all-btn');
    const toggles = document.querySelectorAll('.checkin-toggle');

    if (selectAllBtn) {
      selectAllBtn.addEventListener('click', function() {
        toggles.forEach(toggle => toggle.checked = true);
      });
    }

    if (deselectAllBtn) {
      deselectAllBtn.addEventListener('click', function() {
        toggles.forEach(toggle => toggle.checked = false);
      });
    }
  });
</script>

<?php require './views/components/footer.php'; ?>