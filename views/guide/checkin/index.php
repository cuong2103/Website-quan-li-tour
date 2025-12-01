<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';
?>

<main class="mt-28 px-6 pb-20 text-gray-700">

  <!-- Header -->
  <div class="flex items-center justify-between mb-6">
    <div>
      <h1 class="text-2xl font-bold text-gray-900">Điểm danh khách hàng</h1>
      <p class="text-sm text-gray-600 mt-1">
        Tour: <?= htmlspecialchars($assignment['tour_name']) ?>
        (<?= date('d/m/Y', strtotime($assignment['start_date'])) ?>)
      </p>
    </div>
    <a href="<?= BASE_URL . '?act=guide-schedule' ?>"
      class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg text-sm">
      <i class="w-4 h-4" data-lucide="arrow-left"></i>
      Quay lại
    </a>
  </div>

  <!-- Thống kê -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm text-gray-500">Tổng khách</p>
          <p class="text-2xl font-bold text-gray-900"><?= $stats['total_customers'] ?></p>
        </div>
        <div class="p-3 bg-blue-100 rounded-lg">
          <i class="w-6 h-6 text-blue-600" data-lucide="users"></i>
        </div>
      </div>
    </div>

    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm text-gray-500">Đã điểm danh</p>
          <p class="text-2xl font-bold text-green-600"><?= $stats['checked_in_count'] ?></p>
        </div>
        <div class="p-3 bg-green-100 rounded-lg">
          <i class="w-6 h-6 text-green-600" data-lucide="check-circle"></i>
        </div>
      </div>
    </div>

    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm text-gray-500">Chưa điểm danh</p>
          <p class="text-2xl font-bold text-orange-600">
            <?= $stats['total_customers'] - $stats['checked_in_count'] ?>
          </p>
        </div>
        <div class="p-3 bg-orange-100 rounded-lg">
          <i class="w-6 h-6 text-orange-600" data-lucide="clock"></i>
        </div>
      </div>
    </div>
  </div>

  <!-- Danh sách khách hàng -->
  <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-lg font-semibold">Danh sách khách hàng</h2>
      <button onclick="openBulkCheckinModal()"
        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm flex items-center gap-2">
        <i class="w-4 h-4" data-lucide="check-square"></i>
        Điểm danh hàng loạt
      </button>
    </div>

    <div class="space-y-3">
      <?php foreach ($customers as $customer): ?>
        <div class="flex items-center justify-between p-4 border border-gray-100 rounded-xl hover:bg-gray-50">
          <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-semibold">
              <?= strtoupper(substr($customer['name'], 0, 2)) ?>
            </div>

            <div>
              <p class="font-medium text-gray-800 flex items-center gap-2">
                <?= htmlspecialchars($customer['name']) ?>
                <?php if ($customer['is_representative']): ?>
                  <span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded">
                    Đại diện
                  </span>
                <?php endif; ?>

                <?php if ($customer['checkin_count'] > 0): ?>
                  <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded flex items-center gap-1">
                    <i class="w-3 h-3" data-lucide="check"></i>
                    Đã điểm danh
                  </span>
                <?php endif; ?>
              </p>

              <div class="flex items-center gap-4 mt-1 text-sm text-gray-600">
                <span class="flex items-center gap-1">
                  <i class="w-3.5 h-3.5" data-lucide="phone"></i>
                  <?= htmlspecialchars($customer['phone']) ?>
                </span>
                <?php if ($customer['email']): ?>
                  <span class="flex items-center gap-1">
                    <i class="w-3.5 h-3.5" data-lucide="mail"></i>
                    <?= htmlspecialchars($customer['email']) ?>
                  </span>
                <?php endif; ?>
              </div>
            </div>
          </div>

          <?php if ($customer['checkin_count'] == 0): ?>
            <button onclick="openCheckinModal(<?= $customer['id'] ?>, '<?= htmlspecialchars($customer['name']) ?>')"
              class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm flex items-center gap-2">
              <i class="w-4 h-4" data-lucide="check"></i>
              Điểm danh
            </button>
          <?php else: ?>
            <a href="<?= BASE_URL ?>?act=checkin-delete&id=<?= $customer['latest_checkin_id'] ?>&assignment_id=<?= $assignment['assignment_id'] ?>"
              onclick="return confirm('Bạn có chắc muốn hủy điểm danh cho khách hàng này?')"
              class="px-4 py-2 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg text-sm flex items-center gap-2">
              <i class="w-4 h-4" data-lucide="x-circle"></i>
              Hủy điểm danh
            </a>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Lịch sử điểm danh -->
  <?php if (!empty($checkinHistory)): ?>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
      <h2 class="text-lg font-semibold mb-4">Lịch sử điểm danh</h2>

      <div class="space-y-3">
        <?php foreach ($checkinHistory as $checkin): ?>
          <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
            <div class="flex items-center gap-3">
              <div class="p-2 bg-green-100 rounded-lg">
                <i class="w-5 h-5 text-green-600" data-lucide="check-circle"></i>
              </div>
              <div>
                <p class="font-medium text-gray-800">
                  <?= htmlspecialchars($checkin['customer_name']) ?>
                </p>
                <p class="text-sm text-gray-600">
                  <?= date('d/m/Y H:i', strtotime($checkin['checkin_time'])) ?>
                  <?php if ($checkin['location']): ?>
                    - <?= htmlspecialchars($checkin['location']) ?>
                  <?php endif; ?>
                </p>
                <?php if ($checkin['notes']): ?>
                  <p class="text-sm text-gray-500 mt-1">
                    Ghi chú: <?= htmlspecialchars($checkin['notes']) ?>
                  </p>
                <?php endif; ?>
              </div>
            </div>

            <div class="flex items-center gap-2">
              <?php if ($checkin['image_url']): ?>
                <a href="<?= BASE_URL ?>uploads/checkins/<?= $checkin['image_url'] ?>"
                  target="_blank"
                  class="p-2 hover:bg-gray-200 rounded-lg">
                  <i class="w-4 h-4 text-gray-600" data-lucide="image"></i>
                </a>
              <?php endif; ?>

              <a href="<?= BASE_URL ?>?act=checkin-delete&id=<?= $checkin['id'] ?>&assignment_id=<?= $assignment['assignment_id'] ?>"
                onclick="return confirm('Bạn có chắc muốn xóa?')"
                class="p-2 hover:bg-red-100 rounded-lg text-red-600">
                <i class="w-4 h-4" data-lucide="trash-2"></i>
              </a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endif; ?>
</main>

<!-- Modal điểm danh đơn -->
<div id="checkinModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
  <div class="bg-white rounded-xl p-6 w-full max-w-md mx-4">
    <h3 class="text-lg font-semibold mb-4">Điểm danh khách hàng</h3>

    <form method="POST" action="<?= BASE_URL ?>?act=checkin-store" enctype="multipart/form-data">
      <input type="hidden" name="assignment_id" value="<?= $assignment['assignment_id'] ?>">
      <input type="hidden" name="customer_id" id="modal_customer_id">

      <div class="mb-4">
        <label class="block font-medium mb-2">Khách hàng</label>
        <input type="text" id="modal_customer_name" readonly
          class="w-full px-3 py-2 border rounded-lg bg-gray-50">
      </div>

      <div class="mb-4">
        <label class="block font-medium mb-2">Vị trí</label>
        <input type="text" name="location" placeholder="Nhập vị trí điểm danh"
          class="w-full px-3 py-2 border rounded-lg">
      </div>

      <div class="mb-4">
        <label class="block font-medium mb-2">Ghi chú</label>
        <textarea name="notes" rows="3" placeholder="Ghi chú thêm..."
          class="w-full px-3 py-2 border rounded-lg resize-none"></textarea>
      </div>

      <div class="mb-4">
        <label class="block font-medium mb-2">Ảnh (tùy chọn)</label>
        <input type="file" name="image" accept="image/*"
          class="w-full px-3 py-2 border rounded-lg">
      </div>

      <div class="flex gap-3">
        <button type="submit"
          class="flex-1 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg">
          Xác nhận điểm danh
        </button>
        <button type="button" onclick="closeCheckinModal()"
          class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg">
          Hủy
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Modal điểm danh hàng loạt -->
<div id="bulkCheckinModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
  <div class="bg-white rounded-xl p-6 w-full max-w-md mx-4">
    <h3 class="text-lg font-semibold mb-4">Điểm danh hàng loạt</h3>

    <form method="POST" action="<?= BASE_URL ?>?act=checkin-bulk">
      <input type="hidden" name="assignment_id" value="<?= $assignment['assignment_id'] ?>">

      <div class="mb-4">
        <label class="block font-medium mb-2">Chọn khách hàng chưa điểm danh</label>
        <div class="border rounded-lg p-3 max-h-60 overflow-y-auto space-y-2">
          <?php foreach ($customers as $customer): ?>
            <?php if ($customer['checkin_count'] == 0): ?>
              <label class="flex items-center gap-2 p-2 hover:bg-gray-50 rounded">
                <input type="checkbox" name="customer_ids[]"
                  value="<?= $customer['id'] ?>" class="rounded">
                <span><?= htmlspecialchars($customer['name']) ?></span>
              </label>
            <?php endif; ?>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="mb-4">
        <label class="block font-medium mb-2">Vị trí</label>
        <input type="text" name="location" placeholder="Nhập vị trí điểm danh"
          class="w-full px-3 py-2 border rounded-lg">
      </div>

      <div class="flex gap-3">
        <button type="submit"
          class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
          Xác nhận
        </button>
        <button type="button" onclick="closeBulkCheckinModal()"
          class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg">
          Hủy
        </button>
      </div>
    </form>
  </div>
</div>

<script>
  function openCheckinModal(customerId, customerName) {
    document.getElementById('modal_customer_id').value = customerId;
    document.getElementById('modal_customer_name').value = customerName;
    document.getElementById('checkinModal').classList.remove('hidden');
  }

  function closeCheckinModal() {
    document.getElementById('checkinModal').classList.add('hidden');
  }

  function openBulkCheckinModal() {
    document.getElementById('bulkCheckinModal').classList.remove('hidden');
  }

  function closeBulkCheckinModal() {
    document.getElementById('bulkCheckinModal').classList.add('hidden');
  }

  // Đóng modal khi click bên ngoài
  window.onclick = function(event) {
    const checkinModal = document.getElementById('checkinModal');
    const bulkModal = document.getElementById('bulkCheckinModal');
    if (event.target === checkinModal) {
      closeCheckinModal();
    }
    if (event.target === bulkModal) {
      closeBulkCheckinModal();
    }
  }

  // Giữ vị trí scroll khi reload
  document.addEventListener("DOMContentLoaded", function(event) {
    var scrollpos = sessionStorage.getItem('checkin_scroll_pos');
    if (scrollpos) window.scrollTo(0, scrollpos);
  });

  window.onbeforeunload = function(e) {
    sessionStorage.setItem('checkin_scroll_pos', window.scrollY);
  };
</script>

<?php require_once './views/components/footer.php'; ?>