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
            $email = $_POST['email'];
            $password = $_POST['password'];
            // Kiểm tra đăng nhập
            $currentUser = $this->userModel->checkLogin($email, $password);

            if (is_array($currentUser)) {
                // Login thành công
                $_SESSION["currentUser"] = $currentUser; // Lưu toàn bộ user
                header("Location: " . BASE_URL);
                exit;
            } else {
                // Đăng nhập thất bại
                $_SESSION['error'] = $currentUser;
                $_SESSION['flash'] = true;
                header('Location:' . BASE_URL . '?act=login-admin');
                exit();
            }
        }
    }

    // Đăng xuất
    public function logout()
    {
        if (isset($_SESSION)) {
            unset($_SESSION);
            header('Location:' . BASE_URL . '?act=login-admin');
        }
    }
}
