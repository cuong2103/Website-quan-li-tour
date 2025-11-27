<?php
class ContractModel
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    // Lấy tất cả hợp đồng
    public function getAll()
    {
        try {
            $sql = "SELECT * FROM customer_contracts ORDER BY id DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Lỗi ContractModel::getAll(): " . $e->getMessage());
        }
    }

    // Lấy hợp đồng theo booking ID
    public function getByBookingId($bookingId)
    {
        try {
            $sql = "SELECT * FROM customer_contracts WHERE booking_id = ? ORDER BY id DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$bookingId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Lỗi getByBookingId(): " . $e->getMessage());
        }
    }

    // Lấy 1 hợp đồng theo id
    public function getById($id)
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM customer_contracts WHERE id=?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Lỗi getById(): " . $e->getMessage());
        }
    }
    
    public function findById($id)
    {
        try {
            $sql = "SELECT cc.*, 
                           b.id AS booking_id,
                           c.name AS customer_name,
                           c.email AS customer_email,
                           c.phone AS customer_phone
                    FROM customer_contracts cc
                    LEFT JOIN bookings b ON b.id = cc.booking_id
                    LEFT JOIN customers c ON c.id = cc.customer_id
                    WHERE cc.id = :id";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo "Lỗi: " . $e->getMessage();
            return false;
        }
    }



    // Tạo hợp đồng
    public function create($data)
    {
        try {
            $sql = "INSERT INTO customer_contracts 
                    (booking_id, contract_name, signing_date, effective_date, expiry_date,
                     signer_id, customer_id, status, file_name, file_url, notes, created_at)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                $data['booking_id'],
                $data['contract_name'],
                $data['signing_date'],
                $data['effective_date'],
                $data['expiry_date'],
                $data['signer_id'],
                $data['customer_id'],
                $data['status'],
                $data['file_name'],
                $data['file_url'],
                $data['notes']
            ]);

            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            die("Lỗi ContractModel::create(): " . $e->getMessage());
        }
    }

    // Cập nhật hợp đồng
    public function update($id, $data)
    {
        try {
            $sql = "UPDATE customer_contracts 
                    SET contract_name=?, signing_date=?, effective_date=?, expiry_date=?, 
                        signer_id=?, customer_id=?, status=?, file_name=?, file_url=?, notes=?, updated_at=NOW()
                    WHERE id=?";

            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                $data['contract_name'],
                $data['signing_date'],
                $data['effective_date'],
                $data['expiry_date'],
                $data['signer_id'],
                $data['customer_id'],
                $data['status'],
                $data['file_name'],
                $data['file_url'],
                $data['notes'],
                $id
            ]);
        } catch (PDOException $e) {
            die("Lỗi ContractModel::update(): " . $e->getMessage());
        }
    }

    // Xóa
    public function delete($id)
    {
        try {
            $stmt = $this->conn->prepare("DELETE FROM customer_contracts WHERE id=?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            die("Lỗi ContractModel::delete(): " . $e->getMessage());
        }
    }
}
