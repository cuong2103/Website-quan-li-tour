<?php
class CategoryModel
{
  public $conn;
  public function __construct()
  {
    $this->conn = connectDB();
    //goị kết nối từ common
  }

  // Viết truy vấn danh sách sản phẩm 
  public function getAll()
  {
    $sql = "SELECT * FROM categories ";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
  }

  public function create($data)
  {
    $sql = "INSERT INTO categories (name, parent_id, created_by)
                VALUES (?, ?, ?)";
    $stmt = $this->conn->prepare($sql);
    return $stmt->execute([
      $data['name'],
      $data['parent_id'],
      $data['created_by']
    ]);
  }
  public function getById($id)
  {
    $sql = "SELECT * FROM categories WHERE id = :id";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetch();
  }
  public function hasChildren($id)
  {
    $sql = "SELECT COUNT(*) FROM categories WHERE parent_id = :id";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    return $stmt->fetchColumn();
  }
  public function update($name, $parent_id, $id)
  {
    $sql = "UPDATE categories 
                    SET name = ?, parent_id =? , updated_at = NOW()
                    WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    return $stmt->execute([
      $name,
      $parent_id,
      $id
    ]);
  }

  public function delete($id)
  {
    $sql = "DELETE FROM categories WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    return $stmt->execute([$id]);
  }
}
