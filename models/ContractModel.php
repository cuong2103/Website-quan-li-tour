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
                           b.booking_code,
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
            error_log("Lỗi ContractModel::findById: " . $e->getMessage());
            return false;
        }
    }

    public function getCustomers($bookingId)
    {
        $sql = "SELECT c.id, c.name FROM booking_customers bc
                JOIN customers c ON bc.customer_id = c.id
                WHERE bc.booking_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$bookingId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Tạo hợp đồng
    public function create($data)
    {
        try {
            $sql = "INSERT INTO customer_contracts 
                    (booking_id, contract_name, effective_date, expiry_date,
                     signer_id, customer_id, status, file_name, file_url, created_by, created_at)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                $data['booking_id'],
                $data['contract_name'],
                $data['effective_date'],
                $data['expiry_date'],
                $data['signer_id'],
                $data['customer_id'],
                $data['status'],
                $data['file_name'],
                $data['file_url'],
                $data['created_by']
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
                    SET contract_name=?, effective_date=?, expiry_date=?, 
                        signer_id=?, customer_id=?, status=?, file_name=?, file_url=?, updated_by=?, updated_at=NOW()
                    WHERE id=?";

            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                $data['contract_name'],
                $data['effective_date'],
                $data['expiry_date'],
                $data['signer_id'],
                $data['customer_id'],
                $data['status'],
                $data['file_name'],
                $data['file_url'],
                $data['updated_by'],
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

    // Tự động cập nhật trạng thái các hợp đồng hết hạn
    public function autoUpdateStatus()
    {
        try {
            $sql = "UPDATE customer_contracts 
                    SET status = 'expired' 
                    WHERE status = 'active' AND expiry_date < CURDATE()";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute();
        } catch (PDOException $e) {
            die("Lỗi autoUpdateStatus: " . $e->getMessage());
        }
    }
}
