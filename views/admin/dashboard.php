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
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        <span>Tạo Booking</span>
      </button>
      <button class="px-5 py-2.5  text-gray-900 text-sm font-medium rounded-lg border border-gray-300 hover:bg-gray-100 flex items-center space-x-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
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
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 3l7 7-7 7-1.414-1.414L14.172 12H3v-2h11.172L8.586 5.414 10 4z" />
            </svg>
            +12% so với tháng trước
          </p>
        </div>
        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
          <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
          </svg>
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
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 3l7 7-7 7-1.414-1.414L14.172 12H3v-2h11.172L8.586 5.414 10 4z" />
            </svg>
            +8% so với tháng trước
          </p>
        </div>
        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
          <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
          </svg>
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
          <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>
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
            <svg class="w-4 h-4 mr-1 rotate-180" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 3l7 7-7 7-1.414-1.414L14.172 12H3v-2h11.172L8.586 5.414 10 4z" />
            </svg>
            -24% so với tháng trước
          </p>
        </div>
        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
          <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
          </svg>
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
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                  </svg>
                </a>
                <!-- Sửa -->
                <a href="#" class=" hover:blue-600 text-blue-400 transition">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                </a>
                <!-- Xóa -->
                <button type="button" onclick="confirmDelete()" class="text-red-400 hover:text-red-600 transition">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                  </svg>
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
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                  </svg>
                </a>
                <a href="#" class="text-gray-600 hover:text-orange-600 transition">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                </a>
                <button type="button" class="text-red-400 hover:text-red-600 transition">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                  </svg>
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
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                  </svg>
                </a>
                <a href="#" class="text-gray-600 hover:text-orange-600 transition">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                </a>
                <button type="button" class="text-red-400 hover:text-red-600 transition">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                  </svg>
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
  new Chart(document.getElementById('revenueChart'), {
    type: 'line',
    data: {
      labels: ['T1', 'T2', 'T3', 'T4', 'T5', 'T6'],
      datasets: [{
        label: 'Doanh thu (triệu đồng)',
        data: [45, 52, 48, 55, 50, 67], // giữ nguyên hoặc thay bằng biến PHP
        borderColor: '#FF571A', // Cam đậm mạnh – cực nổi bật
        backgroundColor: 'rgba(255, 87, 26, 0.15)', // Cam nhạt trong suốt (đẹp lung linh)
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
          beginAtZero: true
        }
      }
    }
  });

  const data = [12, 28, 18, 5]
  // BIỂU ĐỒ TRẠNG THÁI BOOKING (Bar Chart)
  new Chart(document.getElementById('bookingStatusChart'), {
    type: 'bar',
    data: {
      labels: ['Chờ xử lý', 'Đã cọc', 'Hoàn thành', 'Đã hủy'],
      datasets: [{
        label: 'Số lượng',
        data: data, // giữ nguyên biến data của bạn (PHP truyền vào)
        backgroundColor: [
          '#FF8C42', // Chờ xử lý → cam nhạt tươi (dễ nhìn)
          '#FF571A', // Đã cọc     → cam đậm mạnh (nổi bật nhất)
          '#FF9F1C', // Hoàn thành → cam vàng tươi (tích cực)
          '#FF6B35' // Đã hủy     → cam đỏ nhẹ (cảnh báo vừa đủ)
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
          suggestedMax: Math.max(...data) * 1.25
        }
      }
    }
  });
</script>
<?php
require_once './views/components/footer.php';
?>