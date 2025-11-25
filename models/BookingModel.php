<?php
class BookingModel
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    // Lấy danh sách booking
    public function getAll()
    {
        try {
            $sql = "SELECT b.*, t.name AS tour_name
                FROM bookings b
                LEFT JOIN tours t ON t.id = b.tour_id
                ORDER BY b.id DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            die("Lỗi getAll():" . $e->getMessage());
        }
    }

    // Lấy booking theo id
    public function getById($id)
    {
        try {
            $sql = "SELECT b.*, t.name AS tour_name
                FROM bookings b
                LEFT JOIN tours t ON t.id = b.tour_id
                WHERE b.id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            $booking = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($booking) {
                $booking['customers'] = $this->getCustomers($id);
                // --- Lấy người đại diện ---
                $rep = array_filter($booking['customers'], fn($c) => $c['is_representative'] == 1);
                $booking['is_representative'] = $rep ? array_values($rep)[0]['id'] : null;

                $booking['services'] = $this->getServices($id);
            }

            return $booking;
        } catch (PDOException $e) {
            die("Lỗi getById():" . $e->getMessage());
        }
    }

    // Form tạo booking 
    public function create($data)
    {
        try {
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
                $data['created_by'] ?? null
            ]);

            $bookingId = $this->conn->lastInsertId();

            // --- Lưu khách + đại diện ---
            if (!empty($data['customers'])) {
                foreach ($data['customers'] as $customerId) {

                    $isRep = ($data['is_representative'] == $customerId) ? 1 : 0;

                    $this->addCustomer($bookingId, $customerId, $isRep);
                }
            }
            // Lưu dịch vụ
            if (!empty($data['services'])) {
                foreach ($data['services'] as $serviceId) {
                    $this->addService($bookingId, $serviceId);
                }
            }

            return $bookingId;
        } catch (PDOException $e) {
            die("Lỗi create():" . $e->getMessage());
        }
    }

    // Xử lí cấp nhật booking
    public function update($id, $data)
    {
        try {
            $sql = "UPDATE bookings
                SET tour_id=?, start_date=?, end_date=?, adult_count=?, child_count=?, total_amount=?, deposit_amount=?, remaining_amount=?, status=?, special_requests=?, updated_at=NOW()
                WHERE id=?";
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
                $data['status'],
                $data['special_requests'] ?? null,
                $id
            ]);

            // Xóa khách cũ
            $this->deleteCustomers($id);

            // Thêm lại khách mới + đại diện
            foreach ($data['customers'] as $custId) {

                $isRep = ($data['is_representative'] == $custId) ? 1 : 0;

                $this->addCustomer($id, $custId, $isRep);
            }

            // Xóa dịch vụ cũ
            $this->deleteServices($id);

            // Thêm dịch vụ mới
            if (!empty($data['services'])) {
                foreach ($data['services'] as $serviceId) {
                    $this->addService($id, $serviceId);
                }
            }

            // 🔥 XÓA toàn bộ dịch vụ cũ
            $this->deleteServices($id);

            // Thêm lại dịch vụ mới
            if (!empty($data['services'])) {
                foreach ($data['services'] as $serviceId) {
                    $this->addService($id, $serviceId);
                }
            }

            return true;
        } catch (PDOException $e) {
            die("Lỗi update():" . $e->getMessage());
        }
    }

    // Xóa booking
    public function delete($id)
    {
        $this->conn->beginTransaction();
        try {
            $this->conn->prepare("DELETE FROM booking_services WHERE booking_id = ?")->execute([$id]);
            $this->conn->prepare("DELETE FROM booking_customers WHERE booking_id = ?")->execute([$id]);
            $this->conn->prepare("DELETE FROM customer_contracts WHERE booking_id = ?")->execute([$id]);
            $this->conn->prepare("DELETE FROM payments WHERE booking_id = ?")->execute([$id]);

            $stmt = $this->conn->prepare("SELECT id FROM tour_assignments WHERE booking_id = ?");
            $stmt->execute([$id]);
            $assignments = $stmt->fetchAll(PDO::FETCH_COLUMN);

            foreach ($assignments as $assignId) {
                // Xóa dữ liệu liên quan đến tour_assignment
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
        } catch (PDOException $e) {
            $this->conn->rollBack();
            die("Lỗi delete(): " . $e->getMessage());
        }
    }

    // Xóa toàn bộ khách hàng của booking
    public function deleteCustomers($bookingId)
    {
        try {
            $sql = "DELETE FROM booking_customers WHERE booking_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$bookingId]);
            return $stmt;
        } catch (PDOException $e) {
            die("Lỗi deleteCustomers(): " . $e->getMessage());
        }
    }

    // Lấy ra danh sách tour
    public function getTours()
    {
        try {
            $sql = "SELECT id, name, adult_price, child_price FROM tours ORDER BY name";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Lỗi getTours(): " . $e->getMessage());
        }
    }


    // Quản lí khách hàng trong booking
    public function addCustomer($bookingId, $customerId, $isRepresentative = 0)
    {
        try {
            $sql = "INSERT INTO booking_customers (booking_id, customer_id, is_representative) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$bookingId, $customerId, $isRepresentative]);
            return $stmt;
        } catch (PDOException $e) {
            die("Lỗi addCustomer(): " . $e->getMessage());
        }
    }
    // Lấy danh sách khách hàng của booking_customers
    public function getCustomers($bookingId)
    {
        try {
            $sql = "SELECT c.*, bc.is_representative 
            FROM booking_customers bc
            JOIN customers c ON c.id = bc.customer_id
            WHERE bc.booking_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$bookingId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Lỗi getCustomers(): " . $e->getMessage());
        }
    }

    // Quản lí dịch vụ
    public function getServices($bookingId)
    {
        try {
            $sql = "SELECT bs.*, s.name
                    FROM booking_services bs
                    JOIN services s ON s.id = bs.service_id
                    WHERE bs.booking_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$bookingId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Lỗi getServices(): " . $e->getMessage());
        }
    }

    // Thêm dịch vụ
    public function addService($bookingId, $serviceId)
    {
        try {
            $sql = "INSERT INTO booking_services (booking_id, service_id) VALUES (?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$bookingId, $serviceId]);
            return true;
        } catch (PDOException $e) {
            die("Lỗi addService(): " . $e->getMessage());
        }
    }

    // Xóa toàn bộ dịch vụ của một booking
    public function deleteServices($bookingId)
    {
        try {
            $sql = "DELETE FROM booking_services WHERE booking_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$bookingId]);
            return true;
        } catch (PDOException $e) {
            die("Lỗi deleteServices():" . $e->getMessage());
        }
    }

    // hiễn thị dịch vụ vào detail
    public function getServicesByBooking($bookingId)
    {
        $sql = "SELECT s.*
                FROM booking_services bs
                JOIN services s ON bs.service_id = s.id
                WHERE bs.booking_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$bookingId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
