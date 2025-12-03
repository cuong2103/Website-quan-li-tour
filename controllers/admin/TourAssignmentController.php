<?php
class TourAssignmentController
{
    public $tourAssignmentModel;
    public $bookingModel;
    public $guideModel;

    public function __construct()
    {
        requireAdmin();
        $this->tourAssignmentModel = new TourAssignmentModel();
        $this->bookingModel = new BookingModel();
        $this->guideModel = new UserModel();
    }

    // List all assignments
    public function index()
    {
        $assignments = $this->tourAssignmentModel->getAll();
        require_once './views/admin/Tour_Assignments/index.php';
    }

    // Show form to create assignment for a specific booking
    public function create()
    {
        $bookingId = $_GET['booking_id'] ?? null;
        if (!$bookingId) {
            Message::set('errors', 'Không tìm thấy Booking ID');
            header('Location: ' . BASE_URL . '?act=bookings');
            exit;
        }
        $booking = $this->bookingModel->getById($bookingId);
        if (!$booking) {
            Message::set('errors', 'Booking không tồn tại');
            header('Location: ' . BASE_URL . '?act=bookings');
            exit;
        }

        // --- KIỂM TRA THANH TOÁN ---
        // Chỉ cho phép phân công nếu Status = 3 (Đã thanh toán đủ) VÀ Remaining Amount <= 0
        if ($booking['status'] != 3 || $booking['remaining_amount'] > 0) {
            Message::set('errors', 'Booking chưa thanh toán đủ. Vui lòng thanh toán trước khi phân công Tour.');
            header('Location: ' . BASE_URL . '?act=bookings');
            exit;
        }

        // Lọc HDV trống lịch
        $guides = $this->tourAssignmentModel->getAvailableGuides($booking['start_date'], $booking['end_date']);

        // If an assignment already exists for this booking, load it for editing
        $assignment = $this->tourAssignmentModel->findByBookingId($bookingId);
        require_once './views/admin/Tour_Assignments/create.php';
    }

    // Store new assignment or update existing one
    public function store()
    {
        $booking_id = $_POST['booking_id'];
        $guide_id   = ($_POST['guide_id'] == "") ? null : $_POST['guide_id'];
        $status     = $_POST['status'] ?? 1;
        $created_by = $_SESSION['user_id'] ?? 1;

        // --- KIỂM TRA THANH TOÁN (Double Check) ---
        $booking = $this->bookingModel->getById($booking_id);
        if ($booking['status'] != 3 || $booking['remaining_amount'] > 0) {
            Message::set('errors', 'Booking chưa thanh toán đủ. Không thể phân công.');
            header('Location: ' . BASE_URL . '?act=bookings');
            exit;
        }

        $existing = $this->tourAssignmentModel->getByBookingId($booking_id);
        if ($existing) {
            // Update existing assignment
            $this->tourAssignmentModel->updateAssignment($existing['id'], [
                'guide_id' => $guide_id,
                'status'   => $status
            ]);
            Message::set('success', 'Cập nhật phân công thành công');
        } else {
            // Create new assignment
            $this->tourAssignmentModel->store($booking_id, $guide_id, $created_by);
            Message::set('success', 'Tạo phân công mới thành công');
        }
        header('Location: ' . BASE_URL . '?act=bookings');
        exit;
    }

    // Show edit form for an assignment (by assignment ID)
    public function edit()
    {
        $id = $_GET['id'] ?? null;
        $bookingId = $_GET['booking_id'] ?? null;

        $assignment = null;

        if ($id) {
            $assignment = $this->tourAssignmentModel->find($id);
        } elseif ($bookingId) {
            $assignment = $this->tourAssignmentModel->findByBookingId($bookingId);
        }

        if (!$assignment) {
            Message::set('errors', 'Phân công không tồn tại');
            header('Location: ' . BASE_URL . '?act=tour-assignments');
            exit;
        }

        $booking = $this->bookingModel->getById($assignment['booking_id']);

        // Lọc HDV trống lịch (trừ chính booking này ra)
        $guides = $this->tourAssignmentModel->getAvailableGuides($booking['start_date'], $booking['end_date'], $assignment['booking_id']);

        require_once './views/admin/Tour_Assignments/edit.php';
    }

    // Update assignment (only guide change)
    public function update()
    {
        $id       = $_POST['id'];
        $guide_id = ($_POST['guide_id'] == "") ? null : $_POST['guide_id'];
        $this->tourAssignmentModel->updateGuide($id, $guide_id);
        Message::set('success', 'Cập nhật phân công thành công');
        header('Location: ' . BASE_URL . '?act=tour-assignments');
        exit;
    }

    // Delete assignment
    public function delete()
    {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->tourAssignmentModel->delete($id);
            Message::set('success', 'Xóa phân công thành công');
        }
        header('Location: ' . BASE_URL . '?act=tour-assignments');
        exit;
    }
}
