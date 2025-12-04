<?php
class UserModel
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    // LẤY TOÀN BỘ USER
    public function getAll()
    {
        $sql = "SELECT * FROM users";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // LẤY USER THEO ID
    public function getById($id)
    {
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // THÊM USER
    public function create($data)
    {
        $sql = "INSERT INTO users (fullname, email, phone, password, roles, status, avatar)
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['fullname'],
            $data['email'],
            $data['phone'],
            $data['password'],
            $data['roles'],
            $data['status'],
            $data['avatar'] ?? null,
        ]);
    }

    // UPDATE USER
       // UserModel.php
public function update($id, $data)
{
    $sql = "UPDATE users 
        SET 
            fullname = :fullname, 
            email = :email, 
            phone = :phone, 
            avatar = :avatar, 
            roles = :roles,
            status = :status,
            updated_at = NOW()
        WHERE id = :id";

    $stmt = $this->conn->prepare($sql);

    return $stmt->execute([
        ':id' => $id,
        ':fullname' => $data['fullname'],
        ':email' => $data['email'],
        ':phone' => $data['phone'],
        ':avatar' => $data['avatar'] ?? null,
        ':roles' => $data['roles'], // phải đúng kiểu trong DB
        ':status' => $data['status'], // 0 hoặc 1
    ]);

    
}


    // XÓA USER
    public function delete($id)
    {
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]);
    }

    // CHECK EMAIL TỒN TẠI
    public function emailExists($email, $excludeId = null)
    {
        $sql = "SELECT id FROM users WHERE email = ?" . ($excludeId ? " AND id != ?" : "");
        $stmt = $this->conn->prepare($sql);

        $params = $excludeId ? [$email, $excludeId] : [$email];

        $stmt->execute($params);

        return $stmt->rowCount() > 0;
    }

    // LOGIN: LẤY THEO EMAIL
    public function loginByEmail($email)
    {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    // KIỂM TRA MẬT KHẨU CŨ
    public function verifyPassword($userId, $currentPassword){
        $sql = "SELECT password FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$userId]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$user) return false;

        // So sánh trực tiếp
        return $currentPassword === $user['password'];
    }

    // ĐỔI MẬT KHẨU
    public function changePassword($userId, $newPassword){
        $sql = "UPDATE users SET password = ?, updated_at = NOW() WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$newPassword, $userId]);
    }

    // CHECK LOGIN trực tiếp
    public function checkLogin($email, $password)
    {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['email' => $email]);

        $user = $stmt->fetch();

        if (!$user) {
            return "Không tìm thấy email trong DB";
        }

        // So sánh trực tiếp
        if ($password !== $user['password']) {
            return "Sai mật khẩu";
        }

        if ($user['status'] != 1) {
            return "Tài khoản đang bị khóa";
        }

        return $user;
    }


}