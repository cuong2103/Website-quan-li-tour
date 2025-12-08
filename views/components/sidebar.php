<?php
// Determine current action for active state
$currentAct = $_GET['act'] ?? '';

// Helper function to check if menu is active
function isActiveMenu($acts, $currentAct)
{
  if (is_array($acts)) {
    return in_array($currentAct, $acts);
  }
  return $currentAct === $acts;
}

// Active and inactive classes
$activeClass = 'bg-indigo-50 text-indigo-700';
$inactiveClass = 'text-gray-700 hover:bg-gray-100';
?>
<aside class="w-64 bg-white shadow-lg h-screen fixed inset-y-0 left-0 flex flex-col z-50">
  <!-- Logo -->
  <div class="px-6 py-7 border-b border-gray-200">
    <div class="flex items-center space-x-3">
      <!-- Icon xanh-->
      <div class="relative w-12 h-12">
        <?= $role === 'Admin' ? '<div class="absolute inset-0 bg-orange-500 rounded-2xl"></div>
        <i class="w-7 h-7 text-white absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2" data-lucide="map-pin"></i>' : '<div class="absolute inset-0 bg-blue-500 rounded-2xl"></div>
        <i data-lucide="map" class="w-7 h-7 text-white absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2"></i>' ?>
      </div>

      <!-- Text -->
      <div>
        <?= $role === 'Admin' ? '<h1 class="text-xl font-bold text-gray-900">Tour Manager</h1>
        <p class="text-xs text-gray-500 ">Admin Panel</p>' : ' <h1 class="text-xl font-bold text-gray-900">Tour Guide</h1>
        <p class="text-xs text-gray-500 ">Hướng dẫn viên</p>' ?>
      </div>
    </div>
  </div>
  <!-- Menu -->
  <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto scollbar-thin">
    <?php if ($role === 'Admin'): ?>

      <a href="<?= BASE_URL ?>" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg <?= $currentAct === '' ? $activeClass : $inactiveClass ?> transition">
        <i class="mr-3 w-6 h-6" data-lucide="layout-dashboard"></i>
        Dashboard
      </a>
      <!-- Quản lý tour -->
      <?php $tourActs = ['tours', 'tour-detail', 'tour-create', 'tour-edit']; ?>
      <a href="<?= BASE_URL ?>?act=tours" class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium <?= isActiveMenu($tourActs, $currentAct) ? $activeClass : $inactiveClass ?> rounded-lg transition">
        <div class="flex items-center">
          <i class="mr-3 w-6 h-6" data-lucide="calendar"></i>
          Quản lý tour
        </div>
      </a>
      <!-- Quản lý và điều hành tour -->
      <?php $bookingActs = ['bookings', 'booking-detail', 'booking-create', 'booking-edit']; ?>
      <a href="<?= BASE_URL . '?act=bookings' ?>" class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium <?= isActiveMenu($bookingActs, $currentAct) ? $activeClass : $inactiveClass ?> rounded-lg transition">
        <div class="flex items-center ">
          <i class="mr-3 w-6 h-6" data-lucide="clipboard"></i>
          Quản lý và điều hành tour
        </div>
      </a>



      <!-- Dữ liệu -->
      <?php $dataActs = ['destination', 'destination-detail', 'destination-create', 'destination-edit', 'policies', 'policy-detail', 'policy-create', 'policy-edit', 'categories', 'category-detail', 'category-create', 'category-edit', 'service', 'service-detail', 'service-create', 'service-edit', 'service-type', 'service-type-detail', 'service-type-create', 'service-type-edit', 'itinerary', 'itinerary-detail', 'itinerary-create', 'itinerary-edit', 'suppliers', 'supplier-detail', 'supplier-create', 'supplier-edit']; ?>
      <div class="menu-group">
        <button class="menu-toggle w-full flex items-center justify-between px-4 py-3 text-sm font-medium <?= isActiveMenu($dataActs, $currentAct) ? $activeClass : $inactiveClass ?> rounded-lg transition">
          <div class="flex items-center">
            <i class="mr-3 w-6 h-6" data-lucide="menu"></i>
            Dữ liệu
          </div>
          <i class="w-4 h-4" data-lucide="chevron-down"></i>
        </button>
        <div class="submenu pl-12 space-y-1 overflow-hidden transition-all duration-300 <?= isActiveMenu($dataActs, $currentAct) ? 'max-h-96' : 'max-h-0' ?>">

          <?php $catActs = ['categories', 'category-detail', 'category-create', 'category-edit']; ?>
          <a href="<?= BASE_URL ?>?act=categories" class="block px-4 mt-1 py-2 text-sm <?= isActiveMenu($catActs, $currentAct) ? $activeClass : $inactiveClass ?> rounded">Danh mục</a>

          <?php $destActs = ['destination', 'destination-detail', 'destination-create', 'destination-edit']; ?>
          <a href="<?= BASE_URL . '?act=destination' ?>" class="block px-4 py-2  text-sm <?= isActiveMenu($destActs, $currentAct) ? $activeClass : $inactiveClass ?> rounded">Địa điểm</a>

          <?php $supplierActs = ['suppliers', 'supplier-detail', 'supplier-create', 'supplier-edit']; ?>
          <a href="<?= BASE_URL . '?act=suppliers' ?>" class="block px-4 py-2  text-sm <?= isActiveMenu($supplierActs, $currentAct) ? $activeClass : $inactiveClass ?> rounded">Nhà cung cấp</a>

          <?php $serviceTypeActs = ['service-type', 'service-type-detail', 'service-type-create', 'service-type-edit']; ?>
          <a href="<?= BASE_URL ?>?act=service-type" class="block px-4 py-2 text-sm <?= isActiveMenu($serviceTypeActs, $currentAct) ? $activeClass : $inactiveClass ?> rounded">Loại Dịch vụ</a>

          <?php $serviceActs = ['service', 'service-detail', 'service-create', 'service-edit']; ?>
          <a href="<?= BASE_URL ?>?act=service" class="block px-4 py-2 text-sm <?= isActiveMenu($serviceActs, $currentAct) ? $activeClass : $inactiveClass ?> rounded">Dịch vụ</a>

          <?php $policyActs = ['policies', 'policy-detail', 'policy-create', 'policy-edit']; ?>
          <a href="<?= BASE_URL ?>?act=policies" class="block px-4 py-2 text-sm <?= isActiveMenu($policyActs, $currentAct) ? $activeClass : $inactiveClass ?> rounded">Chính sách</a>
        </div>
      </div>

      <!-- Khách hàng -->
      <?php $customerActs = ['customers', 'customer-detail', 'customer-create', 'customer-edit']; ?>
      <a href="<?= BASE_URL ?>?act=customers" class="flex items-center px-4 py-3 text-sm font-medium <?= isActiveMenu($customerActs, $currentAct) ? $activeClass : $inactiveClass ?> rounded-lg transition">
        <i class="mr-3 w-6 h-6" data-lucide="users-round"></i>
        Khách hàng
      </a>



      <!-- Quản lý nhân viên -->
      <?php $userActs = ['user', 'user-detail', 'user-create', 'user-edit', 'user-on-leave']; ?>
      <div class="menu-group">
        <button class="menu-toggle w-full flex items-center justify-between px-4 py-3 text-sm font-medium <?= isActiveMenu($userActs, $currentAct) ? $activeClass : $inactiveClass ?> rounded-lg transition">
          <div class="flex items-center">
            <i class="mr-3 w-6 h-6" data-lucide="users"></i>
            Nhân viên
          </div>
          <i class="w-4 h-4" data-lucide="chevron-down"></i>
        </button>
        <div class="submenu pl-12 space-y-1 overflow-hidden transition-all duration-300 <?= isActiveMenu($userActs, $currentAct) ? 'max-h-96' : 'max-h-0' ?>">
          <?php $userListActs = ['user', 'user-detail', 'user-create', 'user-edit']; ?>
          <a href="<?= BASE_URL . '?act=user' ?>" class="block px-4 py-2 text-sm <?= isActiveMenu($userListActs, $currentAct) ? $activeClass : $inactiveClass ?> rounded">Danh sách nhân viên</a>
          <a href="<?= BASE_URL . '?act=user-on-leave' ?>" class="block px-4 py-2 text-sm <?= $currentAct === 'user-on-leave' ? $activeClass : $inactiveClass ?> rounded">Nhân viên nghỉ phép</a>
        </div>
      </div>

      <!-- Thông báo -->
      <?php $notifActs = ['notifications', 'notification-detail', 'notification-create', 'notification-edit']; ?>
      <a href="<?= BASE_URL . '?act=notifications' ?>" class="flex items-center px-4 py-3 text-sm font-medium <?= isActiveMenu($notifActs, $currentAct) ? $activeClass : $inactiveClass ?> rounded-lg transition">
        <i class="mr-3 w-6 h-6" data-lucide="megaphone"></i>
        Quản lí thông báo
      </a>

      <!-- Thêm các menu Admin khác ở đây -->
    <?php else: ?>
      <!-- Guide Menu -->
      <?php $scheduleActs = ['my-schedule']; ?>
      <a href="<?= BASE_URL . '?act=my-schedule' ?>"
        class="flex items-center px-4 py-3 text-sm font-medium rounded-lg <?= isActiveMenu($scheduleActs, $currentAct) ? $activeClass : $inactiveClass ?> transition">
        <i data-lucide="home" class="mr-3 w-6 h-6"></i>
        Trang chủ
      </a>

      <!-- Tour của tôi -->
      <?php $guideActs = ['guide-tour-assignments', 'guide-tour-assignment-detail']; ?>
      <a href="<?= BASE_URL . '?act=guide-tour-assignments' ?>" class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium <?= isActiveMenu($guideActs, $currentAct) ? $activeClass : $inactiveClass ?> rounded-lg transition">
        <div class="flex items-center">
          <i class="mr-3 w-6 h-6" data-lucide="clipboard"></i>
          Tour của tôi
        </div>
      </a>

      <!-- Viết nhật kí -->
      <?php $journalActs = ['journal', 'journal-create', 'journal-detail', 'journal-edit']; ?>
      <a href="<?= BASE_URL ?>?act=journal"
        class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium <?= isActiveMenu($journalActs, $currentAct) ? $activeClass : $inactiveClass ?> rounded-lg transition">
        <div class="flex items-center">
          <i data-lucide="notebook-pen" class="mr-3 w-6 h-6"></i>
          Viết nhật ký
        </div>
      </a>

    <?php endif; ?>

    <!-- Thông báo (chung cho cả Admin và Guide) -->
    <?php $myNotifActs = ['my-notifications']; ?>
    <a href="<?= BASE_URL ?>?act=my-notifications"
      class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium <?= isActiveMenu($myNotifActs, $currentAct) ? $activeClass : $inactiveClass ?> rounded-lg transition">
      <div class="flex items-center">
        <i data-lucide="bell" class="mr-3 w-6 h-6"></i>
        Thông báo
      </div>
      <?= ($_SESSION['unreadCount'] ?? '') > 0 ? '<span class="ml-auto w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">' . $_SESSION['unreadCount'] . '</span>' : '' ?>
    </a>
  </nav>
  <!-- Đăng xuất -->
  <div class="border-t border-gray-200 px-4 py-4">
    <a href="<?= BASE_URL . '?act=logout' ?>" class="flex items-center px-4 py-3 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition">
      <i class="mr-3 w-6 h-6 text-red-500" data-lucide="log-out"></i>
      Đăng xuất
    </a>
  </div>
</aside>