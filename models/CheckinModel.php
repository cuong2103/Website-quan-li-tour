<?php
class CheckinModel
{
  public $conn;

  public function __construct()
  {
    $this->conn = connectDB();
  }

  // Lấy tất cả các đợt check-in của một tour assignment
  public function getCheckinLinks($assignmentId)
  {
    try {
      $sql = "SELECT * FROM tour_checkin_links 
                    WHERE tour_assignment_id = :assignment_id 
                    ORDER BY created_at DESC";
      $stmt = $this->conn->prepare($sql);
      $stmt->execute([':assignment_id' => $assignmentId]);
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      die("Lỗi getCheckinLinks(): " . $e->getMessage());
    }
  }

  // Tạo đợt check-in mới
  public function createCheckinLink($assignmentId, $title, $note)
  {
    try {
      $sql = "INSERT INTO tour_checkin_links (tour_assignment_id, title, note, created_by, created_at) 
                    VALUES (:assignment_id, :title, :note, :created_by, NOW())";
      $stmt = $this->conn->prepare($sql);
      $stmt->execute([
        ':assignment_id' => $assignmentId,
        ':title' => $title,
        ':note' => $note,
        ':created_by' => $_SESSION['currentUser']['id'] ?? null
      ]);
      return $this->conn->lastInsertId();
    } catch (PDOException $e) {
      die("Lỗi createCheckinLink(): " . $e->getMessage());
    }
  }

  // Lấy danh sách khách hàng với trạng thái check-in cho một đợt cụ thể
  public function getCustomersWithCheckinStatus($assignmentId, $checkinLinkId)
  {
    try {
      $sql = "SELECT c.*, bc.room_number,
                           cc.id as checkin_id,
                           cc.checkin_time
                    FROM customers c
                    JOIN booking_customers bc ON c.id = bc.customer_id
                    JOIN bookings b ON bc.booking_id = b.id
                    JOIN tour_assignments ta ON ta.booking_id = b.id
                    LEFT JOIN customer_checkins cc ON cc.customer_id = c.id AND cc.tour_checkin_link_id = :link_id
                    WHERE ta.id = :assignment_id
                    ORDER BY c.name ASC";

      $stmt = $this->conn->prepare($sql);
      $stmt->execute([
        ':assignment_id' => $assignmentId,
        ':link_id' => $checkinLinkId
      ]);
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      die("Lỗi getCustomersWithCheckinStatus(): " . $e->getMessage());
    }
  }

  // Check-in khách hàng
  public function checkinCustomer($checkinLinkId, $customerId)
  {
    try {
      // Kiểm tra đã check-in chưa
      $sqlCheck = "SELECT id FROM customer_checkins WHERE tour_checkin_link_id = ? AND customer_id = ?";
      $stmtCheck = $this->conn->prepare($sqlCheck);
      $stmtCheck->execute([$checkinLinkId, $customerId]);

      if ($stmtCheck->fetchColumn()) {
        return true; // Đã check-in rồi
      }

      // Thực hiện check-in
      $sql = "INSERT INTO customer_checkins (tour_checkin_link_id, customer_id, checkin_time, created_by, created_at)
                        VALUES (?, ?, NOW(), ?, NOW())";
      $stmt = $this->conn->prepare($sql);
      $stmt->execute([$checkinLinkId, $customerId, $_SESSION['currentUser']['id'] ?? null]);
      return true;
    } catch (PDOException $e) {
      die("Lỗi checkinCustomer(): " . $e->getMessage());
    }
  }

  // Hủy check-in khách hàng
  public function uncheckinCustomer($checkinLinkId, $customerId)
  {
    try {
      $sql = "DELETE FROM customer_checkins WHERE tour_checkin_link_id = ? AND customer_id = ?";
      $stmt = $this->conn->prepare($sql);
      $stmt->execute([$checkinLinkId, $customerId]);
      return true;
    } catch (PDOException $e) {
      die("Lỗi uncheckinCustomer(): " . $e->getMessage());
    }
  }

  // Xóa đợt check-in
  public function deleteCheckinLink($linkId)
  {
    try {
      $this->conn->beginTransaction();

      // Xóa các check-in của khách trong đợt này
      $sql1 = "DELETE FROM customer_checkins WHERE tour_checkin_link_id = ?";
      $stmt1 = $this->conn->prepare($sql1);
      $stmt1->execute([$linkId]);

      // Xóa đợt check-in
      $sql2 = "DELETE FROM tour_checkin_links WHERE id = ?";
      $stmt2 = $this->conn->prepare($sql2);
      $stmt2->execute([$linkId]);

      $this->conn->commit();
      return true;
    } catch (PDOException $e) {
      $this->conn->rollBack();
      die("Lỗi deleteCheckinLink(): " . $e->getMessage());
    }
  }

  // Kiểm tra thời gian tour
  public function canCheckin($assignmentId)
  {
    try {
      $sql = "SELECT b.start_date, b.end_date 
                    FROM tour_assignments ta
                    JOIN bookings b ON ta.booking_id = b.id
                    WHERE ta.id = ?";
      $stmt = $this->conn->prepare($sql);
      $stmt->execute([$assignmentId]);
      $tour = $stmt->fetch(PDO::FETCH_ASSOC);

      if (!$tour) return ['allowed' => false, 'message' => 'Tour không tồn tại'];

      $today = date('Y-m-d');
      if ($today < $tour['start_date']) {
        return ['allowed' => false, 'message' => 'Tour chưa bắt đầu'];
      }
      if ($today > $tour['end_date']) {
        return ['allowed' => false, 'message' => 'Tour đã kết thúc'];
      }

      return ['allowed' => true, 'message' => 'OK'];
    } catch (PDOException $e) {
      return ['allowed' => false, 'message' => $e->getMessage()];
    }
  }

  // Cập nhật số phòng
  public function updateRoom($customerId, $bookingId, $room)
  {
    try {
      $sql = "UPDATE booking_customers 
                    SET room_number = :room 
                    WHERE customer_id = :customer_id AND booking_id = :booking_id";
      $stmt = $this->conn->prepare($sql);
      $stmt->execute([
        ':room' => $room,
        ':customer_id' => $customerId,
        ':booking_id' => $bookingId
      ]);
      return true;
    } catch (PDOException $e) {
      die("Lỗi updateRoom(): " . $e->getMessage());
    }
  }

  public function getCheckinLink($linkId, $assignmentId)
  {
    try {
      $sql = "SELECT tcl.*, ta.*, t.name as tour_name, b.start_date, b.end_date, b.booking_code
            FROM tour_checkin_links tcl
            JOIN tour_assignments ta ON tcl.tour_assignment_id = ta.id
            JOIN bookings b ON ta.booking_id = b.id
            JOIN tours t ON b.tour_id = t.id
            WHERE tcl.id = ? AND ta.id = ?";
      $stmt = $this->conn->prepare($sql);
      $stmt->execute([$linkId, $assignmentId]);
      $checkinLink = $stmt->fetch(PDO::FETCH_ASSOC);
      return $checkinLink;
    } catch (PDOException $e) {
      die("Lỗi getCheckinLink(): " . $e->getMessage());
    }
  }
}
