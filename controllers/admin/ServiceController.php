<?php

class ServiceController
{
    private $serviceModel;
    private $supplierModel;

    public function __construct()
    {
        $this->serviceModel = new ServiceModel();
        // $this->supplierModel = new SupplierModel();
    }

    // Danh sách dịch vụ
    public function index()
    {
        $services = $this->serviceModel->getAll();
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

    public function delete($id){
        //lấy id từ tham số hoặc Get
        $id = $id ?? $_GET['id'] ?? null;
        //kiểm tra xem hợp lệ hay ko
        if(!$id || !is_numeric($id)){
            $_SESSION['error'] = "ID KO HỢP LỆ";
            header("Location: ?act=service");
        }
        // gọi hàm xóa ở model
        $delete = $this->serviceModel->delete($id);
        if($delete){
            $_SESSION['success'] = "Xóa Dịch Vụ Thành Công";
        } else {
            $_SESSION['success'] = "Xóa Dịch Vụ Không Thành Công";
        }

        header("Location: ?act=service");
    }

    public function create(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            $data = [
                'name' => $_POST['name'],
                'description' => $_POST['description'],
                'service_type_id' => $_POST['service_type_id'],
                'supplier_id' => $_POST['supplier_id'],
                'price' => $_POST['price'],
                'created_by' => $_SESSION['username'] ?? 'admin',
            ];
            
            if($this->serviceModel->create($data)){
                $_SESSION['success'] = "thêm dịch vụ thành công";
                header("Location: ?act=service");
            } else {
                $_SESSION['error'] = "Thêm Dịch Vụ Thất Bại";
            }
        }
        require_once './views/admin/services/create.php';
    }

    public function edit($id = null){
    $id = $id ?? $_GET['id'] ?? 0;
    $service = $this->serviceModel->getDetail($id);
    if(!$service){
        $_SESSION['error'] = 'Dịch Vụ Không Tồn Tại';
        header("Location: ?act=service");
        exit();
    }

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $data = [
            'name' => $_POST['name'],
            'description' => $_POST['description'],
            'service_type_id' => $_POST['service_type_id'],
            'supplier_id' => $_POST['supplier_id'],
            'price' => $_POST['price'],
        ];

        if($this->serviceModel->update($id, $data)){
            $_SESSION['success'] = "Cập nhật thành công";
            header("Location: ?act=service");
            exit();
        } else {
            $_SESSION['error'] = 'Cập nhật thất bại';
        }
    }

    require_once './views/admin/services/edit.php';
}


}