<?php
class GuideTourAssignmentController
{
    public $assignmentModel;
    public $bookingModel;
    public $customerModel;
    public $serviceModel;
    public $tourModel;


    public function __construct()
    {
        $this->assignmentModel = new TourAssignmentModel();
        $this->bookingModel = new BookingModel();
        $this->customerModel = new CustomerModel();
        $this->serviceModel = new ServiceModel();
        $this->tourModel = new TourModel();
    }

    // danh sách tour của guide
    public function index()
    {
        checkLogin();
        $guideId = $_SESSION['currentUser']['id'];

        // Tab trạng thái
        $status_tab = $_GET['status'] ?? 'upcoming';

        // Lấy tất cả assignment
        $allAssignments = $this->assignmentModel->getAssignmentsByGuide($guideId);

        $today = date('Y-m-d');
        $assignments = [];
        $upcomingCount = $ongoingCount = $completedCount = 0;

        foreach ($allAssignments as $a) {
            if ($a['end_date'] < $today) {
                $a['status'] = 'completed';
                $completedCount++;
            } elseif ($a['start_date'] <= $today && $a['end_date'] >= $today) {
                $a['status'] = 'ongoing';
                $ongoingCount++;
            } else {
                $a['status'] = 'upcoming';
                $upcomingCount++;
            }

            if ($a['status'] === $status_tab) {
                $assignments[] = $a;
            }
        }

        require_once './views/guide/tour-assignments/index.php';
    }

    // chi tiết tour
    public function detail()
    {
        $assignmentId = $_GET['id'] ?? null;
        $tab = $_GET['tab'] ?? 'customers';

        if (!$assignmentId) {
            header("Location: " . BASE_URL . "?act=guide-tour-assignments");
            exit;
        }

        $assignment = $this->bookingModel->getBookingDetails($assignmentId);
        if (!$assignment) {
            echo "Không tìm thấy phân công tour!";
            exit;
        }

        $bookingId = $assignment['booking_id'];

        // Khởi tạo biến
        $customers = $services = $journals = $itinerary_days = $policies = [];

        // Lấy dữ liệu theo tab
        switch ($tab) {
            case 'customers':
                $customers = $this->bookingModel->getCustomers($bookingId);
                break;

            case 'services':
                $services = $this->bookingModel->getServices($bookingId);
                break;

            case 'journals':
                $journals = $this->assignmentModel->getJournalsByAssignment($assignmentId);
                break;

            case 'itinerary':
                $itineraries = $this->tourModel->getItineraries($bookingId);
                if (!empty($itineraries)) {
                    foreach ($itineraries as $item) {
                        $day = $item['order_number'] ?? 1;
                        $itinerary_days[$day][] = [
                            'destination_name' => $item['destination_name'] ?? '',
                            'arrival_time' => $item['arrival_time'] ?? '',
                            'departure_time' => $item['departure_time'] ?? '',
                            'description' => $item['description'] ?? ''
                        ];
                    }
                }
                break;

            case 'info':
                break;
        }

        // Tính trạng thái tour
        $today = date('Y-m-d');

        if ($today < $assignment['start_date']) {
            $assignment['status_text'] = 'Sắp đi';
            $assignment['status_color'] = 'bg-yellow-200 text-yellow-800';
        } elseif ($today >= $assignment['start_date'] && $today <= $assignment['end_date']) {
            $assignment['status_text'] = 'Đang đi';
            $assignment['status_color'] = 'bg-green-200 text-green-800';
        } else { // $today > end_date
            $assignment['status_text'] = 'Đã hoàn thành';
            $assignment['status_color'] = 'bg-gray-200 text-gray-700';
        }

        require_once './views/guide/tour-assignments/detail.php';
    }
}
