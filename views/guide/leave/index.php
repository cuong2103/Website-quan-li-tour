<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';

// Xác định trạng thái
$hasRequest = !empty($leaveRequest['leave_start']);
$status = $leaveRequest['leave_status'] ?? null;
$canRequest = !$hasRequest || $status === 'rejected';
?>

<main class="pt-28 px-6 pb-20 text-gray-700">
    
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-semibold">Đơn Xin Nghỉ Phép</h1>
        <?php if ($canRequest): ?>
            <a href="<?= BASE_URL ?>?act=guide-leave-create"
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium">
                <i class="w-4 h-4 inline" data-lucide="plus"></i>
                Xin nghỉ phép
            </a>
        <?php endif; ?>
    </div>

    <?php if ($hasRequest): ?>
        <!-- Có đơn xin nghỉ -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            
            <!-- Trạng thái -->
            <div class="mb-4">
                <h2 class="text-base font-semibold mb-3">Trạng thái đơn</h2>
                <?php
                $statusConfig = [
                    'pending' => ['icon' => 'clock', 'text' => 'Chờ duyệt', 'class' => 'bg-yellow-100 text-yellow-800'],
                    'approved' => ['icon' => 'check-circle', 'text' => 'Đã duyệt', 'class' => 'bg-green-100 text-green-800'],
                    'rejected' => ['icon' => 'x-circle', 'text' => 'Bị từ chối', 'class' => 'bg-red-100 text-red-800']
                ];
                $config = $statusConfig[$status] ?? ['icon' => 'help-circle', 'text' => 'Không xác định', 'class' => 'bg-gray-100 text-gray-800'];
                ?>
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full <?= $config['class'] ?>">
                    <i class="w-4 h-4" data-lucide="<?= $config['icon'] ?>"></i>
                    <span class="font-medium"><?= $config['text'] ?></span>
                </div>
            </div>

            <!-- Thông tin đơn -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Từ ngày</p>
                    <p class="font-medium flex items-center gap-2">
                        <i class="w-4 h-4" data-lucide="calendar"></i>
                        <?= date('d/m/Y', strtotime($leaveRequest['leave_start'])) ?>
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Đến ngày</p>
                    <p class="font-medium flex items-center gap-2">
                        <i class="w-4 h-4" data-lucide="calendar"></i>
                        <?= date('d/m/Y', strtotime($leaveRequest['leave_end'])) ?>
                    </p>
                </div>
            </div>

            <div class="mb-4">
                <p class="text-sm text-gray-500 mb-1">Lý do</p>
                <p class="text-gray-700"><?= nl2br(htmlspecialchars($leaveRequest['leave_reason'])) ?></p>
            </div>

            <!-- Actions -->
            <?php if ($status === 'pending'): ?>
                <div class="flex gap-2">
                    <a href="<?= BASE_URL ?>?act=guide-leave-cancel"
                        onclick="return confirm('Bạn có chắc muốn hủy đơn xin nghỉ này?');"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium">
                        Hủy đơn
                    </a>
                </div>
            <?php elseif ($status === 'rejected'): ?>
                <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                    <p class="text-red-800 text-sm">
                        <i class="w-4 h-4 inline" data-lucide="info"></i>
                        Đơn của bạn đã bị từ chối. Bạn có thể xin nghỉ lại bằng cách click nút "Xin nghỉ phép" ở trên.
                    </p>
                </div>
            <?php elseif ($status === 'approved'): ?>
                <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                    <p class="text-green-800 text-sm">
                        <i class="w-4 h-4 inline" data-lucide="check-circle"></i>
                        Đơn xin nghỉ của bạn đã được duyệt. Bạn sẽ nghỉ từ ngày <?= date('d/m/Y', strtotime($leaveRequest['leave_start'])) ?> đến <?= date('d/m/Y', strtotime($leaveRequest['leave_end'])) ?>.
                    </p>
                </div>
            <?php endif; ?>

        </div>
    <?php else: ?>
        <!-- Chưa có đơn -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="w-8 h-8 text-gray-400" data-lucide="calendar-off"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Chưa có đơn xin nghỉ phép</h3>
            <p class="text-gray-500 mb-4">Bạn chưa có đơn xin nghỉ phép nào. Click nút bên trên để tạo đơn mới.</p>
        </div>
    <?php endif; ?>

</main>

<?php require_once './views/components/footer.php'; ?>
