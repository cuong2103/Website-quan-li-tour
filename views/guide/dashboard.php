<?php require_once './views/guide/header_guide.php'; ?>
<?php require_once './views/guide/sidebar_guide.php'; ?>

<div class="flex-1 ml-64 flex flex-col">

<header class="bg-white shadow-sm border-b border-gray-200 fixed top-0 left-64 right-0 z-40">
  <div class="px-6 py-6 flex items-center justify-between">
    <h2 class="text-lg font-semibold text-gray-800">Chào mừng trở lại, <?= htmlspecialchars($fullname) ?>!</h2>

    <div class="flex items-center gap-3">
      <button class="relative p-2 text-gray-600 hover:bg-gray-100 rounded-full">
        <i data-lucide="bell"></i>
        <span class="absolute top-0 right-0 h-2 w-2 rounded-full bg-red-500"></span>
      </button>

      <div class="flex items-center space-x-3">
        <div class="w-9 h-9 rounded-full bg-gray-300 flex items-center justify-center text-gray-600 font-semibold">
          <?= $avatar ?>
        </div>
      </div>
    </div>
  </div>
</header>

<main class="mt-28 px-6 pb-20">

  <!-- TOUR HIỆN TẠI -->
  <div class="bg-white p-6 rounded-xl shadow-sm border mb-6">
    <p class="text-sm text-blue-600 font-semibold mb-2 flex items-center">
      <i data-lucide="map-pin" class="mr-2"></i>
      Tour hiện tại
    </p>

    <h3 class="text-xl font-bold text-gray-800 mb-4">Tour Đà Nẵng – Hội An 4N3Đ</h3>

    <div class="grid grid-cols-3 gap-4 text-sm mb-4">
      <div>
        <p class="text-gray-500">Thời gian</p>
        <p class="font-medium">20/12 – 23/12</p>
      </div>
      <div>
        <p class="text-gray-500">Số khách</p>
        <p class="font-medium">15 người</p>
      </div>
      <div>
        <p class="text-gray-500">Tiến độ</p>
        <p class="font-medium">Ngày 2/4</p>
      </div>
    </div>

    <div class="flex gap-4">
      <a href="#" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm">Xem chi tiết</a>
      <a href="#" class="px-4 py-2 bg-gray-200 rounded-lg text-sm">Viết nhật ký</a>
    </div>
  </div>

  <!-- TOUR SẮP TỚI -->
  <div class="bg-white p-6 rounded-xl shadow-sm border mb-6">
    <p class="text-sm text-orange-600 font-semibold flex items-center">
      <i data-lucide="calendar" class="mr-2"></i>
      Tour sắp tới
    </p>

    <h3 class="text-lg font-semibold text-gray-800 mb-3">Tour Phú Quốc 5N4Đ</h3>

    <div class="grid grid-cols-3 gap-4 text-sm mb-3">
      <div>
        <p class="text-gray-500">Thời gian</p>
        <p class="font-medium">25/12 – 29/12</p>
      </div>
      <div>
        <p class="text-gray-500">Số khách</p>
        <p class="font-medium">12 người</p>
      </div>
      <div class="flex items-end justify-end text-gray-500 text-xs">
        Còn 5 ngày
      </div>
    </div>

    <a href="#" class="px-4 py-2 bg-gray-200 rounded-lg text-sm">Xem chi tiết để chuẩn bị</a>
  </div>

  <!-- THỐNG KÊ -->
  <div class="bg-white p-6 rounded-xl shadow-sm border mb-6 flex items-center gap-4">
    <i data-lucide="bar-chart" class="w-8 h-8 text-blue-600"></i>
    <div>
      <p class="text-gray-500 text-sm">Tour tháng này</p>
      <p class="text-2xl font-bold">8</p>
    </div>
  </div>

  <!-- THÔNG BÁO -->
  <div class="bg-white p-6 rounded-xl shadow-sm border">
    <div class="flex items-center justify-between mb-4">
      <h3 class="text-lg font-semibold">Thông báo mới</h3>
      <a href="#" class="text-sm text-blue-600 hover:underline">Xem tất cả</a>
    </div>

    <div class="space-y-3">

      <div class="p-4 bg-blue-50 rounded-lg text-sm text-gray-800">
        Cập nhật lịch trình tour Đà Nẵng — <span class="text-gray-500">2 giờ trước</span>
      </div>

      <div class="p-4 bg-blue-50 rounded-lg text-sm text-gray-800">
        Yêu cầu đặc biệt từ khách hàng — <span class="text-gray-500">5 giờ trước</span>
      </div>

      <div class="p-4 bg-blue-50 rounded-lg text-sm text-gray-800">
        Phân công tour mới cho tuần sau — <span class="text-gray-500">1 ngày trước</span>
      </div>

    </div>
  </div>

</main>
</div>

<?php require_once './views/guide/footer_guide.php'; ?>
