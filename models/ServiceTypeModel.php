<?php
class ServiceTypeModel
{
  public $conn;
  public function __construct()
  {
    $this->conn = connectDB();
  }
  // lấy dữ liệu
  public function getAll()
  {
    $sql = "SELECT * FROM service_types ";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
  }
  // xem chi tiết
  public function getDetail($id)
  {
    $sql = "SELECT * FROM service_types WHERE id = :id";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }
  // thêm
  public function create($name, $description, $created_by)
  {
    $sql = "INSERT INTO service_types (name, description, created_by) 
              VALUES (:name, :description, :created_by)";
    $stmt = $this->conn->prepare($sql);
    // gắn các giá trị
    $stmt->bindParam(":name", $name);
    $stmt->bindParam(":description", $description);
    $stmt->bindParam(":created_by", $created_by);
    return $stmt->execute();
  }
  // xóa
  public function delete($id)
  {
    $sql = "DELETE FROM service_types WHERE id = :id";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(":id", $id);
    return $stmt->execute();
  }
  //sửa
  public function update($id, $name, $description)
  {
    $sql = "UPDATE service_types 
        SET name = :name , 
            description = :description , 
            updated_at = now() 
            WHERE id = :id";

    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(":name", $name);
    $stmt->bindParam(":description", $description);
    $stmt->bindParam(":id", $id);
    return $stmt->execute();
  }
  //tìm kiếm
  public function search($keyword)
  {
    $sql = "SELECT * FROM service_types WHERE name LIKE :keyword";
    $stmt = $this->conn->prepare($sql);
    $keyword = "%$keyword%";
    $stmt->bindParam(":keyword", $keyword);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}
