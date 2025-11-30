<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white w-full max-w-md p-8 rounded-xl shadow-lg">
        <h2 class="text-2xl font-semibold text-center mb-2">Đăng nhập</h2>
        <p class="text-center text-gray-500 mb-6">Chào mừng trở lại! Vui lòng đăng nhập.</p>

        <!-- Hiển thị lỗi -->
        <?php if(isset($_SESSION['error'])): ?>
            <p class="text-red-500 text-center mb-4">
                <?= htmlspecialchars($_SESSION['error']) ?>
            </p>
        <?php endif; ?>

        <form action="<?= BASE_URL . '?act=check-login' ?>" method="POST" class="space-y-4">

            <div>
                <label class="block text-gray-700 font-medium mb-1">Email</label>
                <input type="email" name="email"
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-orange-400 focus:outline-none">
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">Mật khẩu</label>
                <input type="password" name="password"
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-orange-400 focus:outline-none">
            </div>

            <button class="w-full bg-orange-500 text-white py-2 rounded-lg hover:bg-orange-600 transition">
                Đăng nhập
            </button>
        </form>

        <p class="text-center text-gray-500 text-sm mt-6">
            Hệ thống dành cho nhân viên nội bộ. Tài khoản được cấp bởi quản trị viên.
        </p>
    </div>

</body>
</html>
