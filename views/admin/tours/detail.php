<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';

// Dữ liệu mẫu tour (trong thực tế sẽ lấy từ DB)
// $tour = [
//   'tour_name' => 'Hà Giang 4N3Đ - Vòng cung Đông Bắc',
//   'introduction' => 'Hành trình khám phá Hà Giang - mảnh đất địa đầu Tổ quốc với cung đường đèo hiểm trở nhất Việt Nam, cột cờ Lũng Cú, đèo Mã Pí Lèng, sông Nho Quế và những bản làng dân tộc thiểu số còn giữ nguyên nét nguyên sơ...',
//   'category' => 'Miền Bắc • Khám phá • Trekking',
//   'duration' => '4 ngày 3 đêm',
//   'adult_price' => 4850000,
//   'child_price' => 3900000,
//   'status' => 'active',
//   'itineraries' => [
//     [
//       'day' => 1,
//       'destination' => 'Hà Nội - Hà Giang - Quản Bạ - Yên Minh',
//       'arrival_time' => '12:00',
//       'departure_time' => '13:30',
//       'description' => "• 06:00 Xe và hướng dẫn viên đón quý khách tại điểm hẹn nội thành Hà Nội.\n• Di chuyển lên Hà Giang (khoảng 300km).\n• Dừng chân nghỉ ngơi, ăn trưa tại thị trấn Việt Quang.\n• Chiêm ngưỡng cổng trời Quản Bạ, núi Đôi Cô Tiên.\n• Đến Yên Minh nhận phòng homestay, ăn tối và nghỉ ngơi."
//     ],
//     [
//       'day' => 2,
//       'destination' => 'Yên Minh - Phố Cáo - Sủng Là - Đồng Văn',
//       'arrival_time' => '08:00',
//       'departure_time' => '17:00',
//       'description' => "• Tham quan Phố Cáo, làng văn hóa Lùng Tám người H’Mông.\n• Ghé thăm nhà của Pao nổi tiếng qua bộ phim “Chuyện nhà Pao”.\n• Chinh phục đèo 9 khoanh, dốc Thẩm Mã.\n• Đến thị trấn Đồng Văn, dạo phố cổ Đồng Văn về đêm."
//     ],
//     [
//       'day' => 3,
//       'destination' => 'Đồng Văn - Cột cờ Lũng Cú - Đèo Mã Pí Lèng - Du thuyền sông Nho Quế',
//       'arrival_time' => '07:30',
//       'departure_time' => '16:00',
//       'description' => "• Tham quan cột cờ Lũng Cú – điểm cực Bắc Tổ quốc.\n• Chinh phục đèo Mã Pí Lèng – tứ đại đỉnh đèo Việt Nam.\n• Trải nghiệm du thuyền trên sông Nho Quế ngắm hẻm vực Tu Sản.\n• Quay lại Đồng Văn ăn tối."
//     ],
//     [
//       'day' => 4,
//       'destination' => 'Đồng Văn - Hà Giang - Hà Nội',
//       'arrival_time' => '08:00',
//       'departure_time' => '20:00',
//       'description' => "• Tham quan dinh thự họ Vương (nhà Vương).\n• Trả phòng, ăn sáng và khởi hành về Hà Nội.\n• Dừng chân mua đặc sản (hồng, mật ong bạc hà...).\n• Về đến Hà Nội khoảng 20h. Kết thúc chương trình."
//     ]
//   ],
//   'policies' => [
//     ['id' => 1, 'title' => 'Chính sách hủy tour', 'content' => 'Hủy trước 30 ngày: miễn phí. Hủy 15-29 ngày: mất 50% tiền tour. Hủy dưới 15 ngày: mất 100%.'],
//     ['id' => 2, 'title' => 'Chính sách trẻ em', 'content' => 'Trẻ em dưới 5 tuổi: miễn phí (ngủ chung giường với bố mẹ). Từ 5-10 tuổi: 75% giá tour. Từ 11 tuổi trở lên: tính như người lớn.'],
//     ['id' => 3, 'title' => 'Bao gồm', 'content' => 'Xe đời mới, hướng dẫn viên, ăn uống theo chương trình, vé tham quan, bảo hiểm du lịch mức 30.000.000 VNĐ/vụ.']
//   ]
// ];
?>

<main class="pt-28 px-6 bg-gray-50 min-h-screen overflow-y-auto">
  <div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
      <div class="flex items-center gap-4">
        <button onclick="history.back()" class="p-2 hover:bg-gray-100 rounded-lg transition">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
        </button>
        <div>
          <h2 class="text-3xl font-bold text-gray-900">Chi tiết tour</h2>
          <p class="text-sm text-gray-600">Xem toàn bộ thông tin tour • Mã tour: HG403</p>
        </div>
      </div>
      <div class="flex gap-3">
        <button class="px-5 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
          Sao chép tour
        </button>
        <button class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
          Chỉnh sửa
        </button>
      </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 space-y-10">

      <!-- 1. Thông tin cơ bản -->
      <section>
        <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center gap-3">
          <span class="bg-blue-100 text-blue-700 w-8 h-8 rounded-full flex items-center justify-center text-sm">1</span>
          Thông tin cơ bản
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">Tên tour</label>
            <p class="text-lg font-medium text-gray-900"><?= htmlspecialchars($tour['name']) ?></p>
          </div>

          <div class="col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">Giới thiệu ngắn</label>
            <p class="text-gray-700 leading-relaxed"><?= nl2br(htmlspecialchars($tour['introduction'])) ?></p>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Danh mục</label>
            <p class="text-gray-900"><?= htmlspecialchars($tour['category_name']) ?></p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Thời gian</label>
            <p class="text-gray-900">$ ngày 3 đêm</p>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Giá người lớn</label>
            <p class="text-xl font-semibold text-green-600"><?= number_format($tour['adult_price']) ?> ₫</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Giá trẻ em</label>
            <p class="text-xl font-semibold text-orange-600"><?= number_format($tour['child_price']) ?> ₫</p>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Trạng thái</label>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                            <?= $tour['status'] == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
              <?= $tour['status'] == 'active' ? 'Đang hoạt động' : 'Tạm dừng' ?>
            </span>
          </div>
        </div>
      </section>

      <!-- 2. Lịch trình chi tiết -->
      <section>
        <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center gap-3">
          <span class="bg-blue-100 text-blue-700 w-8 h-8 rounded-full flex items-center justify-center text-sm">2</span>
          Lịch trình tour
        </h3>
        <div class="space-y-6">
          <?php foreach ($itineraries as $day): ?>
            <div class="border border-gray-200 rounded-xl p-6 hover:border-gray-300 transition">
              <div class="flex items-center justify-between mb-4">
                <h4 class="text-lg font-semibold text-blue-700">Ngày <?= $day['order_number'] ?></h4>
                <span class="text-sm text-gray-500"><?= $day['arrival_time'] ?> → <?= $day['departure_time'] ?></span>
              </div>
              <h5 class="font-medium text-gray-900 mb-3"><?= htmlspecialchars($day['destination']) ?></h5>
              <div class="text-gray-600 whitespace-pre-line leading-relaxed">
                <?= nl2br(htmlspecialchars($day['description'])) ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </section>

      <!-- 3. Hình ảnh tour (gallery) -->
      <section>
        <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center gap-3">
          <span class="bg-blue-100 text-blue-700 w-8 h-8 rounded-full flex items-center justify-center text-sm">3</span>
          Hình ảnh tour
        </h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <?php for ($i = 1; $i <= 8; $i++): ?>
            <div class="bg-gray-200 border-2 border-dashed rounded-xl aspect-w-1 aspect-h-1 overflow-hidden">
              <img src="https://picsum.photos/400/300?hagiangvietnam&random=<?= $i ?>"
                alt="Hà Giang" class="w-full h-full object-cover hover:scale-105 transition">
            </div>
          <?php endfor; ?>
        </div>
      </section>

      <!-- 4. Chính sách -->
      <section>
        <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center gap-3">
          <span class="bg-blue-100 text-blue-700 w-8 h-8 rounded-full flex items-center justify-center text-sm">4</span>
          Chính sách tour
        </h3>
        <div class="space-y-5">
          <?php foreach ($policies as $policy): ?>
            <div class="flex gap-4 p-5 bg-gray-50 rounded-lg">
              <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center font-semibold">
                  i
                </div>
              </div>
              <div class="flex-1">
                <h4 class="font-semibold text-gray-900"><?= htmlspecialchars($policy['name']) ?></h4>
                <p class="text-sm text-gray-600 mt-1"><?= htmlspecialchars($policy['content']) ?></p>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </section>

    </div>
  </div>
</main>

<?php require_once './views/components/footer.php'; ?>