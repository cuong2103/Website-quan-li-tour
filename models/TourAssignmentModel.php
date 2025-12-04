<?php
class TourAssignmentModel
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    public function getGuides()
    {
        try {
            $sql = "SELECT * FROM users WHERE roles = 'guide' AND status = 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Lỗi getGuides: " . $e->getMessage());
        }
    }

    public function getByBookingId($bookingId)
    {
        try {
            $sql = "SELECT ta.*, u.fullname as guide_name FROM tour_assignments ta JOIN users u ON ta.guide_id = u.id WHERE ta.booking_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$bookingId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Lỗi getByBookingId: " . $e->getMessage());
        }
    }

    public function create($data)
    {
        try {
            $sql = "INSERT INTO tour_assignments (booking_id, guide_id, status, created_at) VALUES (:booking_id, :guide_id, :status, NOW())";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($data);
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            die("Lỗi create assignment: " . $e->getMessage());
        }
    }

    public function updateAssignment($id, $data)
    {
        try {
            $sql = "UPDATE tour_assignments SET guide_id = :guide_id, status = :status, updated_at = NOW() WHERE id = :id";
            $data['id'] = $id;
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($data);
        } catch (PDOException $e) {
            die("Lỗi update assignment: " . $e->getMessage());
        }
    }

    public function getAll()
    {
        try {
            $sql = "
                SELECT ta.*, b.id AS booking_code, b.start_date, b.end_date, u.fullname AS guide_name
                FROM tour_assignments ta
                LEFT JOIN bookings b ON b.id = ta.booking_id
                LEFT JOIN users u ON u.id = ta.guide_id
                ORDER BY ta.id DESC
            ";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    public function getAllGuides()
    {
        try {
            $sql = "SELECT * FROM users WHERE roles = 'guide'";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    public function getAvailableGuides($startDate, $endDate, $excludeBookingId = null)
    {
        try {
            // 1. Lấy tất cả HDV
            $allGuides = $this->getAllGuides();

            // 2. Tìm các HDV đang bận trong khoảng thời gian này
            // Điều kiện trùng: (StartA <= EndB) AND (EndA >= StartB)
            $sql = "
                SELECT DISTINCT ta.guide_id 
                FROM tour_assignments ta
                JOIN bookings b ON ta.booking_id = b.id
                WHERE ta.status IN ('assigned', 'in_progress') 
                AND (b.start_date <= ? AND b.end_date >= ?)
            ";

            $params = [$endDate, $startDate];

            if ($excludeBookingId) {
                $sql .= " AND ta.booking_id != ?";
                $params[] = $excludeBookingId;
            }

            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            $busyGuideIds = $stmt->fetchAll(PDO::FETCH_COLUMN); // Mảng các ID bận

            // 3. Lọc danh sách: loại bỏ HDV bận tour HOẶC đang nghỉ phép
            $availableGuides = [];
            foreach ($allGuides as $guide) {
                // Bỏ qua nếu HDV đang bận tour
                if (in_array($guide['id'], $busyGuideIds)) {
                    continue;
                }

                // Bỏ qua nếu HDV đang nghỉ phép
                if (!empty($guide['leave_start']) && !empty($guide['leave_end'])) {
                    // Kiểm tra trùng ngày nghỉ: (leave_start <= tour_end) AND (leave_end >= tour_start)
                    if ($guide['leave_start'] <= $endDate && $guide['leave_end'] >= $startDate) {
                        continue; // HDV đang nghỉ trong khoảng thời gian tour
                    }
                }

                $availableGuides[] = $guide;
            }

            return $availableGuides;
        } catch (Exception $e) {
            return [];
        }
    }

    public function getAllBookings()
    {
        try {
            $sql = "
                SELECT b.*, t.name AS tour_name
                FROM bookings b
                LEFT JOIN tours t ON t.id = b.tour_id
                ORDER BY b.id DESC
            ";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    public function getBookingsWithoutGuide()
    {
        try {
            $sql = "
                SELECT b.*, t.name AS tour_name
                FROM bookings b
                LEFT JOIN tours t ON t.id = b.tour_id
                WHERE b.id NOT IN (SELECT booking_id FROM tour_assignments)
                ORDER BY b.id DESC
            ";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    public function store($booking_id, $guide_id, $created_by)
    {
        try {
            $sql = "INSERT INTO tour_assignments (booking_id, guide_id, status, created_by) VALUES (?, ?, 'assigned', ?)";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$booking_id, $guide_id, $created_by]);
        } catch (Exception $e) {
            return false;
        }
    }

    public function find($id)
    {
        try {
            $sql = "SELECT * FROM tour_assignments WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return null;
        }
    }

    // Alias for getByBookingId used by controller edit flow
    public function findByBookingId($bookingId)
    {
        return $this->getByBookingId($bookingId);
    }


    public function updateGuide($id, $guide_id)
    {
        try {
            $sql = "UPDATE tour_assignments SET guide_id = ? WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$guide_id, $id]);
        } catch (Exception $e) {
            return false;
        }
    }

    public function delete($id)
    {
        try {
            $sql = "DELETE FROM tour_assignments WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$id]);
        } catch (Exception $e) {
            return false;
        }
    }

    public function getAssignmentsByGuide($guideId)
    {
        $sql = "SELECT ta.*, b.booking_code, t.name AS tour_name, b.start_date, b.end_date,
                       (SELECT COUNT(*) FROM booking_customers bc WHERE bc.booking_id = b.id) AS total_customers
                FROM tour_assignments ta
                JOIN bookings b ON ta.booking_id = b.id
                JOIN tours t ON b.tour_id = t.id
                WHERE ta.guide_id = ?
                ORDER BY b.start_date DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$guideId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getJournalsByAssignment($assignmentId)
    {
        $sql = "SELECT j.*, u.fullname AS created_by_name
            FROM journals j
            LEFT JOIN users u ON j.created_by = u.id
            WHERE j.tour_assignment_id = ?
            ORDER BY j.date DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$assignmentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy chi tiết phân công theo ID
    public function getAssignmentById($assignmentId)
    {
        try {
            $sql = "SELECT 
             ta.id AS assignment_id,
             t.id AS tour_id,
             t.name AS tour_name,
             t.duration_days,
             t.introduction,
             t.adult_price,
             t.child_price,
             t.status AS tour_status,
             b.id AS booking_id,
             b.booking_code,
             b.start_date,
             b.end_date,
             b.adult_count,
             b.child_count,
             b.total_amount,
             b.deposit_amount,
             b.remaining_amount,
             b.special_requests
             FROM tour_assignments ta
             JOIN bookings b ON ta.booking_id = b.id
             JOIN tours t ON b.tour_id = t.id
             WHERE ta.id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$assignmentId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Lỗi getAssignmentById(): " . $e->getMessage());
        }
    }

    // Lấy lịch trình tour của guide    
    public function getGuideSchedule($guideId)
    {
        try {
            $sql = "SELECT 
             ta.id AS assignment_id,
             t.id AS tour_id,
             t.name AS tour_name,
             t.duration_days,
             b.id AS booking_id,
             b.booking_code,
             b.start_date,
             b.end_date,
             (SELECT COUNT(*) FROM booking_customers bc WHERE bc.booking_id = b.id) AS guest_count
             FROM tour_assignments ta
             JOIN bookings b ON ta.booking_id = b.id
             JOIN tours t ON b.tour_id = t.id
             WHERE ta.guide_id = ?
             ORDER BY b.start_date DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$guideId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Lỗi getGuideSchedule(): " . $e->getMessage());
        }
    }
}
