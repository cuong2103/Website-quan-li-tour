<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex justify-between flex-wrap items-center gap-3 mb-4">
                <h2 class="text-base font-semibold text-gray-800">Quản lý Check-in</h2>
            </div>

            <?php if (!empty($checkinData)): ?>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th class="px-4 py-3">STT</th>
                                <th class="px-4 py-3">Tiêu đề</th>
                                <th class="px-4 py-3">Ghi chú</th>
                                <th class="px-4 py-3">Thời gian tạo</th>
                                <th class="px-4 py-3">Người tạo</th>
                                <th class="px-4 py-3">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; foreach ($checkinData as $linkId => $data): ?>
                                <?php $link = $data['link']; ?>
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-4 py-3"><?= $i++ ?></td>
                                    <td class="px-4 py-3 font-medium text-gray-900"><?= htmlspecialchars($link['title']) ?></td>
                                    <td class="px-4 py-3"><?= htmlspecialchars($link['note'] ?? '-') ?></td>
                                    <td class="px-4 py-3"><?= date('H:i d/m/Y', strtotime($link['created_at'])) ?></td>
                                    <td class="px-4 py-3"><?= htmlspecialchars($data['created_by_name']) ?></td>
                                    <td class="px-4 py-3">
                                        <button onclick="document.getElementById('checkin-modal-<?= $linkId ?>').classList.remove('hidden')"
                                            class="text-blue-600 hover:text-blue-800 font-medium text-xs flex items-center gap-1">
                                            <i data-lucide="eye" class="w-4 h-4"></i> Chi tiết
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Modals for Details -->
                <?php foreach ($checkinData as $linkId => $data): ?>
                    <?php 
                    $link = $data['link']; 
                    $customersWithStatus = $data['customers'];
                    ?>
                    <div id="checkin-modal-<?= $linkId ?>" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
                        <div class="bg-white rounded-xl w-full max-w-4xl max-h-[90vh] overflow-y-auto">
                            <div class="p-6 border-b border-gray-100 flex justify-between items-center sticky top-0 bg-white z-10">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800">Chi tiết: <?= htmlspecialchars($link['title']) ?></h3>
                                    <p class="text-sm text-gray-500">Tour: <?= htmlspecialchars($booking['tour_name']) ?> (<?= $booking['booking_code'] ?>)</p>
                                </div>
                                <button onclick="document.getElementById('checkin-modal-<?= $linkId ?>').classList.add('hidden')"
                                    class="text-gray-400 hover:text-gray-600">
                                    <i data-lucide="x" class="w-6 h-6"></i>
                                </button>
                            </div>

                            <div class="p-6">
                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm text-left text-gray-500">
                                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-3">STT</th>
                                                <th class="px-4 py-3">Tên khách hàng</th>
                                                <th class="px-4 py-3">SĐT</th>
                                                <th class="px-4 py-3">Email</th>
                                                <th class="px-4 py-3">Số phòng</th>
                                                <th class="px-4 py-3">Trạng thái</th>
                                                <th class="px-4 py-3">Thời gian</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($customersWithStatus)): ?>
                                                <?php foreach ($customersWithStatus as $idx => $c): ?>
                                                    <tr class="bg-white border-b hover:bg-gray-50">
                                                        <td class="px-4 py-3"><?= $idx + 1 ?></td>
                                                        <td class="px-4 py-3 font-medium text-gray-900"><?= htmlspecialchars($c['name']) ?></td>
                                                        <td class="px-4 py-3"><?= htmlspecialchars($c['phone']) ?></td>
                                                        <td class="px-4 py-3"><?= htmlspecialchars($c['email'] ?? '-') ?></td>
                                                        <td class="px-4 py-3"><?= htmlspecialchars($c['room_number'] ?? '-') ?></td>
                                                        <td class="px-4 py-3">
                                                            <?php if ($c['checkin_id']): ?>
                                                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-medium flex items-center gap-1 w-fit">
                                                                    <i data-lucide="check-circle" class="w-3 h-3"></i> Đã check-in
                                                                </span>
                                                            <?php else: ?>
                                                                <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded text-xs font-medium">Chưa check-in</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="px-4 py-3">
                                                            <?= $c['checkin_time'] ? date('H:i d/m/Y', strtotime($c['checkin_time'])) : '-' ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                                        Không có khách hàng nào.
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="p-6 border-t border-gray-100 bg-gray-50 flex justify-end rounded-b-xl">
                                <button onclick="document.getElementById('checkin-modal-<?= $linkId ?>').classList.add('hidden')"
                                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg text-sm font-medium">
                                    Đóng
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

            <?php else: ?>
                <p class="text-gray-500 text-sm flex items-center gap-2">
                    <i class="w-4 h-4 text-gray-400" data-lucide="info"></i>
                    Chưa có đợt check-in nào được tạo.
                </p>
            <?php endif; ?>
        </div>