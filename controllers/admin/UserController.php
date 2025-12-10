<?php
class UserController
{

    public $userModel;

    public function __construct()
    {
        requireAdmin();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        // Lấy tham số tìm kiếm và lọc
        $search = $_GET['search'] ?? '';
        $role = $_GET['role'] ?? '';

        // Lấy danh sách users với filter
        $users = $this->userModel->getAll($search, $role);

        require './views/admin/Users/index.php';
    }

    public function detail()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) redirect("user");

        $user = $this->userModel->getById($id);

        if (!$user) redirect("user");
        require './views/admin/Users/detail.php';
    }

    public function create()
    {

        require './views/admin/Users/create.php';
    }

    public function edit()
    {
        $id = $_GET['id'];
        if (!$id) redirect("user"); // chuyển về danh sách nếu không có id
        $user = $this->userModel->getById($id);

        if (!$user) {
            Message::set('error', 'Không tìm thấy nhân viên');
            redirect("user"); // chuyển về danh sách nếu id không tồn tại
        }
        require_once './views/admin/Users/edit.php'; // truyền $user sang view
    }

    public function store()
    {
        $data = [
            'fullname'    => trim($_POST['fullname'] ?? ''),
            'email'       => trim($_POST['email'] ?? ''),
            'phone'       => trim($_POST['phone'] ?? ''),
            'roles'       => $_POST['roles'] ?? 'guide',
            'status'      => isset($_POST['status']) ? (int)$_POST['status'] : 1,
            'password'    => !empty($_POST['password'])
                ? password_hash($_POST['password'], PASSWORD_DEFAULT)
                : password_hash('123456', PASSWORD_DEFAULT),
            'created_by'  => $_SESSION['currentUser']['id'] ?? null,
            'updated_by'  => $_SESSION['currentUser']['id'] ?? null,
        ];

        // Validate dữ liệu
        $rules = [
            'fullname' => 'required|min:2|max:100',
            'email'    => 'required|email|max:100',
            'phone'    => 'required|phone',
            'roles'    => 'required',
        ];

        $errors = validate($data, $rules);

        // Check email tồn tại
        if ($this->userModel->emailExists($data['email'])) {
            $errors['email'] = 'Email đã tồn tại trong hệ thống';
        }

        if (!empty($errors)) {
            $_SESSION['validate_errors'] = $errors;
            $_SESSION['old'] = $data;
            redirect('user-create');
            exit;
        }

        // --- Upload avatar trước khi lưu ---
        if (!empty($_FILES['avatar']['name']) && $_FILES['avatar']['error'] == 0) {
            $avatar = $_FILES['avatar'];
            $extention = pathinfo($avatar['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . "." . $extention;
            $uploadDir = __DIR__ . '/../../uploads/avatar/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $uploadPath = $uploadDir . $filename;
            if (move_uploaded_file($avatar['tmp_name'], $uploadPath)) {
                $data['avatar'] = $filename; // lưu tên file vào $data
            }
        }

        $result = $this->userModel->create($data);

        if ($result) {
            Message::set('success', 'Tạo nhân viên thành công');
        } else {
            Message::set('error', 'Tạo nhân viên thất bại');
        }

        redirect("user");
    }


    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect("user");

        $id = $_POST['id'];
        $fullname = trim($_POST['fullname']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        $roles = $_POST['roles'] ?? 'guide';
        $status = ($_POST['status']);

        // Check email tồn tại
        if ($this->userModel->emailExists($email, $id)) {
            Message::set('error', 'Email đã tồn tại');
            redirect("user-edit&id=$id");
        }

        // Lấy thông tin hiện tại để check nghỉ phép
        $currentUser = $this->userModel->getById($id);

        // Logic: Nếu đang trong thời gian nghỉ phép thì KHÔNG ĐƯỢC set status = 1 (Hoạt động)
        // Logic: Nếu đang trong thời gian nghỉ phép thì KHÔNG ĐƯỢC set status = 1 (Hoạt động)
        $isOnLeave = false;
        if (!empty($currentUser['leave_start']) && !empty($currentUser['leave_end'])) {
            $today = new DateTime();
            $today->setTime(0, 0, 0);
            $start = new DateTime($currentUser['leave_start']);
            $start->setTime(0, 0, 0);
            $end = new DateTime($currentUser['leave_end']);
            $end->setTime(0, 0, 0);

            if ($today >= $start && $today <= $end) {
                $isOnLeave = true;
            }
        }

        if ($isOnLeave && $status == 1) {
            $status = 0; // Force về tạm dừng
            Message::set('warning', 'Nhân viên đang trong thời gian nghỉ phép, trạng thái được giữ là "Tạm dừng"');
        }

        // --- Xử lý avatar nếu có upload mới ---
        $data = [
            'fullname' => $fullname,
            'email' => $email,
            'phone' => $phone,
            'roles' => $roles,
            'status' => $status,
            'updated_by' => $_SESSION['currentUser']['id'] ?? null,
        ];

        if (!empty($_POST['password'])) {
            $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        }

        // Lấy avatar cũ từ DB (đã lấy ở trên)

        if (!empty($_FILES['avatar']['name']) && $_FILES['avatar']['error'] == 0) {
            $avatar = $_FILES['avatar'];
            $extention = pathinfo($avatar['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . "." . $extention;
            $uploadDir = __DIR__ . '/../../uploads/avatar/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            $uploadPath = $uploadDir . $filename;
            if (move_uploaded_file($avatar['tmp_name'], $uploadPath)) {
                $data['avatar'] = $filename; // dùng avatar mới
            }
        } else {
            $data['avatar'] = $currentUser['avatar']; // giữ avatar cũ
        }


        $result = $this->userModel->update($id, $data);

        if ($result) {
            // Chỉ set success nếu chưa có warning (trường hợp bị force status)
            if (!isset($_SESSION['flash_message']) || $_SESSION['flash_message']['type'] !== 'warning') {
                Message::set('success', 'Cập nhật thành công');
            }
        } else {
            Message::set('error', 'Cập nhật thất bại');
        }

        redirect("user");
    }

    public function delete()
    {
        $id = $_GET['id'] ?? null;
        if ($id === $_SESSION['currentUser']['id']) {
            Message::set('error', 'Không thể xóa chính bạn');
            redirect("user");
        }

        // Check active assignments
        $tourAssignmentModel = new TourAssignmentModel();
        $activeAssignments = $tourAssignmentModel->getAssignmentsByGuide($id);
        $hasActiveBooking = false;
        $today = new DateTime();
        $today->setTime(0, 0, 0);

        foreach ($activeAssignments as $assignment) {
            $endDate = new DateTime($assignment['end_date']);
            $endDate->setTime(0, 0, 0);
            if ($endDate >= $today) {
                $hasActiveBooking = true;
                break;
            }
        }

        if ($hasActiveBooking) {
            Message::set('error', 'Không thể xóa hướng dẫn viên này vì đang có lịch tour chưa kết thúc!');
            redirect("user");
        }

        if ($id) {
            $result = $this->userModel->delete($id);
            if ($result === "FOREIGN_KEY_CONSTRAINT") {
                Message::set('error', 'Không thể xóa nhân viên này vì có dữ liệu liên quan!');
            } elseif ($result) {
                Message::set('success', 'Xóa nhân viên thành công!');
            } else {
                Message::set('error', 'Xóa nhân viên thất bại!');
            }

            redirect("user");
            exit;
        } else {
            echo "ID nhân viên không hợp lệ!";
        }
    }

    // CẬP NHẬT THÔNG TIN NGHỈ PHÉP
    public function updateLeave()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect("user");
        }

        $id = $_POST['id'] ?? null;
        $leave_start = !empty($_POST['leave_start']) ? $_POST['leave_start'] : null;
        $leave_end = !empty($_POST['leave_end']) ? $_POST['leave_end'] : null;

        if (!$id) {
            Message::set('error', 'ID không hợp lệ');
            redirect("user");
        }

        // Trường hợp xóa nghỉ phép (cả 2 trường trống)
        if (empty($leave_start) && empty($leave_end)) {
            $sql = "UPDATE users SET leave_start = NULL, leave_end = NULL, leave_status = NULL, leave_reason = NULL, status = 1, updated_by = :updated_by, updated_at = NOW() WHERE id = :id";
            $stmt = $this->userModel->conn->prepare($sql);
            $result = $stmt->execute([
                ':id' => $id,
                ':updated_by' => $_SESSION['currentUser']['id'] ?? null,
            ]);

            if ($result) {
                Message::set('success', "Đã xóa thông tin nghỉ phép và chuyển trạng thái sang Hoạt động");
            } else {
                Message::set('error', "Cập nhật thất bại");
            }
            redirect("user-detail&id=$id");
            return;
        }

        // Validate: Nếu có ngày bắt đầu thì phải có ngày kết thúc và ngược lại
        if (($leave_start && !$leave_end) || (!$leave_start && $leave_end)) {
            Message::set('error', 'Vui lòng nhập đầy đủ ngày bắt đầu và kết thúc nghỉ phép');
            redirect("user-detail&id=$id");
        }

        // Validate: Ngày kết thúc phải sau ngày bắt đầu
        if ($leave_start && $leave_end && strtotime($leave_end) < strtotime($leave_start)) {
            Message::set('error', 'Ngày kết thúc phải sau ngày bắt đầu');
            redirect("user-detail&id=$id");
        }

        // Tự động cập nhật status: Nếu đang nghỉ phép thì status = 0
        $status = 0;

        // Update leave fields và status
        $sql = "UPDATE users SET leave_start = :leave_start, leave_end = :leave_end, status = :status, updated_by = :updated_by, updated_at = NOW() WHERE id = :id";
        $stmt = $this->userModel->conn->prepare($sql);
        $result = $stmt->execute([
            ':id' => $id,
            ':leave_start' => $leave_start,
            ':leave_end' => $leave_end,
            ':status' => $status,
            ':updated_by' => $_SESSION['currentUser']['id'] ?? null,
        ]);

        if ($result) {
            Message::set('success', "Đã cập nhật thông tin nghỉ phép và chuyển trạng thái sang Tạm dừng");
        } else {
            Message::set('error', "Cập nhật thất bại");
        }

        redirect("user-detail&id=$id");
    }

    // DANH SÁCH HDV ĐANG NGHỈ PHÉP
    public function onLeave()
    {
        $users = $this->userModel->getOnLeave();
        require './views/admin/Users/on_leave.php';
    }

    // KẾT THÚC NGHỈ PHÉP
    public function endLeave()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            Message::set('error', 'ID không hợp lệ');
            redirect("user-on-leave");
        }

        // Xóa thông tin nghỉ phép và chuyển status về hoạt động
        $sql = "UPDATE users SET leave_start = NULL, leave_end = NULL, leave_status = NULL, leave_reason = NULL, status = 1, updated_by = :updated_by, updated_at = NOW() WHERE id = :id";
        $stmt = $this->userModel->conn->prepare($sql);
        $result = $stmt->execute([
            ':id' => $id,
            ':updated_by' => $_SESSION['currentUser']['id'] ?? null,
        ]);

        if ($result) {
            Message::set('success', 'Đã kết thúc nghỉ phép và chuyển trạng thái sang Hoạt động');
        } else {
            Message::set('error', 'Kết thúc nghỉ phép thất bại');
        }

        redirect("user-on-leave");
    }

    public function leaveRequests()
    {
        $requests = $this->userModel->getPendingLeaveRequests();
        require './views/admin/users/leave_requests.php';
    }
    // DUYỆT ĐƠN XIN NGHỈ
    public function approveLeave()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            Message::set('error', 'ID không hợp lệ');
            redirect('user-leave-requests');
            exit;
        }
        $user = $this->userModel->getById($id);
        if (!$user) {
            Message::set('error', 'Không tìm thấy nhân viên');
            redirect('user-leave-requests');
            exit;
        }
        if ($this->userModel->approveLeaveRequest($id, $_SESSION['currentUser']['id'])) {
            // Gửi notification cho HDV
            $notificationModel = new NotificationModel();
            $notifId = $notificationModel->create([
                'title' => 'Đơn xin nghỉ đã được duyệt',
                'message' => 'Đơn xin nghỉ của bạn từ ' . date('d/m/Y', strtotime($user['leave_start'])) . ' đến ' . date('d/m/Y', strtotime($user['leave_end'])) . ' đã được duyệt.',
                'type' => 'general',
                'created_by' => $_SESSION['currentUser']['id']
            ]);
            $notificationModel->addRecipients($notifId, [$id]);
            Message::set('success', 'Đã duyệt đơn xin nghỉ!');
        } else {
            Message::set('error', 'Duyệt đơn thất bại!');
        }
        redirect('user-leave-requests');
        exit;
    }
    // TỪ CHỐI ĐƠN XIN NGHỈ
    public function rejectLeave()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            Message::set('error', 'ID không hợp lệ');
            redirect('user-leave-requests');
            exit;
        }
        $user = $this->userModel->getById($id);
        if (!$user) {
            Message::set('error', 'Không tìm thấy nhân viên');
            redirect('user-leave-requests');
            exit;
        }
        if ($this->userModel->rejectLeaveRequest($id, $_SESSION['currentUser']['id'])) {
            // Gửi notification cho HDV
            $notificationModel = new NotificationModel();
            $notifId = $notificationModel->create([
                'title' => 'Đơn xin nghỉ bị từ chối',
                'message' => 'Đơn xin nghỉ của bạn từ ' . date('d/m/Y', strtotime($user['leave_start'])) . ' đến ' . date('d/m/Y', strtotime($user['leave_end'])) . ' đã bị từ chối. Vui lòng liên hệ admin để biết thêm chi tiết.',
                'type' => 'general',
                'created_by' => $_SESSION['currentUser']['id']
            ]);
            $notificationModel->addRecipients($notifId, [$id]);
            Message::set('success', 'Đã từ chối đơn xin nghỉ!');
        } else {
            Message::set('error', 'Từ chối đơn thất bại!');
        }
        redirect('user-leave-requests');
        exit;
    }
}
