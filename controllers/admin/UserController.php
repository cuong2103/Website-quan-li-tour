<?php
class UserController
{

    public $userModel;

    public function __construct()
    {
        requireAdmin();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $users = $this->userModel->getAll();
        require './views/admin/User_management/index.php';
    }

    public function detail()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) redirect("user");

        $user = $this->userModel->getById($id);

        if (!$user) redirect("user");
        require './views/admin/User_management/detail.php';
    }

    public function create()
    {

        require './views/admin/User_management/create.php';
    }

    public function edit()
    {
        $id = $_GET['id'];
        if (!$id) redirect("user"); // chuyển về danh sách nếu không có id
        $user = $this->userModel->getById($id);

        if (!$user) {
            Message::set('error', 'Không tìm thấy nhân viên');
            redirect("user"); // chuyển về danh sách nếu id không tồn tại
        }
        require_once './views/admin/User_management/edit.php'; // truyền $user sang view
    }

    public function store()
    {
        $data = [
            'fullname'    => $_POST['fullname'] ?? '',
            'email'       => $_POST['email'] ?? '',
            'phone'       => $_POST['phone'] ?? '',
            'roles'       => isset($_POST['roles']) ? (int)$_POST['roles'] : 2,
            'status'      => isset($_POST['status']) ? (int)$_POST['status'] : 1,
            'password'    => !empty($_POST['password'])
                ? password_hash($_POST['password'], PASSWORD_DEFAULT)
                : password_hash('123456', PASSWORD_DEFAULT),
            'birthday'    => $_POST['birthday'] ?? null,
            'gender'      => $_POST['gender'] ?? null,
            'address'     => $_POST['address'] ?? null,
            'start_date'  => $_POST['start_date'] ?? null,
            'certificate' => $_POST['certificate'] ?? null,
        ];
        // --- Upload avatar trước khi lưu ---
        if (!empty($_FILES['avatar']['name']) && $_FILES['avatar']['error'] == 0) {
            $avatar = $_FILES['avatar'];
            $extention = pathinfo($avatar['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . "." . $extention;
            $uploadDir = __DIR__ . '/../../uploads/avatar/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $uploadPath = $uploadDir . $filename;
            if (move_uploaded_file($avatar['tmp_name'], $uploadPath)) {
                $data['avatar'] = $filename; // lưu tên file vào $data
            }
        }

        $result = $this->userModel->create($data);

        if ($result) {
            Message::set('success', 'Tạo nhân viên thành công');
        } else {
            Message::set('error', 'Tạo nhân viên thất bại');
        }

        redirect("user");
    }


    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect("user");

        $id = $_POST['id'];
        $fullname = trim($_POST['fullname']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        $roles = $_POST['roles'] ?? 'guide';
        $status = ($_POST['status']);

        // Check email tồn tại
        if ($this->userModel->emailExists($email, $id)) {
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
        $currentUser = $this->userModel->getById($id);

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


        $result = $this->userModel->update($id, $data);

        if ($result) {
            Message::set('success', 'Cập nhật thành công');
        } else {
            Message::set('error', 'Cập nhật thất bại');
        }

        redirect("user");
    }
    public function delete()
    {
        $id = $_GET['id'] ?? null;
        if ($id === $_SESSION['currentUser']['id']) {
            Message::set('error', 'Không thể xóa chính bạn');
        }
        if ($id) {
            $result = $this->userModel->delete($id);
            if ($result === "FOREIGN_KEY_CONSTRAINT") {
                Message::set('error', 'Không thể xóa nhân viên này vì có dữ liệu liên quan!');
            } elseif ($result) {
                Message::set('success', 'Xóa nhân viên thành công!');
            } else {
                Message::set('error', 'Xóa nhân viên thất bại!');
            }

            redirect("user");
            exit;
        } else {
            echo "ID nhân viên không hợp lệ!";
        }
    }
}
