<?php
class CheckinModel
{
  public $conn;

  public function __construct()
  {
    $this->conn = connectDB();
  }

  // Lấy danh sách khách hàng của tour assignment
  public function getCustomersByAssignment($assignmentId)
  {
    try {
      $sql = "SELECT c.*, bc.is_representative, bc.room_number,
                    (SELECT COUNT(*) FROM customer_checkins 
                     WHERE customer_id = c.id 
                     AND tour_assignment_id = :assignment_id) as checkin_count,
                    (SELECT id FROM customer_checkins 
                     WHERE customer_id = c.id 
                     AND tour_assignment_id = :assignment_id 
                     ORDER BY checkin_time DESC LIMIT 1) as latest_checkin_id,
                    (SELECT checkin_time FROM customer_checkins 
                     WHERE customer_id = c.id 
                     AND tour_assignment_id = :assignment_id 
                     ORDER BY checkin_time DESC LIMIT 1) as latest_checkin_time
                    FROM booking_customers bc
                    JOIN customers c ON bc.customer_id = c.id
                    JOIN tour_assignments ta ON ta.booking_id = bc.booking_id
                    WHERE ta.id = :assignment_id
                    ORDER BY bc.is_representative DESC, c.name ASC";

      $stmt = $this->conn->prepare($sql);
      $stmt->execute([':assignment_id' => $assignmentId]);
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      die("Lỗi getCustomersByAssignment(): " . $e->getMessage());
    }
  }

  // Kiểm tra khách đã check-in chưa
  public function hasCheckedIn($assignmentId, $customerId)
  {
    try {
      $sql = "SELECT COUNT(*) as count 
                    FROM customer_checkins 
                    WHERE tour_assignment_id = ? 
                    AND customer_id = ?";

      $stmt = $this->conn->prepare($sql);
      $stmt->execute([$assignmentId, $customerId]);
      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      return $result['count'] > 0;
    } catch (PDOException $e) {
      die("Lỗi hasCheckedIn(): " . $e->getMessage());
    }
  }

  // Tạo check-in mới
  public function createCheckin($data)
  {
    try {
      $sql = "INSERT INTO customer_checkins 
                    (tour_assignment_id, customer_id, 
                     checkin_time, created_by, created_at)
                    VALUES (?, ?, ?, ?, NOW())";

      $stmt = $this->conn->prepare($sql);
      return $stmt->execute([
        $data['tour_assignment_id'],
        $data['customer_id'],
        $data['checkin_time'] ?? date('Y-m-d H:i:s'),
        $data['created_by']
      ]);
    } catch (PDOException $e) {
      die("Lỗi createCheckin(): " . $e->getMessage());
    }
  }

  // Lấy lịch sử check-in
  public function getCheckinHistory($assignmentId)
  {
    try {
      $sql = "SELECT cc.*, c.name as customer_name, c.phone, c.email
                    FROM customer_checkins cc
                    JOIN customers c ON cc.customer_id = c.id
                    WHERE cc.tour_assignment_id = ?
                    ORDER BY cc.checkin_time DESC";

      $stmt = $this->conn->prepare($sql);
      $stmt->execute([$assignmentId]);
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      die("Lỗi getCheckinHistory(): " . $e->getMessage());
    }
  }

  // Lấy chi tiết check-in
  public function getCheckinDetail($checkinId)
  {
    try {
      $sql = "SELECT cc.*, c.name as customer_name, c.phone, c.email,
                    u.fullname as created_by_name
                    FROM customer_checkins cc
                    JOIN customers c ON cc.customer_id = c.id
                    LEFT JOIN users u ON cc.created_by = u.id
                    WHERE cc.id = ?";

      $stmt = $this->conn->prepare($sql);
      $stmt->execute([$checkinId]);
      return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      die("Lỗi getCheckinDetail(): " . $e->getMessage());
    }
  }

  // Xóa check-in
  public function deleteCheckin($checkinId)
  {
    try {
      // Lấy thông tin check-in để xóa ảnh
      $checkin = $this->getCheckinDetail($checkinId);

      if ($checkin && !empty($checkin['image_url'])) {
        $imagePath = __DIR__ . '/../../uploads/checkins/' . $checkin['image_url'];
        if (file_exists($imagePath)) {
          unlink($imagePath);
        }
      }

      $sql = "DELETE FROM customer_checkins WHERE id = ?";
      $stmt = $this->conn->prepare($sql);
      return $stmt->execute([$checkinId]);
    } catch (PDOException $e) {
      die("Lỗi deleteCheckin(): " . $e->getMessage());
    }
  }

  // Thống kê check-in
  public function getCheckinStats($assignmentId)
  {
    try {
      $sql = "SELECT 
                    COUNT(DISTINCT bc.customer_id) as total_customers,
                    COUNT(DISTINCT cc.customer_id) as checked_in_count
                    FROM booking_customers bc
                    JOIN tour_assignments ta ON ta.booking_id = bc.booking_id
                    LEFT JOIN customer_checkins cc ON cc.customer_id = bc.customer_id 
                        AND cc.tour_assignment_id = ta.id
                    WHERE ta.id = ?";

      $stmt = $this->conn->prepare($sql);
      $stmt->execute([$assignmentId]);
      return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      die("Lỗi getCheckinStats(): " . $e->getMessage());
    }
  }

  // Cập nhật số phòng
  public function updateRoom($customerId, $bookingId, $room)
  {
    try {
      $sql = "UPDATE booking_customers SET room_number = ? WHERE customer_id = ? AND booking_id = ?";
      $stmt = $this->conn->prepare($sql);
      return $stmt->execute([$room, $customerId, $bookingId]);
    } catch (PDOException $e) {
      die("Lỗi updateRoom(): " . $e->getMessage());
    }
  }

  // Kiểm tra xem có thể check-in cho tour này không (dựa vào ngày)
  public function canCheckin($assignmentId)
  {
    try {
      // JOIN với bảng bookings để lấy start_date và end_date
      $sql = "SELECT b.start_date, b.end_date 
              FROM tour_assignments ta
              JOIN bookings b ON ta.booking_id = b.id
              WHERE ta.id = ?";

      $stmt = $this->conn->prepare($sql);
      $stmt->execute([$assignmentId]);
      $tour = $stmt->fetch(PDO::FETCH_ASSOC);

      if (!$tour) {
        return [
          'allowed' => false,
          'message' => 'Không tìm thấy thông tin tour!'
        ];
      }

      $today = date('Y-m-d');
      $startDate = $tour['start_date'];
      $endDate = $tour['end_date'];

      // Kiểm tra tour chưa bắt đầu
      if ($today < $startDate) {
        return [
          'allowed' => false,
          'message' => 'Chưa đến thời gian khởi hành! Tour bắt đầu từ ' . date('d/m/Y', strtotime($startDate))
        ];
      }

      // Kiểm tra tour đã kết thúc
      if ($today > $endDate) {
        return [
          'allowed' => false,
          'message' => 'Tour đã kết thúc từ ngày ' . date('d/m/Y', strtotime($endDate))
        ];
      }

      // Tour đang diễn ra
      return [
        'allowed' => true,
        'message' => 'OK'
      ];
    } catch (PDOException $e) {
      return [
        'allowed' => false,
        'message' => 'Lỗi kiểm tra thời gian tour: ' . $e->getMessage()
      ];
    }
  }
}
