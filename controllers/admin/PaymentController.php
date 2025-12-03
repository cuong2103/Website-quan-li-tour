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
            'payment_date'   => $_POST['payment_date'] ?? date('Y-m-d H:i:s'),
            'created_by'     => $_SESSION['currentUser']['id'] ?? 1
        ];

        // Validate số tiền
        $booking = $this->bookingModel->getById($data['booking_id']);
        $totalPaid = $this->bookingModel->getTotalPaid($data['booking_id']);
        $remaining = $booking['total_amount'] - $totalPaid;

        if ($data['status'] == 'success' && $data['amount'] > $remaining) {
             Message::set('errors', 'Số tiền thanh toán vượt quá số tiền còn lại (' . number_format($remaining) . 'đ)');
             header("Location: " . BASE_URL . "?act=payment-create&booking_id=" . $data['booking_id']);
             exit();
        }

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
            'payment_date'   => $_POST['payment_date'] ?? date('Y-m-d H:i:s')
        ];

        // Validate số tiền
        $payment = $this->paymentModel->findById($id);
        $bookingId = $payment['booking_id'];
        $booking = $this->bookingModel->getById($bookingId);
        
        $totalPaid = $this->bookingModel->getTotalPaid($bookingId);
        // Trừ đi số tiền của payment hiện tại (nếu nó đã success) để tính lại remaining thực tế trước khi update
        if ($payment['status'] == 'success') {
            $totalPaid -= $payment['amount'];
        }
        
        $remaining = $booking['total_amount'] - $totalPaid;

        if ($data['status'] == 'success' && $data['amount'] > $remaining) {
             Message::set('errors', 'Số tiền thanh toán vượt quá số tiền còn lại (' . number_format($remaining) . 'đ)');
             header("Location: " . BASE_URL . "?act=payment-edit&id=" . $id);
             exit();
        }

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
        $remaining = $totalAmount - $totalPaid;

        // Cập nhật số tiền vào booking
        $this->bookingModel->updateFinancials($bookingId, $totalPaid, $remaining);

        if ($totalPaid >= $totalAmount) {
            $this->bookingModel->updateStatus($bookingId, '3'); // 3 = đã thanh toán đủ
        } elseif ($totalPaid > 0) {
            $this->bookingModel->updateStatus($bookingId, '2'); // 2 = đã cọc
        } else {
            $this->bookingModel->updateStatus($bookingId, '1'); // 1 = chưa thanh toán
        }
    }
}
