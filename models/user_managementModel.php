<?php
class user_managementModel{
    public $conn;
    public function __construct()
    {
        $this->conn = connectDB();
    }
    //lấy toàn bộ dữ liệu
    public function getAll(){
        $sql = "SELECT * FROM users";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
        public function getById($id)
        {
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
        }  

    // thêm
        public function create($data){
        $sql = "INSERT INTO users (fullname, email, phone, password, role_id, status)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['fullname'],
            $data['email'],
            $data['phone'],
            $data['password'],   
            $data['role_id'],
            $data['status'],
        ]);
    }


    public function update($data, $id){
            $sql = "UPDATE users SET fullname=?, email=?, phone=?, role_id=?, status=? WHERE id=?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                $data['fullname'],
                $data['email'],
                $data['phone'],
                $data['role_id'],
                $data['status'],
                $id
            ]);
    }

    public function emailExists($email, $excludeId = null){
        $sql = "SELECT id  FROM users WHERE email = ?" . ($excludeId ? "AND id != ?" : " ");
        $stmt = $this->conn->prepare($sql);
        $params = $excludeId ? [$email, $excludeId] : [$email];
        $stmt->execute($params);
        return $stmt->rowCount() > 0;
    }

    public function delete($id){
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]);
    }

}