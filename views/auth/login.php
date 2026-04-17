<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <?php
    $loginErrors = $_SESSION['login_errors'] ?? [];
    unset($_SESSION['login_errors']);
    $oldEmail = $_SESSION['old_email'] ?? '';
    unset($_SESSION['old_email']);
    ?>

    <div class="bg-white w-full max-w-md p-8 rounded-xl shadow-lg">
        <h2 class="text-2xl font-semibold text-center mb-2">Đăng nhập</h2>
        <p class="text-center text-gray-500 mb-6">Chào mừng trở lại! Vui lòng đăng nhập.</p>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="flex items-center gap-2 bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm">
                <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                <?= htmlspecialchars($_SESSION['error']) ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form action="<?= BASE_URL . '?act=check-login' ?>" method="POST" class="space-y-4">

            <div>
                <label class="block text-gray-700 font-medium mb-1">Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($oldEmail) ?>"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-orange-400 focus:outline-none <?= isset($loginErrors['email']) ? 'border-red-400 bg-red-50' : 'border-gray-300' ?>">
                <?php if (isset($loginErrors['email'])): ?>
                    <p class="text-red-500 text-xs mt-1"><?= htmlspecialchars($loginErrors['email']) ?></p>
                <?php endif; ?>
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">Mật khẩu</label>
                <input type="password" name="password"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-400 focus:outline-none">
            </div>

            <button type="submit" class="w-full bg-orange-500 text-white py-2 rounded-lg hover:bg-orange-600 transition font-medium">
                Đăng nhập
            </button>
        </form>

        <p class="text-center text-gray-500 text-sm mt-6">
            Hệ thống dành cho nhân viên nội bộ. Tài khoản được cấp bởi quản trị viên.
        </p>
    </div>

</body>

</html>
