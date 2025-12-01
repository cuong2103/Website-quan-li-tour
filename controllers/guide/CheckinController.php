<?php
class CheckinController
{
  public $checkinModel;
  public $assignmentModel;

  public function __construct()
  {
    // Có thể requireAdmin() hoặc requireGuide() tùy yêu cầu
    $this->checkinModel = new CheckinModel();
    $this->assignmentModel = new TourAssignmentModel();
  }

  // Hiển thị trang check-in cho guide
  public function index()
  {
    $assignmentId = $_GET['assignment_id'] ?? null;

    if (!$assignmentId) {
      Message::set('error', 'Không tìm thấy tour assignment');
      redirect('my-schedule');
      exit;
    }

    // Lấy thông tin assignment
    $assignment = $this->assignmentModel->getAssignmentById($assignmentId);

    if (!$assignment) {
      Message::set('error', 'Không tìm thấy thông tin tour');
      redirect('my-schedule');
      exit;
    }

    // Lấy danh sách khách hàng
    $customers = $this->checkinModel->getCustomersByAssignment($assignmentId);

    // Lấy lịch sử check-in
    $checkinHistory = $this->checkinModel->getCheckinHistory($assignmentId);

    // Thống kê
    $stats = $this->checkinModel->getCheckinStats($assignmentId);

    require_once './views/guide/checkin/index.php';
  }

  // Xử lý check-in
  public function checkin()
  {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      redirect('checkin');
      exit;
    }

    $assignmentId = $_POST['assignment_id'];
    $customerId = $_POST['customer_id'];

    // Kiểm tra đã check-in chưa
    if ($this->checkinModel->hasCheckedIn($assignmentId, $customerId)) {
      Message::set('error', 'Khách hàng này đã được điểm danh!');
      redirect("checkin&assignment_id=" . $assignmentId);
      exit;
    }

    // Upload ảnh (nếu có)
    $imageUrl = null;
    if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === 0) {
      $uploadDir = __DIR__ . '/../../uploads/checkins/';
      if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
      }

      $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
      $fileName = uniqid() . '_' . time() . '.' . $ext;
      $uploadPath = $uploadDir . $fileName;

      if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
        $imageUrl = $fileName;
      }
    }

    // Tạo check-in
    $data = [
      'tour_assignment_id' => $assignmentId,
      'customer_id' => $customerId,
      'notes' => $_POST['notes'] ?? null,
      'image_url' => $imageUrl,
      'checkin_time' => date('Y-m-d H:i:s'),
      'location' => $_POST['location'] ?? null,
      'created_by' => $_SESSION['currentUser']['id']
    ];

    if ($this->checkinModel->createCheckin($data)) {
      Message::set('success', 'Điểm danh thành công!');
    } else {
      Message::set('error', 'Điểm danh thất bại!');
    }

    redirect("checkin&assignment_id=" . $assignmentId);
  }

  // Xem chi tiết check-in
  public function detail()
  {
    $checkinId = $_GET['id'] ?? null;

    if (!$checkinId) {
      Message::set('error', 'Không tìm thấy thông tin check-in');
      redirect('my-schedule');
      exit;
    }

    $checkin = $this->checkinModel->getCheckinDetail($checkinId);

    if (!$checkin) {
      Message::set('error', 'Không tìm thấy thông tin check-in');
      redirect('my-schedule');
      exit;
    }

    require_once './views/guide/checkin/detail.php';
  }

  // Xóa check-in
  public function delete()
  {
    $checkinId = $_GET['id'] ?? null;
    $assignmentId = $_GET['assignment_id'] ?? null;

    if ($checkinId && $this->checkinModel->deleteCheckin($checkinId)) {
      Message::set('success', 'Xóa check-in thành công!');
    } else {
      Message::set('error', 'Xóa check-in thất bại!');
    }

    redirect("checkin&assignment_id=" . $assignmentId);
  }

  // Check-in hàng loạt
  public function bulkCheckin()
  {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      redirect('checkin');
      exit;
    }

    $assignmentId = $_POST['assignment_id'];
    $customerIds = $_POST['customer_ids'] ?? [];

    if (empty($customerIds)) {
      Message::set('error', 'Vui lòng chọn khách hàng!');
      redirect("checkin&assignment_id=" . $assignmentId);
      exit;
    }

    $success = 0;
    $failed = 0;

    foreach ($customerIds as $customerId) {
      // Kiểm tra đã check-in chưa
      if ($this->checkinModel->hasCheckedIn($assignmentId, $customerId)) {
        $failed++;
        continue;
      }

      $data = [
        'tour_assignment_id' => $assignmentId,
        'customer_id' => $customerId,
        'notes' => 'Check-in hàng loạt',
        'image_url' => null,
        'checkin_time' => date('Y-m-d H:i:s'),
        'location' => $_POST['location'] ?? null,
        'created_by' => $_SESSION['currentUser']['id']
      ];

      if ($this->checkinModel->createCheckin($data)) {
        $success++;
      } else {
        $failed++;
      }
    }

    if ($success > 0) {
      Message::set('success', "Điểm danh thành công {$success} khách hàng!");
    }
    if ($failed > 0) {
      Message::set('error', "{$failed} khách hàng đã được điểm danh hoặc lỗi!");
    }

    redirect("checkin&assignment_id=" . $assignmentId);
  }
}
