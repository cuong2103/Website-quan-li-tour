<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';
?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<main class="mt-28 px-6 pb-20 overflow-auto scrollbar-hide">

  <!-- Ti ÊU ĐỀ + NÚT TẠO -->
  <div class="flex justify-between items-center mb-6">
    <div>
      <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
      <p class="text-sm text-gray-600">Tổng quan hoạt động kinh doanh</p>
    </div>
    <div class="flex space-x-3">
      <button class="px-5 py-2.5  text-white text-sm font-medium rounded-lg bg-orange-400 hover:bg-orange-500 flex items-center space-x-2">
        <i data-lucide="plus" class="w-5 h-5"></i>
        <span>Tạo Booking</span>
      </button>
      <button class="px-5 py-2.5  text-gray-900 text-sm font-medium rounded-lg border border-gray-300 hover:bg-gray-100 flex items-center space-x-2">
        <i data-lucide="plus" class="w-5 h-5"></i>
        <span>Tạo Tour</span>
      </button>
    </div>
  </div>

  <!-- 4 CARD THỐNG KÊ -->
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Card 1: Booking mới -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm text-gray-600">Booking mới (tháng này)</p>
          <p class="text-3xl font-bold text-gray-900 mt-2">48</p>
          <p class="text-sm text-green-600 mt-2 flex items-center">
            <i data-lucide="arrow-up" class="w-4 h-4 mr-1"></i>
            +12% so với tháng trước
          </p>
        </div>
        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
          <i data-lucide="arrow-up" class="w-6 h-6 text-blue-600"></i>
        </div>
      </div>
    </div>

    <!-- Card 2: Doanh thu -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm text-gray-600">Doanh thu (tháng này)</p>
          <p class="text-3xl font-bold text-gray-900 mt-2">67,000,000đ</p>
          <p class="text-sm text-green-600 mt-2 flex items-center">
            <i data-lucide="arrow-up" class="w-4 h-4 mr-1"></i>
            +8% so với tháng trước
          </p>
        </div>
        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
          <i data-lucide="arrow-up" class="w-6 h-6 text-green-600"></i>
        </div>
      </div>
    </div>

    <!-- Card 3: Tour đang hoạt động -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm text-gray-600">Tour đang hoạt động</p>
          <p class="text-3xl font-bold text-gray-900 mt-2">24</p>
          <p class="text-sm text-gray-600 mt-2">8 tour đang trong chuyến đi</p>
        </div>
        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
          <i data-lucide="users" class="w-6 h-6 text-purple-600"></i>
        </div>
      </div>
    </div>

    <!-- Card 4: Khách hàng mới -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm text-gray-600">Khách hàng mới</p>
          <p class="text-3xl font-bold text-gray-900 mt-2">156</p>
          <p class="text-sm text-red-600 mt-2 flex items-center">
            <i data-lucide="arrow-down" class="w-4 h-4 mr-1 rotate-180"></i>
            -24% so với tháng trước
          </p>
        </div>
        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
          <i data-lucide="arrow-down" class="w-6 h-6 text-orange-600"></i>
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
      <a href="#" class="text-sm text-indigo-600 hover:text-indigo-800">Xem tất cả →</a>
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
          <tr>
            <td class="px-6 py-4 text-sm text-gray-900">Tour Hà Nội - Sapa 3N2Đ</td>
            <td class="px-6 py-4 text-sm text-gray-900">Trần Thị B</td>
            <td class="px-6 py-4 text-sm text-gray-500">2024-12-20</td>
            <td class="px-6 py-4 text-sm font-medium text-gray-900">12,500,000đ</td>
            <td class="px-6 py-4 text-sm text-gray-500">
              <div class="flex items-center space-x-4">
                <!-- Xem -->
                <a href="#" class="text-gray-600 hover:text-orange-600 transition">
                  <i data-lucide="eye" class="w-5 h-5"></i>
                </a>
                <!-- Sửa -->
                <a href="#" class=" hover:blue-600 text-blue-400 transition">
                  <i data-lucide="edit" class="w-5 h-5"></i>
                </a>
                <!-- Xóa -->
                <button type="button" onclick="confirmDelete()" class="text-red-400 hover:text-red-600 transition">
                  <i data-lucide="trash" class="w-5 h-5"></i>
                </button>
              </div>
            </td>
          </tr>

          <!-- Các dòng khác tương tự... -->
          <tr class="bg-gray-50">
            <td class="px-6 py-4 text-sm text-gray-900">Tour Đà Nẵng - Hội An 4N3Đ</td>
            <td class="px-6 py-4 text-sm text-gray-900">Lê Văn C</td>
            <td class="px-6 py-4 text-sm text-gray-500">2024-12-22</td>
            <td class="px-6 py-4 text-sm font-medium text-gray-900">18,900,000đ</td>
            <td class="px-6 py-4 text-sm text-gray-500">
              <div class="flex items-center space-x-4">
                <a href="#" class="text-gray-600 hover:text-orange-600 transition">
                  <i data-lucide="eye" class="w-5 h-5"></i>
                </a>
                <a href="#" class="text-gray-600 hover:text-orange-600 transition">
                  <i data-lucide="edit" class="w-5 h-5"></i>
                </a>
                <button type="button" class="text-red-400 hover:text-red-600 transition">
                  <i data-lucide="trash" class="w-5 h-5"></i>
                </button>
              </div>
            </td>
          </tr>

          <!-- Dòng 3 -->
          <tr>
            <td class="px-6 py-4 text-sm text-gray-900">Tour Phú Quốc 5N4Đ</td>
            <td class="px-6 py-4 text-sm text-gray-900">Phạm Văn D</td>
            <td class="px-6 py-4 text-sm text-gray-500">2024-12-25</td>
            <td class="px-6 py-4 text-sm font-medium text-gray-900">25,000,000đ</td>
            <td class="px-6 py-4 text-sm text-gray-500">
              <div class="flex items-center space-x-4">
                <a href="#" class="text-gray-600 hover:text-orange-600 transition">
                  <i data-lucide="eye" class="w-5 h-5"></i>
                </a>
                <a href="#" class="text-gray-600 hover:text-orange-600 transition">
                  <i data-lucide="edit" class="w-5 h-5"></i>
                </a>
                <button type="button" class="text-red-400 hover:text-red-600 transition">
                  <i data-lucide="trash" class="w-5 h-5"></i>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</main>

<!-- ==================== JAVASCRIPT VẼ BIỂU ĐỒ (đặt trước </body>) ==================== -->
<script>
  // BIỂU ĐỒ DOANH THU 6 THÁNG (Line Chart)
  const data = [45, 52, 48, 55, 50, 67]
  new Chart(document.getElementById('revenueChart'), {
    type: 'line',
    data: {
      labels: ['T1', 'T2', 'T3', 'T4', 'T5', 'T6'],
      datasets: [{
        label: 'Doanh thu (triệu đồng)',
        data: data,
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
          suggestedMax: Math.max(...data) * 1.1
        }
      }
    }
  });


  // BIỂU ĐỒ TRẠNG THÁI BOOKING (Bar Chart)
  new Chart(document.getElementById('bookingStatusChart'), {
    type: 'bar',
    data: {
      labels: ['Chờ xử lý', 'Đã cọc', 'Hoàn thành', 'Đã hủy'],
      datasets: [{
        label: 'Số lượng',
        data: data,
        backgroundColor: [
          '#FF8C42',
          '#FF571A',
          '#FF9F1C',
          '#FF6B35'
        ],
        borderColor: '#ffffff',
        borderWidth: 3,
        borderRadius: 8,
        borderSkipped: false,
        hoverBackgroundColor: [
          '#FF6B1A',
          '#E04A10',
          '#E58A17',
          '#E55A2B'
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
          suggestedMax: Math.max(...data) * 0.5
        }
      }
    }
  });
</script>
<?php
require_once './views/components/footer.php';
?>