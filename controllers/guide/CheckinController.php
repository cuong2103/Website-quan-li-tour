<?php
class CheckinController
{
  public $checkinModel;
  public $bookingModel;

  public function __construct()
  {
    $this->checkinModel = new CheckinModel();
    $this->bookingModel = new BookingModel();
  }

  // Hiển thị chi tiết đợt check-in và danh sách khách
  public function detail()
  {
    $linkId = $_GET['link_id'] ?? null;
    $assignmentId = $_GET['assignment_id'] ?? null;
    $customers = $this->checkinModel->getCustomersWithCheckinStatus($assignmentId, $linkId);
    $checkinLink = $this->checkinModel->getCheckinLink($linkId, $assignmentId);
    if (!$linkId || !$assignmentId) {
      Message::set('error', 'Thiếu thông tin');
      redirect('my-schedule');
      exit;
    }
    if (!$checkinLink) {
      Message::set('error', 'Không tìm thấy đợt check-in');
      redirect('my-schedule');
      exit;
    }
    require './views/guide/checkin/detail.php';
  }

  // Tạo đợt check-in mới
  public function create()
  {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      header('Location: ' . BASE_URL . '?act=guide-tour-assignments');
      exit;
    }

    $assignmentId = $_POST['assignment_id'] ?? null;
    $title = trim($_POST['title'] ?? '');
    $note = trim($_POST['note'] ?? '');

    if (!$assignmentId || empty($title)) {
      Message::set('error', 'Vui lòng nhập đầy đủ thông tin');
      redirect('my-schedule');
      exit;
    }
    // dd($assignmentId);
    // Kiểm tra quyền check-in
    $canCheckin = $this->checkinModel->canCheckin($assignmentId);
    if (!$canCheckin['allowed']) {
      Message::set('error', $canCheckin['message']);
      redirect('my-schedule');
      exit;
    }

    // Tạo đợt check-in
    $linkId = $this->checkinModel->createCheckinLink($assignmentId, $title, $note);

    if ($linkId) {
      Message::set('success', 'Tạo đợt check-in thành công');
      // Chuyển đến trang chi tiết để check-in khách
      redirect('guide-checkin-detail&link_id=' . $linkId . '&assignment_id=' . $assignmentId);
    } else {
      Message::set('error', 'Có lỗi xảy ra khi tạo đợt check-in');
      redirect('my-schedule');
    }
    exit;
  }


  // Cập nhật batch check-in từ toggle
  public function batchUpdate()
  {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      header('Location: ' . BASE_URL . '?act=guide-tour-assignments');
      exit;
    }

    $linkId = $_POST['link_id'] ?? null;
    $assignmentId = $_POST['assignment_id'] ?? null;
    $checkedCustomers = $_POST['checked_customers'] ?? [];
    if (!$linkId || !$assignmentId) {
      Message::set('error', 'Thiếu thông tin');
      redirect('my-schedule');
      exit;
    }

    // Kiểm tra quyền check-in
    $canCheckin = $this->checkinModel->canCheckin($assignmentId);
    if (!$canCheckin['allowed']) {
      Message::set('error', $canCheckin['message']);
      redirect('guide-checkin-detail&link_id=' . $linkId . '&assignment_id=' . $assignmentId);
      exit;
    }

    // Lấy danh sách khách hàng hiện tại
    $customers = $this->checkinModel->getCustomersWithCheckinStatus($assignmentId, $linkId);

    $checkinCount = 0;
    $uncheckinCount = 0;

    foreach ($customers as $customer) {
      $customerId = $customer['id'];
      $isCurrentlyCheckedIn = !empty($customer['checkin_id']);
      $shouldBeCheckedIn = in_array($customerId, $checkedCustomers);

      if ($shouldBeCheckedIn && !$isCurrentlyCheckedIn) {
        // Check-in khách hàng
        if ($this->checkinModel->checkinCustomer($linkId, $customerId)) {
          $checkinCount++;
        }
      } elseif (!$shouldBeCheckedIn && $isCurrentlyCheckedIn) {
        // Hủy check-in khách hàng
        if ($this->checkinModel->uncheckinCustomer($linkId, $customerId)) {
          $uncheckinCount++;
        }
      }
    }

    if ($checkinCount > 0 || $uncheckinCount > 0) {
      $message = [];
      if ($checkinCount > 0) $message[] = "Check-in $checkinCount khách";
      if ($uncheckinCount > 0) $message[] = "Hủy $uncheckinCount khách";
      Message::set('success', implode(', ', $message));
    } else {
      Message::set('info', 'Không có thay đổi nào');
    }

    redirect('guide-checkin-detail&link_id=' . $linkId . '&assignment_id=' . $assignmentId);
    exit;
  }

  // Xóa đợt check-in
  public function delete()
  {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      header('Location: ' . BASE_URL . '?act=guide-tour-assignments');
      exit;
    }

    $linkId = $_POST['link_id'] ?? null;
    $assignmentId = $_POST['assignment_id'] ?? null;

    if (!$linkId || !$assignmentId) {
      Message::set('error', 'Thiếu thông tin');
      header('Location: ' . BASE_URL . '?act=guide-tour-assignments');
      exit;
    }

    $result = $this->checkinModel->deleteCheckinLink($linkId);

    if ($result) {
      Message::set('success', 'Xóa đợt check-in thành công');
    } else {
      Message::set('error', 'Có lỗi xảy ra khi xóa đợt check-in');
    }

    header('Location: ' . BASE_URL . '?act=guide-tour-assignments-detail&id=' . $assignmentId . '&tab=checkin');
    exit;
  }

  // Xuất danh sách check-in ra Excel
  public function exportCheckinList()
  {
    $assignmentId = $_GET['id'] ?? null;
    $linkId = $_GET['link_id'] ?? null;

    if (!$assignmentId) {
      Message::set('error', 'Không tìm thấy thông tin tour!');
      header("Location: " . BASE_URL . "?act=guide-tour-assignments");
      exit;
    }

    $assignment = $this->bookingModel->getBookingDetails($assignmentId);
    $customers = $this->checkinModel->getCustomersWithCheckinStatus($assignmentId, $linkId);

    if (empty($customers)) {
      Message::set('error', 'Chưa có khách hàng nào trong tour này!');
      header("Location: " . BASE_URL . "?act=guide-tour-assignments-detail&id=" . $assignmentId . "&tab=checkin");
      exit;
    }

    require_once './lib/SimpleXLSXGen.php';

    $data = [
      ['DANH SÁCH KHÁCH HÀNG - ' . mb_strtoupper($assignment['tour_name'])],
      ['Mã Booking: ' . $assignment['booking_code']],
      ['Ngày khởi hành: ' . date('d/m/Y', strtotime($assignment['start_date']))],
      [],
      ['STT', 'Tên khách hàng', 'Số điện thoại', 'Email', 'Trạng thái', 'Thời gian check-in', 'Phòng']
    ];

    foreach ($customers as $i => $c) {
      $status = !empty($c['checkin_id']) ? 'Đã check-in' : 'Chưa check-in';
      $time = !empty($c['checkin_time']) ? date('H:i d/m/Y', strtotime($c['checkin_time'])) : '';
      $room = $c['room_number'] ?? '';

      $data[] = [
        $i + 1,
        $c['name'],
        $c['phone'],
        $c['email'] ?? '',
        $status,
        $time,
        $room
      ];
    }

    $xlsx = Shuchkin\SimpleXLSXGen::fromArray($data);
    $xlsx->downloadAs('Danh_sach_checkin_' . date('Ymd_His') . '.xlsx');
    exit;
  }
}
