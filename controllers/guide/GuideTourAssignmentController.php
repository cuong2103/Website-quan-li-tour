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

            case 'checkin':
                $customers = $this->checkinModel->getCustomersByAssignment($assignmentId);
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

    // Xuất danh sách check-in ra Excel
    public function exportCheckinList()
    {
        $assignmentId = $_GET['id'] ?? null;

        if (!$assignmentId) {
            Message::set('error', 'Không tìm thấy thông tin tour!');
            header("Location: " . BASE_URL . "?act=guide-tour-assignments");
            exit;
        }

        $assignment = $this->bookingModel->getBookingDetails($assignmentId);
        $customers = $this->checkinModel->getCustomersByAssignment($assignmentId);

        if (empty($customers)) {
            Message::set('error', 'Chưa có khách hàng nào trong tour này!');
            header("Location: " . BASE_URL . "?act=guide-tour-assignments-detail&id=" . $assignmentId . "&tab=checkin");
            exit;
        }

        require_once './lib/SimpleXLSXGen.php';

        $data = [
            ['DANH SÁCH KHÁCH HÀNG - ' . mb_strtoupper($assignment['tour_name'])],
            ['Mã Booking: ' . $assignment['booking_code']],
            ['Ngày khởi hành: ' . date('d/m/Y', strtotime($assignment['start_date']))],
            [], // Empty row
            ['STT', 'Tên khách hàng', 'Số điện thoại', 'Email', 'Trạng thái', 'Thời gian check-in', 'Vị trí', 'Ghi chú']
        ];

        foreach ($customers as $i => $c) {
            $status = ($c['checkin_count'] > 0) ? 'Đã check-in' : 'Chưa check-in';
            $checkinTime = ($c['checkin_count'] > 0) ? date('H:i d/m/Y', strtotime($c['latest_checkin_time'] ?? 'now')) : '';

            $data[] = [
                $i + 1,
                $c['name'],
                $c['phone'],
                $c['email'],
                $status,
                '', // Time placeholder
                '', // Location placeholder
                ''  // Note placeholder
            ];
        }


        $checkinHistory = $this->checkinModel->getCheckinHistory($assignmentId);
        $checkinMap = [];
        foreach ($checkinHistory as $h) {
            $checkinMap[$h['customer_id']] = $h;
        }

        // Re-build data with map
        $data = [
            ['DANH SÁCH KHÁCH HÀNG - ' . mb_strtoupper($assignment['tour_name'])],
            ['Mã Booking: ' . $assignment['booking_code']],
            ['Ngày khởi hành: ' . date('d/m/Y', strtotime($assignment['start_date']))],
            [],
            ['STT', 'Tên khách hàng', 'Số điện thoại', 'Email', 'Trạng thái', 'Thời gian check-in', 'Phòng']
        ];

        foreach ($customers as $i => $c) {
            $info = $checkinMap[$c['id']] ?? null;
            $status = $info ? 'Đã check-in' : 'Chưa check-in';
            $time = $info ? date('H:i d/m/Y', strtotime($info['checkin_time'])) : '';
            $room = $c['room_number'] ?? '';

            $data[] = [
                $i + 1,
                $c['name'],
                $c['phone'],
                $c['email'],
                $status,
                $time,
                $room
            ];
        }

        $xlsx = Shuchkin\SimpleXLSXGen::fromArray($data);
        $xlsx->downloadAs('Danh_sach_checkin_' . date('Ymd_His') . '.xlsx');
        exit;
    }


    // Xử lý check-in từ trang detail
    public function checkinStore()
    {
        $assignmentId = $_POST['assignment_id'];
        $customerId = $_POST['customer_id'];

        // Kiểm tra thời gian tour
        $canCheckin = $this->checkinModel->canCheckin($assignmentId);
        if (!$canCheckin['allowed']) {
            Message::set('error', $canCheckin['message']);
            header("Location: " . BASE_URL . "?act=guide-tour-assignments-detail&id=" . $assignmentId . "&tab=checkin");
            exit;
        }

        // Kiểm tra đã check-in chưa
        if ($this->checkinModel->hasCheckedIn($assignmentId, $customerId)) {
            Message::set('error', 'Khách hàng này đã được điểm danh!');
        } else {
            $data = [
                'tour_assignment_id' => $assignmentId,
                'customer_id' => $customerId,
                'checkin_time' => date('Y-m-d H:i:s'),
                'created_by' => $_SESSION['currentUser']['id']
            ];

            if ($this->checkinModel->createCheckin($data)) {
                Message::set('success', 'Điểm danh thành công!');
            } else {
                Message::set('error', 'Điểm danh thất bại!');
            }
        }

        header("Location: " . BASE_URL . "?act=guide-tour-assignments-detail&id=" . $assignmentId . "&tab=checkin");
        exit;
    }

    // Xử lý hủy check-in
    public function checkinDestroy()
    {
        $assignmentId = $_POST['assignment_id'];
        $checkinId = $_POST['checkin_id'];

        if ($this->checkinModel->deleteCheckin($checkinId)) {
            Message::set('success', 'Đã hủy điểm danh!');
        } else {
            Message::set('error', 'Hủy điểm danh thất bại!');
        }

        header("Location: " . BASE_URL . "?act=guide-tour-assignments-detail&id=" . $assignmentId . "&tab=checkin");
        exit;
    }
}
