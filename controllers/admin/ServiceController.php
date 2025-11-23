<?php

class ServiceController
{
    private $serviceModel;
    private $supplierModel;
    private $serviceTypeModel;

    public function __construct()
    {
        $this->serviceModel = new ServiceModel();
        $this->supplierModel = new SupplierModel();
        $this->serviceTypeModel = new ServiceTypeModel();
    }

    // Danh sách dịch vụ
    public function index()
    {
        $keyword = $_GET['keyword'] ?? '';
        $service_type_id = $_GET['service_type_id'] ?? '';
        $supplier_id = $_GET['supplier_id'] ?? '';


        $services = $this->serviceModel->getAll($keyword,$service_type_id,$supplier_id);
        $serviceTypes = $this->serviceTypeModel->getAll();
        $suppliers = $this->supplierModel->getALL();
        require_once './views/admin/services/index.php';
    }

    // Chi tiết dịch vụ
    public function detail($id = null)
    {
        $id = $id ?? $_GET['id'] ?? null;
        if (!$id || !is_numeric($id)) {
            $_SESSION['error'] = "ID không hợp lệ!";
            header('Location: ?act=service');
            exit;
        }

        $service = $this->serviceModel->getDetail((int)$id);
        if (!$service) {
            $_SESSION['error'] = "Dịch vụ không tồn tại!";
            header('Location: ?act=service');
            exit;
        }
        require_once './views/admin/services/detail.php';
    }

    // Xóa dịch vụ
    public function delete($id = null)
    {
        $id = $id ?? $_GET['id'] ?? null;
        if (!$id || !is_numeric($id)) {
            $_SESSION['error'] = "ID không hợp lệ!";
            header("Location: ?act=service");
            exit;
        }

        if ($this->serviceModel->delete($id)) {
            $_SESSION['success'] = "Xóa dịch vụ thành công!";
        } else {
            $_SESSION['error'] = "Xóa dịch vụ thất bại!";
        }

        header("Location: ?act=service");
    }

    // Form thêm dịch vụ
    public function create()
    {
        $serviceTypes = $this->serviceTypeModel->getAll();
        $suppliers = $this->supplierModel->getAll();
        require_once './views/admin/services/create.php';
    }

    // Xử lý thêm dịch vụ
    public function store()
    {
        $data = [
            'name' => $_POST['name'] ?? '',
            'description' => $_POST['description'] ?? '',
            'service_type_id' => $_POST['service_type_id'] ?? null,
            'supplier_id' => $_POST['supplier_id'] ?? null,
            'price' => $_POST['price'] ?? 0,
            'created_by' => $_SESSION['admin_id'] ?? 1,
        ];

        if(empty($data['name']) || empty($data['service_type_id']) || empty($data['supplier_id'])){
            $_SESSION['error'] = "Vui lòng điền đầy đủ thông tin bắt buộc!";
            header("Location: ?act=service-create");
            exit;
        }

        if ($this->serviceModel->create($data)) {
            $_SESSION['success'] = "Thêm dịch vụ thành công!";
            header("Location: ?act=service");
            exit;
        } else {
            $_SESSION['error'] = "Thêm dịch vụ thất bại!";
            header("Location: ?act=service-create");
            exit;
        }
    }

    //sửa
    public function edit($id = null)
    {
        $id = $id ?? ($_GET['id'] ?? 0);
        $service = $this->serviceModel->getDetail($id);

        if (!$service) {
            $_SESSION['error'] = 'Dịch vụ không tồn tại!';
            header("Location: ?act=service");
            exit();
        }

        $serviceTypes = $this->serviceTypeModel->getAll();
        $suppliers = $this->supplierModel->getAll();

        require_once './views/admin/services/edit.php';
    }

    public function update(){
        $id = $_POST['id'] ?? null;
        if(!$id){
            $_SESSION['error'] = 'ID KO HỢP LỆ';
            header("Location: ?act=service");
            exit();
        }

        $data = [
            'name' => $_POST['name'],
            'description' => $_POST['description'],
            'service_type_id' => $_POST['service_type_id'],
            'supplier_id' => $_POST['supplier_id'],
            'price' => $_POST['price'],
        ];

        if ($this->serviceModel->update($id, $data)) {
            $_SESSION['success'] = "vào trang cập nhập thành công";
        } else {
            $_SESSION['error'] = "CẬP NHẬP THẤT BẠI";
        }

        header("Location: ?act=service");
        exit();
        }

}
