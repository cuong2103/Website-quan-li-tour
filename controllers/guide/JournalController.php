<?php
class JournalController
{
    protected $model;

    public function __construct()
    {
        $this->model = new JournalModel();
    }

    // Danh sách
    public function index()
    {
        $journals = $this->model->getAll();

        require './views/guide/journals/index.php';
    }

    // Form tạo
    public function create()
    {
        $guideId = $_SESSION['currentUser']['id'];

        // Lấy tất cả tour assignment của guide
        $tourAssignments = $this->model->getAssignmentsByGuide($guideId);

        // Chỉ giữ những tour chưa hoàn thành
        $tourAssignments = array_filter($tourAssignments, fn($ta) => $ta['tour_status'] != '1');

        require './views/guide/journals/create.php';
    }

    // Lưu journal
    public function store()
    {
        $data = [
            'tour_assignment_id' => $_POST['tour_assignment_id'],
            'date' => $_POST['date'] ?? date('Y-m-d'),
            'content' => $_POST['content'],
            'type' => $_POST['type'] ?? 'note',
            'created_by' => $_SESSION['currentUser']['id'],
        ];

        $rules = ['tour_assignment_id' => 'required', 'content' => 'required|min:5'];
        $errors = validate($data, $rules);

        if ($errors) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $data;
            header('Location: ' . BASE_URL . '?act=journal-create&tour_assignment_id=' . $data['tour_assignment_id']);
            exit;
        }

        $journal_id = $this->model->create($data);

        if (!empty($_FILES['images']['name'][0])) {
            $uploadDir = __DIR__ . '/../../uploads/journals/';
            foreach ($_FILES['images']['name'] as $k => $filename) {
                $tmp = $_FILES['images']['tmp_name'][$k];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $newName = uniqid() . '.' . $ext;
                if (move_uploaded_file($tmp, $uploadDir . $newName)) {
                    $this->model->addImage($journal_id, $newName, $_SESSION['currentUser']['id']);
                }
            }
        }

        Message::set('success', 'Thêm nhật ký thành công!');
        header('Location: ' . BASE_URL . '?act=journal&tour_assignment_id=' . $data['tour_assignment_id']);
        exit;
    }

    // Form sửa
    public function edit()
    {
        $id = $_GET['id'];
        $journal = $this->model->getById($id);
        $images = $this->model->getImages($id);

        $guideId = $_SESSION['currentUser']['id'];
        $tourAssignments = $this->model->getAssignmentsByGuide($guideId);

        // Chỉ giữ những tour chưa hoàn thành hoặc tour của journal hiện tại
        $tourAssignments = array_filter(
            $tourAssignments,
            fn($ta) =>
            $ta['tour_status'] != '1' || $ta['id'] == $journal['tour_assignment_id']
        );

        require_once './views/guide/journals/edit.php';
    }

    // Cập nhật
    public function update()
    {
        $id = $_POST['id'];
        $data = [
            'date' => $_POST['date'] ?? date('Y-m-d'),
            'content' => $_POST['content'],
            'type' => $_POST['type'] ?? 'note',
            'updated_by' => $_SESSION['currentUser']['id'],
        ];

        $rules = ['content' => 'required|min:5'];
        $errors = validate($data, $rules);
        if ($errors) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $data;
            header('Location: ' . BASE_URL . '?act=journal-edit&id=' . $id);
            exit;
        }

        $this->model->update($id, $data);

        if (!empty($_FILES['images']['name'][0])) {
            $uploadDir = __DIR__ . '/../../uploads/journals/';
            foreach ($_FILES['images']['name'] as $k => $filename) {
                $tmp = $_FILES['images']['tmp_name'][$k];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $newName = uniqid() . '.' . $ext;
                if (move_uploaded_file($tmp, $uploadDir . $newName)) {
                    $this->model->addImage($id, $newName, $_SESSION['currentUser']['id']);
                }
            }
        }

        Message::set('success', 'Cập nhật nhật ký thành công!');
        header('Location: ' . BASE_URL . '?act=journal&tour_assignment_id=' . $_POST['tour_assignment_id']);
        exit;
    }

    // Xóa journal
    public function delete()
    {
        $id = $_GET['id'];
        $images = $this->model->getImages($id);
        $uploadDir = __DIR__ . '/../../uploads/journals/';

        foreach ($images as $img) {
            $filePath = $uploadDir . $img['image_url'];
            if (file_exists($filePath)) unlink($filePath);
        }

        $this->model->delete($id);
        Message::set('success', 'Xóa nhật ký thành công!');
        header('Location: ' . BASE_URL . '?act=journal&tour_assignment_id=' . $_GET['tour_assignment_id']);
        exit;
    }

    // Xóa ảnh riêng
    public function deleteImage()
    {
        $id = $_GET['id'];
        $image = $this->model->getImageById($id);
        if (!$image) die('Không tìm thấy ảnh');

        $filePath = __DIR__ . '/../../uploads/journals/' . $image['image_url'];
        if (file_exists($filePath)) unlink($filePath);

        $this->model->deleteImage($id);
        header('Location: ' . BASE_URL . '?act=journal-edit&id=' . $image['journal_id']);
        exit;
    }

    // Xem chi tiết
    // Hiển thị chi tiết
    public function detail()
    {
        $id = $_GET['id'];
        $journal = $this->model->getById($id);
        if (!$journal) {
            Message::set('error', 'Không tìm thấy nhật ký');
            header('Location: ' . BASE_URL . '?act=journal');
            exit;
        }

        $images = $this->model->getImages($id);
        $tour = $this->model->getTourByAssignment($journal['tour_assignment_id']);

        require_once './views/guide/journals/detail.php';
    }
}
