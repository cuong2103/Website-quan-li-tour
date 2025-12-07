<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';
?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<main class="pt-28 px-6 pb-20 overflow-auto scrollbar-hide">

  <div class="flex justify-between items-center mb-6">
    <div>
      <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
      <p class="text-sm text-gray-600">Tổng quan hoạt động kinh doanh</p>
    </div>
    <div class="flex space-x-3">
      <a href="<?= BASE_URL ?>?act=booking-create" class="px-5 py-2.5  text-white text-sm font-medium rounded-lg bg-orange-400 hover:bg-orange-500 flex items-center space-x-2">
        <i data-lucide="plus" class="w-5 h-5"></i>
        <span>Tạo Booking</span>
      </a>
      <a href="<?= BASE_URL ?>?act=tours-create" class="px-5 py-2.5  text-gray-900 text-sm font-medium rounded-lg border border-gray-300 hover:bg-gray-100 flex items-center space-x-2">
        <i data-lucide="plus" class="w-5 h-5"></i>
        <span>Tạo Tour</span>
      </a>
    </div>
  </div>

  <!-- 4 CARD THỐNG KÊ -->
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Card 1: Booking mới -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm text-gray-600">Booking mới (tháng này)</p>
          <p class="text-3xl font-bold text-gray-900 mt-2"><?= $currentBookings ?? 0 ?></p>
          <?php if (isset($bookingGrowth)): ?>
            <?php if ($bookingGrowth >= 0): ?>
              <p class="text-sm text-green-600 mt-2 flex items-center">
                <i data-lucide="arrow-up" class="w-4 h-4 mr-1"></i>
                +<?= number_format($bookingGrowth, 1) ?>% so với tháng trước
              </p>
            <?php else: ?>
              <p class="text-sm text-red-600 mt-2 flex items-center">
                <i data-lucide="arrow-down" class="w-4 h-4 mr-1"></i>
                <?= number_format($bookingGrowth, 1) ?>% so với tháng trước
              </p>
            <?php endif; ?>
          <?php endif; ?>
        </div>
        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
          <i data-lucide="calendar-plus" class="w-6 h-6 text-blue-600"></i>
        </div>
      </div>
    </div>

    <!-- Card 2: Doanh thu -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm text-gray-600">Doanh thu (tháng này)</p>
          <p class="text-3xl font-bold text-gray-900 mt-2"><?= number_format($currentRevenue ?? 0, 0, ',', '.') ?>đ</p>
          <?php if (isset($revenueGrowth)): ?>
            <?php if ($revenueGrowth >= 0): ?>
              <p class="text-sm text-green-600 mt-2 flex items-center">
                <i data-lucide="arrow-up" class="w-4 h-4 mr-1"></i>
                +<?= number_format($revenueGrowth, 1) ?>% so với tháng trước
              </p>
            <?php else: ?>
              <p class="text-sm text-red-600 mt-2 flex items-center">
                <i data-lucide="arrow-down" class="w-4 h-4 mr-1"></i>
                <?= number_format($revenueGrowth, 1) ?>% so với tháng trước
              </p>
            <?php endif; ?>
          <?php endif; ?>
        </div>
        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
          <i data-lucide="wallet" class="w-6 h-6 text-green-600"></i>
        </div>
      </div>
    </div>

    <!-- Card 3: Booking chờ xử lý -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm text-gray-600">Booking chờ xử lý</p>
          <p class="text-3xl font-bold text-gray-900 mt-2"><?= $bookingStatusChartData[0] ?? 0 ?></p>
          <p class="text-sm text-gray-600 mt-2">Cần xác nhận</p>
        </div>
        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
          <i data-lucide="clock" class="w-6 h-6 text-orange-600"></i>
        </div>
      </div>
    </div>

    <!-- Card 4: Booking hoàn thành -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm text-gray-600">Booking hoàn thành</p>
          <p class="text-3xl font-bold text-gray-900 mt-2"><?= $bookingStatusChartData[2] ?? 0 ?></p>
          <p class="text-sm text-gray-600 mt-2">Tổng đã hoàn thành</p>
        </div>
        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
          <i data-lucide="check-circle" class="w-6 h-6 text-purple-600"></i>
        </div>
      </div>
    </div>
  </div>

  <!-- BIỂU ĐỒ + TRẠNG THÁI BOOKING -->
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Biểu đồ doanh thu 6 tháng gần nhất -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
      <h3 class="text-lg font-semibold text-gray-900 mb-4">Doanh thu 6 tháng gần nhất</h3>
      <canvas id="revenueChart" height="100"></canvas>
    </div>

    <!-- Biểu đồ trạng thái Booking -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
      <h3 class="text-lg font-semibold text-gray-900 mb-4">Trạng thái Booking</h3>
      <canvas id="bookingStatusChart" height="100"></canvas>
    </div>
  </div>

  <!-- BẢNG BOOKING CHỜ XỬ LÝ -->
  <div class="bg-white rounded-xl shadow-sm border border-gray-200">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
      <h3 class="text-lg font-semibold text-gray-900">Booking chờ xử lý</h3>
      <a href="?page=bookings" class="text-sm text-indigo-600 hover:text-indigo-800">Xem tất cả →</a>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên Tour</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Khách hàng</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày đi</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tổng tiền</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hành động</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <?php if (!empty($pendingBookings)): ?>
            <?php foreach ($pendingBookings as $index => $booking): ?>
              <tr class="<?= $index % 2 == 1 ? 'bg-gray-50' : '' ?>">
                <td class="px-6 py-4 text-sm text-gray-900"><?= htmlspecialchars($booking['tour_name']) ?></td>
                <td class="px-6 py-4 text-sm text-gray-900"><?= htmlspecialchars($booking['customer_name'] ?? 'N/A') ?></td>
                <td class="px-6 py-4 text-sm text-gray-500"><?= date('d/m/Y', strtotime($booking['start_date'])) ?></td>
                <td class="px-6 py-4 text-sm font-medium text-gray-900"><?= number_format($booking['total_amount'], 0, ',', '.') ?>đ</td>
                <td class="px-6 py-4 text-sm text-gray-500">
                  <div class="flex items-center space-x-4">
                    <!-- Xem -->
                    <a href="?act=booking-detail&id=<?= $booking['id'] ?>" class="text-gray-600 hover:text-orange-600 transition">
                      <i data-lucide="eye" class="w-5 h-5"></i>
                    </a>
                    <!-- Sửa -->
                    <a href="?act=booking-edit&id=<?= $booking['id'] ?>" class="text-blue-400 hover:text-blue-600 transition">
                      <i data-lucide="edit" class="w-5 h-5"></i>
                    </a>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-3 text-gray-300"></i>
                <p>Không có booking nào đang chờ xử lý</p>
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</main>

<!-- ==================== JAVASCRIPT VẼ BIỂU ĐỒ (đặt trước </body>) ==================== -->
<script>
  // BIỂU ĐỒ DOANH THU 6 THÁNG (Line Chart)
  document.addEventListener("DOMContentLoaded", function() {
    const revenueData = <?= json_encode($revenueChartData ?? []) ?>;
    console.log(revenueData);
    const bookingStatusData = <?= json_encode($bookingStatusChartData ?? []) ?>;
    console.log(bookingStatusData);
    new Chart(document.getElementById('revenueChart'), {
      type: 'line',
      data: {
        labels: ['T7', 'T8', 'T9', 'T10', 'T11', 'T12'],
        datasets: [{
          label: 'Doanh thu (VNĐ)',
          data: revenueData,
          borderColor: '#FF571A',
          backgroundColor: 'rgba(255, 87, 26, 0.15)',
          tension: 0.4,
          fill: true,
          pointBackgroundColor: '#FF571A',
          pointBorderColor: '#ffffff',
          pointBorderWidth: 3,
          pointRadius: 6,
          pointHoverRadius: 8,
          pointHoverBackgroundColor: '#FF571A',
          pointHoverBorderColor: '#ffffff'
        }]
      },
      options: {
        plugins: {
          legend: {
            display: false
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              callback: function(value) {
                return new Intl.NumberFormat('vi-VN').format(value);
              }
            }
          }
        }
      }
    });


    // BIỂU ĐỒ TRẠNG THÁI BOOKING (Bar Chart)
    new Chart(document.getElementById('bookingStatusChart'), {
      type: 'bar',
      data: {
        labels: ['Chờ xử lý', 'Đã cọc', 'Đã thanh toán', 'Đã hủy', 'Hoàn thành'],
        datasets: [{
          label: 'Số lượng',
          data: bookingStatusData,
          backgroundColor: [
            '#FBBF24',
            '#3B82F6',
            '#10B981',
            '#EF4444',
            '#8B5CF6'
          ],
          borderColor: '#ffffff',
          borderWidth: 3,
          borderRadius: 8,
          borderSkipped: false,
          hoverBackgroundColor: [
            '#F59E0B',
            '#2563EB',
            '#059669',
            '#DC2626',
            '#7C3AED'
          ]
        }]
      },
      options: {
        plugins: {
          legend: {
            display: false
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              stepSize: 1
            }
          }
        }
      }
    });
  });
</script>
<?php
require_once './views/components/footer.php';
?>