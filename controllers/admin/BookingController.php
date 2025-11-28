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
        $tours = $this->tourModel->getAll();
        $customers = $this->customerModel->getAll();
        $services = $this->serviceModel->getAll();
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
            'status' => 'required'
        ];

        $errors = validate($_POST, $rules);

        if (!empty($errors)) {
            Message::set('errors', 'Vui lòng kiểm tra lại dữ liệu đã nhập.');
            $_SESSION['old'] = $_POST;
            $_SESSION['validate_errors'] = $errors;
            header("Location:" . BASE_URL . '?act=booking-create');
        }

        $data = [
            'tour_id' => $_POST['tour_id'],
            'start_date' => $_POST['start_date'],
            'end_date' => $_POST['end_date'],
            'adult_count' => $_POST['adult_count'],
            'child_count' => $_POST['child_count'],
            'total_amount' => $_POST['total_amount'],
            'deposit_amount' => $_POST['deposit_amount'] ?? 0,
            'remaining_amount' => $_POST['remaining_amount'] ?? 0,
            'status' => $_POST['status'] ?? 1,
            'special_requests' => $_POST['special_requests'] ?? null,
            'customers' => $_POST['customers'] ?? [],
            'is_representative' => $_POST['is_representative'] ?? null,
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
    private function autoUpdatePaymentStatus($bookingId)
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


}
