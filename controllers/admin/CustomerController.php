<?php
class CustomerController
{
    public $model;
    public function __construct()
    {
        requireAdmin();
        $this->model = new CustomerModel();
    }
    //list danh sách khách hàng
    public function index()
    {
        // Lấy dữ liệu filter từ URL
        $search = $_GET['search'] ?? '';
        // $email = $_GET['email'] ?? '';


        // Gọi model để lọc
        $listCustomers = $this->model->filter(
            $search,
            // $email
        );

        $customers = $this->model->getAll();
        require_once "./views/admin/customers/index.php";
    }
    public function detail()
    {
        $id = $_GET['id'];
        $customer = $this->model->getByID($id);
        require_once "./views/admin/customers/detail.php";
    }
    public function delete()
    {
        $id = $_GET['id'];
        $this->model->delete($id);
        redirect("customers");
        Message::set("success", "Xóa thành công!");
        die();
    }
    public function edit()
    {
        $id = $_GET['id'];
        $customer = $this->model->getByID($id);
        require_once "./views/admin/customers/edit.php";
    }
    // Thêm mới khách hàng
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Lấy dữ liệu từ form
            $name = trim($_POST['name']);
            $email = trim($_POST['email']);
            $phone = trim($_POST['phone']);
            $address = trim($_POST['address']);
            $created_by = $_SESSION['currentUser']['id'] ?? 1; // Lấy ID người tạo từ session
            $passport = trim($_POST['passport']);
            $gender = trim($_POST['gender']);
            $citizen_id = trim($_POST['citizen_id']); // Lấy CCCD

            // Validate dữ liệu
            if (empty($name) || empty($email) || empty($phone) || empty($address)) {
                $err = "Vui lòng nhập đầy đủ thông tin bắt buộc.";
                require_once "./views/admin/customers/create.php";
                return;
            } else {
                // Gọi model để thêm mới
                $this->model->create($name, $email, $phone, $address, $created_by, $passport, $gender, $citizen_id);
                Message::set("success", "Thêm khách hàng thành công!");
                redirect("customers");
                die();
            }
        } else {
            // Hiển thị form thêm mới
            require_once "./views/admin/customers/create.php";
        }
    }
    // Cập nhật thông tin khách hàng
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Lấy dữ liệu từ form
            $id = $_GET['id'];
            $name = trim($_POST['name']);
            $email = trim($_POST['email']);
            $phone = trim($_POST['phone']);
            $address = trim($_POST['address']);
            $address = trim($_POST['address']);
            $updated_by = $_SESSION['currentUser']['id'] ?? 1;
            $gender = trim($_POST['gender']);
            $passport = trim($_POST['passport']);
            $citizen_id = trim($_POST['citizen_id']); // Lấy CCCD

            // Validate dữ liệu
            if (empty($name) || empty($email) || empty($phone) || empty($address)) {
                $err = "Vui lòng nhập đầy đủ thông tin bắt buộc.";
                // Cần lấy lại thông tin khách hàng để hiển thị lại form edit nếu lỗi
                $customer = $this->model->getByID($id);
                require_once "./views/admin/customers/edit.php";
                return;
            } else {
                // Gọi model để cập nhật
                $this->model->update($id, $name, $email, $phone, $address, $updated_by, $gender, $passport, $citizen_id);
                Message::set("success", "Cập nhật khách hàng thành công!");
                redirect("customers");
                die();
            }
        }
    }

    // Export danh sách khách hàng ra Excel
    public function exportCustomers()
    {
        $customers = $this->model->getAll();

        require_once './lib/SimpleXLSXGen.php';

        $data = [
            ['STT', 'Họ tên', 'Email', 'Số điện thoại', 'Địa chỉ', 'Giới tính', 'Hộ chiếu', 'CCCD']
        ];

        $i = 1;
        foreach ($customers as $c) {
            $gender = 'Khác';
            if ($c['gender'] == 'male') $gender = 'Nam';
            elseif ($c['gender'] == 'female') $gender = 'Nữ';

            $data[] = [
                $i++,
                $c['name'],
                $c['email'],
                $c['phone'],
                $c['address'],
                $gender,
                $c['passport'] ?? '',
                $c['citizen_id'] ?? ''
            ];
        }

        $filename = 'Danh_sach_khach_hang_' . date('Y-m-d_H-i-s') . '.xlsx';
        \Shuchkin\SimpleXLSXGen::fromArray($data)->downloadAs($filename);
        exit;
    }

    // Import khách hàng từ file Excel
    public function importCustomers()
    {
        if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
            $file = $_FILES['file']['tmp_name'];

            // Check file extension
            $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
            if (!in_array(strtolower($ext), ['xlsx', 'xls'])) {
                Message::set('errors', 'Vui lòng chọn file Excel (.xlsx, .xls).');
                header("Location:" . BASE_URL . '?act=customers');
                exit;
            }

            require_once './lib/SimpleXLSX.php';

            if ($xlsx = \Shuchkin\SimpleXLSX::parse($file)) {
                $count = 0;
                $skipped = 0;
                $rows = $xlsx->rows();

                // Loop through rows (skip header)
                foreach ($rows as $index => $row) {
                    if ($index == 0) continue; // Skip header row

                    // Excel Format: STT, Họ tên, Email, Số điện thoại, Địa chỉ, Giới tính, Hộ chiếu
                    // hoặc: Họ tên, Email, Số điện thoại, Địa chỉ, Giới tính, Hộ chiếu
                    $name = trim($row[1] ?? $row[0] ?? '');
                    $email = trim($row[2] ?? $row[1] ?? '');
                    $phone = trim($row[3] ?? $row[2] ?? '');
                    $address = trim($row[4] ?? $row[3] ?? '');
                    $genderText = trim($row[5] ?? $row[4] ?? 'Khác');
                    $passport = trim($row[6] ?? $row[5] ?? '');
                    $citizen_id = trim($row[7] ?? $row[6] ?? '');

                    // Validate required fields
                    if (empty($name) || empty($email) || empty($phone) || empty($address)) {
                        $skipped++;
                        continue;
                    }

                    // Convert gender text to value
                    $gender = 'other';
                    if (mb_strtolower($genderText) == 'nam' || mb_strtolower($genderText) == 'male') {
                        $gender = 'male';
                    } elseif (mb_strtolower($genderText) == 'nữ' || mb_strtolower($genderText) == 'female') {
                        $gender = 'female';
                    }

                    // Check if customer already exists
                    $existingCustomer = $this->model->findByEmailOrPhone($email, $phone);

                    if ($existingCustomer) {
                        $skipped++;
                        continue;
                    }

                    // Create new customer
                    $created_by = $_SESSION['currentUser']['id'];
                    $this->model->create($name, $email, $phone, $address, $created_by, $passport, $gender, $citizen_id);
                    $count++;
                }

                if ($count > 0) {
                    Message::set('success', "Đã thêm $count khách hàng từ file Excel." . ($skipped > 0 ? " Bỏ qua $skipped dòng (trùng lặp hoặc thiếu thông tin)." : ""));
                } else {
                    Message::set('errors', 'Không có khách hàng nào được thêm. ' . ($skipped > 0 ? "Đã bỏ qua $skipped dòng (trùng lặp hoặc thiếu thông tin)." : ""));
                }
            } else {
                Message::set('errors', 'Không thể đọc file Excel: ' . \Shuchkin\SimpleXLSX::parseError());
            }
        } else {
            Message::set('errors', 'Vui lòng chọn file Excel.');
        }

        header("Location:" . BASE_URL . '?act=customers');
        exit;
    }
}
