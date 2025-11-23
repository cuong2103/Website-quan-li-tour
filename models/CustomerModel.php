<?php
class CustomerModel
{
    public $conn;
    public function __construct()
    {
        $this->conn = connectDB();
    }
    // lấy toàn bộ roles
    public function getALL()
    {
        $sql = "SELECT * FROM customers ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // lấy 1 khách hàng theo id
    public function getByID($id)
    {
        $sql = "SELECT * FROM customers WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    // thêm mới khách hàng
    public function create($name, $email, $phone, $address, $created_by, $passport, $gender)
    {
        $sql = "INSERT INTO customers (`name`, `email`, `phone`, `address`, `created_by`, `passport`, `gender`) VALUES (:name, :email, :phone, :address, :created_by, :passport, :gender)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":phone", $phone);
        $stmt->bindParam(":address", $address);
        $stmt->bindParam(":created_by", $created_by);
        $stmt->bindParam(":passport", $passport);
        $stmt->bindParam(":gender", $gender);
        return $stmt->execute();
    }
    // cập nhật khách hàng
    public function update($id, $name, $email, $phone, $address, $created_by, $passport, $gender)
    {
        $sql = "UPDATE customers SET name = :name, email = :email, phone = :phone, address = :address, created_by = :created_by WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":phone", $phone);
        $stmt->bindParam(":address", $address);
        $stmt->bindParam(":created_by", $created_by);
        $stmt->bindParam(":passport", $passport);
        $stmt->bindParam(":gender", $gender);
        return $stmt->execute();
    }
    // xoá khách hàng
    public function delete($id)
    {
        $sql = "DELETE FROM customers WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }
    public function detail($id)
    {
        $sql = "SELECT * FROM customers WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function filter($search = '',)
    {
        try {
            $sql = "SELECT * FROM customers WHERE 1";
            $params = [];

            // Lọc theo tên và email
            if (!empty($search)) {
                $sql .= " AND name LIKE :name OR email LIKE :email OR phone LIKE :phone OR address LIKE :address";
                $params['name'] = "%" . $search . "%";
                $params['email'] = "%" . $search . "%";
                $params['phone'] = "%$search%";
                $params['address'] = "%$search%";
            }

            // Sắp xếp mới nhất
            $sql .= " ORDER BY id DESC";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }
    }
}
