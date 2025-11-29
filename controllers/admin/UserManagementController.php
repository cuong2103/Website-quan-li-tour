<?php 
class UserManagementController {

    public $model;

    public function __construct() {
        $this->model = new UserModel();
    }

    public function index() {
        $users = $this->model->getAll();
        // dd($users);
        require './views/admin/User_management/index.php';        
    }

    public function detail()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) redirect("user");

        $user = $this->model->getById($id);
       
        if (!$user) redirect("user");
        require './views/admin/User_management/detail.php';
    }

    public function create(){
        require './views/admin/User_management/create.php';
    }

    public function edit(){
    $id = $_GET['id'];
    if(!$id) redirect("user"); // chuyển về danh sách nếu không có id
    $user = $this->model->getById($id);
    
    if(!$user){
        Message::set('error', 'Không tìm thấy nhân viên');
        redirect("user"); // chuyển về danh sách nếu id không tồn tại
    }
    require_once './views/admin/User_management/edit.php'; // truyền $user sang view
    }

    public function store() {
    $data = [];
    $data['fullname'] = $_POST['fullname'] ?? '';
    $data['email']    = $_POST['email'] ?? '';
    $data['phone']    = $_POST['phone'] ?? '';
    $data['roles']  = isset($_POST['roles']) ? (int)$_POST['roles'] : 2;
    $data['status']   = isset($_POST['status']) ? (int)$_POST['status'] : 1;
    $data['password'] = !empty($_POST['password'])
                        ? password_hash($_POST['password'], PASSWORD_DEFAULT)
                        : password_hash('123456', PASSWORD_DEFAULT);
    $data['birthday'] = $_POST['birthday'] ?? null;
    $data['gender']   = $_POST['gender'] ?? null;
    $data['address']  = $_POST['address'] ?? null;
    $data['start_date'] = $_POST['start_date'] ?? null;
    $data['certificate'] = $_POST['certificate'] ?? null;
    // --- Upload avatar trước khi lưu ---
    if (!empty($_FILES['avatar']['name']) && $_FILES['avatar']['error'] == 0) {
        $avatar = $_FILES['avatar'];
        $extention = pathinfo($avatar['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . "." . $extention;
        $uploadDir = __DIR__ . '/../../uploads/avatar/';
        // dd($uploadDir);
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        $uploadPath = $uploadDir . $filename;
        if (move_uploaded_file($avatar['tmp_name'], $uploadPath)) {
            $data['avatar'] = $filename; // lưu tên file vào $data
        }
    }

    // Tạo user mới với avatar
    $userModel = new UserModel();
    $result = $userModel->create($data);

    if ($result) {
        Message::set('success', 'Tạo nhân viên thành công');
    } else {
        Message::set('error', 'Tạo nhân viên thất bại');
    }

    redirect("user");
}


    public function update(){
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect("user");

    $id = $_POST['id'];
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $roles = $_POST['roles'] ?? 'guide';
    $status = ($_POST['status']);
    // dd($status); 

    // Check email tồn tại
    if($this->model->emailExists($email, $id)){
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
    $currentUser = $this->model->getById($id);

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


    $result = $this->model->update($id, $data);

        if($result){
            Message::set('success', 'Cập nhật thành công');
        } else {
            Message::set('error', 'Cập nhật thất bại');
        }

        redirect("user");
    }
    public function delete() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $userModel = new UserModel();
            $userModel->delete($id); // gọi model để xóa user theo id
            header("Location: ?act=user"); // quay về danh sách
            exit;
        } else {
            echo "ID nhân viên không hợp lệ!";
        }
    }


}
