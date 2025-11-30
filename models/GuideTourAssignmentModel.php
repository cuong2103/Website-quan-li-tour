<?php
class GuideTourAssignmentModel
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
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

    /**
     * Lấy chi tiết assignment theo id
     */
    public function getById($id)
    {
        $sql = "SELECT ta.*, b.booking_code, t.name AS tour_name, b.start_date, b.end_date
                FROM tour_assignments ta
                JOIN bookings b ON ta.booking_id = b.id
                JOIN tours t ON b.tour_id = t.id
                WHERE ta.id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy chi tiết assignment + booking + tour
    public function getBookingDetails($assignmentId)
    {
        $sql = "SELECT ta.*, 
                       b.*, 
                       t.name AS tour_name,
                       (b.adult_count + b.child_count) AS total_customers
                FROM tour_assignments ta
                JOIN bookings b ON ta.booking_id = b.id
                JOIN tours t ON b.tour_id = t.id
                WHERE ta.id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$assignmentId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy danh sách khách hàng của booking
    public function getCustomersByBooking($bookingId)
    {
        $sql = "SELECT c.*, bc.notes, bc.is_representative
                FROM booking_customers bc
                JOIN customers c ON bc.customer_id = c.id
                WHERE bc.booking_id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$bookingId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy dịch vụ theo booking
    public function getServicesByBooking($bookingId)
    {
        $sqlTour = "SELECT tour_id FROM bookings WHERE id = ?";
        $stmtTour = $this->conn->prepare($sqlTour);
        $stmtTour->execute([$bookingId]);
        $tour = $stmtTour->fetch(PDO::FETCH_ASSOC);
        if (!$tour) return [];

        $tourId = $tour['tour_id'];

        $sql = "SELECT bs.*, s.name AS service_name, s.description, bs.quantity
                FROM booking_services bs
                JOIN services s ON s.id = bs.service_id
                WHERE bs.tour_id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$tourId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy nhật ký theo assignment
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

    // Lấy lịch trình theo booking
    public function getItinerariesByBooking($bookingId)
    {
        $sql = "SELECT i.*, d.name AS destination_name
                FROM itineraries i
                JOIN bookings b ON i.tour_id = b.tour_id
                JOIN destinations d ON i.destination_id = d.id
                WHERE b.id = ?
                ORDER BY i.order_number ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$bookingId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy chính sách theo booking
    public function getPoliciesByBooking($bookingId)
    {
        $sql = "SELECT p.*
                FROM tour_policies tp
                JOIN policies p ON tp.policy_id = p.id
                JOIN bookings b ON tp.tour_id = b.tour_id
                WHERE b.id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$bookingId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
