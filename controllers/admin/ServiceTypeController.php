<?php
class ServiceTypeController
{
    public $serviceTypeModel;
    public $serviceModel;

    public function __construct()
    {
        requireAdmin();
        $this->serviceTypeModel = new ServiceTypeModel();
        $this->serviceModel = new ServiceModel(); // để check services liên quan khi xóa
    }

    // Trang danh sách + form thêm
    public function index()
    {
        $search = $_GET['search'] ?? null;
        $serviceTypes = $search ? $this->serviceTypeModel->search($search) : $this->serviceTypeModel->getAll();

        $data = $_POST ?? [];
        $errors = $_SESSION['errors'] ?? [];
        unset($_SESSION['errors']);

        require_once './views/admin/service-type/index.php';
    }

    public function store()
    {
        $data = [
            'name' => $_POST['name'] ?? '',
            'description' => $_POST['description'] ?? ''
        ];

        // Validate
        $errors = validate($data, [
            'name' => 'required|min:2|max:100',
            'description' => 'max:255'
        ]);

        // Kiểm tra trùng tên
        if (empty($errors) && $this->serviceTypeModel->existsByName($data['name'])) {
            $errors['name'][] = "Loại dịch vụ '{$data['name']}' đã tồn tại.";
        }

        // Nếu lỗi → quay lại form + hiện lỗi
        if (!empty($errors)) {
            $serviceTypes = $this->serviceTypeModel->getAll();
            require_once './views/admin/service-type/index.php';
            return;
        }

        // Người tạo
        $created_by = $_SESSION['currentUser']['id'] ?? 1;

        // Gọi model thêm dữ liệu
        $result = $this->serviceTypeModel->create(
            $data['name'],
            $data['description'],
            $created_by
        );

        if ($result) {
            Message::set('success', "Thêm loại dịch vụ thành công!");
        } else {
            Message::set('error', "Thêm loại dịch vụ thất bại. Vui lòng thử lại!");
        }

        // Điều hướng
        redirect('service-type');
        exit;
    }


    // Form edit
    public function edit()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            redirect('service-type');
            exit;
        }

        $serviceType = $this->serviceTypeModel->getDetail($id);
        if (!$serviceType) {
            redirect('service-type');
            exit;
        }

        $serviceTypes = $this->serviceTypeModel->getAll();
        $data = $_POST ?? ['name' => $serviceType['name'], 'description' => $serviceType['description']];
        $errors = $_SESSION['validate_errors'] ?? [];
        unset($_SESSION['validate_errors']);

        require_once './views/admin/service-type/edit.php';
    }

    // Cập nhật
    public function update()
    {
        $id = $_POST['id'] ?? null;

        if (!$id) {
            redirect('service-type');
            exit;
        }

        $data = [
            'name' => $_POST['name'] ?? '',
            'description' => $_POST['description'] ?? ''
        ];

        // Validate
        $errors = validate($data, [
            'name' => 'required|min:2|max:100',
            'description' => 'max:255'
        ]);

        // Kiểm tra trùng tên (bỏ qua chính bản ghi)
        if (empty($errors) && $this->serviceTypeModel->existsByName($data['name'], $id)) {
            $errors['name'][] = "Loại dịch vụ '{$data['name']}' đã tồn tại.";
        }

        // Nếu lỗi → giữ lại dữ liệu nhập và trả về form edit
        if (!empty($errors)) {
            $serviceType = [
                'id' => $id,
                'name' => $data['name'],
                'description' => $data['description']
            ];
            require_once './views/admin/service-type/edit.php';
            return;
        }

        // Người sửa
        $updated_by = $_SESSION['currentUser']['id'] ?? 1;

        // Gọi model update
        $result = $this->serviceTypeModel->update(
            $id,
            $data['name'],
            $data['description'],
            $updated_by
        );

        if ($result) {
            Message::set('success', "Cập nhật loại dịch vụ thành công!");
        } else {
            Message::set('error', "Cập nhật thất bại, vui lòng thử lại!");
        }

        // Quay lại danh sách
        header("Location:" . BASE_URL . "?act=service-type");
        exit;
    }


    public function delete()
    {
        $id = $_GET['id'] ?? null;

        if ($id) {
            $result = $this->serviceTypeModel->delete($id);

            if ($result === "FOREIGN_KEY_CONSTRAINT") {
                $_SESSION['error'] = "Không thể xoá loại dịch vụ vì đang được sử dụng!";
                header("Location: " . BASE_URL . "?act=service-type");
                exit();
            }

            if ($result) {
                Message::set('success', 'Xóa thành công!');
            } else {
                Message::set('error', 'Xóa thất bại!');
            }
        }

        header("Location: " . BASE_URL . "?act=service-type");
        exit();
    }

    // Xem chi tiết
    public function detail()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location:" . BASE_URL . "?act=service-type");
            exit;
        }

        $serviceType = $this->serviceTypeModel->getDetail($id);
        if (!$serviceType) {
            header("Location:" . BASE_URL . "?act=service-type");
            exit;
        }

        require_once './views/admin/service-type/detail.php';
    }
}
