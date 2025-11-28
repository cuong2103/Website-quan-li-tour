<?php

class PaymentModel
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    public function getAllByBooking($booking_id)
    {
        $sql = "SELECT * FROM payments WHERE booking_id = :booking_id ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['booking_id' => $booking_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id)
    {
        $sql = "SELECT * FROM payments WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function store($data)
    {
        try {
            $sql = "INSERT INTO payments 
                    (booking_id, payment_method, amount, type, status, notes, payment_date, created_by, created_at) 
                    VALUES 
                    (:booking_id, :payment_method, :amount, :type, :status, :notes, :payment_date, :created_by, NOW())";

            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                ':booking_id'     => $data['booking_id'],
                ':payment_method' => $data['payment_method'],
                ':amount'         => $data['amount'],
                ':type'           => $data['type'],
                ':status'         => $data['status'],
                ':notes'          => $data['notes'],
                ':payment_date'   => $data['payment_date'],
                ':created_by'     => $data['created_by'],
            ]);
        } catch (PDOException $e) {
            die("Lỗi PaymentModel::store(): " . $e->getMessage());
        }
    }

    public function update($id, $data)
    {
        try {
            $sql = "UPDATE payments SET
                        payment_method = :payment_method,
                        amount = :amount,
                        type = :type,
                        status = :status,
                        notes = :notes,
                        payment_date = :payment_date,
                        updated_at = NOW()
                    WHERE id = :id";

            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                ':payment_method' => $data['payment_method'],
                ':amount'         => $data['amount'],
                ':type'           => $data['type'],
                ':status'         => $data['status'],
                ':notes'          => $data['notes'],
                ':payment_date'   => $data['payment_date'],
                ':id'             => $id,
            ]);
        } catch (PDOException $e) {
            die("Lỗi PaymentModel::update(): " . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $sql = "DELETE FROM payments WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
