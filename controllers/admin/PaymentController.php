<?php

class PaymentController
{
    public $paymentModel;
    public $bookingModel;

    public function __construct()
    {
        requireAdmin(); // Bắt buộc admin mới được dùng controller này
        $this->paymentModel = new PaymentModel(); // Model xử lý thanh toán
        $this->bookingModel = new BookingModel(); // Model booking để cập nhật trạng thái booking
    }

    // Hiển thị danh sách thanh toán của 1 booking
    public function index()
    {
        $booking_id = $_GET['booking_id']; // Lấy booking_id từ URL
        $payments = $this->paymentModel->getAllByBooking($booking_id); // Lấy tất cả payment của booking

        require_once './views/admin/payments/list.php'; // Load view
    }

    // Hiển thị form tạo thanh toán
    public function create()
    {
        $booking_id = $_GET['booking_id']; // Lấy booking ID để gắn vào form
        // $totalPaid = $this->bookingModel->getTotalPaid($booking_id);
        // $remaining = $booking['total_amount'] - $totalPaid;
        require_once './views/admin/payments/create.php';
    }

    // Lưu thanh toán mới
    public function store()
    {
        // Gom dữ liệu vào mảng
        $data = [
            'booking_id'     => $_POST['booking_id'],
            'payment_method' => $_POST['payment_method'],
            'amount'         => $_POST['amount'],
            'type'           => $_POST['type'], // payment hoặc refund
            'payment_date'   => $_POST['payment_date'] ?? date('Y-m-d'),
            'created_by'     => $_SESSION['currentUser']['id'] ?? 1
        ];

        // Validate số tiền thanh toán
        $booking = $this->bookingModel->getById($data['booking_id']); // Lấy thông tin booking
        $totalPaid = $this->bookingModel->getTotalPaid($data['booking_id']); // Lấy tổng đã thanh toán
        $remaining = $booking['total_amount'] - $totalPaid; // Tính số tiền còn lại

        // Nếu thanh toán vượt quá số tiền còn lại (chỉ áp dụng cho payment, không áp dụng cho refund)
        if ($data['type'] == 'payment' && $data['amount'] > $remaining) {
            Message::set('error', 'Số tiền thanh toán vượt quá số tiền còn lại (' . number_format($remaining) . 'đ)');
            header("Location: " . BASE_URL . "?act=payment-create&booking_id=" . $data['booking_id']);
            exit();
        }

        // Lưu thanh toán mới vào DB
        $this->paymentModel->store($data);

        // Tự cập nhật trạng thái booking cho đúng logic
        $this->autoUpdateBookingStatus($data['booking_id']);

        // Chuyển về trang chi tiết booking
        Message::set('success', 'Thêm thanh toán thành công!');
        header("Location: " . BASE_URL . "?act=booking-detail&id=" . $data['booking_id'] . "&tab=payments");
        exit();
    }

    // Form sửa thanh toán
    public function edit()
    {
        $id = $_GET['id']; // ID của payment
        $payment = $this->paymentModel->findById($id); // Lấy payment từ DB

        require_once './views/admin/payments/edit.php';
    }

    // Xử lý cập nhật thanh toán
    public function update()
    {
        $id = $_POST['id'];

        // Lấy dữ liệu update
        $data = [
            'payment_method' => $_POST['payment_method'],
            'amount'         => $_POST['amount'],
            'type'           => $_POST['type'],
            'payment_date'   => $_POST['payment_date'] ?? date('Y-m-d')
        ];

        // Lấy payment hiện tại
        $payment = $this->paymentModel->findById($id);
        $bookingId = $payment['booking_id'];

        // Lấy booking để tính tiền còn lại
        $booking = $this->bookingModel->getById($bookingId);
        $totalPaid = $this->bookingModel->getTotalPaid($bookingId);

        // Trừ payment cũ ra để tính lại số tiền đúng
        $totalPaid -= $payment['amount'];
        $remaining = $booking['total_amount'] - $totalPaid;

        // Check số tiền có vượt quá số dư không (chỉ cho payment)
        if ($data['type'] == 'payment' && $data['amount'] > $remaining) {
            Message::set('error', 'Số tiền thanh toán vượt quá số tiền còn lại (' . number_format($remaining) . 'đ)');
            header("Location: " . BASE_URL . "?act=payment-edit&id=" . $id);
            exit();
        }

        // Cập nhật payment
        $this->paymentModel->update($id, $data);

        // Cập nhật lại trạng thái booking
        $this->autoUpdateBookingStatus($bookingId);

        // Quay lại trang booking
        Message::set('success', 'Cập nhật thanh toán thành công!');
        header("Location: " . BASE_URL . "?act=booking-detail&id=" . $bookingId . "&tab=payments");
        exit();
    }

    // Xem chi tiết thanh toán
    public function detail()
    {
        $id = $_GET['id'];
        $payment = $this->paymentModel->findById($id);

        require_once './views/admin/payments/detail.php';
    }

    // Xóa thanh toán
    public function delete()
    {
        $id = $_GET['id'];

        // Lấy booking_id của payment để lát cập nhật
        $payment = $this->paymentModel->findById($id);
        $booking_id = $payment['booking_id'];

        // Xóa payment
        $this->paymentModel->destroy($id);

        // Cập nhật lại trạng thái booking
        $this->autoUpdateBookingStatus($booking_id);

        header("Location: " . BASE_URL . "?act=booking-detail&id=$booking_id&tab=payments");
        exit();
    }

    // Logic cập nhật trạng thái booking dựa vào payment
    public function autoUpdateBookingStatus($bookingId)
    {
        $totalPaid = $this->bookingModel->getTotalPaid($bookingId); // Tổng tiền đã thanh toán
        $booking = $this->bookingModel->getById($bookingId); // Lấy booking

        if (!$booking) return; // Nếu không có booking thì dừng

        // Kiểm tra xem có payment hoàn tiền (refund) không
        $payments = $this->paymentModel->getAllByBooking($bookingId);
        $hasRefund = false;

        foreach ($payments as $payment) {
            if ($payment['type'] === 'refund') {
                $hasRefund = true;
                break;
            }
        }

        // Nếu có refund thì booking bị coi như hủy
        if ($hasRefund) {
            $totalAmount = $booking['total_amount'];
            $remaining = $totalAmount - $totalPaid;

            // Cập nhật tiền nhưng đổi trạng thái sang cancelled
            $this->bookingModel->updateFinancials($bookingId, $totalPaid, $remaining);
            $this->bookingModel->updateStatus($bookingId, 'cancelled');
            return;
        }

        // Không sửa status nếu đã hoàn thành hoặc đã hủy
        if (in_array($booking['status'], ['completed', 'cancelled'])) {
            $totalAmount = $booking['total_amount'];
            $remaining = $totalAmount - $totalPaid;

            // Nhưng vẫn cập nhật tiền
            $this->bookingModel->updateFinancials($bookingId, $totalPaid, $remaining);
            return;
        }

        // Tính lại số dư
        $totalAmount = $booking['total_amount'];
        $remaining = $totalAmount - $totalPaid;

        // Cập nhật số tiền
        $this->bookingModel->updateFinancials($bookingId, $totalPaid, $remaining);

        // Logic đổi trạng thái dựa vào tổng tiền thanh toán
        if ($totalPaid >= $totalAmount) {
            $this->bookingModel->updateStatus($bookingId, 'paid'); // Thanh toán đủ
        } elseif ($totalPaid > 0) {
            $this->bookingModel->updateStatus($bookingId, 'deposited'); // Cọc một phần
        } else {
            $this->bookingModel->updateStatus($bookingId, 'pending'); // Chưa thanh toán
        }
    }
}
