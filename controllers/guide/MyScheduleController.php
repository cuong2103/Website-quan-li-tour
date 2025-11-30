<?php
class MyScheduleController
{
    public $bookingModel;

    public function __construct()
    {
        $this->bookingModel = new BookingModel(); // hoặc model khác nếu cần
    }

    public function index()
    {
        // Kiểm tra login
        checkLogin(); // guide đã login thì mới vào được

        $user = $_SESSION['currentUser'];

        // Nếu muốn, có thể check role nữa
        if ($user['roles'] === 'admin') {
            header("Location: " . BASE_URL); // admin không được vào đây
            exit();
        }

        // Lấy dữ liệu guide cần hiển thị
        $schedules = $this->bookingModel->getByGuideId($user['id']); 

        // Load view
        require_once './views/guide/my_schedule.php';
    }
}
