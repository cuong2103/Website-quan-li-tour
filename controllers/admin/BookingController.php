<?php
class BookingController
{
    public $bookingModel;
    public $tourModel;
    public $customerModel;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
        $this->tourModel = new TourModel();
        $this->customerModel = new CustomerModel();
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
        require './views/admin/bookings/create.php';
    }

    // Xửa lý tạo booking mới
    public function store()
    {
        $data = [
            'tour_id' => $_POST['tour_id'],
            'start_date' => $_POST['start_date'],
            'end_date' => $_POST['end_date'],
            'adult_count' => $_POST['adult_count'],
            'child_count' => $_POST['child_count'],
            'total_amount' => $_POST['total_amount'],
            'deposit_amount' => $_POST['deposit_amount'] ?? 0,
            'remaining_amount' => $_POST['remaining_amount'] ?? 0,
            'special_requests' => $_POST['special_requests'] ?? null,
            'customers' => $_POST['customers'] ?? [],
            'created_by' => 1
        ];

        $this->bookingModel->create($data);
        header('Location: ' . BASE_URL . '?act=bookings');
    }

    // Hiển thị form chỉnh sửa booking
    public function edit()
    {
        $id = $_GET['id'];
        $booking = $this->bookingModel->getById($id);
        $tours = $this->tourModel->getAll();
        $customers = $this->customerModel->getAll();

        require './views/admin/bookings/edit.php';
    }

    // Cập nhật booking
    public function update()
    {
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
            'customers' => $_POST['customers'] ?? []
        ];

        $this->bookingModel->update($id, $data);

        // Xóa khách cũ
        // $this->bookingModel->conn->prepare("DELETE FROM booking_customers WHERE booking_id=?")->execute([$id]);

        // Thêm khách mới
        foreach ($data['customers'] as $customerId) {
            $this->bookingModel->addCustomer($id, $customerId);
        }

        header('Location: ' . BASE_URL . '?act=bookings');
    }

    // Xóa booking
    public function delete()
    {
        $id = $_GET['id'];
        $this->bookingModel->delete($id);
        header('Location: ' . BASE_URL . '?act=bookings');
    }

    // Hiển thị chi tiết booking
    public function detail()
    {
        $id = $_GET['id'];
        $booking = $this->bookingModel->getById($id);
        $customers = $this->bookingModel->getCustomers($id);
        require_once './views/admin/bookings/detail.php';
    }
}
