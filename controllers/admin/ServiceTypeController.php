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
        if ($search) {
            $serviceTypes = $this->serviceTypeModel->search($search);
        } else {
            $serviceTypes = $this->serviceTypeModel->getAll();
        }
        require_once './views/admin/service-type/index.php';
    }
    // hiển thị dữ liệu
    public function detail()
    {
        $id = $_GET['id'];
        $serviceType = $this->serviceTypeModel->getDetail($id);
        require_once './views/admin/service-type/detail.php';
    }
    // xem chi tiết
    public function store()
    {
        $name = $_POST["name"];
        $description = $_POST["description"];

        // Validate bắt buộc nhập
        $errors = validate($_POST, [
            'name' => 'required|min:2|max:100'
        ]);

        // Nếu để trống
        if (!empty($errors)) {
            Message::set("error", "Tên loại dịch vụ không được để trống.");
            redirect("service-type");
            return;
        }

        // Kiểm tra tên trùng
        if ($this->serviceTypeModel->existsByName($name)) {
            Message::set("error", "Loại dịch vụ '$name' đã tồn tại. Vui lòng nhập tên khác.");
            redirect("service-type");
            return;
        }

        // Nếu hợp lệ → thêm mới
        $created_by = $_SESSION['currentUser']['id'] ?? 1;
        $this->serviceTypeModel->create($name, $description, $created_by);

        Message::set("success", "Thêm loại dịch vụ thành công.");
        redirect("service-type");
    }
    // xóa
    public function delete()
    {
        $id = $_GET['id'];
        $this->serviceTypeModel->delete($id);
        Message::set("success", "Xóa loại dịch vụ thành công");
        redirect("service-type");
    }

    //sửa
    public function edit()
    {
        $id = $_GET['id'];
        $serviceType = $this->serviceTypeModel->getDetail($id);
        require_once './views/admin/service-type/edit.php';
    }
    //update
    public function update()
    {
        $id = $_POST["id"];
        $name = $_POST["name"];
        $description = $_POST["description"];
        $updated_by = $_SESSION['currentUser']['id'];
        $this->serviceTypeModel->update($id, $name, $description, $updated_by);
        Message::set("success", "Cập Nhập Thành Công");
        redirect("service-type");
    }
}
