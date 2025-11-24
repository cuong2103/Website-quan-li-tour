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
            (category_id, name, introduction, adult_price, child_price, base_price, status, created_by)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $this->conn->prepare($sql);

    return $stmt->execute([
      $data['category_id'],
      $data['name'],
      $data['introduction'],
      $data['adult_price'],
      $data['child_price'],
      $data['base_price'],
      $data['status'],
      $data['created_by']
    ]);
  }

  // Lấy tour theo ID
  public function getById($id)
  {
    $sql = "SELECT * FROM tours WHERE id = :id";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetch();
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
              base_price = ?,
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
      $data['base_price'],
      $data['status'],
      $id
    ]);
  }

  // Xóa tour
  public function delete($id)
  {
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
}
