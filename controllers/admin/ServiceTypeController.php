<?php
class ServiceTypeController
{
    public $serviceTypeModel;

    public function __construct()
    {
        $this->serviceTypeModel = new ServiceTypeModel();
    }

    public function index()
    {
        $search = $_GET["search"] ?? null;
        if($search){
            $serviceTypes = $this->serviceTypeModel->search($search);
        } else {
            $serviceTypes = $this->serviceTypeModel->getAll();
        }
        //  dd($serviceTypes);
        require_once './views/admin/service-type/index.php';
    }
    // hiển thị dữ liệu
    public function detail($id)
    {
        $serviceType = $this->serviceTypeModel->getDetail($id);
        // dd($serviceType);
        require_once './views/admin/service-type/detail.php';
    }
    // xem chi tiết
    public function store(){
        $name = $_POST["name"];
        $description = $_POST["description"];
        $created_by = 1;
        $this->serviceTypeModel->create($name, $description, $created_by);
        header("Location: index.php?act=service-type");
    }
    // xóa
    public function delete($id){
        $this->serviceTypeModel->delete($id);
        header("Location: index.php?act=service-type");
    }
    //sửa
    public function edit($id){
        $serviceType = $this->serviceTypeModel->getDetail($id);
        require_once './views/admin/service-type/edit.php';
    }
    //update
    public function update(){
        $id = $_POST["id"];
        $name = $_POST["name"];
        $description = $_POST["description"];
        $this->serviceTypeModel->update($id, $name, $description);

        header("Location: index.php?act=service-type");
    }
}


?>