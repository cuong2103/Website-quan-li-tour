<?php
class ContractController
{
    public $contractModel;

    public function __construct()
    {
        $this->contractModel = new ContractModel();
    }

    // Danh sách
    public function index()
    {
        $contracts = $this->contractModel->getAll();
        require_once "./views/admin/contracts/index.php";
    }

    // Form tạo
    public function create()
    {
        $booking_id = $_GET['booking_id'] ?? null;
        require_once "./views/admin/contracts/create.php";
    }

    // Lưu mới
    public function store()
    {
        $file_name = null;
        $file_url = null;

        if (!empty($_FILES['file_upload']['name'])) {
            $uploadDir = __DIR__ . '/../../uploads/contracts/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $ext = pathinfo($_FILES['file_upload']['name'], PATHINFO_EXTENSION);
            $file_name = uniqid() . "." . $ext;
            $filePath = $uploadDir . $file_name;

            move_uploaded_file($_FILES['file_upload']['tmp_name'], $filePath);

            $file_url = BASE_URL . "uploads/contracts/" . $file_name;
        }

        $data = [
            'booking_id'     => $_POST['booking_id'],
            'contract_name'  => $_POST['contract_name'],
            'signing_date'   => $_POST['signing_date'],
            'effective_date' => $_POST['effective_date'],
            'expiry_date'    => $_POST['expiry_date'],
            'signer_id'      => $_POST['signer_id'],
            'customer_id'    => $_POST['customer_id'],
            'status'         => 'active',
            'file_name'      => $file_name,
            'file_url'       => $file_url,
            'notes'          => $_POST['notes'] ?? null
        ];

        $this->contractModel->create($data);

        header("Location: " . BASE_URL . "?act=booking-detail&id={$data['booking_id']}&tab=contracts");
        exit;
    }

    // Form sửa
    public function edit()
    {
        $id = $_GET['id'] ?? null;
        $contract = $this->contractModel->getById($id);

        require "./views/admin/contracts/edit.php";
    }

    // Cập nhật
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
            'signing_date'   => $_POST['signing_date'],
            'effective_date' => $_POST['effective_date'],
            'expiry_date'    => $_POST['expiry_date'],
            'signer_id'      => $_POST['signer_id'],
            'customer_id'    => $_POST['customer_id'],
            'status'         => $_POST['status'],
            'file_name'      => $file_name,
            'file_url'       => $file_url,
            'notes'          => $_POST['notes']
        ];

        $this->contractModel->update($id, $data);

        header("Location: " . BASE_URL . "?act=booking-detail&id={$old['booking_id']}&tab=contracts");
        exit;
    }

    public function detail()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            die("Thiếu id hợp đồng");
        }

        $contract = $this->contractModel->findById($id);

        if (!$contract) {
            die("Không tìm thấy hợp đồng");
        }

        require_once './views/admin/contracts/detail.php';
    }


    // Xóa
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

        header("Location: " . BASE_URL . "?act=booking-detail&id={$contract['booking_id']}&tab=contracts");
        exit;
    }
}
