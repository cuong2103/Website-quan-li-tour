<?php
class ServiceTypeModel
{
  public $conn;

  public function __construct()
  {
    $this->conn = connectDB();
  }

  // Lấy tất cả dữ liệu
  public function getAll()
  {
    $sql = "SELECT * FROM service_types ORDER BY id DESC";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  // Xem chi tiết
  public function getDetail($id)
  {
    $sql = "
          SELECT st.*, 
                 u1.fullname AS creator_name,
                 u2.fullname AS updater_name
          FROM service_types st
          LEFT JOIN users u1 ON st.created_by = u1.id
          LEFT JOIN users u2 ON st.updated_by = u2.id
          WHERE st.id = :id
      ";

    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  // Thêm mới
  public function create($name, $description, $created_by)
  {
    $sql = "INSERT INTO service_types (name, description, created_by) 
                VALUES (:name, :description, :created_by)";
    $stmt = $this->conn->prepare($sql);
    return $stmt->execute([
      ':name' => $name,
      ':description' => $description,
      ':created_by' => $created_by
    ]);
  }

  // Cập nhật
  public function update($id, $name, $description, $updated_by)
  {
    $sql = "UPDATE service_types SET 
                    name = :name,
                    description = :description,
                    updated_by = :updated_by,
                    updated_at = NOW()
                WHERE id = :id";
    $stmt = $this->conn->prepare($sql);
    return $stmt->execute([
      ':name' => $name,
      ':description' => $description,
      ':updated_by' => $updated_by,
      ':id' => $id
    ]);
  }

  // Xóa
  public function delete($id)
  {
    try {
      $sql = "DELETE FROM service_types WHERE id = :id";
      $stmt = $this->conn->prepare($sql);
      $stmt->bindParam(":id", $id);
      return $stmt->execute();
    } catch (PDOException $e) {

      // Kiểm tra lỗi khóa ngoại
      if ($e->getCode() == "23000") {
        return "FOREIGN_KEY_CONSTRAINT";
      }

      return false;
    }
  }

  // Tìm kiếm
  public function search($keyword)
  {
    $sql = "SELECT * FROM service_types WHERE name LIKE :keyword ORDER BY id DESC";
    $stmt = $this->conn->prepare($sql);
    $keyword = "%$keyword%";
    $stmt->bindParam(":keyword", $keyword, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  // Kiểm tra tên dịch vụ đã tồn tại (hỗ trợ bỏ qua id khi update)
  public function existsByName($name, $excludeId = null)
  {
    $sql = "SELECT COUNT(*) FROM service_types WHERE name = :name";
    $params = [':name' => $name];

    if ($excludeId) {
      $sql .= " AND id != :excludeId";
      $params[':excludeId'] = $excludeId;
    }

    $stmt = $this->conn->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchColumn() > 0;
  }
}
