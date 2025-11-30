<?php
class PolicyController
{
    public $model;
    public function __construct()
    {
        requireAdmin();
        $this->model = new PolicyModel();
    }
    // list danh sách khách hàng
    public function index()
    {
        $policies = $this->model->getAll();
        require_once "./views/admin/policies/index.php";
    }
    // chi tiết chính sách
    public function detail()
    {
        $id = $_GET['id'];
        $policy = $this->model->getByID($id);
        // dd($policy);
        require_once "./views/admin/policies/detail.php";
    }

    // xoá chính sách
    public function delete()
    {
        $id = $_GET['id'];
        $this->model->delete($id);
        redirect("policies");
        Message::set("success", "Xóa thành công!");
        die();
    }
    // sửa chính sách
    public function edit()
    {
        $id = $_GET['id'];
        $policy = $this->model->getByID($id);
        $policies = $this->model->getAll();
        require_once "./views/admin/policies/edit.php";
    }

    // thêm chính sách mới
    public function create()
    {
        require_once "./views/admin/policies/create.php";
    }
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('policies-create');
            die();
        }
        // dd($_POST);
        // lấy dữ liệu từ form
        $data = [
            'name' => trim($_POST['name']),
            'content' => trim($_POST['content']),
            'created_by' => $_SESSION['user']['id'],
        ];

        // validate dữ liệu
        $rules = [
            'name' => 'required|min:3|max:50',
            'content' => 'required|min:10',
        ];
        $errors = validate($data, $rules);
        if (!empty($errors)) {
            $policies = $this->model->getAll();
            require_once './views/admin/policies/index.php';
            exit;
        } else {
            $this->model->create($data);
            Message::set("success", "Thêm thành công!");
            redirect("policies");
            exit;
        }
    }

    // cập nhật chính sách
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect("policies");
            exit;
        }

        $id = $_GET['id'];

        $data = [
            'id' => $id,
            'name' => trim($_POST['name']),
            'content' => trim($_POST['content']),
            'created_by' => $_SESSION['user']['id'],
        ];

        // validate
        $rules = [
            'name' => 'required|min:3|max:50',
            'content' => 'required|min:10',
        ];

        $errors = validate($data, $rules);

        if (!empty($errors)) {
            $policy = $this->model->getByID($id);
            $policies = $this->model->getAll();
            require_once './views/admin/policies/edit.php';
            exit;
        }

        $this->model->update($data);

        Message::set("success", "Cập nhật thành công!");
        redirect("policies");
        exit;
    }
}
