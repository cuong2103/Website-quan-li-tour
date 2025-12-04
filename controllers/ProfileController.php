<?php
class ProfileController
{
    public $UserModel;

    public function __construct()
    {
        $this->UserModel = new UserModel();
    }

    public function GetById() {
    // Lấy ID từ query string
    $id = $_GET['id'] ?? null;
    // dd($id);
    if (!$id) {
        // Nếu không có ID, set message lỗi và load view
        Message::set('error', 'Không tìm thấy ID');
        $user = null;
        require_once './views/shared/profile.php';
        return; // thoát hàm
    }
    // Lấy user từ DB
    $user = $this->UserModel->getById($id);
    if (!$user) {
        // Nếu user không tồn tại, set message lỗi
        Message::set('error', 'User không tồn tại');
        $user = null; // gán null để view biết user không có
    }
    // Load view
    require_once './views/shared/profile.php';
    }

    public function changePassword()
{
    if (!isset($_SESSION['currentUser'])) {
        Message::set('error', 'Bạn cần đăng nhập để đổi mật khẩu');
        redirect("login");
        exit;
    }

    $userId = $_SESSION['currentUser']['id'];
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        Message::set('error', 'Yêu cầu không hợp lệ');
       redirect("profile&id=".$userId);
        exit;

    }

    $current = $_POST['current_password'] ?? '';
    $new = $_POST['new_password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if (!$current || !$new || !$confirm) {
        $Message = 'Vui lòng điền đầy đủ thông tin';
    } elseif ($new !== $confirm) {
        $Message = 'Xác nhận mật khẩu không khớp';
    } elseif (strlen($new) < 6) {
        $Message = 'Mật khẩu mới phải ít nhất 6 ký tự';
    } elseif (!$this->UserModel->verifyPassword($userId, $current)) {
        $Message = 'Mật khẩu hiện tại không chính xác';
    } elseif ($current === $new) {
        $Message = 'Mật khẩu mới không được trùng mật khẩu cũ';
        Message::set('warning', $Message);
        redirect("profile&id=" .$userId); 
        exit;
    }

    if (isset($Message)) {
        Message::set('error', $Message);
        redirect("profile&id=" .$userId); 
        exit;
    }

    $this->UserModel->changePassword($userId, $new);
    Message::set('success', 'Đổi mật khẩu thành công!');
    redirect("profile&id=" .$userId); 
    exit;
}
public function edit() {
        $id = $_GET['id'] ?? null;
        $from = $_GET['from'] ?? 'profile'; // để biết redirect sau submit
        
        if (!$id) {
            Message::set('error', 'Không tìm thấy ID');
            redirect('profile');
            exit;
        }
        $user = $this->UserModel->getById($id);
        require_once './views/admin/User_management/edit.php';
    }

    // Xử lý submit edit
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect("user");

        $id = $_POST['id'];    
        $source = $_POST['source'] ?? 'admin';

        $fullname = trim($_POST['fullname']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        $roles = $_POST['roles'] ?? 'guide';
        $status = ($_POST['status']);
        // dd($status); 

        // Check email tồn tại
        if ($this->UserModel->emailExists($email, $id)) {
            Message::set('error', 'Email đã tồn tại');
            redirect("?act=user-edit&id=$id");
        }

        // --- Xử lý avatar nếu có upload mới ---
        $data = [
            'fullname' => $fullname,
            'email' => $email,
            'phone' => $phone,
            'roles' => $roles,
            'status' => $status
        ];

        // Lấy avatar cũ từ DB
        $currentUser = $this->UserModel->getById($id);

        if (!empty($_FILES['avatar']['name']) && $_FILES['avatar']['error'] == 0) {
            $avatar = $_FILES['avatar'];
            $extention = pathinfo($avatar['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . "." . $extention;
            $uploadDir = __DIR__ . '/../../uploads/avatar/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            $uploadPath = $uploadDir . $filename;
            if (move_uploaded_file($avatar['tmp_name'], $uploadPath)) {
                $data['avatar'] = $filename; // dùng avatar mới
            }
        } else {
            $data['avatar'] = $currentUser['avatar']; // giữ avatar cũ
        }


        $result = $this->UserModel->update($id, $data);
         if ($result) {
        Message::set('success', 'Cập nhật thành công');

        // ⭐⭐ PHÂN NHÁNH TẠI ĐÂY ⭐⭐
        return $source === 'profile'
            ? redirect("profile&id=$id") // về lại trang profile
            : redirect("user"); // admin → về lại index
    } else {
        Message::set('error', 'Cập nhật thất bại');

        return $source === 'profile'
            ? redirect("?act=user-edit&id=$id")  // lỗi → về lại form sửa profile
            : redirect("?act=user-edit&id=$id"); // admin → form sửa admin
    }
        // redirect("user");
    }
}

