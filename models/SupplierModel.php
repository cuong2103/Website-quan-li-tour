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
        $sql = "SELECT * FROM suppliers";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // lấy danh sách điểm đến
    public function getDestinations()
    {
        $sql = "SELECT * FROM suppliers JOIN destinations ON suppliers.destination_id = destinations.id ORDER BY destinations.name ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    // lấy 1 nhà cung cấp theo id
    public function getByID($id)
    {
        $sql = "SELECT s.* , d.name as destination_name
            FROM suppliers s
            LEFT JOIN destinations d ON s.destination_id = d.id 
            WHERE s.id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    //Thêm nhà cung cấp
    public function create($data)
    {
        $sql = "INSERT INTO `suppliers` ( `name`, `email`, `phone`, `status`, `destination_id`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES ( :name, :email, :phone, :status, :destination_id, :created_by, :updated_by, NOW(), NOW())";

        $stmt = $this->conn->prepare($sql);
        return  $stmt->execute([
            ':name' => $data['name'],
            ':email' => $data['email'],
            ':phone' => $data['phone'],
            ':status' => $data['status'],
            ':destination_id' => $data['destination_id'],
            ':created_by' => $data['created_by'],
            ':updated_by' => $data['updated_by']
        ]);
    }
    //sửa
    public function update($data)
    {
        $sql = "UPDATE suppliers
                SET name = :name,
                    email = :email,
                    phone = :phone,
                    destination_id = :destination_id,
                    status = :status,
                    updated_by = :updated_by,
                    updated_at = NOW()
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        return  $stmt->execute([
            ':id' => $data['id'],
            ':name' => $data['name'],
            ':email' => $data['email'],
            ':phone' => $data['phone'],
            ':status' => $data['status'],
            ':destination_id' => $data['destination_id'],
            ':updated_by' => $data['updated_by']
        ]);
    }
    // xoá
    public function delete($id)
    {
        try {
            $sql = "DELETE FROM `suppliers` WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
        } catch (PDOException $e) {
            // Xử lý lỗi nếu có ràng buộc khoá ngoại
            if ($e->getCode() == '23000') {
                return "FOREIGN_KEY_CONSTRAINT";
            } else {
                throw $e; // Ném lại ngoại lệ nếu không phải lỗi khoá ngoại
            }
        }
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
