<?php
class DestinationController
{
    public $modelDestination;

    public function __construct()
    {
        $this->modelDestination = new DestinationModel();
    }

    // Hiển thị danh sách
    public function index()
    {
        // Lấy dữ liệu filter từ URL
        $name = $_GET['name'] ?? '';
        $country_id = $_GET['country_id'] ?? '';
        $created_from = $_GET['created_from'] ?? '';
        $created_to = $_GET['created_to'] ?? '';

        // Gọi model để lọc
        $listDestination = $this->modelDestination->filter(
            $name,
            $country_id,
            $created_from,
            $created_to
        );

        // Danh sách quốc gia cho select filter
        $countries = $this->modelDestination->getCountries();

        require_once './views/admin/destination/index.php';
    }

    // Form thêm
    public function create()
    {
        $countries = $this->modelDestination->getCountries();
        require_once './views/admin/destination/create.php';
    }

    // Thêm địa điểm
    public function store()
    {
        $data = [
            'country_id' => $_POST['country_id'],
            'name' => $_POST['name'],
            'address' => $_POST['address'],
            'description' => $_POST['description'],
            'created_by' => 1
        ];

        $destination_id = $this->modelDestination->create($data);

        // Upload ảnh mới
        if (!empty($_FILES['images']['name'][0])) {
            $uploadDir = __DIR__ . '/../../uploads/destinations_image/';
            foreach ($_FILES['images']['name'] as $key => $name) {
                $tmpName = $_FILES['images']['tmp_name'][$key];
                $ext = pathinfo($name, PATHINFO_EXTENSION);
                $fileName = uniqid() . '.' . $ext;
                if (move_uploaded_file($tmpName, $uploadDir . $fileName)) {
                    $this->modelDestination->addImage($destination_id, $fileName, 1);
                }
            }
        }
        header('Location: ' . BASE_URL . '?act=destination');
    }

    // Form edit
    public function edit()
    {
        $id = $_GET['id'];
        $destination = $this->modelDestination->getIdEdit($id);
        $countries = $this->modelDestination->getCountries();
        $images = $this->modelDestination->getImagesByDestination($id);
        require_once './views/admin/destination/edit.php';
    }

    // Cập nhật
    public function update()
    {
        $id = $_POST['id'];
        $data = [
            'country_id' => $_POST['country_id'],
            'name' => $_POST['name'],
            'address' => $_POST['address'],
            'description' => $_POST['description']
        ];
        $this->modelDestination->update($id, $data);

        // Upload ảnh mới
        if (!empty($_FILES['images']['name'][0])) {
            $uploadDir = __DIR__ . '/../../uploads/destinations_image/';
            foreach ($_FILES['images']['name'] as $key => $name) {
                $tmpName = $_FILES['images']['tmp_name'][$key];
                $ext = pathinfo($name, PATHINFO_EXTENSION);
                $fileName = uniqid() . '.' . $ext;
                if (move_uploaded_file($tmpName, $uploadDir . $fileName)) {
                    $this->modelDestination->addImage($id, $fileName, 1);
                }
            }
        }

        header('Location: ' . BASE_URL . '?act=destination');
    }

    // Xóa địa điểm
    public function delete()
    {
        if (!isset($_GET['id'])) die('ID không tồn tại');
        $id = $_GET['id'];
        $this->modelDestination->delete($id);
        header('Location: ' . BASE_URL . '?act=destination');
        exit();
    }

    // Xóa ảnh riêng lẻ
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

    public function detail()
    {
        if (!isset($_GET['id'])) {
            die('ID không tồn tại');
        }

        $id = $_GET['id'];
        $data = $this->modelDestination->getDetail($id);

        $destination = $data['destination'];
        $images = $data['images'];
        $tours = $data['tours'];
        $suppliers = $data['suppliers'];

        require_once './views/admin/destination/detail.php';
    }
}
