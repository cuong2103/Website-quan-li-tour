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
        $sql = "INSERT INTO users (fullname, email, phone, password, role_id, status, avatar)
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['fullname'],
            $data['email'],
            $data['phone'],
            $data['password'],
            $data['role_id'],
            $data['status'],
            $data['avatar'] ?? null,
        ]);
    }

    // UPDATE USER
        public function update($data, $id)
    {
        $fields = [];
        $params = [];

        // Các trường chính
        foreach(['fullname', 'email', 'phone', 'role_id', 'status'] as $field){
            if(isset($data[$field])){
                $fields[] = "$field=?";
                $params[] = $data[$field];
            }
        }

        // Password nếu có
        if(isset($data['password'])){
            $fields[] = "password=?";
            $params[] = $data['password'];
        }

        // Avatar nếu có
        if(isset($data['avatar'])){
            $fields[] = "avatar=?";
            $params[] = $data['avatar'];
        }

        $params[] = $id; // id ở cuối

        $sql = "UPDATE users SET ".implode(", ", $fields)." WHERE id=?";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($params);
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

    // CHECK LOGIN
    public function checkLogin($email, $password)
    {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['email' => $email]);

        $user = $stmt->fetch();

        if (!$user) {
            return "Không tìm thấy email trong DB";
        }

        // Tạm thời so sánh trực tiếp (chưa mã hóa)
        if ($password !== $user['password']) {
            return "Sai mật khẩu";
        }

        // Check tài khoản đang khóa hay không
        if ($user['status'] != 1) {
            return "Tài khoản đang bị khóa";
        }

        return $user;
    }
}
