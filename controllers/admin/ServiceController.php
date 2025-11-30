<?php

class ServiceController
{
    private $serviceModel;
    private $supplierModel;
    private $serviceTypeModel;

    public function __construct()
    {
        requireAdmin();
        $this->serviceModel = new ServiceModel();
        $this->supplierModel = new SupplierModel();
        $this->serviceTypeModel = new ServiceTypeModel();
    }

    public function index()
    {
        $keyword = $_GET['keyword'] ?? '';
        $type = $_GET['service_type_id'] ?? '';
        $supplier = $_GET['supplier_id'] ?? '';

        $services = $this->serviceModel->getAll($keyword, $type, $supplier);
        $serviceTypes = $this->serviceTypeModel->getAll();
        $suppliers = $this->supplierModel->getAll();

        require_once './views/admin/services/index.php';
    }

    public function detail($id = null)
    {
        $id = $id ?? $_GET['id'] ?? 0;

        if (!is_numeric($id) || $id <= 0) {
            Message::set("error", "ID không hợp lệ!");
            redirect("service");
            exit;
        }

        $service = $this->serviceModel->getDetail($id);

        if (!$service) {
            Message::set("error", "Dịch vụ không tồn tại!");
            redirect("service");
            exit;
        }

        require_once './views/admin/services/detail.php';
    }

    public function delete($id = null)
    {
        $id = $id ?? $_GET['id'] ?? 0;

        if (!is_numeric($id) || $id <= 0) {
            Message::set("error", "ID không hợp lệ!");
            redirect("service");
            exit;
        }

        if ($this->serviceModel->delete($id)) {
            Message::set("success", "Xóa dịch vụ thành công!");
        } else {
            Message::set("error", "Xóa dịch vụ thất bại!");
        }

        redirect("service");
    }

    public function create()
    {
        $serviceTypes = $this->serviceTypeModel->getAll();
        $suppliers = $this->supplierModel->getAll();

        require_once './views/admin/services/create.php';
    }

    public function store()
    {
        $data = [
            'name' => $_POST['name'],
            'description' => $_POST['description'],
            'service_type_id' => $_POST['service_type_id'],
            'supplier_id' => $_POST['supplier_id'],
            'estimated_price' => $_POST['estimated_price'] ?? 0,
            'created_by' => $_SESSION['admin_id'] ?? 1
        ];

        // RULES
        $rules = [
            'name' => 'required|min:3|max:100',
            'service_type_id' => 'required|numeric',
            'supplier_id' => 'required|numeric',
            'estimated_price' => 'required|numeric'
        ];

        // VALIDATE
        $errors = validate($data, $rules);

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $data;

            redirect("service-create");
            exit;
        }

        // Kiểm tra trùng
        if ($this->serviceModel->isDuplicate($data['name'], $data['service_type_id'], $data['supplier_id'])) {
            Message::set("error", "Dịch vụ này đã tồn tại!");
            header('Location: ' . BASE_URL . '?act=service-create');
            exit();
        }


        if ($this->serviceModel->create($data)) {
            Message::set("success", "Thêm dịch vụ thành công!");
            redirect("service");
        } else {
            Message::set("error", "Thêm dịch vụ thất bại!");
            redirect("service-create");
        }
    }

    public function edit($id = null)
    {
        $id = $id ?? $_GET['id'] ?? 0;

        $service = $this->serviceModel->getDetail($id);

        if (!$service) {
            Message::set("error", "Dịch vụ không tồn tại!");
            redirect("service");
        }

        $serviceTypes = $this->serviceTypeModel->getAll();
        $suppliers = $this->supplierModel->getAll();

        require_once './views/admin/services/edit.php';
    }

    public function update()
    {
        $id = $_POST['id'] ?? 0;

        if ($id <= 0) {
            Message::set("error", "ID không hợp lệ!");
            redirect("service");
        }

        $data = [
            'name' => $_POST['name'],
            'description' => $_POST['description'],
            'service_type_id' => $_POST['service_type_id'],
            'supplier_id' => $_POST['supplier_id'],
            'estimated_price' => $_POST['estimated_price']
        ];

        // RULES
        $rules = [
            'name' => 'required|min:3|max:100',
            'service_type_id' => 'required|numeric',
            'supplier_id' => 'required|numeric',
            'estimated_price' => 'required|numeric'
        ];

        // VALIDATE
        $errors = validate($data, $rules);

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $data;

            redirect("service-edit&id=" . $id);
            exit;
        }

        // Kiểm tra trùng
        if ($this->serviceModel->isDuplicate($data['name'], $data['service_type_id'], $data['supplier_id'], $id)) {
            Message::set("error", "Dịch vụ này đã tồn tại!");
            header('Location: ' . BASE_URL . '?act=service-edit&id=' . $id);
            exit();
        }


        if ($this->serviceModel->update($id, $data)) {
            Message::set("success", "Cập nhật dịch vụ thành công!");
        } else {
            Message::set("error", "Cập nhật thất bại!");
        }

        redirect("service");
    }
}
