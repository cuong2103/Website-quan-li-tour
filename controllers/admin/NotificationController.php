<?php
class NotificationController
{
  public $notificationModel;

  public function __construct()
  {
    checkLogin();
    $this->notificationModel = new NotificationModel();
  }

  // Danh sách thông báo
  public function index()
  {
    requireAdmin();
    $notifications = $this->notificationModel->getAll();
    $totalNotifications = $this->notificationModel->getTotalNotifications();

    require_once './views/admin/notifications/index.php';
  }

  // Form tạo thông báo mới
  public function create()
  {
    requireAdmin();
    $users = $this->notificationModel->getAllUsers();
    require_once './views/admin/notifications/create.php';
  }

  // Xử lý tạo thông báo
  public function store()
  {
    requireAdmin();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $data = [
        'title' => trim($_POST['title']),
        'message' => trim($_POST['message']),
        'type' => $_POST['type'] ?? 'general',
        'created_by' => $_SESSION['currentUser']['id']
      ];

      $recipientType = $_POST['recipient_type'] ?? 'all';
      $selectedUsers = $_POST['selected_users'] ?? [];

      // Validate
      $rules = [
        'title' => 'required|min:5|max:255',
        'message' => 'required|min:10'
      ];

      $errors = validate($data, $rules);

      if (!empty($errors)) {
        $users = $this->notificationModel->getAllUsers();
        require_once './views/admin/notifications/create.php';
        exit;
      }

      // Tạo thông báo
      $notificationId = $this->notificationModel->create($data);

      // Xác định người nhận
      $recipientIds = [];

      if ($recipientType === 'all') {
        // Gửi cho tất cả nhân viên
        $allUsers = $this->notificationModel->getAllUsers();
        $recipientIds = array_column($allUsers, 'id');
      } elseif ($recipientType === 'role') {
        // Gửi theo vai trò
        $role = $_POST['role'] ?? 'guide';
        $allUsers = $this->notificationModel->getAllUsers();
        foreach ($allUsers as $user) {
          if ($user['roles'] === $role) {
            $recipientIds[] = $user['id'];
          }
        }
      } elseif ($recipientType === 'specific') {
        // Gửi cho người cụ thể
        $recipientIds = $selectedUsers;
      }

      // Thêm người nhận
      if (!empty($recipientIds)) {
        $this->notificationModel->addRecipients($notificationId, $recipientIds);
      }

      Message::set("success", "Tạo thông báo thành công!");
      $_SESSION['unreadCount'] = $this->notificationModel->countUnread($_SESSION['currentUser']['id']);
      redirect("notifications");
      exit;
    }
  }

  // Chi tiết thông báo
  public function detail()
  {
    requireAdmin();
    $id = $_GET['id'] ?? null;
    if (!$id) {
      redirect('notifications');
      exit;
    }

    $notification = $this->notificationModel->getById($id);
    if (!$notification) {
      Message::set("error", "Thông báo không tồn tại");
      redirect('notifications');
      exit;
    }

    $recipients = $this->notificationModel->getRecipients($id);
    require_once './views/admin/notifications/detail.php';
  }

  // Xóa thông báo
  public function delete()
  {
    requireAdmin();
    if (!isset($_GET['id'])) {
      redirect('notifications');
      exit;
    }

    $id = $_GET['id'];
    $notification = $this->notificationModel->getById($id);

    if (!$notification) {
      Message::set("error", "Thông báo không tồn tại");
      redirect("notifications");
      exit;
    }

    $this->notificationModel->delete($id);
    Message::set("success", "Xóa thông báo thành công!");
    redirect("notifications");
    exit;
  }

  public function myNotifications()
  {
    $userId = $_SESSION['currentUser']['id'];
    $notifications = $this->notificationModel->getByUserId($userId, 10);
    $unreadCount = $this->notificationModel->countUnread($userId);
    $_SESSION['unreadCount'] = $unreadCount;
    require_once './views/shared/my_notification.php';
  }

  public function read()
  {
    $notificationId = $_GET['id'] ?? null;
    $userId = $_SESSION['currentUser']['id'];

    if ($notificationId) {
      $this->notificationModel->markAsRead($notificationId, $userId);
      Message::set("success", "Đã đánh dấu thông báo là đã đọc!");
    }

    redirect("my-notifications");
    exit;
  }

  // Đánh dấu tất cả đã đọc
  public function markAllRead()
  {
    $userId = $_SESSION['currentUser']['id'];
    $this->notificationModel->markAllAsRead($userId);

    Message::set("success", "Đã đánh dấu tất cả thông báo là đã đọc!");
    redirect("my-notifications");
    exit;
  }

  // Form chỉnh sửa thông báo
  public function edit()
  {
    requireAdmin();
    $id = $_GET['id'] ?? null;
    if (!$id) {
      redirect('notifications');
      exit;
    }

    $notification = $this->notificationModel->getById($id);
    if (!$notification) {
      Message::set("error", "Thông báo không tồn tại");
      redirect('notifications');
      exit;
    }

    $users = $this->notificationModel->getAllUsers();
    $recipients = $this->notificationModel->getRecipients($id);
    $recipientIds = array_column($recipients, 'id');

    require_once './views/admin/notifications/edit.php';
  }

  // Xử lý cập nhật thông báo
  public function update()
  {
    requireAdmin();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $id = $_POST['id'] ?? null;

      if (!$id) {
        redirect('notifications');
        exit;
      }

      $notification = $this->notificationModel->getById($id);
      if (!$notification) {
        Message::set("error", "Thông báo không tồn tại");
        redirect('notifications');
        exit;
      }

      $data = [
        'title' => trim($_POST['title']),
        'message' => trim($_POST['message']),
        'type' => $_POST['type'] ?? 'general',
        'updated_by' => $_SESSION['currentUser']['id']
      ];

      $recipientType = $_POST['recipient_type'] ?? 'all';
      $selectedUsers = $_POST['selected_users'] ?? [];

      // Validate
      $rules = [
        'title' => 'required|min:5|max:255',
        'message' => 'required|min:10'
      ];

      $errors = validate($data, $rules);

      if (!empty($errors)) {
        $users = $this->notificationModel->getAllUsers();
        $recipients = $this->notificationModel->getRecipients($id);
        $recipientIds = array_column($recipients, 'id');
        require_once './views/admin/notifications/edit.php';
        exit;
      }

      // Cập nhật thông báo
      $this->notificationModel->update($id, $data);

      // Xóa người nhận cũ
      $this->notificationModel->deleteRecipients($id);

      // Xác định người nhận mới
      $recipientIds = [];

      if ($recipientType === 'all') {
        $allUsers = $this->notificationModel->getAllUsers();
        $recipientIds = array_column($allUsers, 'id');
      } elseif ($recipientType === 'role') {
        $role = $_POST['role'] ?? 'guide';
        $allUsers = $this->notificationModel->getAllUsers();
        foreach ($allUsers as $user) {
          if ($user['roles'] === $role) {
            $recipientIds[] = $user['id'];
          }
        }
      } elseif ($recipientType === 'specific') {
        $recipientIds = $selectedUsers;
      }

      // Thêm người nhận mới
      if (!empty($recipientIds)) {
        $this->notificationModel->addRecipients($id, $recipientIds);
      }

      Message::set("success", "Cập nhật thông báo thành công!");
      redirect("notifications");
      exit;
    }
  }
}
