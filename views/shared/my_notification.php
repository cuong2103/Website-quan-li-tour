<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';
?>

<main class="min-h-screen mt-24 bg-gray-50 py-8 px-4">
  <div class="max-w-10xl mx-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
      <div>
        <h1 class="text-2xl font-semibold text-gray-900">Thông báo</h1>
        <p class="text-sm text-gray-500 mt-1">Bạn có <?= $unreadCount ?> thông báo chưa đọc</p>
      </div>

      <?php if ($unreadCount > 0): ?>
        <a href="?act=mark-all-notifications-read"
          class="flex items-center gap-2 text-sm text-gray-600 hover:text-gray-800 transition-colors">
          <i data-lucide="check" class="w-4 h-4"></i>
          Đánh dấu tất cả đã đọc
        </a>
      <?php endif; ?>
    </div>

    <!-- Danh sách thông báo -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
      <div class="px-6 py-4 border-b border-gray-100">
        <h2 class="text-sm font-medium text-gray-700">Tất cả thông báo</h2>
      </div>

      <div class="divide-y divide-gray-100">
        <?php if (empty($notifications)): ?>
          <div class="px-6 py-12 text-center">
            <i data-lucide="bell" class="w-16 h-16 mx-auto text-gray-300 mb-4"></i>
            <p class="text-gray-500 text-sm">Chưa có thông báo nào</p>
          </div>
        <?php else: ?>
          <?php foreach ($notifications as $notification): ?>
            <!-- Thông báo chưa đọc (nền xanh nhạt) -->
            <?php if (!$notification['is_read']): ?>
              <a href="?act=read-notification&id=<?= $notification['id'] ?>"
                class="block px-6 py-5 bg-blue-50/50 hover:bg-blue-50 transition-colors group">
                <div class="flex items-start gap-4">
                  <div class="w-2 h-2 rounded-full bg-blue-600 mt-1.5 flex-shrink-0"></div>
                  <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                      <h3 class="text-sm font-semibold text-gray-900"><?= htmlspecialchars($notification['title']) ?></h3>
                      <span class="text-xs text-gray-500"><?= timeAgo($notification['created_at']) ?></span>
                    </div>
                    <p class="mt-1 text-sm text-gray-700"><?= htmlspecialchars($notification['message']) ?></p>
                    <?php if ($notification['type'] !== 'general'): ?>
                      <span class="inline-block mt-2 px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-700">
                        <?= ucfirst($notification['type']) ?>
                      </span>
                    <?php endif; ?>
                  </div>
                </div>
              </a>

              <!-- Thông báo đã đọc (nền trắng) -->
            <?php else: ?>
              <div class="px-6 py-5 hover:bg-gray-50 transition-colors cursor-pointer">
                <div class="flex items-start gap-4">
                  <div class="w-2 h-2 rounded-full bg-gray-300 mt-1.5 flex-shrink-0"></div>
                  <div class="flex-1 min-w-0">
                    <h3 class="text-sm font-medium text-gray-600"><?= htmlspecialchars($notification['title']) ?></h3>
                    <p class="mt-1 text-sm text-gray-500"><?= htmlspecialchars($notification['message']) ?></p>
                    <?php if ($notification['type'] !== 'general'): ?>
                      <span class="inline-block mt-2 px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-600">
                        <?= ucfirst($notification['type']) ?>
                      </span>
                    <?php endif; ?>
                    <div class="mt-2 text-xs text-gray-400">
                      <?= timeAgo($notification['created_at']) ?>
                      <?php if ($notification['read_at']): ?>
                        • Đã đọc <?= timeAgo($notification['read_at']) ?>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              </div>
            <?php endif; ?>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
</main>

<?php require_once './views/components/footer.php'; ?>