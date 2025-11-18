<?php
class UserModel
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
    $sql = "SELECT * FROM users LIMIT 5";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
  }
}
