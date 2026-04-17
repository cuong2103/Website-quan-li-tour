<?php
class BookingModel
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    // Lấy danh sách booking (có hỗ trợ lọc)
    public function getAll($filters = [])
    {
        try {
            $sql = "SELECT DISTINCT b.*, t.name AS tour_name, u.fullname as guide_name,
                       (SELECT c2.name FROM customers c2 
                        JOIN booking_customers bc2 ON c2.id = bc2.customer_id 
                        WHERE bc2.booking_id = b.id AND bc2.is_representative = 1 LIMIT 1) as representative_name
                    FROM bookings b
                    LEFT JOIN tours t ON t.id = b.tour_id
                    LEFT JOIN tour_assignments ta ON ta.booking_id = b.id
                    LEFT JOIN users u ON u.id = ta.guide_id
                    LEFT JOIN booking_customers bc ON bc.booking_id = b.id
                    LEFT JOIN customers c ON c.id = bc.customer_id
                    WHERE 1=1";

            $params = [];

            if (!empty($filters['keyword'])) {
                $keyword = '%' . $filters['keyword'] . '%';
                $sql .= " AND (b.booking_code LIKE ? OR t.name LIKE ? OR c.name LIKE ?)";
                $params[] = $keyword;
                $params[] = $keyword;
                $params[] = $keyword;
            }

            if (!empty($filters['status'])) {
                $sql .= " AND b.status = ?";
                $params[] = $filters['status'];
            }

            if (!empty($filters['date_from'])) {
                $sql .= " AND b.start_date >= ?";
                $params[] = $filters['date_from'];
            }

            if (!empty($filters['date_to'])) {
                $sql .= " AND b.start_date <= ?";
                $params[] = $filters['date_to'];
            }

            $sql .= " ORDER BY b.id DESC";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
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
                // Auto-update nếu tour đã kết thúc
                if (
                    $booking['end_date'] < date('Y-m-d') &&
                    in_array($booking['status'], ['paid', 'in_progress', 'deposited'])
                ) {
                    $this->updateStatus($id, 'completed');
                    $booking['status'] = 'completed';
                }

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
                (tour_id, booking_code, start_date, end_date, adult_count, child_count, service_amount, total_amount, deposit_amount, remaining_amount, status, special_requests, created_by)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                $data['tour_id'],
                $data['booking_code'],
                $data['start_date'],
                $data['end_date'],
                $data['adult_count'],
                $data['child_count'] ?? 0,
                $data['service_amount'] ?? 0,
                $data['total_amount'],
                $data['deposit_amount'] ?? 0,
                $data['remaining_amount'] ?? 0,
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
                SET tour_id=?, start_date=?, end_date=?, adult_count=?, child_count=?, service_amount=?, total_amount=?, deposit_amount=?, remaining_amount=?, special_requests=?, updated_by=?, updated_at=NOW()
                WHERE id=?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                $data['tour_id'],
                $data['start_date'],
                $data['end_date'],
                $data['adult_count'],
                $data['child_count'] ?? 0,
                $data['service_amount'] ?? 0,
                $data['total_amount'],
                $data['deposit_amount'] ?? 0,
                $data['remaining_amount'] ?? 0,
                $data['special_requests'] ?? null,
                $data['updated_by'] ?? null,
                $id
            ]);
        } catch (PDOException $e) {
            die("Lỗi update():" . $e->getMessage());
        }
    }

    // Xóa booking
    public function delete($id)
    {
        $this->conn->beginTransaction();
        try {
            // Xóa dịch vụ booking theo booking_id (chính xác hơn)
            $this->conn->prepare("DELETE FROM booking_services WHERE booking_id = ?")->execute([$id]);

            // Xóa khách hàng trong booking
            $this->conn->prepare("DELETE FROM booking_customers WHERE booking_id = ?")->execute([$id]);

            // Xóa hợp đồng
            $this->conn->prepare("DELETE FROM customer_contracts WHERE booking_id = ?")->execute([$id]);

            // Xóa thanh toán
            $this->conn->prepare("DELETE FROM payments WHERE booking_id = ?")->execute([$id]);

            // Lấy danh sách tour_assignments
            $stmt = $this->conn->prepare("SELECT id FROM tour_assignments WHERE booking_id = ?");
            $stmt->execute([$id]);
            $assignments = $stmt->fetchAll(PDO::FETCH_COLUMN);

            foreach ($assignments as $assignId) {
                // Lấy danh sách journals
                $journals = $this->conn->prepare("SELECT id FROM journals WHERE tour_assignment_id = ?");
                $journals->execute([$assignId]);
                $journals = $journals->fetchAll(PDO::FETCH_COLUMN);

                foreach ($journals as $journalId) {
                    // Xóa hình ảnh journal
                    $this->conn->prepare("DELETE FROM journal_images WHERE journal_id = ?")->execute([$journalId]);
                }

                // Xóa journals
                $this->conn->prepare("DELETE FROM journals WHERE tour_assignment_id = ?")->execute([$assignId]);

                // Xóa tour_checkin_links trước
                $this->conn->prepare("DELETE FROM tour_checkin_links WHERE tour_assignment_id = ?")->execute([$assignId]);
            }
            // Xóa tour_assignments
            $this->conn->prepare("DELETE FROM tour_assignments WHERE booking_id = ?")->execute([$id]);

            // Xóa chi phí phát sinh
            $this->conn->prepare("DELETE FROM incurred_expenses WHERE booking_id = ?")->execute([$id]);

            // Cuối cùng xóa booking
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
    public function addCustomer($bookingId, $customerId, $isRepresentative = 0, $notes = null)
    {
        try {
            $sql = "INSERT INTO booking_customers (booking_id, customer_id, is_representative,  notes) VALUES (?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$bookingId, $customerId, $isRepresentative, $notes]);
            return $stmt;
        } catch (PDOException $e) {
            die("Lỗi addCustomer(): " . $e->getMessage());
        }
    }
    // Lấy danh sách khách hàng của booking_customers
    public function getCustomers($bookingId)
    {
        try {
            $sql = "SELECT c.*, bc.is_representative, bc.room_number ,bc.notes
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

    public function getServices($bookingId)
    {
        try {
            // Lấy dịch vụ theo booking_id (sửa lỗi: trước đây lọc theo tour_id)
            $sql = "SELECT bs.*, s.name AS service_name
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
    public function addService($bookingId, $serviceId, $quantity, $currentPrice)
    {
        // Lấy tour_id từ booking (vẫn lưu tour_id cho đầy đủ, hoặc bỏ nếu không cần)
        $stmt = $this->conn->prepare("SELECT tour_id FROM bookings WHERE id = ?");
        $stmt->execute([$bookingId]);
        $tour = $stmt->fetch(PDO::FETCH_ASSOC);
        $tourId = $tour['tour_id'];

        $sql = "INSERT INTO booking_services 
            (booking_id, tour_id, service_id, quantity, current_price)
            VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $bookingId,
            $tourId,
            $serviceId,
            $quantity,
            $currentPrice
        ]);
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

    public function getServicesByBooking($bookingId)
    {
        $sql = "SELECT s.*, bs.quantity, bs.current_price
            FROM booking_services bs
            JOIN services s ON bs.service_id = s.id
            WHERE bs.booking_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$bookingId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Hàm lấy tổng tiền đã thanh toán (refund bị trừ ra)
    public function getTotalPaid($bookingId)
    {
        try {
            $sql = "SELECT SUM(CASE WHEN type = 'refund' THEN -amount ELSE amount END) AS total
                FROM payments
                WHERE booking_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$bookingId]);
            return $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
        } catch (PDOException $e) {
            die("Lỗi getTotalPaid(): " . $e->getMessage());
        }
    }

    // Hàm cập nhật trạng thái booking
    public function updateStatus($bookingId, $status)
    {
        try {
            $sql = "UPDATE bookings SET status = ?, updated_at = NOW() WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$status, $bookingId]);
        } catch (PDOException $e) {
            die("Lỗi updateStatus(): " . $e->getMessage());
        }
    }

    // Cập nhật số tiền cọc và còn lại
    public function updateFinancials($bookingId, $depositAmount, $remainingAmount)
    {
        try {
            $sql = "UPDATE bookings SET deposit_amount = ?, remaining_amount = ?, updated_at = NOW() WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$depositAmount, $remainingAmount, $bookingId]);
        } catch (PDOException $e) {
            die("Lỗi updateFinancials(): " . $e->getMessage());
        }
    }
    // Lọc danh sách booking trong form Thêm phân công
    public function getBookingsWithoutGuide()
    {
        $sql = "
            SELECT 
                b.*, 
                t.name AS tour_name
            FROM bookings b
            LEFT JOIN tours t ON t.id = b.tour_id
            LEFT JOIN tour_assignments ta ON ta.booking_id = b.id
            WHERE ta.booking_id IS NULL
            ORDER BY b.id DESC
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy booking theo guide_id (dành cho guide) - dùng tour_assignments
    public function getByGuideId($guideId)
    {
        $sql = "SELECT b.*, t.name AS tour_name
                  FROM bookings b
                  LEFT JOIN tours t ON t.id = b.tour_id
                  JOIN tour_assignments ta ON ta.booking_id = b.id
                  WHERE ta.guide_id = :guide_id
                  ORDER BY b.start_date ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':guide_id', $guideId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy chi tiết assignment + booking + tour
    public function getBookingDetails($assignmentId)
    {
        $sql = "SELECT ta.*, 
                       b.*, 
                       t.name AS tour_name,
                       (SELECT COUNT(*) FROM booking_customers bc WHERE bc.booking_id = b.id) AS total_customers
                FROM tour_assignments ta
                JOIN bookings b ON ta.booking_id = b.id
                JOIN tours t ON b.tour_id = t.id
                WHERE ta.id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$assignmentId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function removeCustomer($bookingId, $customerId)
    {
        try {
            $sql = "DELETE FROM booking_customers WHERE booking_id = ? AND customer_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$bookingId, $customerId]);
        } catch (PDOException $e) {
            die("Lỗi removeCustomer: " . $e->getMessage());
        }
    }

    public function updateRoomNumber($bookingId, $customerId, $roomNumber, $notes = null)
    {
        try {
            $sql = "UPDATE booking_customers 
                    SET room_number = :room_number, notes = :notes
                    WHERE booking_id = :booking_id AND customer_id = :customer_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':room_number' => $roomNumber,
                ':notes' => $notes,
                ':booking_id' => $bookingId,
                ':customer_id' => $customerId
            ]);
        } catch (PDOException $e) {
            die("Lỗi updateRoomNumber: " . $e->getMessage());
        }
    }

    // ===== Dashboard =====
    // Doanh thu theo ngày của một tháng/năm (để vẽ biểu đồ dạng 01/01...31/01)
    public function getRevenueByDay($month, $year)
    {
        $month = (int)$month;
        $year = (int)$year;
        if ($month < 1 || $month > 12) {
            $month = (int)date('n');
        }
        if ($year < 1970 || $year > 2100) {
            $year = (int)date('Y');
        }

        $daysInMonth = (int)cal_days_in_month(CAL_GREGORIAN, $month, $year);

        $dataByDay = [];
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $dataByDay[$d] = 0;
        }

        $sql = "SELECT 
                    DAY(payment_date) as day_num,
                    SUM(CASE WHEN type = 'refund' THEN -amount ELSE amount END) as total
                FROM payments
                WHERE MONTH(payment_date) = ? AND YEAR(payment_date) = ?
                GROUP BY DAY(payment_date)
                ORDER BY DAY(payment_date) ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$month, $year]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rows as $row) {
            $day = (int)($row['day_num'] ?? 0);
            if ($day >= 1 && $day <= $daysInMonth) {
                $dataByDay[$day] = (float)($row['total'] ?? 0);
            }
        }

        $labels = [];
        $data = [];
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $labels[] = str_pad((string)$d, 2, '0', STR_PAD_LEFT) . '/' . str_pad((string)$month, 2, '0', STR_PAD_LEFT);
            $data[] = $dataByDay[$d];
        }

        return [
            'labels' => $labels,
            'data' => $data,
            'month' => $month,
            'year' => $year
        ];
    }

    // Doanh thu theo tháng của một năm (để vẽ biểu đồ 12 tháng)
    public function getRevenueByMonth($year)
    {
        $year = (int)$year;
        if ($year < 1970 || $year > 2100) {
            $year = (int)date('Y');
        }

        $dataByMonth = array_fill(1, 12, 0);

        $sql = "SELECT 
                    MONTH(payment_date) as month_num,
                    SUM(CASE WHEN type = 'refund' THEN -amount ELSE amount END) as total
                FROM payments
                WHERE YEAR(payment_date) = ?
                GROUP BY MONTH(payment_date)
                ORDER BY MONTH(payment_date) ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$year]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rows as $row) {
            $m = (int)($row['month_num'] ?? 0);
            if ($m >= 1 && $m <= 12) {
                $dataByMonth[$m] = (float)($row['total'] ?? 0);
            }
        }

        $labels = [];
        $data = [];
        for ($m = 1; $m <= 12; $m++) {
            $labels[] = 'T' . $m;
            $data[] = $dataByMonth[$m];
        }

        return [
            'labels' => $labels,
            'data' => $data,
            'month' => 0, // 0 = tất cả tháng
            'year' => $year
        ];
    }

    public function getRevenueSummary($year = null)
    {
        $year = $year ? (int)$year : (int)date('Y');
        if ($year < 1970 || $year > 2100) {
            $year = (int)date('Y');
        }

        $sql = "SELECT
                    SUM(CASE WHEN DATE(payment_date) = CURDATE()
                        THEN (CASE WHEN type = 'refund' THEN -amount ELSE amount END)
                        ELSE 0 END) as revenue_today,
                    SUM(CASE WHEN YEAR(payment_date) = :year AND MONTH(payment_date) = MONTH(CURDATE())
                        THEN (CASE WHEN type = 'refund' THEN -amount ELSE amount END)
                        ELSE 0 END) as revenue_month,
                    SUM(CASE WHEN YEAR(payment_date) = :year
                        THEN (CASE WHEN type = 'refund' THEN -amount ELSE amount END)
                        ELSE 0 END) as revenue_year
                FROM payments";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['year' => $year]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

        return [
            'today' => (float)($row['revenue_today'] ?? 0),
            'month' => (float)($row['revenue_month'] ?? 0),
            'year' => (float)($row['revenue_year'] ?? 0),
            'year_value' => $year,
        ];
    }

    public function getBookingsToday()
    {
        $sql = "SELECT COUNT(*) as total FROM bookings WHERE DATE(created_at) = CURDATE()";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
        return (int)($row['total'] ?? 0);
    }

    // Lấy booking chờ xử lý mới nhất
    public function getPendingBookings($limit = 5)
    {
        $sql = "SELECT b.*, t.name as tour_name, 
                       (SELECT name FROM customers c JOIN booking_customers bc ON c.id = bc.customer_id WHERE bc.booking_id = b.id AND bc.is_representative = 1 LIMIT 1) as customer_name
                FROM bookings b
                JOIN tours t ON b.tour_id = t.id
                WHERE b.status = 'pending' 
                ORDER BY b.created_at DESC 
                LIMIT ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
