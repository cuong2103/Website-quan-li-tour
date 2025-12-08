<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';
?>

<main class="pt-28 px-6 pb-20 text-gray-700">
    
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-semibold">Đơn Xin Nghỉ Phép Chờ Duyệt</h1>
        <a href="<?= BASE_URL ?>?act=user"
            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg text-sm">
            Quay lại
        </a>
    </div>

    <?php if (!empty($requests)): ?>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hướng dẫn viên</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Từ ngày</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Đến ngày</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lý do</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hành động</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($requests as $req): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <?php if ($req['avatar']): ?>
                                            <img src="uploads/avatar/<?= $req['avatar'] ?>" 
                                                alt="Avatar" 
                                                class="w-10 h-10 rounded-full object-cover">
                                        <?php else: ?>
                                            <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center">
                                                <i class="w-5 h-5 text-gray-500" data-lucide="user"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <p class="font-medium text-gray-900"><?= htmlspecialchars($req['fullname']) ?></p>
                                            <p class="text-sm text-gray-500"><?= htmlspecialchars($req['email']) ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    <i class="w-4 h-4 inline text-gray-400" data-lucide="calendar"></i>
                                    <?= date('d/m/Y', strtotime($req['leave_start'])) ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    <i class="w-4 h-4 inline text-gray-400" data-lucide="calendar"></i>
                                    <?= date('d/m/Y', strtotime($req['leave_end'])) ?>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-700 max-w-xs truncate" title="<?= htmlspecialchars($req['leave_reason']) ?>">
                                        <?= htmlspecialchars($req['leave_reason']) ?>
                                    </p>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex gap-2">
                                        <a href="<?= BASE_URL ?>?act=user-approve-leave&id=<?= $req['id'] ?>"
                                            onclick="return confirm('Duyệt đơn xin nghỉ của <?= htmlspecialchars($req['fullname']) ?>?');"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium"
                                            title="Duyệt">
                                            <i class="w-4 h-4" data-lucide="check"></i>
                                            Duyệt
                                        </a>
                                        <a href="<?= BASE_URL ?>?act=user-reject-leave&id=<?= $req['id'] ?>"
                                            onclick="return confirm('Từ chối đơn xin nghỉ của <?= htmlspecialchars($req['fullname']) ?>?');"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium"
                                            title="Từ chối">
                                            <i class="w-4 h-4" data-lucide="x"></i>
                                            Từ chối
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php else: ?>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="w-8 h-8 text-gray-400" data-lucide="inbox"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Không có đơn chờ duyệt</h3>
            <p class="text-gray-500">Hiện tại không có đơn xin nghỉ phép nào cần duyệt.</p>
        </div>
    <?php endif; ?>

</main>

<?php require_once './views/components/footer.php'; ?>
