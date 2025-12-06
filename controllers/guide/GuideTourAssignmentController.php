<?php
class GuideTourAssignmentController
{
    public $assignmentModel;
    public $bookingModel;
    public $customerModel;
    public $serviceModel;
    public $tourModel;
    public $checkinModel;


    public function __construct()
    {
        $this->assignmentModel = new TourAssignmentModel();
        $this->bookingModel = new BookingModel();
        $this->customerModel = new CustomerModel();
        $this->serviceModel = new ServiceModel();
        $this->tourModel = new TourModel();
        $this->checkinModel = new CheckinModel();
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
                $itineraries = $this->tourModel->getItineraries($assignment['tour_id']);

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

            case 'checkin':
                // Lấy danh sách các đợt check-in
                $checkinLinks = $this->checkinModel->getCheckinLinks($assignmentId);

                // Lấy link_id từ URL hoặc dùng link mới nhất
                $currentLinkId = $_GET['link_id'] ?? null;
                if (!$currentLinkId && !empty($checkinLinks)) {
                    $currentLinkId = $checkinLinks[0]['id'];
                }

                // Lấy danh sách khách hàng với trạng thái check-in cho link hiện tại
                if ($currentLinkId) {
                    $customers = $this->checkinModel->getCustomersWithCheckinStatus($assignmentId, $currentLinkId);
                }
                break;

            case 'journals':
                $journals = $this->assignmentModel->getJournalsByAssignment($assignmentId);
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





    // Tạo đợt check-in mới
    public function createCheckinSession()
    {
        $assignmentId = $_POST['assignment_id'];
        $title = $_POST['title'];
        $note = $_POST['note'] ?? '';

        if ($this->checkinModel->createCheckinLink($assignmentId, $title, $note)) {
            Message::set('success', 'Tạo đợt điểm danh thành công!');
        } else {
            Message::set('error', 'Tạo đợt điểm danh thất bại!');
        }

        header("Location: " . BASE_URL . "?act=guide-tour-assignments-detail&id=" . $assignmentId . "&tab=checkin");
        exit;
    }

    // Xóa đợt check-in
    public function deleteCheckinSession()
    {
        $assignmentId = $_POST['assignment_id'];
        $linkId = $_POST['link_id'];

        if ($this->checkinModel->deleteCheckinLink($linkId)) {
            Message::set('success', 'Xóa đợt điểm danh thành công!');
        } else {
            Message::set('error', 'Xóa đợt điểm danh thất bại!');
        }

        header("Location: " . BASE_URL . "?act=guide-tour-assignments-detail&id=" . $assignmentId . "&tab=checkin");
        exit;
    }

    // Xử lý check-in/uncheck-in khách hàng
    public function checkinStore()
    {
        $assignmentId = $_POST['assignment_id'];
        $linkId = $_POST['link_id'];
        $customerId = $_POST['customer_id'];
        $action = $_POST['action'] ?? 'checkin';

        if ($action === 'uncheckin') {
            if ($this->checkinModel->uncheckinCustomer($linkId, $customerId)) {
                Message::set('success', 'Đã hủy điểm danh!');
            } else {
                Message::set('error', 'Hủy điểm danh thất bại!');
            }
        } else {
            if ($this->checkinModel->checkinCustomer($linkId, $customerId)) {
                Message::set('success', 'Điểm danh thành công!');
            } else {
                Message::set('error', 'Điểm danh thất bại!');
            }
        }

        header("Location: " . BASE_URL . "?act=guide-tour-assignments-detail&id=" . $assignmentId . "&tab=checkin&link_id=" . $linkId);
        exit;
    }
}
