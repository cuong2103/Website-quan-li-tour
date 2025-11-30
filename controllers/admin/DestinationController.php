<?php
class DestinationController
{
    public $modelDestination;

    public function __construct()
    {
        requireAdmin();
        $this->modelDestination = new DestinationModel();
    }

    // danh sách
    public function index()
    {
        // Lọc
        $name = $_GET['name'] ?? '';
        $category_id = $_GET['category_id'] ?? '';
        $created_from = $_GET['created_from'] ?? '';
        $created_to = $_GET['created_to'] ?? '';

        $listDestination = $this->modelDestination->filter(
            $name,
            $category_id,
            $created_from,
            $created_to
        );

        // Lấy danh mục
        $categories = $this->modelDestination->getCategories();

        require_once './views/admin/destination/index.php';
    }

    // form thêm
    public function create()
    {
        $categories = $this->modelDestination->getCategories();
        require_once './views/admin/destination/create.php';
    }

    // thêm địa điểm
    public function store()
    {
        $data = [
            'category_id' => $_POST['category_id'],
            'name' => $_POST['name'],
            'locations' => $_POST['locations'],
            'description' => $_POST['description'],
            'created_by' => $_SESSION['currentUser']['id'] ?? 1,
        ];

        $rules = [
            'name' => 'required|min:3|max:255',
            'category_id' => 'required',
        ];

        $errors = validate($data, $rules);


        if ($this->modelDestination->isDuplicateNameInCategory($data['name'], $data['category_id'])) {
            Message::set("error", "Địa điểm này đã tồn tại trong danh mục đã chọn!");
            $_SESSION['old'] = $data;
            header('Location: ' . BASE_URL . '?act=destination-create');
            exit();
        }

        if (!empty($errors)) {
            // Lưu lỗi và dữ liệu cũ vào session để hiển thị lại form
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $data;
            header('Location: ' . BASE_URL . '?act=destination-create');
            exit;
        }

        $destination_id = $this->modelDestination->create($data);
        if ($destination_id) {
            Message::set('success', 'Thêm địa điểm thành công!');
        } else {
            Message::set('error', 'Thêm địa điểm thất bại!');
        }

        // Upload ảnh
        if (!empty($_FILES['images']['name'][0])) {
            $uploadDir = __DIR__ . '/../../uploads/destinations_image/';

            foreach ($_FILES['images']['name'] as $key => $filename) {
                $tmpName = $_FILES['images']['tmp_name'][$key];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $newName = uniqid() . '.' . $ext;

                if (move_uploaded_file($tmpName, $uploadDir . $newName)) {
                    $this->modelDestination->addImage($destination_id, $newName, 1);
                }
            }
        }
        header('Location: ' . BASE_URL . '?act=destination');
    }

    // form sửa
    public function edit()
    {
        $id = $_GET['id'];
        $destination = $this->modelDestination->getIdEdit($id);
        $categories = $this->modelDestination->getCategories();
        $images = $this->modelDestination->getImagesByDestination($id);

        require_once './views/admin/destination/edit.php';
    }

    // cập nhật
    public function update()
    {
        $id = $_POST['id'];

        $data = [
            'category_id' => $_POST['category_id'],
            'name' => $_POST['name'],
            'locations' => $_POST['locations'],
            'description' => $_POST['description'],
            'updated_by' => $_SESSION['currentUser']['id'] ?? 1,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Validate
        $rules = [
            'name' => 'required|min:3|max:255',
            'category_id' => 'required',
        ];

        $errors = validate($data, $rules);

        if ($this->modelDestination->isDuplicateNameInCategory($data['name'], $data['category_id'], $id)) {
            Message::set("error", "Địa điểm này đã tồn tại trong danh mục đã chọn!");
            $_SESSION['old'] = $data;
            header('Location: ' . BASE_URL . '?act=destination-edit&id=' . $id);
            exit();
        }


        $success = $this->modelDestination->update($id, $data);

        if ($success) {
            Message::set('success', 'Cập nhật địa điểm thành công!');
        } else {
            Message::set('error', 'Cập nhật địa điểm thất bại!');
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $data;
            header('Location: ' . BASE_URL . '?act=destination-edit&id=' . $id);
            exit;
        }

        // Upload ảnh mới
        if (!empty($_FILES['images']['name'][0])) {
            $uploadDir = __DIR__ . '/../../uploads/destinations_image/';

            foreach ($_FILES['images']['name'] as $key => $filename) {
                $tmpName = $_FILES['images']['tmp_name'][$key];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $newName = uniqid() . '.' . $ext;

                if (move_uploaded_file($tmpName, $uploadDir . $newName)) {
                    $this->modelDestination->addImage($id, $newName, 1);
                }
            }
        }

        header('Location: ' . BASE_URL . '?act=destination');
    }

    // xóa địa điểm
    public function delete()
    {
        if (!isset($_GET['id'])) die('ID không tồn tại');
        $id = $_GET['id'];

        // Xóa ảnh trong DB + trên ổ đĩa
        $images = $this->modelDestination->getImagesByDestination($id);
        $uploadDir = __DIR__ . '/../../uploads/destinations_image/';

        foreach ($images as $img) {
            $filePath = $uploadDir . $img['image_url'];
            if (file_exists($filePath)) unlink($filePath);
        }

        // Xóa destination

        if ($this->modelDestination->delete($id)) {
            Message::set('success', 'Xóa địa điểm thành công!');
        } else {
            Message::set('error', 'Xóa địa điểm thất bại!');
        }
        header('Location: ' . BASE_URL . '?act=destination');
        exit();
    }
    // xóa ảnh riêng lẻ
    public function deleteImage()
    {
        if (!isset($_GET['id'])) die('ID ảnh không tồn tại');

        $id = $_GET['id'];
        $image = $this->modelDestination->getImageById($id);

        if ($image) {
            $filePath = __DIR__ . '/../../uploads/destinations_image/' . $image['image_url'];
            if (file_exists($filePath)) unlink($filePath);

            $this->modelDestination->deleteImage($id);
        }

        header('Location: ' . BASE_URL . '?act=destination-edit&id=' . $image['destination_id']);
        exit();
    }
    // chi tiết
    public function detail()
    {
        if (!isset($_GET['id'])) die('ID không tồn tại');

        $id = $_GET['id'];

        $result = $this->modelDestination->getDetail($id);
        $destination = $result['destination'] ?? [];
        $images = $result['images'] ?? [];
        $relatedTours = $result['relatedTours'] ?? [];

        require_once './views/admin/destination/detail.php';
    }
}
