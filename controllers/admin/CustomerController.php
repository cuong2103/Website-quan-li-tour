<?php
class CustomerController
{
    public $model;
    public function __construct()
    {
        $this->model = new CustomerModel();
    }
    //list danh sách khách hàng
    public function index()
    {
        // Lấy dữ liệu filter từ URL
        $search = $_GET['search'] ?? '';
        // $email = $_GET['email'] ?? '';


        // Gọi model để lọc
        $listCustomers = $this->model->filter(
            $search,
            // $email
        );

        $customers = $this->model->getAll();
        require_once "./views/admin/customers/index.php";
    }
    public function detail()
    {
        $id = $_GET['id'];
        $customer = $this->model->getByID($id);
        require_once "./views/admin/customers/detail.php";
    }
    public function delete()
    {
        $id = $_GET['id'];
        $this->model->delete($id);
        header("location: ?act=customers");
        die();
    }
    public function edit()
    {
        $id = $_GET['id'];
        $customer = $this->model->getByID($id);
        require_once "./views/admin/customers/edit.php";
    }
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = trim($_POST['name']);
            $email = trim($_POST['email']);
            $phone = trim($_POST['phone']);
            $address = trim($_POST['address']);
            $created_by = 1; //tạm thời
            $passport = trim($_POST['passport']);
            $gender = trim($_POST['gender']);

            if (empty($name) || empty($email) || empty($phone) || empty($address)) {
                $err = " Vui lòng nhập đầy đủ thông tin";
                require_once "./views/admin/customers/create.php";
                return;
            } else {
                $passport = trim($_POST['passport']);
                $gender = trim($_POST['gender']);
                $this->model->create($name, $email, $phone, $address, $created_by, $passport, $gender);
                header("location: ?act=customers");
                die();
            }
        } else {
            require_once "./views/admin/customers/create.php";
        }
    }
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_GET['id'];
            $name = trim($_POST['name']);
            $email = trim($_POST['email']);
            $phone = trim($_POST['phone']);
            $address = trim($_POST['address']);
            $created_by = $_SESSION['user']['id'];
            $passport = trim($_POST['passport']);
            $gender = trim($_POST['gender']);

            if (empty($name) || empty($email) || empty($phone) || empty($address)) {
                $err = " Vui lòng nhập đầy đủ thông tin";
                require_once "./views/admin/customers/create.php";
                return;
            } else {
                $passport = trim($_POST['passport']);
                $gender = trim($_POST['gender']);
                $this->model->update($id, $name, $email, $phone, $address, $created_by, $passport, $gender);
                header("location: ?act=customers");
                die();
            }
        }
    }
}
