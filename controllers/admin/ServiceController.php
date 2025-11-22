<?php

class ServiceController
{
    private $serviceModel;

    public function __construct()
    {
        $this->serviceModel = new ServiceModel();
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

    public function create(){
        $service = $this->serviceModel->create();
        require_once './views/admin/services/create.php';
    }


}