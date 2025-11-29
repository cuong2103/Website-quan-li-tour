<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';


?>
<main class="mt-28 p-6 min-h-screen bg-gray-50">
  <!-- Breadcrumb + Back button -->
  <div class="flex items-center gap-4 mb-8">
    <a href="?act=user" class="text-gray-600 hover:text-gray-900 flex items-center gap-2">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
      </svg>
      <a href="?act=user"><span>Quay lại danh sách</span></a>
    </a>
    <span class="text-gray-400">/</span>
    <span class="text-gray-900 font-medium">Chi tiết nhân viên</span>
  </div>
    
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left: Thông tin chính + avatar -->
    <div class="lg:col-span-1">
      <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 text-center">
        <!-- Avatar lớn -->
        <div class="mx-auto w-32 h-32 rounded-full bg-blue-100 flex items-center justify-center mb-6 overflow-hidden">
          <?php if(!empty($user['avatar'])): ?>
            <img src="/uploads/avatar/<?= $user['avatar'] ?>" alt="Avatar" class="w-full h-full object-cover">
          <?php else: ?>
            <span class="text-5xl font-bold text-blue-600"><?= strtoupper(substr($user['fullname'], 0, 1)) ?></span>
          <?php endif; ?>
        </div>


        <h2 class="text-2xl font-bold text-gray-900"><?= $user["fullname"] ?></h2>
        <p class="text-gray-500 mt-1">Mã nhân viên: HDV00</p>

        <!-- Vai trò -->
        <div class="mt-5">
          <?= $user["roles"] == 'admin' ? '<span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-pink-100 text-pink-800">
                Admin
              </span>' : ' <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                Hướng dẫn viên
              </span>' ?>
        </div>

        <!-- Trạng thái -->
        <div class="mt-4">
          <td class="px-6 py-5">
              <?php 
                  echo $user['status'] == 1 ? '<span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                    Hoạt động
                  </span>' : '<span class="inline-flex px-3 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">
                    Tạm dừng
                  </span>'; 
                ?>
            </td>
        </div>

        <!-- Nút hành động -->
        <div class="mt-8 flex gap-3 justify-center">
          <a href="?act=user-edit&id=<?= $user['id'] ?>" 
            class="bg-orange-500 hover:bg-orange-600 text-white px-5 py-2.5 rounded-lg font-medium transition-colors flex items-center gap-2">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
              </svg>
              Chỉnh sửa
          </a>

          
        </div>
      </div>
    </div>

    <!-- Right: Thông tin chi tiết -->
    <div class="lg:col-span-2 space-y-6">
      <!-- Card thông tin cá nhân -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center gap-2">
          <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
          </svg>
          Thông tin cá nhân
        </h3>
       
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Họ và tên</label>
            <p class="text-gray-900 font-medium"><?= $user["fullname"] ?></p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Email</label>
            <p class="text-gray-900 font-medium"><?= $user["email"] ?></p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Số điện thoại</label>
            <p class="text-gray-900 font-medium"><?= $user["phone"] ?></p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Ngày sinh</label>
            <p class="text-gray-900 font-medium">15/03/1992</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Giới tính</label>
            <p class="text-gray-900 font-medium">Nam</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Địa chỉ</label>
            <p class="text-gray-900 font-medium">123 Đường Lê Lợi, Quận 1, TP.HCM</p>
          </div>
        </div>
      </div>    
     

      <!-- Card thông tin công việc -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center gap-2">
          <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 0h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          Thông tin công việc
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Ngày vào làm</label>
            <p class="text-gray-900 font-medium">01/06/2021</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Chứng chỉ HDV</label>
            <p class="text-gray-900 font-medium">HDV Quốc tế - 2020</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Ngoại ngữ</label>
            <div class="flex gap-2 mt-1">
              <span class="px-3 py-1 bg-gray-100 text-gray-700 text-xs rounded-full">Tiếng Anh</span>
              <span class="px-3 py-1 bg-gray-100 text-gray-700 text-xs rounded-full">Tiếng Trung</span>
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Số tour đã dẫn</label>
            <p class="text-gray-900 font-medium">127 tour</p>
          </div>
        </div>
      </div>

      <!-- Card lịch sử hoạt động (tùy chọn thêm sau) -->
      <!-- <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">...</div> -->
    </div>
  </div>
</main>
<?php
require_once './views/components/footer.php';
;
?>