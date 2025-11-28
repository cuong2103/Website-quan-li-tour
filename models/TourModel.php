<?php
class TourModel
{
  public $conn;

  public function __construct()
  {
    $this->conn = connectDB(); // gọi kết nối DB
  }

  // Lấy danh sách tour
  public function getAll()
  {
    $sql = "SELECT * FROM tours ORDER BY id DESC";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
  }

  // Thêm tour mới
  public function create($data)
  {
    $sql = "INSERT INTO tours 
            (category_id, name, introduction, adult_price, child_price, status, created_by, created_at)
            VALUES (?, ?, ?, ?, ?, ?, ?,NOW())";

    $stmt = $this->conn->prepare($sql);

    $stmt->execute([
      $data['category_id'],
      $data['name'],
      $data['introduction'],
      $data['adult_price'],
      $data['child_price'],
      $data['status'],
      $data['created_by']
    ]);
    return $this->conn->lastInsertId();
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

  public function getItinerariesByTourId($tourId)
  {
    $sql = "SELECT * FROM itineraries WHERE tour_id = ? ORDER BY order_number ASC";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$tourId]);
    return $stmt->fetchAll();
  }

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
  public function update($id, $data)
  {
    $sql = "UPDATE tours SET
              category_id = ?,
              name = ?,
              introduction = ?,
              adult_price = ?,
              child_price = ?,
              status = ?,
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
      $id
    ]);
  }

  // Xóa tour
  public function delete($id)
  {
    $sql1 = "DELETE FROM itineraries WHERE tour_id = ?";
    $stmt1 = $this->conn->prepare($sql1);
    $stmt1->execute([$id]);

    $sql2 = "DELETE FROM tour_policies WHERE tour_id = ?";
    $stmt2 = $this->conn->prepare($sql2);
    $stmt2->execute([$id]);

    $sql3 = "DELETE FROM itineraries WHERE tour_id = ?";
    $stmt3 = $this->conn->prepare($sql3);
    $stmt3->execute([$id]);

    $sql = "DELETE FROM tours WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    return $stmt->execute([$id]);
  }

  // Filter tìm kiếm tour (name, category_id, status)
  public function filter($ten, $category_id, $status)
  {
    $sql = "SELECT * FROM tours WHERE 1=1";
    $params = [];

    if (!empty($ten)) {
      $sql .= " AND name LIKE ?";
      $params[] = "%$ten%";
    }

    if (!empty($category_id)) {
      $sql .= " AND category_id = ?";
      $params[] = $category_id;
    }

    if (!empty($status)) {
      $sql .= " AND status = ?";
      $params[] = $status;
    }

    $stmt = $this->conn->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll();
  }

  public function attachPolicy($tourId, $policyId)
  {
    $sql = "INSERT INTO tour_policies (tour_id, policy_id) VALUES (?, ?)";
    $stmt = $this->conn->prepare($sql);
    return $stmt->execute([$tourId, $policyId]);
  }

  public function clearPolicies($tourId)
  {
    $sql = "DELETE FROM tour_policies WHERE tour_id = ?";
    $stmt = $this->conn->prepare($sql);
    return $stmt->execute([$tourId]);
  }

  public function getPolicies($tourId)
  {
    $sql = "SELECT policy_id FROM tour_policies WHERE tour_id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$tourId]);
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
  }
  public function addItinerary($data)
  {
    $sql = "INSERT INTO itineraries (tour_id,order_number, destination_id, arrival_time, departure_time, description) 
              VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([
      $data['tour_id'],
      $data['order_number'],
      $data['destination_id'],
      $data['arrival_time'],
      $data['departure_time'],
      $data['description']
    ]);
  }
  public function getTourPolicies($tourId)
  {
    $sql = "SELECT p.* 
            FROM policies p
            INNER JOIN tour_policies tp ON p.id = tp.policy_id
            WHERE tp.tour_id = :tour_id";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute(['tour_id' => $tourId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
  public function getItineraries($tourId)
  {
    $sql = "SELECT ti.*, d.name as destination_name 
            FROM itineraries ti
            LEFT JOIN destinations d ON ti.destination_id = d.id
            WHERE ti.tour_id = :tour_id
            ORDER BY ti.order_number ASC";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute(['tour_id' => $tourId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
  public function deleteItineraries($tourId)
  {
    $sql = "DELETE FROM itineraries WHERE tour_id = :tour_id";
    $stmt = $this->conn->prepare($sql);
    return $stmt->execute(['tour_id' => $tourId]);
  }
  public function detachAllPolicies($tourId)
  {
    $sql = "DELETE FROM tour_policies WHERE tour_id = :tour_id";
    $stmt = $this->conn->prepare($sql);
    return $stmt->execute(['tour_id' => $tourId]);
  }
}
