<?php
class TourAssignmentController
{
    public $model;
    public $bookingModel;
    public $guideModel;

    public function __construct()
    {
        requireAdmin();
        $this->model = new TourAssignmentModel();
        $this->bookingModel = new BookingModel();
        $this->guideModel = new UserModel();
    }

    // =============================
    //  Danh sách phân công
    // =============================
    public function index()
    {
        $assignments = $this->model->getAll();

        require_once './views/admin/tour_assignments/index.php';
    }

    // =============================
    //  Form tạo phân công
    // =============================
    public function create()
    {
        // Booking chưa được phân công
        $bookings = $this->bookingModel->getBookingsWithoutGuide();

        // Hướng dẫn viên
        $guides = $this->model->getAllGuides();

        require_once './views/admin/tour_assignments/create.php';
    }

    // =============================
    //  Lưu phân công mới
    // =============================
    public function store()
    {
        $booking_id = $_POST['booking_id'];
        $guide_id   = ($_POST['guide_id'] == "") ? null : $_POST['guide_id'];
        $created_by = $_SESSION['user_id'] ?? 1;

        $this->model->store($booking_id, $guide_id, $created_by);

        header("Location: " . BASE_URL . "?act=tour-assignments");
        exit();
    }

    // =============================
    //  Form sửa phân công
    // =============================
    public function edit()
    {
        $id = $_GET['id'];

        // Lấy phân công hiện tại
        $assignment = $this->model->find($id);
        if (!$assignment) {
            die("Không tìm thấy phân công tour");
        }

        // Lấy booking tương ứng (đã có tour_name do join)
        $booking = $this->bookingModel->getById($assignment['booking_id']);
        if (!$booking) {
            die("Không tìm thấy booking");
        }

        // Gán tour_name vào assignment để view dùng
        $assignment['tour_name'] = $booking['tour_name'] ?? 'Không có tên tour';

        // Danh sách HDV
        $guides = $this->model->getAllGuides();


        require_once './views/admin/tour_assignments/edit.php';
    }

    // =============================
    //  Cập nhật phân công
    // =============================
    public function update()
    {
        $id        = $_POST['id'];
        $guide_id  = ($_POST['guide_id'] == "") ? null : $_POST['guide_id'];

        $this->model->update($id, $guide_id);

        header("Location: " . BASE_URL . "?act=tour-assignments");
        exit();
    }

    // =============================
    //  Xóa phân công
    // =============================
    public function delete()
    {
        $id = $_GET['id'];
        $this->model->delete($id);

        header("Location: " . BASE_URL . "?act=tour-assignments");
        exit();
    }
}
