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

        require_once './views/admin/bookings/create.php';
    }


    // Xửa lý tạo booking mới
    public function store()
    {
        // validate dữ liệu
        $rules = [
            'tour_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'adult_count' => 'required',
            'total_amount' => 'required',
            'status' => 'required',
            // Validate representative info
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

        // Generate booking code: BK + timestamp
        $bookingCode = 'BK-' . time();

        // Handle Representative Customer
        $repName = $_POST['rep_name'];
        $repEmail = $_POST['rep_email'];
        $repPhone = $_POST['rep_phone'];
        $repAddress = $_POST['rep_address'] ?? '';
        $repGender = $_POST['rep_gender'] ?? 'other';
        $repPassport = $_POST['rep_passport'] ?? '';

        // Check if customer exists
        $customer = $this->customerModel->findByEmailOrPhone($repEmail, $repPhone);

        if ($customer) {
            $customerId = $customer['id'];
            // Optional: Update existing customer info if needed, but usually we respect existing data or ask user.
            // For now, we use the existing ID.
        } else {
            // Create new customer
            $this->customerModel->create($repName, $repEmail, $repPhone, $repAddress, $_SESSION['currentUser']['id'], $repPassport, $repGender);
            // Get the ID of the newly created customer. 
            // Since create() returns boolean in the current model, we need to fetch it or update create() to return ID.
            // Let's fetch it again to be sure.
            $customer = $this->customerModel->findByEmailOrPhone($repEmail, $repPhone);
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
            'status' => $_POST['status'] ?? 1,
            'special_requests' => $_POST['special_requests'] ?? null,
            'customers' => [$customerId], // Initially only the representative
            'is_representative' => $customerId,
            'services' => $_POST['services'] ?? [],
            'created_by' => $_SESSION['currentUser']['id']
        ];

        $bookingId = $this->bookingModel->create($data);

        // Thông báo nếu thành công
        Message::set('success', 'Tạo booking thành công.');
        header("Location:" . BASE_URL . '?act=bookings');
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

        // Thông báo nếu thành công
        Message::set('success', 'Cập nhật booking thành công!');
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
            $this->bookingModel->updateStatus($bookingId, 'thanh toán đủ'); // 
        } elseif ($totalPaid > 0) {
            $this->bookingModel->updateStatus($bookingId, 'thanh toán 1 phần'); // 
        } else {
            $this->bookingModel->updateStatus($bookingId, 'chưa thanh toán'); // 
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
                        $this->customerModel->create($name, $email, $phone, $address, $_SESSION['currentUser']['id'], $passport, $gender);
                        $customer = $this->customerModel->findByEmailOrPhone($email, $phone);
                        $customerId = $customer['id'];
                    }

                    // Add to booking
                    $existingCustomers = $this->bookingModel->getCustomers($bookingId);
                    $isAlreadyIn = false;
                    foreach($existingCustomers as $ec) {
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
                foreach($existing as $c) {
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
}
