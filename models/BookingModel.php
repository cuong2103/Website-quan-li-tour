<?php
class BookingModel
{
    private $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    /* =====================
        LẤY DANH SÁCH BOOKING
    ====================== */
    public function getAll()
    {
        $sql = "SELECT b.*, t.name AS tour_name
                FROM bookings b
                LEFT JOIN tours t ON t.id = b.tour_id
                ORDER BY b.id DESC";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $sql = "SELECT b.*, t.name AS tour_name
                FROM bookings b
                LEFT JOIN tours t ON t.id = b.tour_id
                WHERE b.id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        $booking = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($booking) {
            $booking['customers'] = $this->getCustomers($id);
        }

        return $booking;
    }

    /* =====================
        TẠO BOOKING MỚI
    ====================== */
    public function create($data)
    {
        $sql = "INSERT INTO bookings 
                (tour_id, start_date, end_date, adult_count, child_count, total_amount, deposit_amount, remaining_amount, status, special_requests, created_by)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            $data['tour_id'],
            $data['start_date'],
            $data['end_date'],
            $data['adult_count'],
            $data['child_count'],
            $data['total_amount'],
            $data['deposit_amount'] ?? 0,
            $data['remaining_amount'] ?? 0,
            $data['status'] ?? 1,
            $data['special_requests'] ?? null,
            $data['created_by'] ?? 1
        ]);

        $bookingId = $this->conn->lastInsertId();

        // Nếu có khách hàng được chọn
        if (!empty($data['customers']) && is_array($data['customers'])) {
            foreach ($data['customers'] as $customerId) {
                $this->addCustomer($bookingId, $customerId);
            }
        }

        return $bookingId;
    }

    /* =====================
        CẬP NHẬT BOOKING
    ====================== */
    public function update($id, $data)
    {
        $sql = "UPDATE bookings
                SET tour_id=?, start_date=?, end_date=?, adult_count=?, child_count=?, total_amount=?, deposit_amount=?, remaining_amount=?, status=?, special_requests=?, updated_at=NOW()
                WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['tour_id'],
            $data['start_date'],
            $data['end_date'],
            $data['adult_count'],
            $data['child_count'],
            $data['total_amount'],
            $data['deposit_amount'] ?? 0,
            $data['remaining_amount'] ?? 0,
            $data['status'],
            $data['special_requests'] ?? null,
            $id
        ]);
    }

    /* =====================
        XÓA BOOKING
    ====================== */
    public function delete($id)
    {
        $this->conn->beginTransaction();
        try {
            $this->conn->prepare("DELETE FROM booking_customers WHERE booking_id = ?")->execute([$id]);
            $this->conn->prepare("DELETE FROM customer_contracts WHERE booking_id = ?")->execute([$id]);
            $this->conn->prepare("DELETE FROM payments WHERE booking_id = ?")->execute([$id]);

            $stmt = $this->conn->prepare("SELECT id FROM tour_assignments WHERE booking_id = ?");
            $stmt->execute([$id]);
            $assignments = $stmt->fetchAll(PDO::FETCH_COLUMN);

            foreach ($assignments as $assignId) {
                $journals = $this->conn->prepare("SELECT id FROM journals WHERE tour_assignment_id = ?");
                $journals->execute([$assignId]);
                $journals = $journals->fetchAll(PDO::FETCH_COLUMN);

                foreach ($journals as $journalId) {
                    $this->conn->prepare("DELETE FROM journal_images WHERE journal_id = ?")->execute([$journalId]);
                }

                $this->conn->prepare("DELETE FROM journals WHERE tour_assignment_id = ?")->execute([$assignId]);
                $this->conn->prepare("DELETE FROM customer_checkins WHERE tour_assignment_id = ?")->execute([$assignId]);
            }

            $this->conn->prepare("DELETE FROM tour_assignments WHERE booking_id = ?")->execute([$id]);
            $this->conn->prepare("DELETE FROM bookings WHERE id = ?")->execute([$id]);

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    /* =====================
        DANH SÁCH TOUR
    ====================== */
    public function getTours()
    {
        return $this->conn->query("SELECT id, name, adult_price, child_price FROM tours ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
    }

    /* =====================
        QUẢN LÝ KHÁCH HÀNG BOOKING
    ====================== */
    public function addCustomer($bookingId, $customerId)
    {
        $stmt = $this->conn->prepare("INSERT INTO booking_customers (booking_id, customer_id) VALUES (?, ?)");
        return $stmt->execute([$bookingId, $customerId]);
    }

    public function getCustomers($bookingId)
    {
        $stmt = $this->conn->prepare("
            SELECT c.* 
            FROM booking_customers bc
            JOIN customers c ON c.id = bc.customer_id
            WHERE bc.booking_id = ?
        ");
        $stmt->execute([$bookingId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
