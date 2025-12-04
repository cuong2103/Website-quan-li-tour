<?php
class JournalModel
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    // Lấy tất cả journal theo tour assignment (có thể filter theo keyword)
    public function getAll($tour_assignment_id = '', $keyword = '')
    {
        $guideId = $_SESSION['currentUser']['id'];
        $sql = "SELECT j.*, ta.booking_id, b.booking_code, b.status AS tour_status, t.name AS tour_name,
                (SELECT image_url FROM journal_images WHERE journal_id = j.id ORDER BY id ASC LIMIT 1) AS thumbnail
                FROM journals j
                INNER JOIN tour_assignments ta ON ta.id = j.tour_assignment_id
                INNER JOIN bookings b ON b.id = ta.booking_id
                INNER JOIN tours t ON t.id = b.tour_id
                WHERE ta.guide_id = :guide_id";

        $params = ['guide_id' => $guideId];

        if ($tour_assignment_id) {
            $sql .= " AND j.tour_assignment_id = :ta_id";
            $params['ta_id'] = $tour_assignment_id;
        }

        if ($keyword) {
            $sql .= " AND j.content LIKE :keyword";
            $params['keyword'] = "%$keyword%";
        }

        $sql .= " ORDER BY j.id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy 1 journal
    public function getById($id)
    {
        try {
            $sql = "SELECT j.*, u.fullname as created_by_name
                    FROM journals j
                    LEFT JOIN users u ON j.created_by = u.id
                    WHERE j.id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Lỗi truy vấn: getById" . $e->getMessage());
        }
    }

    // Lấy danh sách tour assignment của guide
    public function getAssignmentsByGuide($guideId)
    {
        try {
            $sql = "SELECT ta.id, t.name AS tour_name, b.booking_code, b.status AS tour_status
                    FROM tour_assignments ta
                    INNER JOIN bookings b ON b.id = ta.booking_id
                    INNER JOIN tours t ON t.id = b.tour_id
                    WHERE ta.guide_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$guideId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Lỗi truy vấn: getAssignmentsByGuide" . $e->getMessage());
        }
    }

    // Lấy ảnh journal
    public function getImages($journal_id)
    {
        try {
            $sql = "SELECT * FROM journal_images WHERE journal_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$journal_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Lỗi truy vấn: " . $e->getMessage());
        }
    }

    // Tạo journal
    public function create($data)
    {
        $stmt = $this->conn->prepare("
            INSERT INTO journals (tour_assignment_id, date, content, type, created_by)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['tour_assignment_id'],
            $data['date'],
            $data['content'],
            $data['type'],
            $data['created_by']
        ]);
        return $this->conn->lastInsertId();
    }

    // Cập nhật journal
    public function update($id, $data)
    {
        try {
            $sql = "UPDATE journals
                    SET date = ?, content = ?, type = ?, updated_by = ?, updated_at = NOW()
                    WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                $data['date'],
                $data['content'],
                $data['type'],
                $data['updated_by'],
                $id
            ]);
        } catch (PDOException $e) {
            die("Lỗi truy vấn: update" . $e->getMessage());
        }
    }

    // Xóa journal + ảnh
    public function delete($id)
    {
        $images = $this->getImages($id);
        foreach ($images as $img) {
            $filePath = __DIR__ . '/../../uploads/journals/' . ltrim($img['image_url'], '/');
            if (file_exists($filePath)) unlink($filePath);
        }
        $this->conn->prepare("DELETE FROM journal_images WHERE journal_id = ?")->execute([$id]);
        return $this->conn->prepare("DELETE FROM journals WHERE id = ?")->execute([$id]);
    }

    // Thêm ảnh journal
    public function addImage($journal_id, $file_name, $created_by)
    {
        try {
            $sql = "INSERT INTO journal_images (journal_id, image_url, created_by)
                    VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$journal_id, $file_name, $created_by]);
        } catch (PDOException $e) {
            die("Lỗi truy vấn: addImage" . $e->getMessage());
        }
    }
    // Lấy 1 ảnh theo ID
    public function getImageById($id)
    {
        try {
            $sql = "SELECT * FROM journal_images WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Lỗi truy vấn: getImageById" . $e->getMessage());
        }
    }

    // Xóa 1 ảnh
    public function deleteImage($id)
    {
        try {
            $sql = "DELETE FROM journal_images WHERE id = ?";
            return $this->conn->prepare($sql)->execute([$id]);
        } catch (PDOException $e) {
            die("Lỗi truy vấn: deleteImage" . $e->getMessage());
        }
    }

    // Lấy thông tin tour + booking theo tour_assignment_id
    public function getTourByAssignment($tour_assignment_id)
    {
        try {
            $sql = "SELECT ta.id AS tour_assignment_id,
                   b.id AS booking_id,
                   b.booking_code,
                   t.name AS tour_name,
                   b.status AS tour_status
            FROM tour_assignments ta
            INNER JOIN bookings b ON b.id = ta.booking_id
            INNER JOIN tours t ON t.id = b.tour_id
            WHERE ta.id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$tour_assignment_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Lỗi truy vấn: getTourByAssignment - " . $e->getMessage());
        }
    }

    // Lấy tất cả journals theo booking_id
    public function getJournalsByBookingId($bookingId)
    {
        try {
            $sql = "SELECT j.*, u.fullname as created_by_name,
                           (SELECT image_url FROM journal_images WHERE journal_id = j.id ORDER BY id ASC LIMIT 1) AS thumbnail
                    FROM journals j
                    INNER JOIN tour_assignments ta ON ta.id = j.tour_assignment_id
                    LEFT JOIN users u ON j.created_by = u.id
                    WHERE ta.booking_id = :booking_id
                    ORDER BY j.date DESC, j.id DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':booking_id' => $bookingId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Lỗi truy vấn: getJournalsByBookingId - " . $e->getMessage());
        }
    }
    // Lấy thông tin assignment theo booking_id
    public function getAssignmentByBookingId($bookingId)
    {
        try {
            $sql = "SELECT ta.*, b.start_date, b.end_date
                    FROM tour_assignments ta
                    INNER JOIN bookings b ON b.id = ta.booking_id
                    WHERE ta.booking_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$bookingId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Lỗi truy vấn: getAssignmentByBookingId - " . $e->getMessage());
        }
    }
}
