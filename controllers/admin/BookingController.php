<?php
class BookingController
{
    public $bookingModel;
    public $tourModel;
    public $customerModel;
    public $serviceModel;
    public $contractModel;
    public $paymentModel;

    public function __construct()
    {
        requireAdmin();
        $this->bookingModel = new BookingModel();
        $this->tourModel = new TourModel();
        $this->customerModel = new CustomerModel();
        $this->serviceModel = new ServiceModel();
        $this->contractModel = new ContractModel();
        $this->paymentModel = new PaymentModel();
    }

    // Hiển thị danh sách booking
    public function index()
    {
        $bookings = $this->bookingModel->getAll();
        require_once './views/admin/bookings/index.php';
    }

    // hiển thị form tạo booking
    public function create()
    {
        $keyword = $_GET['keyword'] ?? '';

        // Model
        $serviceModel = $this->serviceModel;

        // Lọc theo keyword nếu có
        if ($keyword) {
            $services = $serviceModel->search($keyword);
        } else {
            $services = $serviceModel->getAll();
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
            'adult_count' => 'required',
            'total_amount' => 'required',
            'status' => 'required',
            'rep_name' => 'required',
            'rep_phone' => 'required',
            'rep_email' => 'required|email'
        ];

        $errors = validate($_POST, $rules);
        if (!empty($errors)) {
            Message::set('errors', 'Vui lòng kiểm tra lại dữ liệu đã nhập.');
            $_SESSION['old'] = $_POST;
            $_SESSION['validate_errors'] = $errors;
            header("Location:" . BASE_URL . '?act=booking-create');
            exit;
        }

        // ===== CREATE BOOKING =====
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

        $data = [
            'tour_id' => $_POST['tour_id'],
            'booking_code' => $bookingCode,
            'start_date' => $_POST['start_date'],
            'end_date' => $_POST['end_date'],
            'adult_count' => $_POST['adult_count'],
            'child_count' => $_POST['child_count'],
            'total_amount' => $_POST['total_amount'],
            'deposit_amount' => $_POST['deposit_amount'] ?? 0,
            'remaining_amount' => $_POST['remaining_amount'] ?? 0,
            'status' => $_POST['status'],
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

        // ===== DONE =====
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
        // validate dữ liệu
        $rules = [
            'tour_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'adult_count' => 'required',
            'total_amount' => 'required'
        ];

        $errors = validate($_POST, $rules);

        if (!empty($errors)) {
            Message::set('errors', 'Vui lòng kiểm tra lại dữ liệu đã nhập.');
            $_SESSION['old'] = $_POST;
            $_SESSION['validate_errors'] = $errors;
            header("Location:" . BASE_URL . '?act=booking-edit&id=' . $_POST['id']);
        }

        // lấy id booking
        $id = $_POST['id'];

        $data = [
            'tour_id' => $_POST['tour_id'],
            'start_date' => $_POST['start_date'],
            'end_date' => $_POST['end_date'],
            'adult_count' => $_POST['adult_count'],
            'child_count' => $_POST['child_count'],
            'total_amount' => $_POST['total_amount'],
            'deposit_amount' => $_POST['deposit_amount'] ?? 0,
            'remaining_amount' => $_POST['remaining_amount'] ?? 0,
            'status' => $_POST['status'],
            'special_requests' => $_POST['special_requests'] ?? null,
            'customers' => $_POST['customers'] ?? [],
            'is_representative' => $_POST['is_representative'] ?? null,
            'services' => $_POST['services'] ?? []
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

        // Check if booking has payments
        $totalPaid = $this->bookingModel->getTotalPaid($id);
        if ($totalPaid > 0) {
            Message::set('errors', 'Không thể xóa booking đã thanh toán.');
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
        $customers = $this->bookingModel->getCustomers($id);
        $bookingServices = $this->bookingModel->getServicesByBooking($id);
        $bookingContracts = $this->contractModel->getByBookingId($id);
        $bookingPayments = $this->paymentModel->getAllByBooking($booking['id']);
        require_once './views/admin/bookings/detail.php';
    }
    // hàm auto cập nhật trạng thái
    public function autoUpdatePaymentStatus($bookingId)
    {
        $totalPaid = $this->bookingModel->getTotalPaid($bookingId);
        $booking = $this->bookingModel->getById($bookingId);

        if (!$booking) return;

        $totalAmount = $booking['total_amount'];

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

            // Check file extension
            $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
            if (!in_array(strtolower($ext), ['xlsx', 'xls'])) {
                Message::set('errors', 'Vui lòng chọn file Excel (.xlsx, .xls).');
                header("Location:" . BASE_URL . '?act=booking-detail&id=' . $bookingId . '&tab=customers');
                exit;
            }

            require_once './lib/SimpleXLSX.php';

            if ($xlsx = \Shuchkin\SimpleXLSX::parse($file)) {
                $count = 0;
                $rows = $xlsx->rows();

                // Loop through rows (skip header)
                foreach ($rows as $index => $row) {
                    if ($index == 0) continue; // Skip header row

                    // Excel Format: Name, Email, Phone, Address, Gender, Passport
                    $name = $row[0] ?? '';
                    $email = $row[1] ?? '';
                    $phone = $row[2] ?? '';
                    $address = $row[3] ?? '';
                    $gender = $row[4] ?? 'other';
                    $passport = $row[5] ?? '';

                    if (empty($name)) continue;

                    // Check if customer exists
                    $customer = $this->customerModel->findByEmailOrPhone($email, $phone);

                    if ($customer) {
                        $customerId = $customer['id'];
                    } else {
                        // Create new customer
                        $this->customerModel->create($name, $email, $phone, $address, $_SESSION['currentUser']['id'], $passport, $gender, '');
                        $customer = $this->customerModel->findByEmailOrPhone($email, $phone);
                        $customerId = $customer['id'];
                    }

                    // Add to booking
                    $existingCustomers = $this->bookingModel->getCustomers($bookingId);
                    $isAlreadyIn = false;
                    foreach ($existingCustomers as $ec) {
                        if ($ec['id'] == $customerId) {
                            $isAlreadyIn = true;
                            break;
                        }
                    }

                    if (!$isAlreadyIn) {
                        $this->bookingModel->addCustomer($bookingId, $customerId, 0);
                        $count++;
                    }
                }

                Message::set('success', "Đã thêm $count khách hàng từ file Excel.");
            } else {
                Message::set('errors', 'Không thể đọc file Excel: ' . \Shuchkin\SimpleXLSX::parseError());
            }
        } else {
            Message::set('errors', 'Vui lòng chọn file Excel.');
        }

        header("Location:" . BASE_URL . '?act=booking-detail&id=' . $bookingId . '&tab=customers');
    }

    // Export danh sách khách hàng ra Excel
    public function exportCustomers()
    {
        $bookingId = $_GET['booking_id'] ?? null;
        if (!$bookingId) {
            Message::set('errors', 'Không tìm thấy booking ID');
            header('Location: ' . BASE_URL . '?act=bookings');
            exit;
        }

        $booking = $this->bookingModel->getById($bookingId);
        if (!$booking) {
            Message::set('errors', 'Booking không tồn tại');
            header('Location: ' . BASE_URL . '?act=bookings');
            exit;
        }

        $customers = $this->bookingModel->getCustomers($bookingId);

        require_once './lib/SimpleXLSXGen.php';

        $data = [
            ['STT', 'Họ tên', 'Giới tính', 'Hộ chiếu', 'Địa chỉ', 'SĐT', 'Email', 'Người đại diện']
        ];

        $i = 1;
        foreach ($customers as $c) {
            $gender = 'Khác';
            if ($c['gender'] == 'male') $gender = 'Nam';
            elseif ($c['gender'] == 'female') $gender = 'Nữ';

            $data[] = [
                $i++,
                $c['name'],
                $gender,
                $c['passport'] ?? '',
                $c['address'],
                $c['phone'],
                $c['email'],
                $c['is_representative'] ? 'Có' : 'Không'
            ];
        }

        $filename = 'Danh_sach_khach_hang_Booking_' . $booking['booking_code'] . '.xlsx';
        \Shuchkin\SimpleXLSXGen::fromArray($data)->downloadAs($filename);
        exit;
    }

    // Xóa khách hàng khỏi booking
    public function removeCustomer()
    {
        $bookingId = $_GET['booking_id'];
        $customerId = $_GET['customer_id'];

        $this->bookingModel->removeCustomer($bookingId, $customerId);

        Message::set('success', 'Đã xóa khách hàng khỏi booking.');
        header("Location:" . BASE_URL . '?act=booking-detail&id=' . $bookingId . '&tab=customers');
    }

    // Hiển thị form thêm khách hàng vào booking
    public function addCustomer()
    {
        $bookingId = $_GET['booking_id'];
        $booking = $this->bookingModel->getById($bookingId);

        // Xử lý khi submit form
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $customerId = $_POST['customer_id'];

            // Check if already exists
            $existing = $this->bookingModel->getCustomers($bookingId);
            foreach ($existing as $c) {
                if ($c['id'] == $customerId) {
                    Message::set('errors', 'Khách hàng này đã có trong booking.');
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
            Message::set('errors', 'Không tìm thấy Booking ID.');
            header("Location:" . BASE_URL . '?act=bookings');
            exit;
        }

        if (isset($_FILES['excel_file']) && $_FILES['excel_file']['error'] == 0) {
            require_once './lib/SimpleXLSX.php';

            if (class_exists('Shuchkin\SimpleXLSX')) {
                $xlsx = Shuchkin\SimpleXLSX::parse($_FILES['excel_file']['tmp_name']);
            } else {
                Message::set('errors', 'Không tìm thấy thư viện SimpleXLSX.');
                header("Location:" . BASE_URL . '?act=booking-detail&id=' . $bookingId . '&tab=room_assignment');
                exit;
            }

            if ($xlsx) {
                $rows = $xlsx->rows();
                $count = 0;

                // Giả sử dòng 1 là header, dữ liệu từ dòng 2
                // Format: [0] => STT, [1] => Họ tên, [2] => Số phòng

                // Lấy danh sách khách hàng của booking để đối chiếu
                $customers = $this->bookingModel->getCustomers($bookingId);

                foreach ($rows as $k => $r) {
                    if ($k == 0) continue; // Skip header

                    $name = trim($r[1] ?? '');
                    $room = trim($r[2] ?? '');

                    if ($name && $room) {
                        // Tìm khách hàng theo tên (tương đối)
                        foreach ($customers as $c) {
                            if (mb_strtolower($c['name']) == mb_strtolower($name)) {
                                $this->bookingModel->updateRoomNumber($bookingId, $c['id'], $room);
                                $count++;
                                break;
                            }
                        }
                    }
                }

                Message::set('success', "Đã cập nhật phòng cho $count khách hàng.");
            } else {
                Message::set('errors', 'Lỗi đọc file Excel: ' . Shuchkin\SimpleXLSX::parseError());
            }
        } else {
            Message::set('errors', 'Vui lòng chọn file Excel hợp lệ.');
        }

        header("Location:" . BASE_URL . '?act=booking-detail&id=' . $bookingId . '&tab=room_assignment');
    }

    // Export danh sách xếp phòng ra Excel
    public function exportRoomArrangement()
    {
        $bookingId = $_GET['booking_id'] ?? null;
        if (!$bookingId) {
            Message::set('errors', 'Không tìm thấy booking ID');
            header('Location: ' . BASE_URL . '?act=bookings');
            exit;
        }

        $booking = $this->bookingModel->getById($bookingId);
        if (!$booking) {
            Message::set('errors', 'Booking không tồn tại');
            header('Location: ' . BASE_URL . '?act=bookings');
            exit;
        }

        $customers = $this->bookingModel->getCustomers($bookingId);

        require_once './lib/SimpleXLSXGen.php';

        $data = [
            ['STT', 'Họ tên', 'Số phòng']
        ];

        $i = 1;
        foreach ($customers as $c) {
            $data[] = [
                $i++,
                $c['name'],
                $c['room_number'] ?? '',
            ];
        }

        $filename = 'Xep_phong_Booking_' . $booking['booking_code'] . '.xlsx';

        if (class_exists('Shuchkin\SimpleXLSXGen')) {
            \Shuchkin\SimpleXLSXGen::fromArray($data)->downloadAs($filename);
        } else {
            // Fallback if class not found (though it should be there if exportCustomers works)
            Message::set('errors', 'Không tìm thấy thư viện SimpleXLSXGen.');
            header("Location:" . BASE_URL . '?act=booking-detail&id=' . $bookingId . '&tab=room_assignment');
        }
        exit;
    }
}
