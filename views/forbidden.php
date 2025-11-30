<?php
$currentUser = $_SESSION['currentUser'] ?? null;
$fullname = $currentUser['fullname'] ?? 'User';
$role = ($currentUser['role_id'] ?? 0) == 1 ? 'Admin' : 'Hướng dẫn viên';
$avatar = strtoupper(mb_substr($fullname, 0, 1));
?>

<!DOCTYPE html>
<html lang="vi" class="h-full">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>403 - Không có quyền truy cập | Tour Manager</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="<?= BASE_URL ?>assets/common.js"></script>
</head>

<body>

  <main class="pt-28 px-8 bg-gray-50 min-h-screen overflow-hidden flex items-center justify-center">
    <div class="max-w-5xl w-full text-center">

      <!-- 403 Number - Gradient cam rực rỡ -->
      <div class="relative mb-12">
        <h1 class="absolute inset-0 text-9xl md:text-[180px] font-extrabold text-orange-100 opacity-40 
                 animate-ping-slow">
          403
        </h1>
        <h1 class="text-9xl md:text-[180px] font-extrabold text-transparent bg-clip-text 
                 bg-gradient-to-r from-orange-600 via-red-600 to-amber-600 
                 animate-pulse drop-shadow-2xl">
          403
        </h1>
      </div>

      <!-- Tiêu đề + mô tả -->
      <div class="animate-fade-in-up animation-delay-300">
        <p class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">
          Ối! Bạn không có quyền vào đây
        </p>
        <p class="text-xl text-gray-600 max-w-2xl mx-auto leading-relaxed">
          Trang này chỉ dành cho người có đủ quyền hạn. Nếu bạn nghĩ mình đáng được vào, hãy liên hệ Admin nhé!
        </p>
      </div>

      <!-- Ảnh minh họa + trích dẫn vui -->
      <div class="my-16 animate-float">
        <div class="bg-gradient-to-br from-red-100 to-orange-100 w-80 h-80 mx-auto rounded-full blur-3xl opacity-60 absolute -z-10"></div>

        <!-- Icon khóa lớn (SVG) -->
        <svg class="w-48 h-48 mx-auto text-orange-500 drop-shadow-lg" fill="none" stroke="currentColor"
          viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
        </svg>

        <p class="text-sm text-orange-600 font-medium mt-8 animate-fade-in animation-delay-1000">
          “Quyền hạn không phải là tất cả, nhưng không có thì… cũng hơi mệt!” – Admin Tour Manager 2025
        </p>
      </div>

      <!-- Nút hành động -->
      <div class="flex flex-col sm:flex-row gap-5 justify-center items-center mt-10 animate-fade-in-up animation-delay-700">

        <button onclick="history.back()"
          class="group inline-flex items-center gap-3 px-8 py-4 bg-white border-2 border-gray-300 
                 rounded-xl hover:border-orange-500 hover:shadow-xl transform hover:scale-105 
                 transition-all duration-300 font-semibold text-gray-700 shadow-lg">
          <svg class="w-6 h-6 group-hover:-translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
          Quay lại trang trước
        </button>

        <a href="<?= BASE_URL ?>"
          class="group inline-flex items-center gap-3 px-9 py-4 
                bg-gradient-to-r from-orange-500 to-red-600 
                hover:from-orange-600 hover:to-red-700 
                text-white rounded-xl shadow-xl 
                transform hover:scale-105 transition-all duration-300 font-bold text-lg">
          <svg class="w-6 h-6 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
          </svg>
          Về trang chủ
        </a>
      </div>

      <!-- Liên hệ Admin -->
      <div class="mt-20 text-gray-500 text-sm animate-fade-in animation-delay-1500">
        <p>Cần mở quyền truy cập? Liên hệ ngay:
          <a href="mailto:admin@yourtravel.com" class="text-orange-600 font-bold hover:underline">
            admin@yourtravel.com
          </a>
          hoặc gọi <span class="font-bold text-orange-600">1900 1234</span>
        </p>
      </div>
    </div>
  </main>

  <!-- Animation CSS (giống hệt trang 404) -->
  <style>
    @keyframes float {

      0%,
      100% {
        transform: translateY(0px);
      }

      50% {
        transform: translateY(-20px);
      }
    }

    @keyframes ping-slow {

      0%,
      100% {
        opacity: 0.4;
        transform: scale(1);
      }

      50% {
        opacity: 0.15;
        transform: scale(1.2);
      }
    }

    @keyframes fade-in-up {
      from {
        opacity: 0;
        transform: translateY(30px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .animate-float {
      animation: float 7s ease-in-out infinite;
    }

    .animate-ping-slow {
      animation: ping-slow 4s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }

    .animate-fade-in-up {
      animation: fade-in-up 0.9s ease-out forwards;
    }

    .animate-fade-in {
      animation: fade-in-up 0.8s ease-out forwards;
    }

    .animation-delay-300 {
      animation-delay: 300ms;
    }

    .animation-delay-700 {
      animation-delay: 700ms;
    }

    .animation-delay-1000 {
      animation-delay: 1000ms;
    }

    .animation-delay-1500 {
      animation-delay: 1500ms;
    }
  </style>

  <?php require_once './views/components/footer.php'; ?>
</body>

</html>