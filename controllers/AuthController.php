<?php
class AuthController
{
    public $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function formLogin()
    {
        require_once './views/auth/login.php';
        deleteSessionError();
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            // Validate dữ liệu đầu vào
            if (empty($email)) {
                $_SESSION['login_errors']['email'] = 'Vui lòng nhập email.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['login_errors']['email'] = 'Email không đúng định dạng.';
            }

            if (!empty($_SESSION['login_errors'])) {
                $_SESSION['old_email'] = $email;
                header('Location:' . BASE_URL . '?act=login');
                exit();
            }

            // Kiểm tra đăng nhập
            $currentUser = $this->userModel->checkLogin($email, $password);

            if (is_array($currentUser)) {
                // Login thành công
                unset($_SESSION['login_errors'], $_SESSION['old_email'], $_SESSION['error']);
                $_SESSION["currentUser"] = $currentUser;
                Message::set('success', 'Đăng nhập thành công! Chào mừng ' . htmlspecialchars($currentUser['fullname'] ?? $email) . '.');
                if ($currentUser['roles'] != 'admin') {
                    header("Location: " . BASE_URL . '?act=my-schedule');
                } else {
                    header("Location: " . BASE_URL);
                }
                exit;
            } else {
                // Đăng nhập thất bại — luôn hiển thị thông báo rõ ràng
                $errorMsg = (is_string($currentUser) && !empty($currentUser))
                    ? $currentUser
                    : 'Email hoặc mật khẩu không chính xác.';
                $_SESSION['error'] = $errorMsg;
                $_SESSION['old_email'] = $email;
                header('Location:' . BASE_URL . '?act=login');
                exit();
            }
        }
    }

    // Đăng xuất
    public function logout()
    {
        session_destroy();
        header("Location: " . BASE_URL . '?act=login');
        exit();
    }
}
