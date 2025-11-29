<?php
class DestinationController
{
    public $modelDestination;

    public function __construct()
    {
        $this->modelDestination = new DestinationModel();
    }

    // ===============================
    // DANH SÁCH
    // ===============================
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

    // ===============================
    // FORM THÊM
    // ===============================
    public function create()
    {
        $categories = $this->modelDestination->getCategories();
        require_once './views/admin/destination/create.php';
    }

    // ===============================
    // THÊM ĐỊA ĐIỂM
    // ===============================
    public function store()
    {
        $data = [
            'category_id' => $_POST['category_id'],
            'name' => $_POST['name'],
            'address' => $_POST['address'],
            'description' => $_POST['description'],
            'created_by' => 1
        ];

        if ($this->modelDestination->isDuplicateName($data['name'])) {
            Message::set("success", "Địa điểm này đã tồn tại!");
            header('Location: ' . BASE_URL . '?act=destination-create');
            exit();
        }

        $destination_id = $this->modelDestination->create($data);

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

    // ===============================
    // FORM SỬA
    // ===============================
    public function edit()
    {
        $id = $_GET['id'];
        $destination = $this->modelDestination->getIdEdit($id);
        $categories = $this->modelDestination->getCategories();
        $images = $this->modelDestination->getImagesByDestination($id);

        require_once './views/admin/destination/edit.php';
    }

    // ===============================
    // CẬP NHẬT
    // ===============================
    public function update()
    {
        $id = $_POST['id'];

        $data = [
            'category_id' => $_POST['category_id'],
            'name' => $_POST['name'],
            'address' => $_POST['address'],
            'description' => $_POST['description'],
            'updated_by' => 1,
            'updated_at' => date('Y-m-d H:i:s')
        ];


        $this->modelDestination->update($id, $data);

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

    // ===============================
    // XÓA ĐỊA ĐIỂM
    // ===============================
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
        $this->modelDestination->delete($id);

        header('Location: ' . BASE_URL . '?act=destination');
        exit();
    }

    // ===============================
    // XÓA ẢNH RIÊNG LẺ
    // ===============================
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

    // ===============================
    // CHI TIẾT
    // ===============================
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
