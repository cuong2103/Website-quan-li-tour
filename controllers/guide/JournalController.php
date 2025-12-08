<?php
class JournalController
{
    public $modelJournal;
    public $checkinModel;

    public function __construct()
    {
        $this->modelJournal = new JournalModel();
        $this->checkinModel = new CheckinModel();
    }
    // Form tạo
    public function create()
    {
        $guideId = $_SESSION['currentUser']['id'];
        $selected_tour_id = $_GET['tour_assignment_id'] ?? null;
        // Lấy tất cả tour assignment của guide
        $tourAssignments = $this->modelJournal->getAssignmentsByGuide($guideId);
        // Chỉ giữ những tour chưa hoàn thành
        $tourAssignments = array_filter($tourAssignments, fn($ta) => $ta['tour_status'] !== 'completed');
        $now = date('Y-m-d');


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
            redirect('journal-create&tour_assignment_id=' . $data['tour_assignment_id']);
            exit;
        }

        // Kiểm tra ngày tour - chỉ cho phép viết nhật ký khi tour đã bắt đầu

        $tourDateCheck = $this->checkinModel->canCheckin($data['tour_assignment_id']);

        if (!$tourDateCheck['allowed']) {
            // Nếu tour chưa bắt đầu, hiển thị thông báo
            if (strpos($tourDateCheck['message'], 'Chưa đến') !== false) {
                Message::set('error', 'Không thể viết nhật ký! Tour chưa bắt đầu.');
            } else {
                // Tour đã kết thúc - vẫn cho phép viết nhật ký cho tour đã qua
            }

            // Chỉ chặn nếu tour chưa bắt đầu
            if (strpos($tourDateCheck['message'], 'Chưa đến') !== false) {
                redirect('journal-create&tour_assignment_id=' . $data['tour_assignment_id']);
                exit;
            }
        }

        $journal_id = $this->modelJournal->create($data);

        if (!empty($_FILES['images']['name'][0])) {
            $uploadDir = __DIR__ . '/../../uploads/journals/';
            foreach ($_FILES['images']['name'] as $k => $filename) {
                $tmp = $_FILES['images']['tmp_name'][$k];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $newName = uniqid() . '.' . $ext;
                if (move_uploaded_file($tmp, $uploadDir . $newName)) {
                    $this->modelJournal->addImage($journal_id, $newName, $_SESSION['currentUser']['id']);
                }
            }
        }

        Message::set('success', 'Thêm nhật ký thành công!');
        redirect('guide-tour-assignments-detail&id=' . $data['tour_assignment_id'] . '&tab=journals');
        exit;
    }

    // Form sửa
    public function edit()
    {
        $id = $_GET['id'];
        $journal = $this->modelJournal->getById($id);
        $images = $this->modelJournal->getImages($id);

        $guideId = $_SESSION['currentUser']['id'];
        $tourAssignments = $this->modelJournal->getAssignmentsByGuide($guideId);

        // Chỉ giữ những tour chưa hoàn thành hoặc tour của journal hiện tại
        $tourAssignments = array_filter(
            $tourAssignments,
            fn($ta) =>
            $ta['tour_status'] !== 'completed' || $ta['id'] == $journal['tour_assignment_id']
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
            redirect('journal-edit&id=' . $id);
            exit;
        }

        $this->modelJournal->update($id, $data);

        if (!empty($_FILES['images']['name'][0])) {
            $uploadDir = __DIR__ . '/../../uploads/journals/';
            foreach ($_FILES['images']['name'] as $k => $filename) {
                $tmp = $_FILES['images']['tmp_name'][$k];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $newName = uniqid() . '.' . $ext;
                if (move_uploaded_file($tmp, $uploadDir . $newName)) {
                    $this->modelJournal->addImage($id, $newName, $_SESSION['currentUser']['id']);
                }
            }
        }

        Message::set('success', 'Cập nhật nhật ký thành công!');
        redirect('guide-tour-assignments-detail&id=' . $_POST['tour_assignment_id'] . '&tab=journals');
        exit;
    }

    // Xóa journal
    public function delete()
    {
        $id = $_GET['id'];
        $images = $this->modelJournal->getImages($id);
        $uploadDir = __DIR__ . '/../../uploads/journals/';

        foreach ($images as $img) {
            $filePath = $uploadDir . $img['image_url'];
            if (file_exists($filePath)) unlink($filePath);
        }

        $this->modelJournal->delete($id);
        Message::set('success', 'Xóa nhật ký thành công!');
        redirect("guide-tour-assignments-detail&id=" . $_GET['tour_assignment_id'] . "&tab=journals");
        exit;
    }

    // Xóa ảnh riêng
    public function deleteImage()
    {
        $id = $_GET['id'];
        $image = $this->modelJournal->getImageById($id);
        if (!$image) die('Không tìm thấy ảnh');

        $filePath = __DIR__ . '/../../uploads/journals/' . $image['image_url'];
        if (file_exists($filePath)) unlink($filePath);

        $this->modelJournal->deleteImage($id);
        redirect('journal-edit&id=' . $image['journal_id']);
        exit;
    }

    // Xem chi tiết
    // Hiển thị chi tiết
    public function detail()
    {
        $id = $_GET['id'];
        $journal = $this->modelJournal->getById($id);
        if (!$journal) {
            Message::set('error', 'Không tìm thấy nhật ký');
            redirect('guide-tour-assignments');
            exit;
        }

        $images = $this->modelJournal->getImages($id);
        $tour = $this->modelJournal->getTourByAssignment($journal['tour_assignment_id']);

        require_once './views/guide/journals/detail.php';
    }
}
