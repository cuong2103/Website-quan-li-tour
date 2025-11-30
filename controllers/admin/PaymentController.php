<?php

class PaymentController
{
    private $paymentModel;
    private $bookingModel;

    public function __construct()
    {
        requireAdmin();
        $this->paymentModel = new PaymentModel();
        $this->bookingModel = new BookingModel(); // cập nhật trạng thái booking
    }

    // Danh sách thanh toán theo booking
    public function index()
    {
        $booking_id = $_GET['booking_id'];
        $payments = $this->paymentModel->getAllByBooking($booking_id);

        require_once './views/admin/payments/list.php';
    }

    // Form tạo thanh toán
    public function create()
    {
        $booking_id = $_GET['booking_id'];
        require_once './views/admin/payments/create.php';
    }

    // Xử lý tạo thanh toán mới
    public function store()
    {
        $data = [
            'booking_id'     => $_POST['booking_id'],
            'payment_method' => $_POST['payment_method'],
            'amount'         => $_POST['amount'],
            'type'           => $_POST['type'],
            'status'         => $_POST['status'],
            'notes'          => $_POST['notes'] ?? null,
            'payment_date'   => $_POST['payment_date'] ?? date('Y-m-d H:i:s'),
            'created_by'     => $_SESSION['currentUser']['id'] ?? 1
        ];

        $this->paymentModel->store($data);

        // Tự động cập nhật trạng thái booking
        $this->autoUpdateBookingStatus($data['booking_id']);

        header("Location: " . BASE_URL . "?act=booking-detail&id=" . $data['booking_id'] . "&tab=payments");
        exit();
    }

    // Form sửa thanh toán
    public function edit()
    {
        $id = $_GET['id'];
        $payment = $this->paymentModel->findById($id);

        require_once './views/admin/payments/edit.php';
    }

    // Xử lý cập nhật thanh toán
    public function update()
    {
        $id = $_POST['id'];
        $data = [
            'payment_method' => $_POST['payment_method'],
            'amount'         => $_POST['amount'],
            'type'           => $_POST['type'],
            'status'         => $_POST['status'],
            'notes'          => $_POST['notes'] ?? null,
            'payment_date'   => $_POST['payment_date'] ?? date('Y-m-d H:i:s')
        ];

        $this->paymentModel->update($id, $data);

        //Lấy booking_id của payment và cập nhật trạng thái booking
        $payment = $this->paymentModel->findById($id);
        $this->autoUpdateBookingStatus($payment['booking_id']);

        header("Location: " . BASE_URL . "?act=booking-detail&id=" . $payment['booking_id'] . "&tab=payments");
        exit();
    }

    // Chi tiết thanh toán
    public function detail()
    {
        $id = $_GET['id'];
        $payment = $this->paymentModel->findById($id);

        require_once './views/admin/payments/detail.php';
    }

    // Xoá thanh toán
    public function delete()
    {
        $id = $_GET['id'];

        $payment = $this->paymentModel->findById($id);
        $booking_id = $payment['booking_id'];

        $this->paymentModel->destroy($id);

        //Cập nhật lại trạng thái booking
        $this->autoUpdateBookingStatus($booking_id);

        header("Location: " . BASE_URL . "?act=booking-detail&id=$booking_id&tab=payments");
        exit();
    }

    // Hàm tự động cập nhật trạng thái booking
    private function autoUpdateBookingStatus($bookingId)
    {
        $totalPaid = $this->bookingModel->getTotalPaid($bookingId);
        $booking = $this->bookingModel->getById($bookingId);

        if (!$booking) return;

        $totalAmount = $booking['total_amount'];

        if ($totalPaid >= $totalAmount) {
            $this->bookingModel->updateStatus($bookingId, '3'); // 3 = đã thanh toán đủ
        } elseif ($totalPaid > 0) {
            $this->bookingModel->updateStatus($bookingId, '2'); // 2 = đã cọc
        } else {
            $this->bookingModel->updateStatus($bookingId, '1'); // 1 = chưa thanh toán
        }
    }
}
