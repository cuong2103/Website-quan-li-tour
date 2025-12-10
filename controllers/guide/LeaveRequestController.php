<?php

class LeaveRequestController
{
    public $userModel;
    public $tourAssignmentModel;
    public $notificationModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->tourAssignmentModel = new TourAssignmentModel();
        $this->notificationModel = new NotificationModel();
    }

    // Xem trạng thái đơn xin nghỉ
    public function index()
    {
        $userId = $_SESSION['currentUser']['id'];
        $leaveRequest = $this->userModel->getMyLeaveRequest($userId);

        require_once './views/guide/leave/index.php';
    }

    // Form xin nghỉ
    public function create()
    {
        $userId = $_SESSION['currentUser']['id'];
        $currentRequest = $this->userModel->getMyLeaveRequest($userId);

        // Kiểm tra đã có đơn pending hoặc approved chưa
        if ($currentRequest && in_array($currentRequest['leave_status'], ['pending', 'approved'])) {
            Message::set('error', 'Bạn đã có đơn xin nghỉ đang chờ duyệt hoặc đã được duyệt!');
            redirect('guide-leave');
            exit;
        }

        require_once './views/guide/leave/create.php';
    }

    // Lưu đơn xin nghỉ
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('guide-leave');
            exit;
        }

        $userId = $_SESSION['currentUser']['id'];

        $data = [
            'leave_start' => $_POST['leave_start'],
            'leave_end' => $_POST['leave_end'],
            'leave_reason' => trim($_POST['leave_reason'])
        ];

        // Validation
        $rules = [
            'leave_start' => 'required',
            'leave_end' => 'required',
            'leave_reason' => 'required|min:10'
        ];

        $errors = validate($data, $rules);

        // Validate dates
        if (strtotime($data['leave_start']) < strtotime(date('Y-m-d'))) {
            $errors['leave_start'] = 'Ngày bắt đầu phải từ hôm nay trở đi';
        }

        if (strtotime($data['leave_end']) <= strtotime($data['leave_start'])) {
            $errors['leave_end'] = 'Ngày kết thúc phải sau ngày bắt đầu';
        }

        // Kiểm tra xem có tour nào trong khoảng thời gian xin nghỉ không
        if (empty($errors)) {
            $assignments = $this->tourAssignmentModel->getAssignmentsByGuide($userId);

            $conflictTours = [];
            foreach ($assignments as $assignment) {
                // Kiểm tra trùng lặp: (leave_start <= tour_end) AND (leave_end >= tour_start)
                if ($data['leave_start'] <= $assignment['end_date'] && $data['leave_end'] >= $assignment['start_date']) {
                    $conflictTours[] = $assignment['tour_name'] . ' (' . date('d/m/Y', strtotime($assignment['start_date'])) . ' - ' . date('d/m/Y', strtotime($assignment['end_date'])) . ')';
                }
            }

            if (!empty($conflictTours)) {
                $errors['leave_start'] = 'Bạn đã được phân công tour trong khoảng thời gian này: ' . implode(', ', $conflictTours);
            }
        }

        if (!empty($errors)) {
            $_SESSION['validate_errors'] = $errors;
            $_SESSION['old'] = $_POST;
            redirect('guide-leave-create');
            exit;
        }

        // Tạo đơn xin nghỉ
        if ($this->userModel->createLeaveRequest($userId, $data)) {
            // Tạo notification cho tất cả admin
            $user = $this->userModel->getById($userId);

            // Tạo notification
            $notifId = $this->notificationModel->create([
                'title' => 'Đơn xin nghỉ phép mới',
                'message' => $user['fullname'] . ' xin nghỉ từ ' . date('d/m/Y', strtotime($data['leave_start'])) . ' đến ' . date('d/m/Y', strtotime($data['leave_end'])),
                'type' => 'general',
                'created_by' => $userId
            ]);

            // Lấy tất cả admin và gửi notification
            $allUsers = $this->notificationModel->getAllUsers();
            $adminIds = [];
            foreach ($allUsers as $u) {
                if ($u['roles'] === 'admin') {
                    $adminIds[] = $u['id'];
                }
            }
            if (!empty($adminIds)) {
                $this->notificationModel->addRecipients($notifId, $adminIds);
            }
            Message::set('success', 'Đã gửi đơn xin nghỉ phép! Vui lòng chờ admin duyệt.');
        } else {
            Message::set('error', 'Gửi đơn xin nghỉ thất bại!');
        }

        redirect('guide-leave');
        exit;
    }

    // Hủy đơn xin nghỉ
    public function cancel()
    {
        $userId = $_SESSION['currentUser']['id'];
        $currentRequest = $this->userModel->getMyLeaveRequest($userId);

        if (!$currentRequest || $currentRequest['leave_status'] !== 'pending') {
            Message::set('error', 'Không thể hủy đơn này!');
            redirect('guide-leave');
            exit;
        }

        if ($this->userModel->cancelLeaveRequest($userId)) {
            Message::set('success', 'Đã hủy đơn xin nghỉ!');
        } else {
            Message::set('error', 'Hủy đơn thất bại!');
        }

        redirect('guide-leave');
        exit;
    }
}
