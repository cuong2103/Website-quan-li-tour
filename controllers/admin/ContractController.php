<?php
class ContractController
{
    public $contractModel;
    public $bookingModel;

    public function __construct()
    {
        requireAdmin();
        $this->contractModel = new ContractModel();
        $this->bookingModel = new BookingModel();
    }

    // Tự động cập nhật trạng thái
    private function checkAndAutoUpdateStatus()
    {
        $contracts = $this->contractModel->getAll();
        $today = date('Y-m-d');
        foreach ($contracts as $c) {
            if ($c['status'] == 'active' && $c['expiry_date'] < $today) {
                $this->contractModel->updateStatus($c['id'], 'expired');
            }
        }
    }

    // Hiển thị danh sách hợp đồng
    public function index()
    {
        $this->checkAndAutoUpdateStatus();
        $contracts = $this->contractModel->getAll();
        require_once './views/admin/contracts/index.php';
    }

    // Hiển thị form tạo hợp đồng
    public function create()
    {
        $bookingId = $_GET['booking_id'] ?? null;
        if (!$bookingId) redirect('bookings');

        // Lấy danh sách khách hàng của booking này
        $bookingCustomers = $this->bookingModel->getCustomers($bookingId);

        // Lấy thông tin booking để điền ngày
        $booking = $this->bookingModel->getById($bookingId);

        require_once './views/admin/contracts/create.php';
    }

    // Lưu hợp đồng
    public function store()
    {
        $bookingId = $_POST['booking_id'];
        $fileUrl = uploadFile($_FILES['file_upload'], '/uploads/contracts/');

        $data = [
            'booking_id'     => $bookingId,
            'contract_name'  => $_POST['contract_name'],
            'effective_date' => $_POST['effective_date'],
            'expiry_date'    => $_POST['expiry_date'],
            'signer_id'      => $_SESSION['currentUser']['id'], // người ký admin
            'customer_id'    => $_POST['customer_id'],   // chọn từ booking
            'status'         => $_POST['status'],
            'file_name'      => $_FILES['file_upload']['name'],
            'file_url'       => $fileUrl,
            'created_by'     => $_SESSION['currentUser']['id']
        ];

        $this->contractModel->create($data);
        Message::set('success', 'Tạo hợp đồng thành công!');
        redirect("booking-detail&id=$bookingId&tab=contracts");
    }

    // Form sửa hợp đồng
    public function edit()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) redirect('bookings');

        $contract = $this->contractModel->getById($id);
        $bookingId = $contract['booking_id'];

        // Lấy danh sách khách hàng của booking để chọn
        $bookingCustomers = $this->bookingModel->getCustomers($bookingId);

        require "./views/admin/contracts/edit.php";
    }

    // Xử lý cập nhật hợp đồng
    public function update()
    {
        $id = $_POST['id'];
        $old = $this->contractModel->getById($id);

        $file_name = $old['file_name'];
        $file_url = $old['file_url'];

        if (!empty($_FILES['file_upload']['name'])) {
            $uploadDir = __DIR__ . '/../../uploads/contracts/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $ext = pathinfo($_FILES['file_upload']['name'], PATHINFO_EXTENSION);
            $newName = uniqid() . "." . $ext;

            // xóa file cũ nếu có
            if (!empty($old['file_name'])) {
                $oldPath = $uploadDir . $old['file_name'];
                if (file_exists($oldPath)) unlink($oldPath);
            }

            move_uploaded_file($_FILES['file_upload']['tmp_name'], $uploadDir . $newName);

            $file_name = $newName;
            $file_url = BASE_URL . "uploads/contracts/" . $newName;
        }

        $data = [
            'contract_name'  => $_POST['contract_name'],
            'effective_date' => $_POST['effective_date'],
            'expiry_date'    => $_POST['expiry_date'],
            'signer_id'      => $_POST['signer_id'],
            'customer_id'    => $_POST['customer_id'],
            'status'         => $_POST['status'],
            'file_name'      => $file_name,
            'file_url'       => $file_url,
            'updated_by'     => $_SESSION['currentUser']['id']
        ];

        $this->contractModel->update($id, $data);

        redirect("booking-detail&id={$old['booking_id']}&tab=contracts");
    }

    // Chi tiết hợp đồng
    public function detail()
    {
        $this->checkAndAutoUpdateStatus();
        $id = $_GET['id'] ?? null;
        if (!$id) die("Thiếu id hợp đồng");

        // Lấy hợp đồng kèm tên admin và khách hàng
        $contract = $this->contractModel->findById($id);
        if (!$contract) die("Không tìm thấy hợp đồng");

        // Lấy danh sách khách hàng của booking để chọn trong edit (nếu cần)
        $bookingCustomers = $this->bookingModel->getCustomers($contract['booking_id']);
        $booking_id = $contract['booking_id'];
        require_once './views/admin/contracts/detail.php';
    }

    // Xóa hợp đồng
    public function delete()
    {
        $id = $_GET['id'];
        $contract = $this->contractModel->getById($id);

        // Xóa file vật lý
        if (!empty($contract['file_name'])) {
            $filePath = __DIR__ . '/../../uploads/contracts/' . $contract['file_name'];
            if (file_exists($filePath)) unlink($filePath);
        }

        $this->contractModel->delete($id);

        redirect("booking-detail&id={$contract['booking_id']}&tab=contracts");
    }
}
