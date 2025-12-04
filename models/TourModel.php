<?php
class TourModel
{
  public $conn;

  public function __construct()
  {
    $this->conn = connectDB();
  }

  // Lấy danh sách tour với filter
  public function getAll($filters = [])
  {
    $sql = "SELECT DISTINCT t.*, c.name as category_name 
            FROM tours t
            LEFT JOIN categories c ON t.category_id = c.id
            LEFT JOIN itineraries i ON t.id = i.tour_id
            WHERE 1=1";
    $params = [];

    // Filter theo tên tour
    if (!empty($filters['name'])) {
      $sql .= " AND t.name LIKE ?";
      $params[] = "%" . $filters['name'] . "%";
    }

    // Filter theo danh mục
    if (!empty($filters['category_id'])) {
      $sql .= " AND t.category_id = ?";
      $params[] = $filters['category_id'];
    }

    // Filter theo trạng thái
    if (!empty($filters['status'])) {
      $sql .= " AND t.status = ?";
      $params[] = $filters['status'];
    }

    // Filter theo loại tour (cố định/thường)
    if (isset($filters['is_fixed']) && $filters['is_fixed'] !== '') {
      $sql .= " AND t.is_fixed = ?";
      $params[] = $filters['is_fixed'];
    }

    // Filter theo số ngày
    if (!empty($filters['duration'])) {
      switch ($filters['duration']) {
        case '1-3':
          $sql .= " AND t.duration_days BETWEEN 1 AND 3";
          break;
        case '4-7':
          $sql .= " AND t.duration_days BETWEEN 4 AND 7";
          break;
        case '7+':
          $sql .= " AND t.duration_days > 7";
          break;
      }
    }

    // Filter theo điểm đến
    if (!empty($filters['destination_id'])) {
      $sql .= " AND i.destination_id = ?";
      $params[] = $filters['destination_id'];
    }

    // Filter theo khoảng giá
    if (!empty($filters['min_price'])) {
      $sql .= " AND t.adult_price >= ?";
      $params[] = $filters['min_price'];
    }
    if (!empty($filters['max_price'])) {
      $sql .= " AND t.adult_price <= ?";
      $params[] = $filters['max_price'];
    }

    $sql .= " ORDER BY t.id DESC";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
  }

  // Lấy khoảng giá min/max của tour
  public function getPriceRange()
  {
    $sql = "SELECT MIN(adult_price) as min_price, MAX(adult_price) as max_price FROM tours";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetch();
  }


  // Thêm tour mới
  public function create($data)
  {
    // Tạo tour_code tự động (format: TOUR-YYYYMMDD-XXX)
    $tourCode = $this->generateTourCode();

    $sql = "INSERT INTO tours 
            (category_id, tour_code, name, introduction, adult_price, child_price, 
             status, duration_days, is_fixed, created_by, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

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
      $data['is_fixed'] ?? 0,
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
              is_fixed = ?,
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
      $data['is_fixed'] ?? 0,
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
      if (is_array($category_id)) {
        $placeholders = implode(',', array_fill(0, count($category_id), '?'));
        $sql .= " AND t.category_id IN ($placeholders)";
        $params = array_merge($params, $category_id);
      } else {
        $sql .= " AND t.category_id = ?";
        $params[] = $category_id;
      }
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

  // Lấy tất cả tour_services kèm thông tin service
  public function getAllTourServices()
  {
    $sql = "SELECT ts.*, s.estimated_price 
              FROM tour_services ts
              JOIN services s ON ts.service_id = s.id";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }


  // Lấy services của một tour cụ thể
  public function getServicesByTourId($tourId)
  {
    $sql = "SELECT ts.*, s.name, s.estimated_price 
            FROM tour_services ts
            JOIN services s ON ts.service_id = s.id
            WHERE ts.tour_id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$tourId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }


  // Gắn service vào tour
  public function attachService($tourId, $serviceId, $userId)
  {
    $sql = "INSERT INTO tour_services (tour_id, service_id, created_by, created_at) 
            VALUES (?, ?, ?, NOW())";
    $stmt = $this->conn->prepare($sql);
    return $stmt->execute([$tourId, $serviceId, $userId]);
  }

  // Xóa tất cả services của tour
  public function detachAllServices($tourId)
  {
    $sql = "DELETE FROM tour_services WHERE tour_id = ?";
    $stmt = $this->conn->prepare($sql);
    return $stmt->execute([$tourId]);
  }

  // Lấy services của tour
  public function getTourServices($tourId)
  {
    $sql = "SELECT s.* 
            FROM services s
            INNER JOIN tour_services ts ON s.id = ts.service_id
            WHERE ts.tour_id = ?";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$tourId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  // Thống kê tour
  public function getTourStats()
  {
    // Tổng số tour
    $sqlTotal = "SELECT COUNT(*) as total FROM tours";
    $stmtTotal = $this->conn->prepare($sqlTotal);
    $stmtTotal->execute();
    $total = $stmtTotal->fetch(PDO::FETCH_ASSOC)['total'];

    // Tour đang hoạt động (đang diễn ra)
    $sqlActive = "SELECT COUNT(*) as active FROM tours WHERE status = 1";
    $stmtActive = $this->conn->prepare($sqlActive);
    $stmtActive->execute();
    $active = $stmtActive->fetch(PDO::FETCH_ASSOC)['active'];

    // Tour đang trong chuyến đi
    $sqlOngoing = "SELECT COUNT(DISTINCT t.id) as ongoing 
                      FROM tours t
                      JOIN bookings b ON t.id = b.tour_id
                      WHERE b.start_date <= CURDATE() AND b.end_date >= CURDATE() AND b.status IN (2, 3)";
    $stmtOngoing = $this->conn->prepare($sqlOngoing);
    $stmtOngoing->execute();
    $ongoing = $stmtOngoing->fetch(PDO::FETCH_ASSOC)['ongoing'];

    return [
      'total' => $total,
      'active' => $active,
      'ongoing' => $ongoing
    ];
  }
}
