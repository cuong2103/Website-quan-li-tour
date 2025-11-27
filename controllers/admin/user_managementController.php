<?php 
class user_managementController {
    public $model;

    public function __construct() {
        $this->model = new user_managementModel();
    }

    public function index() {
        $users = $this->model->getAll();
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
    // Lấy dữ liệu từ form
    $data = [];
    $data['fullname'] = $_POST['fullname'] ?? '';
    $data['email']    = $_POST['email'] ?? '';
    $data['phone']    = $_POST['phone'] ?? '';
    
    // Role: mặc định 2 = guide nếu không có
    $data['role_id']  = isset($_POST['role']) ? ($_POST['role'] === 'admin' ? 1 : 2) : 2;

    // Status: ép kiểu int, mặc định 1
    $data['status']   = isset($_POST['status']) ? (int)$_POST['status'] : 1;

    // Password: nếu có thì hash, nếu không có thì tạo mặc định
    $data['password'] = !empty($_POST['password'])
                    ? password_hash($_POST['password'], PASSWORD_DEFAULT)
                    : password_hash('123456', PASSWORD_DEFAULT); // default password
        
    $data['birthday'] = $_POST['birthday'] ?? null;
    $data['gender']   = $_POST['gender'] ?? null;
    $data['address']  = $_POST['address'] ?? null;
    $data['start_date'] = $_POST['start_date'] ?? null;
    $data['certificate'] = $_POST['certificate'] ?? null;

    $userModel = new user_managementModel();
    $userModel->create($data);

    redirect("user");
}

    public function update(){
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect("user");

        $id = $_POST['id'];
        $fullname = trim($_POST['fullname']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        $role_id = $_POST['role'] == 'admin' ? 1 : 2;
        $status = isset($_POST['status']) ? (int)$_POST['status'] : 1; // 1 = Hoạt động

        if($this->model->emailExists($email, $id)){
            Message::set('error', 'Email đã tồn tại');
            redirect("user-edit&id=$id");
        }

        $result = $this->model->update([
            'fullname' => $fullname,
            'email' => $email,
            'phone' => $phone,
            'role_id' => $role_id,
            'status' => $status
        ], $id);

        if($result){
            Message::set('success', 'Cập nhật thành công');
        } else {
            Message::set('error', 'Cập nhật thất bại');
        }

        redirect("user");
    }

    public function delete(){
        $id = $_GET['id'] ?? null;
        if ($id){
            $this->model->delete($id);
            Message::set('success', 'Xóa thành công');
        }
        redirect("user");
    }   
}
