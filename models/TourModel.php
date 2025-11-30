<?php
class TourModel
{
  public $conn;

  public function __construct()
  {
    $this->conn = connectDB();
  }

  // Lấy danh sách tour
  public function getAll()
  {
    $sql = "SELECT t.*, c.name as category_name 
            FROM tours t
            LEFT JOIN categories c ON t.category_id = c.id
            ORDER BY t.id DESC";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
  }

  // Thêm tour mới
  public function create($data)
  {
    // Tạo tour_code tự động (format: TOUR-YYYYMMDD-XXX)
    $tourCode = $this->generateTourCode();

    $sql = "INSERT INTO tours 
            (category_id, tour_code, name, introduction, adult_price, child_price, 
             status, duration_days, created_by, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

    $stmt = $this->conn->prepare($sql);

    $stmt->execute([
      $data['category_id'],
      $tourCode,
      $data['name'],
      $data['introduction'],
      $data['adult_price'],
      $data['child_price'],
      $data['status'],
      $data['duration_days'],
      $data['created_by']
    ]);

    return $this->conn->lastInsertId();
  }

  // Generate tour code tự động
  private function generateTourCode()
  {
    $date = date('Ymd');

    // Đếm số tour được tạo trong ngày
    $sql = "SELECT COUNT(*) as count FROM tours 
            WHERE DATE(created_at) = CURDATE()";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch();

    $sequence = str_pad($result['count'] + 1, 3, '0', STR_PAD_LEFT);

    return "TOUR-{$date}-{$sequence}";
  }

  // Lấy tour theo ID
  public function getById($id)
  {
    $sql = "SELECT t.*, c.name as category_name
            FROM tours t
            LEFT JOIN categories c ON t.category_id = c.id
            WHERE t.id = ?";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->fetch();
  }

  // Lấy itineraries theo tour_id
  public function getItinerariesByTourId($tourId)
  {
    $sql = "SELECT i.*, d.name as destination 
            FROM itineraries i
            LEFT JOIN destinations d ON i.destination_id = d.id
            WHERE i.tour_id = ? 
            ORDER BY i.order_number ASC";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$tourId]);
    return $stmt->fetchAll();
  }

  // Lấy policies theo tour_id
  public function getPoliciesByTourId($tourId)
  {
    $sql = "SELECT p.*
            FROM policies p
            INNER JOIN tour_policies tp ON p.id = tp.policy_id
            WHERE tp.tour_id = ?";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$tourId]);
    return $stmt->fetchAll();
  }

  // Cập nhật tour
  public function update($id, $data, $userId)
  {
    $sql = "UPDATE tours SET
              category_id = ?,
              name = ?,
              introduction = ?,
              adult_price = ?,
              child_price = ?,
              status = ?,
              duration_days = ?,
              updated_by = ?,
              updated_at = NOW()
            WHERE id = ?";

    $stmt = $this->conn->prepare($sql);

    return $stmt->execute([
      $data['category_id'],
      $data['name'],
      $data['introduction'],
      $data['adult_price'],
      $data['child_price'],
      $data['status'],
      $data['duration_days'],
      $userId,
      $id
    ]);
  }

  // Xóa tour
  public function delete($id)
  {
    // Xóa itineraries
    $sql1 = "DELETE FROM itineraries WHERE tour_id = ?";
    $stmt1 = $this->conn->prepare($sql1);
    $stmt1->execute([$id]);

    // Xóa tour_policies
    $sql2 = "DELETE FROM tour_policies WHERE tour_id = ?";
    $stmt2 = $this->conn->prepare($sql2);
    $stmt2->execute([$id]);

    // Xóa tour_services (nếu có)
    $sql3 = "DELETE FROM tour_services WHERE tour_id = ?";
    $stmt3 = $this->conn->prepare($sql3);
    $stmt3->execute([$id]);

    // Xóa tour
    $sql = "DELETE FROM tours WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    return $stmt->execute([$id]);
  }

  // Thêm itinerary
  public function addItinerary($data)
  {
    $sql = "INSERT INTO itineraries 
            (tour_id, order_number, destination_id, arrival_time, departure_time, 
             description, created_by, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";

    $stmt = $this->conn->prepare($sql);

    return $stmt->execute([
      $data['tour_id'],
      $data['order_number'],
      $data['destination_id'],
      $data['arrival_time'],
      $data['departure_time'],
      $data['description'],
      $data['created_by']
    ]);
  }

  // Xóa itineraries của tour
  public function deleteItineraries($tourId)
  {
    $sql = "DELETE FROM itineraries WHERE tour_id = ?";
    $stmt = $this->conn->prepare($sql);
    return $stmt->execute([$tourId]);
  }

  // Gắn policy vào tour
  public function attachPolicy($tourId, $policyId, $userId)
  {
    $sql = "INSERT INTO tour_policies (tour_id, policy_id, created_by, created_at) 
            VALUES (?, ?, ?, NOW())";
    $stmt = $this->conn->prepare($sql);
    return $stmt->execute([$tourId, $policyId, $userId]);
  }

  // Xóa tất cả policies của tour
  public function detachAllPolicies($tourId)
  {
    $sql = "DELETE FROM tour_policies WHERE tour_id = ?";
    $stmt = $this->conn->prepare($sql);
    return $stmt->execute([$tourId]);
  }

  // Lấy policies của tour (cho form edit)
  public function getTourPolicies($tourId)
  {
    $sql = "SELECT p.* 
            FROM policies p
            INNER JOIN tour_policies tp ON p.id = tp.policy_id
            WHERE tp.tour_id = ?";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$tourId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  // Lấy itineraries của tour (cho form edit)
  public function getItineraries($tourId)
  {
    $sql = "SELECT i.*, d.name as destination_name 
            FROM itineraries i
            LEFT JOIN destinations d ON i.destination_id = d.id
            WHERE i.tour_id = ?
            ORDER BY i.order_number ASC";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$tourId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  // Filter tìm kiếm tour
  public function filter($name, $category_id, $status)
  {
    $sql = "SELECT t.*, c.name as category_name 
            FROM tours t
            LEFT JOIN categories c ON t.category_id = c.id
            WHERE 1=1";
    $params = [];

    if (!empty($name)) {
      $sql .= " AND t.name LIKE ?";
      $params[] = "%$name%";
    }

    if (!empty($category_id)) {
      $sql .= " AND t.category_id = ?";
      $params[] = $category_id;
    }

    if (!empty($status)) {
      $sql .= " AND t.status = ?";
      $params[] = $status;
    }

    $sql .= " ORDER BY t.id DESC";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll();
  }
}
