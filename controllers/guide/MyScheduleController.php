<?php
class MyScheduleController
{
    public $scheduleModel;

    public function __construct()
    {
        $this->scheduleModel = new MyScheduleModel();
    }

    //   Trang lịch của guide / admin
    public function index()
    {
        checkLogin();

        $user = $_SESSION['currentUser'];
        $guideId = $user['id'];
        $today = date('Y-m-d');

        // 2. Lấy tất cả tour được phân công cho guide
        $assignments = $this->scheduleModel->getGuideSchedule($guideId);

        $currentTours = [];
        $upcomingTours = [];

        // 3. Tính tổng ngày và ngày hiện tại của từng tour
        foreach ($assignments as $a) {
            // Tính tổng số ngày tour
            $a['total_days']   = $this->scheduleModel->calculateTotalDays($a['start_date'], $a['end_date']);
            // Tính ngày hiện tại
            $a['current_day']  = $this->scheduleModel->getCurrentDay($a['start_date'], $a['end_date']);

            // 4. Phân loại tour theo ngày
            if ($a['start_date'] <= $today && $a['end_date'] >= $today) {
                $currentTours[] = $a;
            } elseif ($a['start_date'] > $today) {
                $upcomingTours[] = $a;
            }
        }

        // Thống kê tổng số tour
        $totalAssignedTours = count($assignments);

        // Lấy danh sách khách hàng của tour hiện tại đầu tiên (nếu có)
        $customers = [];
        if (!empty($currentTours)) {
            $bookingId = $currentTours[0]['booking_id'];
            $customers = $this->scheduleModel->getCustomersByBooking($bookingId);
        }

        // Truyền dữ liệu sang view
        require_once './views/guide/my_schedule.php';
    }

    // Trang chi tiết
    public function detail()
    {
        checkLogin();

        $assignmentId = $_GET['id'] ?? 0;

        // Lấy thông tin phân công
        $assignment = $this->scheduleModel->getAssignmentById($assignmentId);

        if (!$assignment) {
            echo "<p>Không tìm thấy tour được phân công!</p>";
            exit();
        }

        // Lấy booking và danh sách khách hàng
        $booking = $this->scheduleModel->getBookingById($assignment['booking_id']);
        $customers = $this->scheduleModel->getCustomersByBooking($assignment['booking_id']);

        // Tính tiến độ tour
        $assignment['total_days']  = $this->scheduleModel->calculateTotalDays($assignment['start_date'], $assignment['end_date']);
        $assignment['current_day'] = $this->scheduleModel->getCurrentDay($assignment['start_date'], $assignment['end_date']);

        require_once './views/guide/tour_assignments/detail.php';
    }
}
