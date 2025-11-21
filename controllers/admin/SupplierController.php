<?php
class SupplierController
{
    public $model;
    public function __construct()
    {
        $this->model = new SupplierModel();
    }

    //list
    public function index()
    {
        $suppliers = $this->model->getALL();
        $destinations = $this->model->getDestinations();

        require_once "./views/admin/suppliers/index.php";
    }
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // dd($_POST);
            $name = trim($_POST['name']);
            $email = trim($_POST['email']);
            $phone = trim($_POST['phone']);
            $destination_id = trim($_POST['destination_id']);

            $created_by = 1;
            // validate dữ liệu
            if (empty($name) || empty($email) || empty($phone) || empty($destination_id) || empty($created_by)) {
                dd($_POST);
                $err = "Vui lòng nhập đầy đủ thông tin";
                $suppliers = $this->model->getALL();
                $destinations = $this->model->getDestinations();
                require_once "./views/admin/suppliers/index.php";
                return;
            } else {
                $this->model->create($name, $email, $phone, $destination_id, $created_by);
                header("Location: ?act=suppliers");
                die();
            }
        }
        //dsach điểm đến để đổ ra select
        $destinations = $this->model->getDestinations();

        require_once "./views/admin/suppliers/create.php";
    }
    // hiển thị form sửa
    public function edit()
    {
        $id = $_GET['id'];
        $suppliers = $this->model->getALL();
        $supplier = $this->model->getByID($id);
        $destinations = $this->model->getDestinations();

        require_once "./views/admin/suppliers/edit.php";
    }
    //cập nhật
    public function update()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $id = trim($_GET['id']);
            $name = trim($_POST['name']);
            $email = trim($_POST['email']);
            $phone = trim($_POST['phone']);
            $destination_id = trim($_POST['destination_id']);
            $created_by = 1;
            $this->model->update($id, $name, $email, $phone, $destination_id, $created_by);
            header("location: ?act=suppliers");
            die();
        }
    }
    //xoá
    public function delete()
    {
        $id = $_GET["id"];
        $this->model->delete($id);
        header("location: ?act=suppliers");
        die();
    }
    // xem chi tiết
    public function detail()
    {
        $id = $_GET['id'];
        $supplier = $this->model->getByID($id);
        require_once "./views/admin/suppliers/detail.php";
    }
}
