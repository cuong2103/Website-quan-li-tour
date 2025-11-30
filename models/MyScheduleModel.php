<?php
class MyScheduleModel
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
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

    // Lấy chi tiết booking theo ID
    public function getBookingById($bookingId)
    {
        try {
            $sql = "SELECT * FROM bookings WHERE id = ?";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$bookingId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Lỗi getBookingById(): " . $e->getMessage());
        }
    }

    // Lấy danh sách khách hàng của booking
    public function getCustomersByBooking($bookingId)
    {
        try {
            $sql = "SELECT c.*, bc.is_representative, bc.notes
                    FROM booking_customers bc
                    JOIN customers c ON bc.customer_id = c.id
                    WHERE bc.booking_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$bookingId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Lỗi getCustomersByBooking(): " . $e->getMessage());
        }
    }
    // Tính tổng số ngày của tour
    public function calculateTotalDays($startDate, $endDate)
    {
        $start = new DateTime($startDate);
        $end   = new DateTime($endDate);
        $end->setTime(0, 0, 0); // đảm bảo tính đúng ngày cuối
        $start->setTime(0, 0, 0);
        $diff = $start->diff($end);
        return $diff->days + 1; // +1 để tính cả ngày đầu và cuối
    }

    // Tính ngày hiện tại của tour (từ start_date)
    public function getCurrentDay($startDate, $endDate)
    {
        $today = new DateTime(date('Y-m-d'));
        $start = new DateTime($startDate);
        $end   = new DateTime($endDate);
        $start->setTime(0, 0, 0);
        $end->setTime(0, 0, 0);

        if ($today < $start) return 0;      // chưa bắt đầu
        if ($today > $end) return $this->calculateTotalDays($startDate, $endDate); // đã kết thúc

        $diff = $start->diff($today);
        return $diff->days + 1; // +1 ngày bắt đầu
    }
}
