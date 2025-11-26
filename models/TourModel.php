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
    $sql = "SELECT 
    t.id AS tour_id,
    t.name AS tour_name,
    t.introduction,
    t.adult_price,
    t.child_price,
    t.status AS tour_status,
    c.name AS category_name,
    c.id AS category_id,
    c.description AS category_description,
    d.id AS destination_id,
    d.name AS destination_name,
    d.address AS destination_address,
    d.description AS destination_description,
    di.image_url AS destination_image,
    i.id AS itinerary_id,
    i.order_number,
    i.description AS itinerary_description,
    i.arrival_time,
    i.departure_time,
    p.id AS policy_id,
    p.name AS policy_name,
    p.content AS policy_content
FROM tours t
LEFT JOIN categories c ON t.category_id = c.id
LEFT JOIN itineraries i ON t.id = i.tour_id
LEFT JOIN destinations d ON i.destination_id = d.id
LEFT JOIN countries co ON d.country_id = co.id
LEFT JOIN destination_images di ON d.id = di.destination_id
LEFT JOIN tour_policies tp ON t.id = tp.tour_id
LEFT JOIN policies p ON tp.policy_id = p.id
WHERE t.id = :tour_id";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':tour_id', $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
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
    $sql = "INSERT INTO itineraries (tour_id, destination_id, arrival_time, departure_time, description) 
              VALUES (?, ?, ?, ?, ?)";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([
      $data['tour_id'],
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
