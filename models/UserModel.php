<?php
class UserModel
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    // LẤY TẤT CẢ USER (với tìm kiếm và lọc)
    public function getAll($search = '', $role = '')
    {
        $sql = "SELECT * FROM users WHERE 1=1";
        $params = [];

        // Tìm kiếm theo tên, email, số điện thoại
        if (!empty($search)) {
            $sql .= " AND (fullname LIKE :search OR email LIKE :search OR phone LIKE :search)";
            $params[':search'] = "%$search%";
        }

        // Lọc theo vai trò
        if (!empty($role)) {
            $sql .= " AND roles = :role";
            $params[':role'] = $role;
        }

        $sql .= " ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
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
        $sql = "INSERT INTO users (fullname, email, phone, password, roles, status, avatar, created_by, updated_by)
                VALUES (:fullname, :email, :phone, :password, :roles, :status, :avatar, :created_by, :updated_by)";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':fullname' => $data['fullname'],
            ':email' => $data['email'],
            ':phone' => $data['phone'],
            ':password' => $data['password'],
            ':roles' => $data['roles'],
            ':status' => $data['status'],
            ':avatar' => $data['avatar'] ?? null,
            ':created_by' => $data['created_by'] ?? null,
            ':updated_by' => $data['updated_by'] ?? null,
        ]);
    }

    // UPDATE USER
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
            updated_by = :updated_by,
            updated_at = NOW()
        WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        $params = [
            ':id' => $id,
            ':fullname' => $data['fullname'],
            ':email' => $data['email'],
            ':phone' => $data['phone'],
            ':avatar' => $data['avatar'] ?? null,
            ':roles' => $data['roles'],
            ':status' => $data['status'],
            ':updated_by' => $data['updated_by'] ?? null,
        ];

        // Handle password update if provided
        if (!empty($data['password'])) {
            $sql = str_replace("updated_at = NOW()", "password = :password, updated_at = NOW()", $sql);
            $stmt = $this->conn->prepare($sql);
            $params[':password'] = $data['password'];
        }

        return $stmt->execute($params);
    }


    // XÓA USER
    public function delete($id)
    {
        try {
            $sql = "DELETE FROM users WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            if ($e->getCode() == '23000') {
                return "Không thể xóa nhân viên này vì đang có dữ liệu liên quan (Tour, Booking, v.v...)";
            } else {
                return $e->getMessage();
            }
        }
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
    // Check loggin
    public function checkLogin($email, $password)
    {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['email' => $email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // if (!$user) {
        //     return "Không tìm thấy email trong DB";
        // }

        // // So sánh mật khẩu hash
        // if (!password_verify($password, $user['password'])) {
        //     return "Tài khoản hoặc mật khẩu không đúng";
        // }

        // if ($user['status'] != 1) {
        //     return "Tài khoản đang bị khóa";
        // }

        return $user;
    }

    // LẤY DANH SÁCH HDV ĐANG NGHỈ PHÉP
    public function getOnLeave()
    {
        $sql = "SELECT * FROM users 
                WHERE leave_start IS NOT NULL 
                AND leave_end IS NOT NULL 
                ORDER BY leave_start DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
