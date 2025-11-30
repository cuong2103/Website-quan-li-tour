<?php
class NotificationModel
{
  public $conn;

  public function __construct()
  {
    $this->conn = connectDB();
  }

  // Lấy tất cả thông báo (cho admin)
  public function getAll($limit = null, $offset = 0)
  {
    $sql = "SELECT n.*, 
                   u.fullname as creator_name,
                   COUNT(DISTINCT nr.user_id) as total_recipients,
                   COUNT(DISTINCT CASE WHEN nr.is_read = 1 THEN nr.user_id END) as read_count
            FROM notifications n
            LEFT JOIN users u ON n.created_by = u.id
            LEFT JOIN notification_recipients nr ON n.id = nr.notification_id
            GROUP BY n.id
            ORDER BY n.created_at DESC";

    if ($limit) {
      $sql .= " LIMIT :limit OFFSET :offset";
    }

    $stmt = $this->conn->prepare($sql);
    if ($limit) {
      $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
      $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    }
    $stmt->execute();
    return $stmt->fetchAll();
  }

  // Lấy thông báo của 1 user cụ thể
  public function getByUserId($userId, $limit = null)
  {
    $sql = "SELECT n.*, nr.is_read, nr.read_at, u.fullname as creator_name
            FROM notifications n
            INNER JOIN notification_recipients nr ON n.id = nr.notification_id
            LEFT JOIN users u ON n.created_by = u.id
            WHERE nr.user_id = :user_id
            ORDER BY n.created_at DESC";

    if ($limit) {
      $sql .= " LIMIT :limit";
    }

    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
    if ($limit) {
      $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    }
    $stmt->execute();
    return $stmt->fetchAll();
  }

  // Đếm thông báo chưa đọc
  public function countUnread($userId)
  {
    $sql = "SELECT COUNT(*) as unread_count 
            FROM notification_recipients 
            WHERE user_id = :user_id AND is_read = 0";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['unread_count'] ?? 0;
  }

  // Tạo thông báo mới
  public function create($data)
  {
    $sql = "INSERT INTO notifications (title, message, type, created_by) 
            VALUES (:title, :message, :type, :created_by)";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([
      ':title' => $data['title'],
      ':message' => $data['message'],
      ':type' => $data['type'],
      ':created_by' => $data['created_by']
    ]);
    return $this->conn->lastInsertId();
  }

  // Thêm người nhận
  public function addRecipients($notificationId, $userIds)
  {
    $sql = "INSERT INTO notification_recipients (notification_id, user_id) VALUES (?, ?)";
    $stmt = $this->conn->prepare($sql);

    foreach ($userIds as $userId) {
      $stmt->execute([$notificationId, $userId]);
    }
    return true;
  }

  // Đánh dấu đã đọc
  public function markAsRead($notificationId, $userId)
  {
    $sql = "UPDATE notification_recipients 
            SET is_read = 1, read_at = NOW() 
            WHERE notification_id = :notification_id AND user_id = :user_id";
    $stmt = $this->conn->prepare($sql);
    return $stmt->execute([
      ':notification_id' => $notificationId,
      ':user_id' => $userId
    ]);
  }

  // Đánh dấu tất cả đã đọc
  public function markAllAsRead($userId)
  {
    $sql = "UPDATE notification_recipients 
            SET is_read = 1, read_at = NOW() 
            WHERE user_id = :user_id AND is_read = 0";
    $stmt = $this->conn->prepare($sql);
    return $stmt->execute([':user_id' => $userId]);
  }

  // Lấy chi tiết thông báo
  public function getById($id)
  {
    $sql = "SELECT n.*, 
                 u1.fullname as creator_name,
                 u1.email as creator_email,
                 u2.fullname as updater_name,
                 u2.email as updater_email
          FROM notifications n
          LEFT JOIN users u1 ON n.created_by = u1.id
          LEFT JOIN users u2 ON n.updated_by = u2.id
          WHERE n.id = :id";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch();
  }

  // Lấy danh sách người nhận của thông báo
  public function getRecipients($notificationId)
  {
    $sql = "SELECT u.*, nr.is_read, nr.read_at
            FROM notification_recipients nr
            INNER JOIN users u ON nr.user_id = u.id
            WHERE nr.notification_id = :notification_id
            ORDER BY u.fullname";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(':notification_id', $notificationId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
  }

  // Xóa thông báo
  public function delete($id)
  {
    $sql = "DELETE FROM notifications WHERE id = :id";
    $stmt = $this->conn->prepare($sql);
    return $stmt->execute([':id' => $id]);
  }

  // Đếm tổng số thông báo
  public function getTotalNotifications()
  {
    $sql = "SELECT COUNT(*) as total FROM notifications";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'] ?? 0;
  }

  // Lấy danh sách users để gửi thông báo
  public function getAllUsers()
  {
    $sql = "SELECT id, fullname, email, roles 
            FROM users 
            WHERE status = 1 
            ORDER BY roles, fullname";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
  }

  public function update($id, $data)
  {
    $sql = "UPDATE notifications 
          SET title = :title, 
              message = :message, 
              type = :type,
              updated_by = :updated_by,
              updated_at = NOW()
          WHERE id = :id";
    $stmt = $this->conn->prepare($sql);
    return $stmt->execute([
      ':id' => $id,
      ':title' => $data['title'],
      ':message' => $data['message'],
      ':type' => $data['type'],
      ':updated_by' => $data['updated_by']
    ]);
  }

  // Xóa người nhận cũ (để cập nhật lại)
  public function deleteRecipients($notificationId)
  {
    $sql = "DELETE FROM notification_recipients WHERE notification_id = :notification_id";
    $stmt = $this->conn->prepare($sql);
    return $stmt->execute([':notification_id' => $notificationId]);
  }
}
