<?php
class SupplierController
{
    public $supplierModel;
    public $destinationModel;
    public $userModel;
    public $serviceModel;
    public function __construct()
    {
        requireAdmin();
        $this->supplierModel = new SupplierModel();
        $this->destinationModel = new DestinationModel();
        $this->userModel = new UserModel();
        $this->serviceModel = new ServiceModel();
    }

    //list
    public function index()
    {
        $suppliers = $this->supplierModel->getALL();
        $destinations = $this->destinationModel->getALL();
        require_once "./views/admin/suppliers/index.php";
    }
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name'           => trim($_POST['name'] ?? ''),
                'email'          => trim($_POST['email'] ?? ''),
                'phone'          => trim($_POST['phone'] ?? ''),
                'status'         => trim($_POST['status'] ?? ''),
                'destination_id' => $_POST['destination_id'] ?? null,
                'created_by'     => $_SESSION['currentUser']['id'] ?? null,
                'updated_by'  => $_SESSION['currentUser']['id'] ?? null,
            ];
            $rules = [
                'name'           => 'required|min:2|max:100',
                'email'          => 'required|email|max:100',
                'phone'          => 'required|phone',
                'status'         => 'required',
                'destination_id' => 'required',
                'created_by'     => 'required',
                'updated_by'     => 'required',
            ];



            $errors = validate($data, $rules);

            // Nếu có lỗi → trả về form với lỗi
            if (!empty($errors)) {
                $suppliers    = $this->supplierModel->getALL();
                $destinations = $this->destinationModel->getALL();
                require_once './views/admin/suppliers/index.php';
                exit;
            }

            // Nếu không có lỗi → lưu dữ liệu
            $this->supplierModel->create($data);
            Message::set("success", "Thêm nhà cung cấp thành công!");
            redirect("suppliers"); // hoặc redirect("suppliers");
            exit;
        }

        // Nếu không phải POST (truy cập trực tiếp) → chuyển về danh sách hoặc form tạo
        redirect("suppliers");
    }
    // hiển thị form sửa
    public function edit()
    {
        $id = $_GET['id'];
        $suppliers = $this->supplierModel->getALL();
        $supplier = $this->supplierModel->getByID($id);
        $destinations = $this->supplierModel->getDestinations();

        require_once "./views/admin/suppliers/edit.php";
    }
    //cập nhật
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $id = $_GET['id'] ?? null;


            $data = [
                'id'             => $id,
                'name'           => trim($_POST['name'] ?? ''),
                'email'          => trim($_POST['email'] ?? ''),
                'phone'          => trim($_POST['phone'] ?? ''),
                'status'         => $_POST['status'] ?? '1',
                'destination_id' => $_POST['destination_id'] ?? null,
                'updated_by'  => $_SESSION['currentUser']['id'] ?? null,
            ];

            $rules = [
                'name'           => 'required|min:2|max:100',
                'email'          => 'required|email|max:100',
                'phone'          => 'required|phone',
                'status'         => 'required',
                'destination_id' => 'required',
            ];

            // Không truyền $messages → dùng lỗi mặc định của hàm validate()
            $errors = validate($data, $rules);

            if (!empty($errors)) {
                // Lưu lỗi + dữ liệu cũ để hiển thị lại form edit
                $suppliers = $this->supplierModel->getALL();
                $destinations = $this->destinationModel->getALL();
                $supplier = $this->supplierModel->getByID($id);
                require_once "./views/admin/suppliers/edit.php";
                exit;
            }

            // Thành công → cập nhật
            $this->supplierModel->update($data);

            Message::set('success', 'Cập nhật nhà cung cấp thành công!');
            redirect('suppliers');
        }

        // Nếu truy cập trực tiếp bằng GET → chuyển về danh sách
        redirect('suppliers');
    }
    //xoá
    public function delete()
    {
        $id = $_GET["id"];
        $result = $this->supplierModel->delete($id);
        if ($result === "FOREIGN_KEY_CONSTRAINT") {
            Message::set("error", "Không thể xóa nhà cung cấp này đang sử dụng dịch vụ liên quan!");
            redirect("suppliers");
            return;
        }
        Message::set("success", "Xóa nhà cung cấp thành công!");
        redirect("suppliers");
    }
    // xem chi tiết
    public function detail()
    {
        $id = $_GET['id'] ?? null;
        $supplier = $this->supplierModel->getByID($id);

        $createdBy = $this->userModel->getByID($supplier['created_by']);

        $updatedBy = $this->userModel->getByID($supplier['updated_by']);

        $services = $this->serviceModel->getBySupplierID($id);

        require_once "./views/admin/suppliers/detail.php";
    }
}
