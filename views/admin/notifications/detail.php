<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';
?>

<main class="pt-28 pt-8 pb-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
  <!-- Breadcrumb & Actions -->
  <div class="flex items-center justify-between mb-8">
    <nav class="flex items-center gap-2 text-sm">
      <a href="<?= BASE_URL ?>?act=notifications" class="text-gray-500 hover:text-gray-700 transition-colors">
        Thông báo
      </a>
      <i class="w-4 h-4 text-gray-400" data-lucide="chevron-right"></i>
      <span class="text-gray-900 font-medium">Chi tiết</span>
    </nav>

    <!-- Action Buttons -->
    <div class="flex items-center gap-3">
      <a href="<?= BASE_URL ?>?act=notification-edit&id=<?= $notification['id'] ?>"
        class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
        <i class="w-4 h-4" data-lucide="edit-3"></i>
        <span class="font-medium">Chỉnh sửa</span>
      </a>
      <a href="<?= BASE_URL ?>?act=notification-delete&id=<?= $notification['id'] ?>"
        onclick="return confirm('Bạn có chắc muốn xóa thông báo này?')"
        class="inline-flex items-center gap-2 px-4 py-2 bg-red-50 border border-red-200 text-red-700 rounded-lg hover:bg-red-100 transition-colors">
        <i class="w-4 h-4" data-lucide="trash-2"></i>
        <span class="font-medium">Xóa</span>
      </a>
    </div>
  </div>

  <div class="grid lg:grid-cols-3 gap-6">
    <!-- Nội dung chính -->
    <div class="lg:col-span-2 space-y-6">
      <!-- Card thông báo -->
      <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <!-- Header với type badge -->
        <div class="px-8 pt-8 pb-6 border-b border-gray-100">
          <div class="flex items-start justify-between gap-4 mb-6">
            <h1 class="text-3xl font-bold text-gray-900 leading-tight flex-1">
              <?= htmlspecialchars($notification['title']) ?>
            </h1>

            <?php
            $badges = [
              'general' => ['bg-gray-100 text-gray-700 border-gray-200', 'Thông tin chung', 'info'],
              'booking' => ['bg-blue-100 text-blue-700 border-blue-200', 'Booking', 'calendar'],
              'tour' => ['bg-emerald-100 text-emerald-700 border-emerald-200', 'Tour', 'map'],
              'payment' => ['bg-amber-100 text-amber-700 border-amber-200', 'Thanh toán', 'credit-card'],
              'urgent' => ['bg-rose-100 text-rose-700 border-rose-200', 'Khẩn cấp', 'alert-circle'],
            ];
            $badge = $badges[$notification['type']] ?? $badges['general'];
            ?>
            <span class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-full border <?= $badge[0] ?>">
              <i class="w-4 h-4" data-lucide="<?= $badge[2] ?>"></i>
              <?= $badge[1] ?>
            </span>
          </div>

          <!-- Metadata -->
          <div class="flex flex-wrap items-center gap-6 text-sm text-gray-600">
            <div class="flex items-center gap-2">
              <i class="w-4 h-4 text-gray-400" data-lucide="calendar"></i>
              <span>Tạo: <?= date('H:i, d/m/Y', strtotime($notification['created_at'])) ?></span>
            </div>
            <?php if ($notification['updated_at']): ?>
              <div class="flex items-center gap-2">
                <i class="w-4 h-4 text-gray-400" data-lucide="clock"></i>
                <span>Cập nhật: <?= date('H:i, d/m/Y', strtotime($notification['updated_at'])) ?></span>
              </div>
            <?php endif; ?>
          </div>
        </div>

        <!-- Nội dung -->
        <div class="px-8 py-8">
          <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
            <?= nl2br(htmlspecialchars($notification['message'])) ?>
          </div>
        </div>

        <!-- Thông tin người tạo/sửa -->
        <div class="px-8 py-6 bg-gray-50 border-t border-gray-200">
          <div class="grid md:grid-cols-2 gap-6">
            <!-- Người tạo -->
            <div class="flex items-start gap-4">
              <div class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-lg shadow-md flex-shrink-0">
                <?= strtoupper(mb_substr($notification['creator_name'] ?? 'H', 0, 2)) ?>
              </div>
              <div class="flex-1 min-w-0">
                <div class="text-xs font-semibold text-gray-500 uppercase mb-1">Người tạo</div>
                <div class="font-semibold text-gray-900"><?= htmlspecialchars($notification['creator_name'] ?? 'Hệ thống') ?></div>
                <?php if (!empty($notification['creator_email'])): ?>
                  <div class="text-sm text-gray-600 truncate"><?= htmlspecialchars($notification['creator_email']) ?></div>
                <?php endif; ?>
              </div>
            </div>

            <!-- Người sửa cuối -->
            <?php if ($notification['updated_by'] && $notification['updater_name']): ?>
              <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-green-500 to-teal-600 flex items-center justify-center text-white font-bold text-lg shadow-md flex-shrink-0">
                  <?= strtoupper(mb_substr($notification['updater_name'], 0, 2)) ?>
                </div>
                <div class="flex-1 min-w-0">
                  <div class="text-xs font-semibold text-gray-500 uppercase mb-1">Cập nhật cuối</div>
                  <div class="font-semibold text-gray-900"><?= htmlspecialchars($notification['updater_name']) ?></div>
                  <?php if (!empty($notification['updater_email'])): ?>
                    <div class="text-sm text-gray-600 truncate"><?= htmlspecialchars($notification['updater_email']) ?></div>
                  <?php endif; ?>
                </div>
              </div>
            <?php else: ?>
              <div class="flex items-center justify-center text-sm text-gray-500 italic">
                Chưa có chỉnh sửa
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
      <!-- Thống kê -->
      <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
          <i class="w-5 h-5 text-indigo-600" data-lucide="bar-chart-2"></i>
          Thống kê
        </h3>
        <div class="space-y-4">
          <div class="flex items-center justify-between">
            <span class="text-sm text-gray-600">Tổng người nhận</span>
            <span class="text-2xl font-bold text-gray-900"><?= count($recipients) ?></span>
          </div>
          <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
            <?php
            $readCount = count(array_filter($recipients, fn($r) => $r['is_read']));
            $readPercent = count($recipients) > 0 ? ($readCount / count($recipients)) * 100 : 0;
            ?>
            <div class="bg-gradient-to-r from-green-500 to-emerald-600 h-2 rounded-full transition-all duration-500"
              style="width: <?= $readPercent ?>%"></div>
          </div>
          <div class="grid grid-cols-2 gap-3 pt-2">
            <div class="text-center p-3 bg-green-50 rounded-lg border border-green-200">
              <div class="text-2xl font-bold text-green-700"><?= $readCount ?></div>
              <div class="text-xs text-green-600 font-medium mt-1">Đã đọc</div>
            </div>
            <div class="text-center p-3 bg-gray-50 rounded-lg border border-gray-200">
              <div class="text-2xl font-bold text-gray-700"><?= count($recipients) - $readCount ?></div>
              <div class="text-xs text-gray-600 font-medium mt-1">Chưa đọc</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Filter người nhận -->
      <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
            <i class="w-5 h-5 text-indigo-600" data-lucide="users"></i>
            Người nhận
          </h3>
          <select id="filterStatus" class="text-sm border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
            onchange="filterRecipients(this.value)">
            <option value="all">Tất cả</option>
            <option value="read">Đã đọc</option>
            <option value="unread">Chưa đọc</option>
          </select>
        </div>

        <div class="space-y-2 max-h-[500px] overflow-y-auto" id="recipientsList">
          <?php if (empty($recipients)): ?>
            <p class="text-center text-gray-400 py-10 text-sm">Chưa có người nhận nào</p>
          <?php else: ?>
            <?php foreach ($recipients as $r): ?>
              <div class="recipient-item flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 transition-colors border border-transparent hover:border-gray-200"
                data-status="<?= $r['is_read'] ? 'read' : 'unread' ?>">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm shadow-md flex-shrink-0">
                  <?= strtoupper(mb_substr($r['fullname'], 0, 2)) ?>
                </div>
                <div class="flex-1 min-w-0">
                  <p class="font-medium text-gray-900 text-sm truncate"><?= htmlspecialchars($r['fullname']) ?></p>
                  <p class="text-xs text-gray-500 truncate"><?= htmlspecialchars($r['email']) ?></p>
                </div>
                <div class="text-right flex-shrink-0">
                  <?php if ($r['is_read']): ?>
                    <div class="flex items-center gap-1 text-xs font-medium text-green-600 mb-1">
                      <i class="w-3 h-3" data-lucide="check-circle"></i>
                      <span>Đã đọc</span>
                    </div>
                    <div class="text-xs text-gray-400"><?= date('d/m H:i', strtotime($r['read_at'])) ?></div>
                  <?php else: ?>
                    <div class="flex items-center gap-1 text-xs font-medium text-gray-500">
                      <i class="w-3 h-3" data-lucide="circle"></i>
                      <span>Chưa đọc</span>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</main>

<script>
  function filterRecipients(status) {
    const items = document.querySelectorAll('.recipient-item');
    items.forEach(item => {
      if (status === 'all') {
        item.style.display = 'flex';
      } else {
        item.style.display = item.dataset.status === status ? 'flex' : 'none';
      }
    });
  }
</script>

<?php require_once './views/components/footer.php'; ?>