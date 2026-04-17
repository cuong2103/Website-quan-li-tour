<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';
?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<main class="pt-28 px-6 pb-20 overflow-auto scrollbar-hide">

  <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">
    <div>
      <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
      <p class="text-sm text-gray-600">Tổng quan hệ thống - <?= date('d/m/Y') ?></p>
    </div>

    <div class="flex flex-wrap items-center gap-3 sm:justify-end">
      <a
        href="<?= BASE_URL ?>?act=booking-create"
        class="inline-flex items-center gap-2 h-10 px-4 rounded-lg bg-orange-500 text-white text-sm font-semibold hover:bg-orange-600 transition-colors whitespace-nowrap"
      >
        <i data-lucide="plus" class="w-5 h-5"></i>
        <span>Tạo Booking</span>
      </a>

      <a
        href="<?= BASE_URL ?>?act=tours-create"
        class="inline-flex items-center gap-2 h-10 px-4 rounded-lg border border-gray-300 bg-white text-gray-900 text-sm font-semibold hover:bg-gray-50 transition-colors whitespace-nowrap"
      >
        <i data-lucide="plus" class="w-5 h-5"></i>
        <span>Tạo Tour</span>
      </a>
    </div>
  </div>

  <!-- 5 CARD THỐNG KÊ -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4 sm:gap-6 mb-8">
    <?php
    $cards = [
      [
        'title' => 'Tổng doanh thu hôm nay',
        'value' => number_format((float)($revenueToday ?? 0), 0, ',', '.') . ' đ',
        'icon' => 'trending-up',
        'iconBg' => 'bg-green-100',
        'iconColor' => 'text-green-600',
      ],
      [
        'title' => 'Tổng doanh thu tháng này',
        'value' => number_format((float)($revenueMonth ?? 0), 0, ',', '.') . ' đ',
        'icon' => 'wallet',
        'iconBg' => 'bg-emerald-100',
        'iconColor' => 'text-emerald-600',
      ],
      [
        'title' => 'Booking hôm nay',
        'value' => (int)($bookingsToday ?? 0),
        'icon' => 'clipboard-list',
        'iconBg' => 'bg-blue-100',
        'iconColor' => 'text-blue-600',
      ],
      [
        'title' => 'Số khách hàng mới',
        'value' => (int)($newCustomersToday ?? 0),
        'icon' => 'users',
        'iconBg' => 'bg-purple-100',
        'iconColor' => 'text-purple-600',
      ],
      [
        'title' => 'Tổng doanh thu năm',
        'value' => number_format((float)($revenueYear ?? 0), 0, ',', '.') . ' đ',
        'icon' => 'bar-chart-3',
        'iconBg' => 'bg-orange-100',
        'iconColor' => 'text-orange-600',
      ],
    ];
    ?>

    <?php foreach ($cards as $card): ?>
      <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6 min-w-0">
        <div class="flex items-start justify-between">
          <div class="w-11 h-11 sm:w-12 sm:h-12 <?= $card['iconBg'] ?> rounded-lg flex items-center justify-center shrink-0">
            <i data-lucide="<?= $card['icon'] ?>" class="w-6 h-6 <?= $card['iconColor'] ?>"></i>
          </div>
        </div>

        <div class="mt-4">
          <p class="text-sm text-gray-600 truncate" title="<?= htmlspecialchars($card['title']) ?>">
            <?= htmlspecialchars($card['title']) ?>
          </p>
          <p class="text-xl sm:text-2xl font-bold text-gray-900 mt-2 break-words"><?= $card['value'] ?></p>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <!-- BIỂU ĐỒ DOANH THU -->
  <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-4">
      <h3 class="text-lg font-semibold text-gray-900">Biểu đồ doanh thu</h3>

      <div class="flex items-center gap-3">
        <select id="revenueMonthSelect" class="text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white">
          <option value="0">Tất cả tháng</option>
          <?php for ($m = 1; $m <= 12; $m++): ?>
            <option value="<?= $m ?>" <?= ((int)($selectedMonth ?? date('n')) === $m) ? 'selected' : '' ?>>
              Tháng <?= $m ?>
            </option>
          <?php endfor; ?>
        </select>

        <?php $yNow = (int)date('Y'); ?>
        <select id="revenueYearSelect" class="text-sm border border-gray-200 rounded-lg px-3 py-2 bg-white">
          <?php for ($y = $yNow - 5; $y <= $yNow + 1; $y++): ?>
            <option value="<?= $y ?>" <?= ((int)($selectedYear ?? $yNow) === $y) ? 'selected' : '' ?>>
              Năm <?= $y ?>
            </option>
          <?php endfor; ?>
        </select>
      </div>
    </div>

    <div class="relative h-96 sm:h-[420px]">
      <canvas id="revenueChart" class="w-full h-full"></canvas>
    </div>
  </div>

  <!-- BOOKING CHỜ XỬ LÍ -->
  <div class="bg-white rounded-xl shadow-sm border border-gray-200 mt-8">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
      <h3 class="text-lg font-semibold text-gray-900">Các booking đang chờ xử lí</h3>
      <a href="<?= BASE_URL ?>?act=bookings" class="text-sm text-orange-600 hover:text-orange-700 font-medium">Xem tất cả →</a>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mã</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên tour</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Khách hàng</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày đi</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tổng tiền</th>
            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Hành động</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <?php if (!empty($pendingBookings)): ?>
            <?php foreach ($pendingBookings as $index => $booking): ?>
              <tr class="<?= $index % 2 == 1 ? 'bg-gray-50' : '' ?>">
                <td class="px-6 py-4 text-sm text-gray-900">#<?= (int)$booking['id'] ?></td>
                <td class="px-6 py-4 text-sm text-gray-900"><?= htmlspecialchars($booking['tour_name'] ?? '') ?></td>
                <td class="px-6 py-4 text-sm text-gray-900"><?= htmlspecialchars($booking['customer_name'] ?? 'N/A') ?></td>
                <td class="px-6 py-4 text-sm text-gray-500">
                  <?= !empty($booking['start_date']) ? date('d/m/Y', strtotime($booking['start_date'])) : 'N/A' ?>
                </td>
                <td class="px-6 py-4 text-sm font-medium text-gray-900">
                  <?= isset($booking['total_amount']) ? number_format((float)$booking['total_amount'], 0, ',', '.') . 'đ' : 'N/A' ?>
                </td>
                <td class="py-3 px-2">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="<?= BASE_URL . '?act=booking-edit&id=' . $b['id']  ?>"
                                        class="flex items-center gap-1.5 px-2.5 py-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors"
                                        title="Sửa">
                                        <i class="w-3.5 h-3.5" data-lucide="square-pen"></i>
                                        <span class="text-xs font-semibold">Sửa</span>
                                    </a>
                                    <a href="<?= BASE_URL . '?act=booking-detail&id=' . $b['id']  ?>"
                                        class="flex items-center gap-1.5 px-2.5 py-1.5 bg-green-50 text-green-600 rounded-lg hover:bg-green-100 transition-colors"
                                        title="Chi tiết">
                                        <i class="w-3.5 h-3.5" data-lucide="eye"></i>
                                        <span class="text-xs font-semibold">Chi tiết</span>
                                    </a>
                                </div>
                            </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-3 text-gray-300"></i>
                <p>Không có booking nào đang chờ xử lí</p>
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
  // BIỂU ĐỒ DOANH THU (Line Chart)
  document.addEventListener("DOMContentLoaded", function() {
    const revenueData = <?= json_encode($revenueChartData ?? []) ?>;
    const revenueLabels = <?= json_encode($revenueChartLabels ?? []) ?>;
    const canvas = document.getElementById('revenueChart');

    const chartConfig = {
      type: 'line',
      data: {
        labels: revenueLabels,
        datasets: [{
          label: 'Doanh thu (VNĐ)',
          data: revenueData,
          borderColor: '#22C55E',
          backgroundColor: 'rgba(34, 197, 94, 0.12)',
          tension: 0.4,
          fill: true,
          pointBackgroundColor: '#22C55E',
          pointBorderColor: '#ffffff',
          pointBorderWidth: 3,
          pointRadius: 4,
          pointHoverRadius: 6,
          pointHoverBackgroundColor: '#22C55E',
          pointHoverBorderColor: '#ffffff'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        animation: false,
        plugins: {
          legend: {
            display: true,
            position: 'top'
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
    };

    const createRevenueChart = () => {
      if (!canvas) return null;
      return new Chart(canvas, chartConfig);
    };

    let revenueChart = createRevenueChart();

    // Fix chart "stuck" after extreme browser zoom changes
    const forceChartResize = () => {
      try {
        if (!revenueChart) return;
        revenueChart.resize();
        revenueChart.update('none');
      } catch (e) {
        // ignore
      }
    };
    window.addEventListener('resize', forceChartResize, { passive: true });
    document.addEventListener('visibilitychange', forceChartResize);
    setTimeout(forceChartResize, 150);
    setTimeout(forceChartResize, 600);

    // Some browsers don't reliably fire resize on zoom; observe container size instead
    const containerEl = canvas ? canvas.parentElement : null;
    if (containerEl && 'ResizeObserver' in window) {
      let rafId = 0;
      const ro = new ResizeObserver(() => {
        if (rafId) cancelAnimationFrame(rafId);
        rafId = requestAnimationFrame(() => {
          rafId = 0;
          forceChartResize();
        });
      });
      ro.observe(containerEl);
    }

    // Detect zoom (devicePixelRatio) changes; rebuild chart if needed
    let lastDpr = window.devicePixelRatio || 1;
    setInterval(() => {
      const dpr = window.devicePixelRatio || 1;
      if (Math.abs(dpr - lastDpr) < 0.001) return;
      lastDpr = dpr;
      try {
        if (revenueChart) revenueChart.destroy();
      } catch (e) {
        // ignore
      }
      revenueChart = createRevenueChart();
      // force a reflow + resize chain
      void document.body.offsetHeight;
      window.dispatchEvent(new Event('resize'));
      setTimeout(forceChartResize, 0);
      setTimeout(forceChartResize, 200);
    }, 300);

    // Auto-filter without changing the current URL (AJAX)
    const monthEl = document.getElementById('revenueMonthSelect');
    const yearEl = document.getElementById('revenueYearSelect');

    const fetchRevenue = async () => {
      if (!monthEl || !yearEl) return;

      try {
        const body = new URLSearchParams();
        body.set('month', monthEl.value);
        body.set('year', yearEl.value);

        const res = await fetch('<?= BASE_URL ?>?act=dashboard-revenue-data', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8' },
          body: body.toString(),
          credentials: 'same-origin'
        });

        const json = await res.json();
        if (!json || json.ok !== true) return;

        if (!revenueChart) revenueChart = createRevenueChart();
        revenueChart.data.labels = json.labels || [];
        revenueChart.data.datasets[0].data = json.data || [];
        revenueChart.update('none');
        setTimeout(forceChartResize, 0);
      } catch (e) {
        // ignore
      }
    };

    if (monthEl) monthEl.addEventListener('change', fetchRevenue);
    if (yearEl) yearEl.addEventListener('change', fetchRevenue);
  });
</script>
<?php
require_once './views/components/footer.php';
?>