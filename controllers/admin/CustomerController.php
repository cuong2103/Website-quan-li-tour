<?php
class CustomerController
{
    public $model;
    public function __construct()
    {
        requireAdmin(); // Gọi hàm kiểm tra xem có phải admin không
        $this->model = new CustomerModel(); // Khởi tạo model khách hàng
    }

    //list danh sách khách hàng
    public function index()
    {
        // Lấy dữ liệu tìm kiếm từ URL (nếu không có thì để rỗng)
        $search = $_GET['search'] ?? '';

        // Gọi model để lọc khách hàng
        $listCustomers = $this->model->filter(
            $search,
        );

        // Lấy toàn bộ khách hàng
        $customers = $this->model->getAll();

        // Gọi view hiển thị danh sách
        require_once "./views/admin/customers/index.php";
    }

    public function detail()
    {
        $id = $_GET['id']; // Lấy id khách hàng từ URL
        $customer = $this->model->getByID($id); // Lấy chi tiết khách hàng
        require_once "./views/admin/customers/detail.php";
    }

    public function delete()
    {
        $id = $_GET['id']; // Lấy id từ URL
        $this->model->delete($id); // Xóa khách hàng
        redirect("customers"); // Quay về danh sách
        Message::set("success", "Xóa thành công!"); // Gửi thông báo
        die();
    }

    public function edit()
    {
        $id = $_GET['id']; // Lấy ID
        $customer = $this->model->getByID($id); // Lấy dữ liệu khách hàng để hiển thị lên form
        require_once "./views/admin/customers/edit.php";
    }

    // Thêm mới khách hàng
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Kiểm tra xem có submit form không
            // Lấy dữ liệu từ form
            $data = [
                'name' => trim($_POST['name'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'phone' => trim($_POST['phone'] ?? ''),
                'address' => trim($_POST['address'] ?? ''),
                'passport' => trim($_POST['passport'] ?? ''),
                'gender' => trim($_POST['gender'] ?? 'other'),
                'citizen_id' => trim($_POST['citizen_id'] ?? ''),
            ];
            $created_by = $_SESSION['currentUser']['id'] ?? 1; // Lấy ID người tạo

            // Rules validate dữ liệu
            $rules = [
                'name' => 'required|min:2|max:100',
                'email' => 'required|email|max:100',
                'phone' => 'required|phone',
                'address' => 'required|min:5|max:255',
                'gender' => 'required',
            ];

            // Thực hiện validate
            $errors = validate($data, $rules);

            // Nếu có lỗi → giữ lại dữ liệu + hiện lại form
            if (!empty($errors)) {
                $_SESSION['validate_errors'] = $errors;
                $_SESSION['old'] = $data;
                redirect("customer-create");
                return;
            }

            // Kiểm tra trùng email hoặc số điện thoại
            $existingCustomer = $this->model->findByEmailOrPhone($data['email'], $data['phone']);
            if ($existingCustomer) {
                Message::set('error', 'Email hoặc số điện thoại đã tồn tại!');
                $_SESSION['old'] = $data;
                redirect("customer-create");
                return;
            }

            // Gọi model để thêm khách hàng
            $this->model->create($data['name'], $data['email'], $data['phone'], $data['address'], $created_by, $data['passport'], $data['gender'], $data['citizen_id']);
            Message::set("success", "Thêm khách hàng thành công!");
            redirect("customers");
            die();
        } else {
            // Hiện form thêm mới
            require_once "./views/admin/customers/create.php";
        }
    }

    // Cập nhật thông tin khách hàng
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Kiểm tra submit form
            // Lấy dữ liệu
            $id = $_GET['id'];
            $data = [
                'name' => trim($_POST['name'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'phone' => trim($_POST['phone'] ?? ''),
                'address' => trim($_POST['address'] ?? ''),
                'passport' => trim($_POST['passport'] ?? ''),
                'gender' => trim($_POST['gender'] ?? 'other'),
                'citizen_id' => trim($_POST['citizen_id'] ?? ''),
            ];
            $updated_by = $_SESSION['currentUser']['id'] ?? 1; // ID người cập nhật

            // Rules validate dữ liệu
            $rules = [
                'name' => 'required|min:2|max:100',
                'email' => 'required|email|max:100',
                'phone' => 'required|phone',
                'address' => 'required|min:5|max:255',
                'gender' => 'required',
            ];

            // Thực hiện validate
            $errors = validate($data, $rules);

            // Nếu có lỗi → giữ lại dữ liệu + hiện lại form
            if (!empty($errors)) {
                $_SESSION['validate_errors'] = $errors;
                $_SESSION['old'] = $data;
                $customer = array_merge($this->model->getByID($id), $data); // Merge để giữ giá trị mới nhập
                require_once "./views/admin/customers/edit.php";
                return;
            }

            // Kiểm tra trùng email hoặc số điện thoại (loại trừ khách hàng hiện tại)
            $existingCustomer = $this->model->findByEmailOrPhone($data['email'], $data['phone']);
            if ($existingCustomer && $existingCustomer['id'] != $id) {
                Message::set('error', 'Email hoặc số điện thoại đã tồn tại!');
                $_SESSION['old'] = $data;
                $customer = array_merge($this->model->getByID($id), $data);
                require_once "./views/admin/customers/edit.php";
                return;
            }

            // Gọi model update
            $this->model->update($id, $data['name'], $data['email'], $data['phone'], $data['address'], $updated_by, $data['gender'], $data['passport'], $data['citizen_id']);
            Message::set("success", "Cập nhật khách hàng thành công!");
            redirect("customers");
            die();
        }
    }

    // Export danh sách khách hàng ra Excel
    public function exportCustomers()
    {
        // Lấy toàn bộ khách hàng
        $customers = $this->model->getAll();

        require_once './lib/SimpleXLSXGen.php'; // Thư viện tạo file Excel

        // Tạo dòng tiêu đề
        $data = [
            ['STT', 'Họ tên', 'Email', 'Số điện thoại', 'Địa chỉ', 'Giới tính', 'Hộ chiếu', 'CCCD']
        ];

        $i = 1;
        foreach ($customers as $c) {
            // Chuyển giới tính sang tiếng Việt
            $gender = 'Khác';
            if ($c['gender'] == 'male') $gender = 'Nam';
            elseif ($c['gender'] == 'female') $gender = 'Nữ';

            // Thêm từng dòng vào Excel
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

        // Tạo tên file
        $filename = 'Danh_sach_khach_hang_' . date('Y-m-d_H-i-s') . '.xlsx';

        // Xuất file Excel
        \Shuchkin\SimpleXLSXGen::fromArray($data)->downloadAs($filename);
        exit;
    }

    public function exportTemplate()
    {
        require_once './lib/SimpleXLSXGen.php'; // Thư viện tạo file Excel

        // Tạo dòng tiêu đề
        $data = [
            ['STT', 'Họ tên', 'SĐT', 'Email', 'Địa chỉ', 'Giới tính', 'Hộ chiếu(nếu có)', 'CCCD']
        ];

        // Tạo tên file
        $filename = 'Template_khach_hang.xlsx';

        // Xuất file Excel
        \Shuchkin\SimpleXLSXGen::fromArray($data)->downloadAs($filename);
        exit;
    }

    // Import khách hàng từ file Excel
    public function importCustomers()
    {
        // Kiểm tra file upload
        if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
            $file = $_FILES['file']['tmp_name'];

            // Kiểm tra phần mở rộng file
            $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
            if (!in_array(strtolower($ext), ['xlsx', 'xls'])) {
                Message::set('error', 'Vui lòng chọn file Excel (.xlsx, .xls).');
                header("Location:" . BASE_URL . '?act=customers');
                exit;
            }

            require_once './lib/SimpleXLSX.php'; // Thư viện đọc Excel

            if ($xlsx = \Shuchkin\SimpleXLSX::parse($file)) {
                $count = 0;   // Số dòng được thêm
                $skipped = 0; // Số dòng bị bỏ qua
                $rows = $xlsx->rows(); // Lấy toàn bộ dòng trong file

                // Lặp qua từng dòng
                foreach ($rows as $index => $row) {
                    if ($index == 0) continue; // Bỏ dòng tiêu đề

                    // Lấy dữ liệu từng ô trong Excel
                    $name = trim($row[1] ?? '');
                    $phone = trim($row[2] ?? '');
                    $email = trim($row[3] ?? '');
                    $genderText = trim($row[4] ?? 'Khác');
                    $address = trim($row[5] ?? '');
                    $citizenId = trim($row[6] ?? '');
                    $passport = trim($row[7] ?? '');

                    // Kiểm tra dữ liệu bắt buộc
                    if (empty($name) || empty($email) || empty($phone) || empty($address)) {
                        $skipped++;
                        continue;
                    }

                    // Chuyển text giới tính sang dạng lưu DB
                    $gender = 'other';
                    if (mb_strtolower($genderText) == 'nam' || mb_strtolower($genderText) == 'male') {
                        $gender = 'male';
                    } elseif (mb_strtolower($genderText) == 'nữ' || mb_strtolower($genderText) == 'female') {
                        $gender = 'female';
                    }

                    // Kiểm tra trùng email hoặc số điện thoại
                    $existingCustomer = $this->model->findByEmailOrPhone($email, $phone);

                    if ($existingCustomer) {
                        $skipped++;
                        continue;
                    }

                    // Thêm khách hàng mới
                    $created_by = $_SESSION['currentUser']['id'];
                    $this->model->create($name, $email, $phone, $address, $created_by, $passport, $gender, $citizenId);
                    $count++;
                }

                // Thông báo sau khi import
                if ($count > 0) {
                    Message::set('success', "Đã thêm $count khách hàng từ file Excel." . ($skipped > 0 ? " Bỏ qua $skipped dòng (trùng lặp hoặc thiếu thông tin)." : ""));
                } else {
                    Message::set('error', 'Không có khách hàng nào được thêm. ' . ($skipped > 0 ? "Đã bỏ qua $skipped dòng (trùng lặp hoặc thiếu thông tin)." : ""));
                }
            } else {
                Message::set('error', 'Không thể đọc file Excel: ' . \Shuchkin\SimpleXLSX::parseError());
            }
        } else {
            Message::set('error', 'Vui lòng chọn file Excel.');
        }

        // Quay về danh sách
        redirect('customers');
        exit;
    }
}
