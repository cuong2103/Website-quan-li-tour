<?php
class CategoryModel
{
  public $conn;
  public function __construct()
  {
    $this->conn = connectDB();
    // tự động fix lỗi thiết kế db sai ban đầu (cho phép trùng tên nếu khác cha)
    try {
        $this->conn->exec("ALTER TABLE categories DROP INDEX name");
    } catch (\Throwable $th) {
        // index đã drop, bỏ qua
    }
  }

  // Kiểm tra trùng tên trong cùng một cấp (cùng parent_id)
  public function isDuplicate($name, $parent_id, $exclude_id = null)
  {
    $sql = "SELECT id FROM categories WHERE name = :name";
    if ($parent_id === null || $parent_id === "") {
        $sql .= " AND parent_id IS NULL";
    } else {
        $sql .= " AND parent_id = :parent_id";
    }

    if ($exclude_id) {
        $sql .= " AND id != :exclude_id";
    }

    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(':name', $name);
    
    if ($parent_id !== null && $parent_id !== "") {
        $stmt->bindValue(':parent_id', $parent_id, PDO::PARAM_INT);
    }
    if ($exclude_id) {
        $stmt->bindValue(':exclude_id', $exclude_id, PDO::PARAM_INT);
    }

    $stmt->execute();
    return $stmt->fetch() !== false;
  }

  // Viết truy vấn danh sách sản phẩm 
  public function getAll()
  {
    $sql = "SELECT * FROM categories ";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
  }

  public function getTotalCategories()
  {
    $sql = "SELECT COUNT(*) as total_categories FROM categories";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total_categories'] ?? 0;
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
