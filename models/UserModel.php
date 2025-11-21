<?php
class UserModel
{
  public $conn;
  public function __construct()
  {
    $this->conn = connectDB();
    //goị kết nối từ common
  }

  public function getAll()
  {
    $sql = "SELECT * FROM users";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
  }

  public function loginByEmail($email){
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$email]);
    return $stmt->fetch();
  }

  public function checkLogin($email, $password) {
    $sql = "SELECT * FROM users WHERE email = :email";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if (!$user) {
        return "Không tìm thấy email trong DB";
    }

    // TẠM THỜI so sánh trực tiếp chưa mã hoá password
    if ($password !== $user['password']) {
        return "Sai mật khẩu";
    }

    // Kiểm tra status
    if ($user['status'] != 1) {
        return "Tài khoản đang bị khóa";
    }

    return $user;
}

}
