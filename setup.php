<?php
/**
 * SETUP.PHP — Khởi tạo tài khoản admin mặc định
 * 
 * CÁCH DÙNG:
 *   1. Đảm bảo đã import db.sql vào database
 *   2. Truy cập: http://localhost/Website-quan-li-tour/setup.php
 *   3. XÓA file này sau khi chạy xong!
 */

require_once __DIR__ . '/config/env.php';

// Kết nối DB
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USERNAME,
        DB_PASSWORD,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    die("<p style='color:red'>Lỗi kết nối DB: " . $e->getMessage() . "</p>");
}

$adminEmail    = 'admin@gmail.com';
$adminPassword = '12345678';
$adminName     = 'Quản trị viên';

$messages = [];

// Kiểm tra đã tồn tại chưa
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$adminEmail]);
$existing = $stmt->fetch();

if ($existing) {
    // Cập nhật mật khẩu nếu đã tồn tại
    $hash = password_hash($adminPassword, PASSWORD_BCRYPT);
    $pdo->prepare("UPDATE users SET password = ?, roles = 'admin', status = 1 WHERE email = ?")
        ->execute([$hash, $adminEmail]);
    $messages[] = ['type' => 'warning', 'text' => "Tài khoản $adminEmail đã tồn tại — đã cập nhật mật khẩu mới."];
} else {
    // Tạo mới
    $hash = password_hash($adminPassword, PASSWORD_BCRYPT);
    $pdo->prepare("INSERT INTO users (fullname, email, password, roles, status, created_at) VALUES (?, ?, ?, 'admin', 1, NOW())")
        ->execute([$adminName, $adminEmail, $hash]);
    $messages[] = ['type' => 'success', 'text' => "Tạo tài khoản admin thành công!"];
}

// Nếu có data.sql seed thêm, có thể thêm vào đây

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Setup - Tour Management</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 60px auto; padding: 20px; }
        .box { padding: 16px 20px; border-radius: 8px; margin: 12px 0; }
        .success { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
        .warning { background: #fef3c7; color: #92400e; border: 1px solid #fcd34d; }
        .info    { background: #dbeafe; color: #1e40af; border: 1px solid #93c5fd; }
        .danger  { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin: 12px 0; }
        td { padding: 8px 12px; border: 1px solid #e5e7eb; }
        td:first-child { font-weight: bold; background: #f9fafb; width: 140px; }
    </style>
</head>
<body>
    <h2>⚙️ Setup — Tour Management System</h2>

    <?php foreach ($messages as $msg): ?>
        <div class="box <?= $msg['type'] ?>"><?= $msg['text'] ?></div>
    <?php endforeach; ?>

    <div class="box info">
        <strong>Thông tin đăng nhập:</strong>
        <table>
            <tr><td>URL đăng nhập</td><td><a href="<?= BASE_URL ?>?act=login"><?= BASE_URL ?>?act=login</a></td></tr>
            <tr><td>Email</td><td><?= $adminEmail ?></td></tr>
            <tr><td>Mật khẩu</td><td><?= $adminPassword ?></td></tr>
            <tr><td>Role</td><td>Admin</td></tr>
        </table>
    </div>

    <div class="box danger">
        ⚠️ Hãy XÓA file <code>setup.php</code> ngay sau khi sử dụng xong!<br>
        File này tiết lộ thông tin đăng nhập nếu để trên server.
    </div>

    <p><a href="<?= BASE_URL ?>?act=login" style="padding:10px 20px; background:#f97316; color:white; border-radius:6px; text-decoration:none;">
        → Đăng nhập ngay
    </a></p>
</body>
</html>
