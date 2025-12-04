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
      <a href="<?= BASE_URL . '?act=guide-tour-assignments-export-checkin&id=' . $assignmentId ?>"
        class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700 flex items-center gap-2">
        <i data-lucide="file-spreadsheet" class="w-4 h-4"></i>
        Xuất Excel
      </a>
    </div>
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
          <th class="p-3 text-left">Hành động</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($customers)): ?>
          <?php foreach ($customers as $i => $customer): ?>
            <tr class="border-t hover:bg-gray-50">
              <td class="p-3"><?= $i + 1 ?></td>
              <td class="p-3 font-medium"><?= htmlspecialchars($customer['name']) ?></td>
              <td class="p-3"><?= htmlspecialchars($customer['phone']) ?></td>
              <td class="p-3 text-gray-600"><?= htmlspecialchars($customer['email'] ?? '-') ?></td>
              <td class="p-3 text-gray-600"><?= htmlspecialchars($customer['room_number'] ?? '-') ?></td>
              <td class="p-3">
                <?php if ($customer['checkin_id']): ?>
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
              <td class="p-3">
                <?php if (!$customer['checkin_id']): ?>
                  <?php if ($canCheckinNow): ?>
                    <form action="<?= BASE_URL . '?act=guide-checkin-customer' ?>" method="POST">
                      <input type="hidden" name="link_id" value="<?= $linkId ?>">
                      <input type="hidden" name="customer_id" value="<?= $customer['id'] ?>">
                      <input type="hidden" name="assignment_id" value="<?= $assignmentId ?>">
                      <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded text-xs hover:bg-blue-700 flex items-center gap-1">
                        <i data-lucide="check" class="w-3 h-3"></i>
                        Check-in
                      </button>
                    </form>
                  <?php else: ?>
                    <button disabled class="bg-gray-300 text-gray-500 px-3 py-1 rounded text-xs cursor-not-allowed">
                      Check-in
                    </button>
                  <?php endif; ?>
                <?php else: ?>
                  <?php if ($canCheckinNow): ?>
                    <form action="<?= BASE_URL . '?act=guide-checkin-undo&id=' . $customer['id'] ?>" method="POST">
                      <input type="hidden" name="link_id" value="<?= $linkId ?>">
                      <input type="hidden" name="customer_id" value="<?= $customer['id'] ?>">
                      <input type="hidden" name="assignment_id" value="<?= $assignmentId ?>">
                      <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded text-xs hover:bg-red-700 flex items-center gap-1">
                        <i data-lucide="x" class="w-3 h-3"></i>
                        Hủy
                      </button>
                    </form>
                  <?php else: ?>
                    <span class="text-green-600 text-xs">Đã check-in</span>
                  <?php endif; ?>
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
  </div>

</main>
<script>
  document.addEventListener("DOMContentLoaded", function(event) {
    let scrollpos = sessionStorage.getItem('checkin_scroll_pos');
    if (scrollpos) window.scrollTo(0, scrollpos);
  });

  window.onbeforeunload = function(e) {
    sessionStorage.setItem('checkin_scroll_pos', window.scrollY);
  };
</script>

<?php require './views/components/footer.php'; ?>