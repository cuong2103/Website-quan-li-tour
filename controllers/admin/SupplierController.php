<?php
class SupplierController
{
    public $supplierModel;
    public $destinationModel;
    public $userModel;
    public $serviceModel;

    public function __construct()
    {
        // Chỉ admin mới truy cập được controller này
        requireAdmin();

        // Khởi tạo các model dùng trong controller
        $this->supplierModel = new SupplierModel();
        $this->destinationModel = new DestinationModel();
        $this->userModel = new UserModel();
        $this->serviceModel = new ServiceModel();
    }

    // Hiển thị danh sách nhà cung cấp
    public function index()
    {
        // Lấy toàn bộ nhà cung cấp
        $suppliers = $this->supplierModel->getALL();

        // Lấy danh sách điểm đến (hiện thị trong filter hoặc bảng)
        $destinations = $this->destinationModel->getALL();

        // Load view danh sách
        require_once "./views/admin/suppliers/index.php";
    }

    // Hiển thị form thêm mới
    public function create()
    {
        // Lấy danh sách điểm đến để chọn
        $destinations = $this->destinationModel->getALL();

        // Load view form tạo mới
        require_once "./views/admin/suppliers/create.php";
    }

    // Xử lý dữ liệu thêm mới
    public function store()
    {
        // Chỉ xử lý khi submit bằng POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Gom dữ liệu gửi lên form
            $data = [
                'name'           => trim($_POST['name'] ?? ''),
                'email'          => trim($_POST['email'] ?? ''),
                'phone'          => trim($_POST['phone'] ?? ''),
                'status'         => trim($_POST['status'] ?? ''),
                'destination_id' => $_POST['destination_id'] ?? null,
                'created_by'     => $_SESSION['currentUser']['id'] ?? null,
                'updated_by'     => $_SESSION['currentUser']['id'] ?? null,
            ];

            // Bộ rule validate dữ liệu
            $rules = [
                'name'           => 'required|min:2|max:100',
                'email'          => 'required|email|max:100',
                'phone'          => 'required|phone',
                'status'         => 'required',
                'destination_id' => 'required',
                'created_by'     => 'required',
                'updated_by'     => 'required',
            ];

            // Thực hiện validate
            $errors = validate($data, $rules);

            // Nếu có lỗi → giữ lại dữ liệu + hiện lại form
            if (!empty($errors)) {
                $suppliers    = $this->supplierModel->getALL();
                $destinations = $this->destinationModel->getALL();
                require_once './views/admin/suppliers/create.php';
                exit;
            }

            // Nếu không lỗi → gọi model để thêm supplier
            $this->supplierModel->create($data);

            // Báo thành công
            Message::set("success", "Thêm nhà cung cấp thành công!");

            // Quay về trang danh sách
            redirect("suppliers");
            exit;
        }

        // Nếu không phải POST → quay về danh sách
        redirect("suppliers");
    }

    // Hiển thị form sửa
    public function edit()
    {
        $id = $_GET['id'];

        // Lấy danh sách (thường dùng cho sidebar/filter)
        $suppliers = $this->supplierModel->getALL();

        // Lấy nhà cung cấp cần sửa
        $supplier = $this->supplierModel->getByID($id);

        // Lấy danh sách điểm đến
        $destinations = $this->destinationModel->getALL();

        // Load view form edit
        require_once "./views/admin/suppliers/edit.php";
    }

    // Xử lý cập nhật
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Lấy id từ URL
            $id = $_GET['id'] ?? null;

            // Lấy dữ liệu gửi lên
            $data = [
                'id'             => $id,
                'name'           => trim($_POST['name'] ?? ''),
                'email'          => trim($_POST['email'] ?? ''),
                'phone'          => trim($_POST['phone'] ?? ''),
                'status'         => $_POST['status'] ?? '1',
                'destination_id' => $_POST['destination_id'] ?? null,
                'updated_by'     => $_SESSION['currentUser']['id'] ?? null,
            ];

            // Rule kiểm tra dữ liệu
            $rules = [
                'name'           => 'required|min:2|max:100',
                'email'          => 'required|email|max:100',
                'phone'          => 'required|phone',
                'status'         => 'required',
                'destination_id' => 'required',
            ];

            // Validate
            $errors = validate($data, $rules);

            // Nếu lỗi → load lại form sửa + giữ thông tin cũ
            if (!empty($errors)) {
                $suppliers = $this->supplierModel->getALL();
                $destinations = $this->destinationModel->getALL();
                $supplier = $this->supplierModel->getByID($id);
                require_once "./views/admin/suppliers/edit.php";
                exit;
            }

            // Nếu hợp lệ → cập nhật
            $this->supplierModel->update($data);

            Message::set('success', 'Cập nhật nhà cung cấp thành công!');
            redirect('suppliers');
        }

        // Nếu nhập trực tiếp URL → quay lại danh sách
        redirect('suppliers');
    }

    // Xóa nhà cung cấp
    public function delete()
    {
        $id = $_GET["id"];

        // Model trả về lỗi nếu supplier đang bị ràng buộc FK
        $result = $this->supplierModel->delete($id);

        if ($result === "FOREIGN_KEY_CONSTRAINT") {
            Message::set("error", "Không thể xóa nhà cung cấp này đang sử dụng dịch vụ liên quan!");
            redirect("suppliers");
            return;
        }

        Message::set("success", "Xóa nhà cung cấp thành công!");
        redirect("suppliers");
    }

    // Xem chi tiết một nhà cung cấp
    public function detail()
    {
        $id = $_GET['id'] ?? null;

        // Lấy thông tin nhà cung cấp
        $supplier = $this->supplierModel->getByID($id);

        // Lấy thông tin người tạo
        $createdBy = $this->userModel->getByID($supplier['created_by']);

        // Lấy thông tin người cập nhật cuối
        $updatedBy = $this->userModel->getByID($supplier['updated_by']);

        // Lấy các dịch vụ thuộc nhà cung cấp
        $services = $this->serviceModel->getBySupplierID($id);

        // Load view chi tiết
        require_once "./views/admin/suppliers/detail.php";
    }
}
