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
        $booking_id = $_GET['booking_id'];
        
        // Lấy thông tin booking
        $booking = $this->bookingModel->getById($booking_id);
        
        // Ngăn thêm payment cho booking đã completed
        if ($booking['status'] === 'completed') {
            Message::set('error', 'Không thể thêm thanh toán cho booking đã hoàn thành');
            header("Location: " . BASE_URL . "?act=booking-detail&id=$booking_id&tab=payments");
            exit();
        }
        
        // Tính tổng đã thanh toán
        $totalPaid = $this->bookingModel->getTotalPaid($booking_id);
        
        // Tính số tiền còn lại
        $remaining = $booking['total_amount'] - $totalPaid;
        
        require_once './views/admin/payments/create.php';
    }

    // Lưu thanh toán mới
    public function store()
    {
        // Validation
        $errors = [];
        
        // Nếu chuyển khoản thì bắt buộc có mã giao dịch
        if ($_POST['payment_method'] === 'bank_transfer' && empty($_POST['transaction_code'])) {
            $errors[] = 'Vui lòng nhập mã giao dịch cho chuyển khoản';
        }
        
        // Xử lý upload file
        $receiptFile = null;
        if (isset($_FILES['receipt_file']) && $_FILES['receipt_file']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/receipts/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'];
            $fileType = $_FILES['receipt_file']['type'];
            
            if (!in_array($fileType, $allowedTypes)) {
                $errors[] = 'Chỉ chấp nhận file JPG, PNG hoặc PDF';
            }
            
            if ($_FILES['receipt_file']['size'] > 5 * 1024 * 1024) {
                $errors[] = 'File không được vượt quá 5MB';
            }
            
            if (empty($errors)) {
                $fileName = time() . '_' . basename($_FILES['receipt_file']['name']);
                if (move_uploaded_file($_FILES['receipt_file']['tmp_name'], $uploadDir . $fileName)) {
                    $receiptFile = $fileName;
                } else {
                    $errors[] = 'Lỗi khi upload file';
                }
            }
        }
        
        // Nếu có lỗi validation
        if (!empty($errors)) {
            $_SESSION['payment_errors'] = $errors;
            header("Location: " . BASE_URL . "?act=payment-create&booking_id=" . $_POST['booking_id']);
            exit();
        }
        
        // Gom dữ liệu vào mảng
        $data = [
            'booking_id'       => $_POST['booking_id'],
            'payment_method'   => $_POST['payment_method'],
            'transaction_code' => $_POST['transaction_code'] ?? null,
            'receipt_file'     => $receiptFile,
            'amount'           => $_POST['amount'],
            'type'             => $_POST['type'],
            'payment_date'     => $_POST['payment_date'] ?? date('Y-m-d'),
            'created_by'       => $_SESSION['currentUser']['id'] ?? 1
        ];

        // Validate số tiền thanh toán
        $booking = $this->bookingModel->getById($data['booking_id']);
        $totalPaid = $this->bookingModel->getTotalPaid($data['booking_id']);
        $remaining = $booking['total_amount'] - $totalPaid;

        // Nếu thanh toán vượt quá số tiền còn lại (không áp dụng cho refund)
        if ($data['type'] != 'refund' && $data['amount'] > $remaining) {
            Message::set('error', 'Số tiền thanh toán vượt quá số tiền còn lại (' . number_format($remaining) . 'đ)');
            header("Location: " . BASE_URL . "?act=payment-create&booking_id=" . $data['booking_id']);
            exit();
        }

        // Lưu thanh toán mới vào DB
        $this->paymentModel->store($data);

        // Tự cập nhật trạng thái booking
        $this->autoUpdateBookingStatus($data['booking_id']);

        // Chuyển về trang chi tiết booking
        Message::set('success', 'Thêm thanh toán thành công!');
        header("Location: " . BASE_URL . "?act=booking-detail&id=" . $data['booking_id'] . "&tab=payments");
        exit();
    }

    // Form sửa thanh toán
    public function edit()
    {
        $id = $_GET['id'];
        $payment = $this->paymentModel->findById($id);
        
        // Lấy thông tin booking
        $booking = $this->bookingModel->getById($payment['booking_id']);
        
        // Ngăn sửa payment của booking đã completed
        if ($booking['status'] === 'completed') {
            Message::set('error', 'Không thể sửa thanh toán của booking đã hoàn thành');
            header("Location: " . BASE_URL . "?act=booking-detail&id={$payment['booking_id']}&tab=payments");
            exit();
        }
        
        // Tính tổng đã thanh toán
        $totalPaid = $this->bookingModel->getTotalPaid($payment['booking_id']);
        
        // Trừ payment hiện tại để tính số tiền còn lại chính xác
        $totalPaid -= $payment['amount'];
        $remaining = $booking['total_amount'] - $totalPaid;

        require_once './views/admin/payments/edit.php';
    }

    // Xử lý cập nhật thanh toán
    public function update()
    {
        $id = $_POST['id'];
        
        // Lấy payment hiện tại
        $payment = $this->paymentModel->findById($id);
        $bookingId = $payment['booking_id'];
        
        // Validation
        $errors = [];
        
        // Nếu chuyển khoản thì bắt buộc có mã giao dịch
        if ($_POST['payment_method'] === 'bank_transfer' && empty($_POST['transaction_code'])) {
            $errors[] = 'Vui lòng nhập mã giao dịch cho chuyển khoản';
        }
        
        // Xử lý upload file mới (nếu có)
        $receiptFile = $payment['receipt_file']; // Giữ file cũ
        if (isset($_FILES['receipt_file']) && $_FILES['receipt_file']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/receipts/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'];
            $fileType = $_FILES['receipt_file']['type'];
            
            if (!in_array($fileType, $allowedTypes)) {
                $errors[] = 'Chỉ chấp nhận file JPG, PNG hoặc PDF';
            }
            
            if ($_FILES['receipt_file']['size'] > 5 * 1024 * 1024) {
                $errors[] = 'File không được vượt quá 5MB';
            }
            
            if (empty($errors)) {
                $fileName = time() . '_' . basename($_FILES['receipt_file']['name']);
                if (move_uploaded_file($_FILES['receipt_file']['tmp_name'], $uploadDir . $fileName)) {
                    // Xóa file cũ nếu có
                    if ($payment['receipt_file'] && file_exists($uploadDir . $payment['receipt_file'])) {
                        unlink($uploadDir . $payment['receipt_file']);
                    }
                    $receiptFile = $fileName;
                } else {
                    $errors[] = 'Lỗi khi upload file';
                }
            }
        }
        
        // Nếu có lỗi validation
        if (!empty($errors)) {
            $_SESSION['payment_errors'] = $errors;
            header("Location: " . BASE_URL . "?act=payment-edit&id=$id");
            exit();
        }

        // Lấy dữ liệu update
        $data = [
            'payment_method'   => $_POST['payment_method'],
            'transaction_code' => $_POST['transaction_code'] ?? null,
            'receipt_file'     => $receiptFile,
            'amount'           => $_POST['amount'],
            'type'             => $_POST['type'],
            'payment_date'     => $_POST['payment_date'] ?? date('Y-m-d')
        ];

        // Lấy booking để tính tiền còn lại
        $booking = $this->bookingModel->getById($bookingId);
        $totalPaid = $this->bookingModel->getTotalPaid($bookingId);

        // Trừ payment cũ ra để tính lại số tiền đúng
        $totalPaid -= $payment['amount'];
        $remaining = $booking['total_amount'] - $totalPaid;

        // Check số tiền có vượt quá số dư không (không áp dụng cho refund)
        if ($data['type'] != 'refund' && $data['amount'] > $remaining) {
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
        
        // Lấy thông tin booking
        $booking = $this->bookingModel->getById($booking_id);
        
        // Ngăn xóa payment của booking đã completed
        if ($booking['status'] === 'completed') {
            Message::set('error', 'Không thể xóa thanh toán của booking đã hoàn thành');
            header("Location: " . BASE_URL . "?act=booking-detail&id=$booking_id&tab=payments");
            exit();
        }

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
        $totalPaid = $this->bookingModel->getTotalPaid($bookingId);
        $booking = $this->bookingModel->getById($bookingId);

        if (!$booking) return;

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
