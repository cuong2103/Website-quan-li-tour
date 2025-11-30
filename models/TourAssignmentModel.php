<?php
class TourAssignmentModel
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    // =========================================
    // Lấy danh sách tất cả phân công
    // =========================================
    public function getAll()
    {
        try {
            $sql = "
                SELECT ta.*, 
                       b.id AS booking_code,
                       b.start_date,
                       b.end_date,
                       u.fullname AS guide_name
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

    // =========================================
    // Lấy tất cả hướng dẫn viên (role_id = 2)
    // =========================================
    public function getAllGuides()
    {
        try {
            $sql = "SELECT * FROM users WHERE role_id = 2";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    // =========================================
    // Lấy tất cả booking (ít dùng trong controller mới)
    // =========================================
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

    // =========================================
    // Lấy booking chưa có hướng dẫn viên
    // =========================================
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

    // =========================================
    // Tạo phân công
    // =========================================
    public function store($booking_id, $guide_id, $created_by)
    {
        try {
            $sql = "
                INSERT INTO tour_assignments (booking_id, guide_id, status, created_by)
                VALUES (?, ?, 1, ?)
            ";

            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$booking_id, $guide_id, $created_by]);
        } catch (Exception $e) {
            return false;
        }
    }

    // =========================================
    // Lấy 1 phân công theo ID
    // =========================================
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

    // =========================================
    // Cập nhật phân công (chỉ đổi guide)
    // =========================================
    public function update($id, $guide_id)
    {
        try {
            $sql = "
                UPDATE tour_assignments 
                SET guide_id = ?
                WHERE id = ?
            ";
            $stmt = $this->conn->prepare($sql);

            return $stmt->execute([$guide_id, $id]);
        } catch (Exception $e) {
            return false;
        }
    }

    // =========================================
    // Xóa phân công
    // =========================================
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
}
