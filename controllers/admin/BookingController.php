<?php
class BookingController
{
    public $bookingModel;
    public $tourModel;
    public $customerModel;
    public $serviceModel;
    public $contractModel;
    public $paymentModel;
    public $checkinModel;
    public $journalModel;


    public function __construct()
    {
        requireAdmin();
        $this->bookingModel = new BookingModel();
        $this->tourModel = new TourModel();
        $this->customerModel = new CustomerModel();
        $this->serviceModel = new ServiceModel();
        $this->contractModel = new ContractModel();
        $this->paymentModel = new PaymentModel();
        $this->checkinModel = new CheckinModel();
        $this->journalModel = new JournalModel();
    }

    // Hiển thị danh sách booking
    public function index()
    {
        $filters = [
            'keyword' => $_GET['keyword'] ?? '',
            'status' => $_GET['status'] ?? '',
            'date_from' => $_GET['date_from'] ?? '',
            'date_to' => $_GET['date_to'] ?? ''
        ];
        // Lấy danh sách booking từ model với bộ lọc
        $bookings = $this->bookingModel->getAll($filters);
        foreach ($bookings as $booking) {
            if (
                $booking['end_date'] < date('Y-m-d') &&
                in_array($booking['status'], ['paid', 'in_progress', 'deposited'])
            ) {
                $this->bookingModel->updateStatus($booking['id'], 'completed');
                $booking['status'] = 'completed';
            }
        }
        $bookings = $this->bookingModel->getAll($filters);
        require_once './views/admin/bookings/index.php';
    }

    // hiển thị form tạo booking
    public function create()
    {
        $keyword = $_GET['keyword'] ?? '';


        // Lọc theo keyword nếu có
        if ($keyword) {
            $services = $this->serviceModel->search($keyword);
        } else {
            $services = $this->serviceModel->getAll();
        }

        // Các dữ liệu khác
        $tours = $this->tourModel->getAll();
        $customers = $this->customerModel->getAll();

        // Xử lý khi chọn tour (PHP Logic)
        $selectedTour = null;
        $selectedTourServices = [];
        if (isset($_GET['tour_id']) && $_GET['tour_id']) {
            $selectedTour = $this->tourModel->getById($_GET['tour_id']);
            $selectedTourServices = $this->tourModel->getServicesByTourId($_GET['tour_id']);
        }

        require_once './views/admin/bookings/create.php';
    }


    // Xửa lý tạo booking mới
    public function store()
    {
        // ===== VALIDATE =====
        $rules = [
            'tour_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'total_amount' => 'required|numeric',
            'rep_name' => 'required',
            'rep_phone' => 'required',
            'rep_email' => 'required|email',
        ];

        $errors = validate($_POST, $rules);
        if (!empty($errors)) {
            // Lưu lỗi và dữ liệu cũ vào session
            $_SESSION['old'] = $_POST;
            $_SESSION['validate_errors'] = $errors;

            // Redirect về trang create với tour_id nếu có
            $redirectUrl = BASE_URL . '?act=booking-create';
            if (!empty($_POST['tour_id'])) {
                $redirectUrl .= '&tour_id=' . $_POST['tour_id'];
            }
            header("Location:" . $redirectUrl);
            exit;
        }

        // ===== tạo booking =====
        $bookingCode = 'BK-' . time();

        $customer = $this->customerModel->findByEmailOrPhone($_POST['rep_email'], $_POST['rep_phone']);
        if ($customer) {
            $customerId = $customer['id'];
        } else {
            $this->customerModel->create(
                $_POST['rep_name'],
                $_POST['rep_email'],
                $_POST['rep_phone'],
                $_POST['rep_address'] ?? '',
                $_SESSION['currentUser']['id'],
                $_POST['rep_passport'] ?? '',
                $_POST['rep_gender'] ?? 'other',
                $_POST['rep_citizen_id'] ?? ''
            );
            $customer = $this->customerModel->findByEmailOrPhone($_POST['rep_email'], $_POST['rep_phone']);
            $customerId = $customer['id'];
        }

        // ===== tính toán service_amount =====
        $serviceAmount = 0;
        $totalPeople = ($_POST['adult_count'] ?? 0) + ($_POST['child_count'] ?? 0);

        if (!empty($_POST['services'])) {
            foreach ($_POST['services'] as $serviceId) {
                // Lấy thông tin dịch vụ để biết đơn vị tính
                $service = $this->serviceModel->getDetail($serviceId);
                $unit = $service['unit'] ?? 'person';

                $currentPrice = $_POST['service_prices'][$serviceId] ?? 0;
                $quantity = $_POST['service_quantities'][$serviceId] ?? 1;

                // Tính theo đơn vị
                if ($unit === 'person') {
                    // Dịch vụ tính theo người: nhân với tổng số người
                    $serviceAmount += ($currentPrice * $quantity * $totalPeople);
                } else {
                    // Các đơn vị khác: không nhân với số người
                    $serviceAmount += ($currentPrice * $quantity);
                }
            }
        }


        $data = [
            'tour_id' => $_POST['tour_id'],
            'booking_code' => $bookingCode,
            'start_date' => $_POST['start_date'],
            'end_date' => $_POST['end_date'],
            'adult_count' => $_POST['adult_count'],
            'child_count' => $_POST['child_count'] ?? 0,
            'service_amount' => $serviceAmount,
            'total_amount' => $_POST['total_amount'],
            'special_requests' => $_POST['special_requests'] ?? null,
            'customers' => [$customerId],
            'is_representative' => $customerId,
            'services' => $_POST['services'] ?? [],
            'created_by' => $_SESSION['currentUser']['id']
        ];

        $bookingId = $this->bookingModel->create($data);

        // xử lý lưu dịch vụ
        if (!empty($_POST['services'])) {

            foreach ($_POST['services'] as $serviceId) {

                // Lấy giá và số lượng từ form
                $currentPrice = $_POST['service_prices'][$serviceId] ?? 0;
                $quantity   = $_POST['service_quantities'][$serviceId] ?? 1;

                // Lưu vào DB qua model
                $this->bookingModel->addService(
                    $bookingId,
                    $serviceId,
                    $quantity,
                    $currentPrice
                );
            }
        }

        // Cập nhật trạng thái thanh toán và tính toán deposit_amount, remaining_amount
        $this->autoUpdatePaymentStatus($bookingId);

        // Thông báo nếu thành công
        Message::set('success', 'Tạo booking thành công.');
        header("Location:" . BASE_URL . '?act=bookings');
    }

    // Hiển thị form chỉnh sửa booking
    public function edit()
    {
        // lấy id booking
        $id = $_GET['id'];


        $booking = $this->bookingModel->getById($id);
        $tours = $this->tourModel->getAll();
        $customers = $this->customerModel->getAll();
        $services = $this->serviceModel->getAll();

        // Lấy danh sách dịch vụ đã chọn của booking
        $selectedServices = $this->bookingModel->getServicesByBooking($id);

        require_once './views/admin/bookings/edit.php';
    }

    // Cập nhật booking
    public function update()
    {
        // Lấy id booking
        $id = $_POST['id'];

        // Lấy thông tin booking hiện tại
        $booking = $this->bookingModel->getById($id);

        // Ngăn update booking đã completed
        if ($booking['status'] === 'completed') {
            Message::set('error', 'Không thể sửa booking đã hoàn thành');
            redirect('bookings');
            exit;
        }

        // validate dữ liệu
        $rules = [
            'tour_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'total_amount' => 'required|numeric',
            'deposit_amount' => 'numeric'
        ];

        $errors = validate($_POST, $rules);

        if (!empty($errors)) {
            // Lưu lỗi và dữ liệu cũ vào session
            $_SESSION['old'] = $_POST;
            $_SESSION['validate_errors'] = $errors;
            header("Location:" . BASE_URL . '?act=booking-edit&id=' . $_POST['id']);
            exit;
        }

        // lấy id booking
        $id = $_POST['id'];

        // tính toán service_amount
        $serviceAmount = 0;
        $totalPeople = ($_POST['adult_count'] ?? 0) + ($_POST['child_count'] ?? 0);

        if (!empty($_POST['services'])) {
            foreach ($_POST['services'] as $serviceId) {
                // Lấy thông tin dịch vụ để biết đơn vị tính
                $service = $this->serviceModel->getDetail($serviceId);
                $unit = $service['unit'] ?? 'person';

                $currentPrice = $_POST['service_prices'][$serviceId] ?? 0;
                $quantity = $_POST['service_quantities'][$serviceId] ?? 1;

                // Tính theo đơn vị
                if ($unit === 'person') {
                    // Dịch vụ tính theo người: nhân với tổng số người
                    $serviceAmount += ($currentPrice * $quantity * $totalPeople);
                } else {
                    // Các đơn vị khác: không nhân với số người
                    $serviceAmount += ($currentPrice * $quantity);
                }
            }
        }

        $data = [
            'tour_id' => $_POST['tour_id'],
            'start_date' => $_POST['start_date'],
            'end_date' => $_POST['end_date'],
            'adult_count' => $_POST['adult_count'],
            'child_count' => $_POST['child_count'] ?? 0,
            'service_amount' => $serviceAmount,
            'total_amount' => $_POST['total_amount'],
            'status' => $_POST['status'],
            'special_requests' => $_POST['special_requests'] ?? null,
            'customers' => $_POST['customers'] ?? [],
            'is_representative' => $_POST['is_representative'] ?? null,
            'services' => $_POST['services'] ?? [],
            'updated_by' => $_SESSION['currentUser']['id']
        ];
        // Cập nhật booking chính vào db
        $this->bookingModel->update($id, $data);

        // Xóa khách cũ
        $this->bookingModel->deleteCustomers($id);

        // Thêm khách mới + đánh dấu đại diện
        foreach ($data['customers'] as $customerId) {
            $isRep = ($data['is_representative'] == $customerId) ? 1 : 0;
            $this->bookingModel->addCustomer($id, $customerId, $isRep);
        }

        // Xóa dịch vụ cũ
        $this->bookingModel->deleteServices($id);

        // Thêm lại dịch vụ mới
        if (!empty($_POST['services'])) {
            foreach ($_POST['services'] as $serviceId) {
                // Lấy giá và số lượng từ form
                $currentPrice = $_POST['service_prices'][$serviceId] ?? 0;
                $quantity   = $_POST['service_quantities'][$serviceId] ?? 1;

                // Lưu vào DB qua model
                $this->bookingModel->addService(
                    $id,
                    $serviceId,
                    $quantity,
                    $currentPrice
                );
            }
        }

        // Cập nhật lại trạng thái thanh toán (đề phòng tổng tiền thay đổi)
        $this->autoUpdatePaymentStatus($id);

        // Thông báo nếu thành công
        Message::set('success', 'Cập nhật booking thành công!');
        header("Location:" . BASE_URL . '?act=bookings');
    }

    // Xóa booking
    public function delete()
    {
        $id = $_GET['id'];

        // Lấy thông tin booking
        $booking = $this->bookingModel->getById($id);

        if (!$booking) {
            Message::set('error', 'Booking không tồn tại.');
            header("Location:" . BASE_URL . '?act=bookings');
            exit;
        }

        // Kiểm tra trạng thái booking
        if ($booking['status'] === 'paid') {
            Message::set('error', 'Không thể xóa booking đã thanh toán đủ.');
            header("Location:" . BASE_URL . '?act=bookings');
            exit;
        }

        if ($booking['status'] === 'deposited') {
            Message::set('error', 'Không thể xóa booking đã cọc. Vui lòng xóa các thanh toán trước.');
            header("Location:" . BASE_URL . '?act=bookings');
            exit;
        }

        $this->bookingModel->delete($id);

        // Thông báo nếu thành công
        Message::set('success', 'Xóa booking thành công!');
        header("Location:" . BASE_URL . '?act=bookings');
    }

    // Hiển thị chi tiết booking
    public function detail()
    {
        $id = $_GET['id'];

        // Tự động cập nhật trạng thái hợp đồng
        $this->contractModel->autoUpdateStatus();

        $tab = $_GET['tab'] ?? 'customers';
        $booking = $this->bookingModel->getById($id);
        $bookingPayments = $this->paymentModel->getAllByBooking($booking['id']);
        $totalPaid = $this->bookingModel->getTotalPaid($booking['id']);
        $remaining = $booking['total_amount'] - $totalPaid;
        switch ($tab) {
            case 'customers':
                $customers = $this->bookingModel->getCustomers($id);
                break;
            case 'services':
                $bookingServices = $this->bookingModel->getServicesByBooking($id);
                break;
            case 'contracts':
                $bookingContracts = $this->contractModel->getByBookingId($id);
                break;
            case 'payments':
                $bookingPayments = $this->paymentModel->getAllByBooking($booking['id']);
                break;
            case 'itinerary':
                // Lấy itinerary từ tour_id của booking
                $itineraries = $this->tourModel->getItineraries($booking['tour_id']);
                // Nhóm theo order_number (ngày)
                $itinerary_days = [];
                foreach ($itineraries as $item) {
                    $day = $item['order_number'];
                    if (!isset($itinerary_days[$day])) {
                        $itinerary_days[$day] = [];
                    }
                    $itinerary_days[$day][] = $item;
                }
                break;
            case 'checkins':
                $checkinLinks = $this->checkinModel->getCheckinLinksByBookingId($id);
                break;
            case 'journals':
                $journals = $this->journalModel->getJournalsByBookingId($id);
                break;
            case 'assignments':
                $tourAssignment = $this->journalModel->getAssignmentByBookingId($id);
                break;
            default:
                $customers = $this->bookingModel->getCustomers($id);
                break;
        }

        require_once './views/admin/bookings/detail.php';
    }
    // hàm auto cập nhật trạng thái
    public function autoUpdatePaymentStatus($bookingId)
    {
        $totalPaid = $this->bookingModel->getTotalPaid($bookingId);
        $booking = $this->bookingModel->getById($bookingId);

        if (!$booking) return;

        $totalAmount = $booking['total_amount'];
        $remainingAmount = $totalAmount - $totalPaid;

        // Cập nhật deposit_amount và remaining_amount
        $this->bookingModel->updateFinancials($bookingId, $totalPaid, $remainingAmount);

        // Cập nhật status dựa trên số tiền đã thanh toán
        if ($totalPaid >= $totalAmount) {
            $this->bookingModel->updateStatus($bookingId, 'paid'); // Đã thanh toán đủ
        } elseif ($totalPaid > 0) {
            $this->bookingModel->updateStatus($bookingId, 'deposited'); // Đã cọc
        } else {
            $this->bookingModel->updateStatus($bookingId, 'pending'); // Chưa thanh toán
        }
    }

    // Upload danh sách khách hàng từ Excel
    public function uploadCustomers()
    {
        $bookingId = $_POST['booking_id'];

        if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
            $file = $_FILES['file']['tmp_name'];

            // Kiểm tra định dạng file
            $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
            if (!in_array(strtolower($ext), ['xlsx', 'xls'])) {
                Message::set('error', 'Vui lòng chọn file Excel (.xlsx, .xls).');
                header("Location:" . BASE_URL . '?act=booking-detail&id=' . $bookingId . '&tab=customers');
                exit;
            }

            require_once './lib/SimpleXLSX.php';

            if ($xlsx = \Shuchkin\SimpleXLSX::parse($file)) {
                $count = 0;
                $rows = $xlsx->rows();

                // Dòng 1 là header, dữ liệu từ dòng 2
                foreach ($rows as $index => $row) {
                    if ($index == 0) continue; // Skip header

                    // Excel Format: STT, Họ tên, Giới tính, Hộ chiếu, CMND/CCCD, Địa chỉ, SĐT, Email, Ghi chú xếp phòng
                    $name = trim($row[1] ?? '');
                    $phone = trim($row[2] ?? '');
                    $email = trim($row[3] ?? '');
                    $genderText = trim($row[4] ?? 'Khác');
                    $address = trim($row[5] ?? '');
                    $citizenId = trim($row[6] ?? '');
                    $passport = trim($row[7] ?? '');
                    $notes = trim($row[8] ?? '');

                    // Chuyển đổi giới tính
                    $gender = 'other';
                    if (mb_strtolower($genderText) == 'nam' || mb_strtolower($genderText) == 'male') {
                        $gender = 'male';
                    } elseif (mb_strtolower($genderText) == 'nữ' || mb_strtolower($genderText) == 'female' || mb_strtolower($genderText) == 'nu') {
                        $gender = 'female';
                    }

                    if (empty($name)) continue;

                    // Tìm kiếm khách hàng theo email hoặc số điện thoại
                    $customer = $this->customerModel->findByEmailOrPhone($email, $phone);

                    if ($customer) {
                        $customerId = $customer['id'];
                    } else {
                        // Tạo khách hàng mới
                        $this->customerModel->create($name, $email, $phone, $address, $_SESSION['currentUser']['id'], $passport, $gender, $citizenId);
                        $customer = $this->customerModel->findByEmailOrPhone($email, $phone);
                        $customerId = $customer['id'];
                    }

                    // Kiểm tra xem khách hàng đã có trong booking chưa
                    $existingCustomers = $this->bookingModel->getCustomers($bookingId);
                    $isAlreadyIn = false;
                    foreach ($existingCustomers as $ec) {
                        if ($ec['id'] == $customerId) {
                            $isAlreadyIn = true;
                            break;
                        }
                    }

                    if (!$isAlreadyIn) {
                        $this->bookingModel->addCustomer($bookingId, $customerId, 0, $notes);
                        $count++;
                    }
                }

                Message::set('success', "Đã thêm $count khách hàng từ file Excel.");
            } else {
                Message::set('error', 'Không thể đọc file Excel: ' . \Shuchkin\SimpleXLSX::parseError());
            }
        } else {
            Message::set('error', 'Vui lòng chọn file Excel.');
        }

        header("Location:" . BASE_URL . '?act=booking-detail&id=' . $bookingId . '&tab=customers');
    }

    // Export danh sách khách hàng ra Excel
    public function exportCustomers()
    {
        $bookingId = $_GET['booking_id'] ?? null;
        if (!$bookingId) {
            Message::set('error', 'Không tìm thấy booking ID');
            header('Location: ' . BASE_URL . '?act=bookings');
            exit;
        }

        $booking = $this->bookingModel->getById($bookingId);
        if (!$booking) {
            Message::set('error', 'Booking không tồn tại');
            header('Location: ' . BASE_URL . '?act=bookings');
            exit;
        }

        $customers = $this->bookingModel->getCustomers($bookingId);

        require_once './lib/SimpleXLSXGen.php';

        $data = [
            ['STT', 'Họ tên', 'SĐT', 'Email', 'CMND/CCCD', 'Địa chỉ', 'Hộ chiếu', 'Giới tính', 'Ghi chú xếp phòng']
        ];

        $i = 1;
        foreach ($customers as $c) {
            $gender = 'Khác';
            if ($c['gender'] == 'male') $gender = 'Nam';
            elseif ($c['gender'] == 'female') $gender = 'Nữ';

            $data[] = [
                $i++,
                $c['name'],
                $c['phone'] ?? '',
                $c['email'] ?? '',
                $c['citizen_id'] ?? '',
                $c['address'] ?? '',
                $c['passport'] ?? '',
                $gender,
            ];
        }

        $filename = 'Danh_sach_khach_hang_Booking_' . $booking['booking_code'] . '.xlsx';
        \Shuchkin\SimpleXLSXGen::fromArray($data)->downloadAs($filename);
        exit;
    }

    public function exportBookingCustomersTemplate()
    {
        require_once './lib/SimpleXLSXGen.php';

        $data = [
            ['STT', 'Họ tên', 'SĐT', 'Email', 'CMND/CCCD', 'Địa chỉ', 'Hộ chiếu', 'Giới tính', 'Ghi chú xếp phòng']
        ];

        $filename = 'Tamplate_khach_hang.xlsx';
        \Shuchkin\SimpleXLSXGen::fromArray($data)->downloadAs($filename);
        exit;
    }

    // Xóa khách hàng khỏi booking
    public function removeCustomer()
    {
        $bookingId = $_GET['booking_id'];
        $customerId = $_GET['customer_id'];

        // Lấy thông tin booking
        $booking = $this->bookingModel->getById($bookingId);

        if (!$booking) {
            Message::set('error', 'Booking không tồn tại.');
            header("Location:" . BASE_URL . '?act=bookings');
            exit;
        }

        // Ngăn xóa customer khi booking đã completed
        if ($booking['status'] === 'completed') {
            Message::set('error', 'Không thể xóa khách hàng của booking đã hoàn thành');
            header("Location:" . BASE_URL . "?act=booking-detail&id=$bookingId&tab=customers");
            exit;
        }

        // Kiểm tra xem khách hàng có phải người đại diện không
        $customers = $this->bookingModel->getCustomers($bookingId);
        $isRepresentative = false;

        foreach ($customers as $c) {
            if ($c['id'] == $customerId && $c['is_representative'] == 1) {
                $isRepresentative = true;
                break;
            }
        }

        if ($isRepresentative) {
            // Đếm số khách hàng còn lại
            $remainingCustomers = count($customers) - 1;

            if ($remainingCustomers > 0) {
                Message::set('error', 'Không thể xóa người đại diện. Vui lòng chỉ định người đại diện khác trước khi xóa.');
            } else {
                Message::set('error', 'Không thể xóa người đại diện duy nhất của booking.');
            }

            header("Location:" . BASE_URL . '?act=booking-detail&id=' . $bookingId . '&tab=customers');
            exit;
        }

        $this->bookingModel->removeCustomer($bookingId, $customerId);

        Message::set('success', 'Đã xóa khách hàng khỏi booking.');
        header("Location:" . BASE_URL . '?act=booking-detail&id=' . $bookingId . '&tab=customers');
    }

    // Hiển thị form thêm khách hàng vào booking
    public function addCustomer()
    {
        $bookingId = $_GET['booking_id'];
        $booking = $this->bookingModel->getById($bookingId);

        // Ngăn thêm customer khi booking đã completed
        if ($booking['status'] === 'completed') {
            Message::set('error', 'Không thể thêm khách hàng cho booking đã hoàn thành');
            header("Location:" . BASE_URL . "?act=booking-detail&id=$bookingId&tab=customers");
            exit;
        }

        // Xử lý khi submit form
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $customerId = $_POST['customer_id'];

            // Kiểm tra xem khách hàng đã có trong booking chưa
            $existing = $this->bookingModel->getCustomers($bookingId);
            foreach ($existing as $c) {
                if ($c['id'] == $customerId) {
                    Message::set('error', 'Khách hàng này đã có trong booking.');
                    header("Location:" . BASE_URL . '?act=booking-add-customer&booking_id=' . $bookingId);
                    exit;
                }
            }

            $this->bookingModel->addCustomer($bookingId, $customerId, 0);
            Message::set('success', 'Đã thêm khách hàng vào booking.');
            header("Location:" . BASE_URL . '?act=booking-detail&id=' . $bookingId . '&tab=customers');
            exit;
        }

        // Lấy danh sách tất cả khách hàng để chọn
        $customers = $this->customerModel->getAll();
        require_once './views/admin/bookings/add_customer.php';
    }

    // Import xếp phòng từ Excel
    public function importRoomArrangement()
    {
        $bookingId = $_POST['booking_id'] ?? null;
        if (!$bookingId) {
            Message::set('error', 'Không tìm thấy Booking ID.');
            header("Location:" . BASE_URL . '?act=bookings');
            exit;
        }


        // Kiểm tra file upload
        if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            Message::set('error', 'Vui lòng chọn file Excel hợp lệ.');
            header("Location:" . BASE_URL . '?act=booking-detail&id=' . $bookingId . '&tab=room_assignment');
            exit;
        }

        // Kiểm tra định dạng file
        $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        if (!in_array(strtolower($ext), ['xlsx', 'xls'])) {
            Message::set('error', 'Vui lòng chọn file Excel (.xlsx, .xls).');
            header("Location:" . BASE_URL . '?act=booking-detail&id=' . $bookingId . '&tab=room_assignment');
            exit;
        }

        require_once './lib/SimpleXLSX.php';

        if (class_exists('Shuchkin\\SimpleXLSX')) {
            $xlsx = Shuchkin\SimpleXLSX::parse($_FILES['file']['tmp_name']);
        } else {
            Message::set('error', 'Không tìm thấy thư viện SimpleXLSX.');
            header("Location:" . BASE_URL . '?act=booking-detail&id=' . $bookingId . '&tab=room_assignment');
            exit;
        }
        if ($xlsx) {
            $rows = $xlsx->rows();
            $count = 0;
            // Format: [0] => STT, [1] => Họ tên, [2] => Số phòng, [3] => Ghi chú
            // Lấy danh sách khách hàng của booking để đối chiếu
            $customers = $this->bookingModel->getCustomers($bookingId);

            foreach ($rows as $k => $r) {
                if ($k == 0) continue; // Bỏ qua header

                $name = trim($r[1] ?? '');
                $room = trim($r[2] ?? '');
                $notes = trim($r[3] ?? '');

                if ($name && $room) {
                    // Tìm khách hàng theo tên (tương đối)
                    foreach ($customers as $c) {
                        if (mb_strtolower($c['name']) == mb_strtolower($name)) {
                            $this->bookingModel->updateRoomNumber($bookingId, $c['id'], $room, $notes);
                            $count++;
                            break;
                        }
                    }
                }
            }

            Message::set('success', "Đã cập nhật phòng cho $count khách hàng.");
        } else {
            Message::set('error', 'Lỗi đọc file Excel: ' . Shuchkin\SimpleXLSX::parseError());
        }


        header("Location:" . BASE_URL . '?act=booking-detail&id=' . $bookingId . '&tab=room_assignment');
    }

    // Export danh sách xếp phòng ra Excel
    public function exportRoomArrangement()
    {
        $bookingId = $_GET['booking_id'] ?? null;
        if (!$bookingId) {
            Message::set('error', 'Không tìm thấy booking ID');
            header('Location: ' . BASE_URL . '?act=bookings');
            exit;
        }

        $booking = $this->bookingModel->getById($bookingId);
        if (!$booking) {
            Message::set('error', 'Booking không tồn tại');
            header('Location: ' . BASE_URL . '?act=bookings');
            exit;
        }

        $customers = $this->bookingModel->getCustomers($bookingId);

        require_once './lib/SimpleXLSXGen.php';

        $data = [
            ['STT', 'Họ tên', 'Số phòng', 'Ghi chú']
        ];

        $i = 1;
        foreach ($customers as $c) {
            $data[] = [
                $i++,
                $c['name'],
                $c['room_number'] ?? '',
                $c['notes'] ?? '',
            ];
        }

        $filename = 'Xep_phong_Booking_' . $booking['booking_code'] . '.xlsx';

        if (class_exists('Shuchkin\\SimpleXLSXGen')) {
            \Shuchkin\SimpleXLSXGen::fromArray($data)->downloadAs($filename);
        } else {
            Message::set('error', 'Không tìm thấy thư viện SimpleXLSXGen.');
            header("Location:" . BASE_URL . '?act=booking-detail&id=' . $bookingId . '&tab=room_assignment');
        }
        exit;
    }

    // Xem chi tiết journal (Admin)
    public function journalDetail()
    {
        $id = $_GET['id'];
        $journal = $this->journalModel->getById($id);
        if (!$journal) {
            Message::set('error', 'Không tìm thấy nhật ký');
            header('Location: ' . BASE_URL . '?act=bookings');
            exit;
        }

        $images = $this->journalModel->getImages($id);
        $tour = $this->journalModel->getTourByAssignment($journal['tour_assignment_id']);

        require_once './views/admin/bookings/journal_detail.php';
    }
}
