<?php
class SupplierModel
{
    public $conn;
    public function __construct()
    {
        $this->conn = connectDB();
    }

    //lấy toàn bộ ncc
    public function getALL()
    {
        $sql = "SELECT * FROM suppliers ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // lấy danh sách điểm đến
    public function getDestinations()
    {
        $sql = "SELECT * FROM destinations ORDER BY name ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    // lấy 1 nhà cung cấp theo id
    public function getByID($id)
    {
        $sql = "SELECT * FROM suppliers WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    //Thêm nhà cung cấp
    public function create($name, $email, $phone, $destination_id, $created_by)
    {
        $sql = "INSERT INTO `suppliers` ( `name`, `email`, `phone`, `destination_id`, `created_by`) VALUES ( :name, :email, :phone, :destination_id, :created_by)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":phone", $phone);
        $stmt->bindParam(":destination_id", $destination_id);
        $stmt->bindParam(":created_by", $created_by);
        return  $stmt->execute();
    }
    //sửa
    public function update($id, $name, $email, $phone, $destination_id, $created_by)
    {
        $sql = "UPDATE suppliers
                SET name = :name,
                    email = :email,
                    phone = :phone,
                    destination_id = :destination_id,
                    created_by = :created_by,
                    updated_at = NOW()
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":phone", $phone);
        $stmt->bindParam(":destination_id", $destination_id);
        $stmt->bindParam(":created_by", $created_by);
        return  $stmt->execute();
    }
    // xoá
    public function delete($id)
    {
        $sql = "DELETE FROM `suppliers` WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
    }
    //xem
    public function detail($id)
    {
        $sql = "SELECT * FROM `suppliers` WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        $data = $stmt->execute();
        return $data;
    }
}
