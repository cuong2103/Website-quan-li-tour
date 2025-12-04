<?php
class TourCheckinLink
{
  public $conn;

  public function __construct()
  {
    $this->conn = connectDB();
  }

  public function create($tour_assignment_id, $title, $note = null)
  {
    try {
      $sql = "INSERT INTO tour_checkin_links (tour_assignment_id, title, note) VALUES (:tour_assignment_id, :title, :note)";
      $stmt = $this->conn->prepare($sql);
      $stmt->execute([
        ':tour_assignment_id' => $tour_assignment_id,
        ':title' => $title,
        ':note' => $note
      ]);
      return $this->conn->lastInsertId();
    } catch (PDOException $e) {
      error_log("Error creating checkin link: " . $e->getMessage());
      return false;
    }
  }

  public function getByAssignmentId($tour_assignment_id)
  {
    try {
      $sql = "SELECT * FROM tour_checkin_links WHERE tour_assignment_id = :tour_assignment_id ORDER BY created_at DESC";
      $stmt = $this->conn->prepare($sql);
      $stmt->execute([':tour_assignment_id' => $tour_assignment_id]);
      return $stmt->fetchAll();
    } catch (PDOException $e) {
      error_log("Error getting checkin links: " . $e->getMessage());
      return [];
    }
  }

  public function find($id)
  {
    try {
      $sql = "SELECT * FROM tour_checkin_links WHERE id = :id";
      $stmt = $this->conn->prepare($sql);
      $stmt->execute([':id' => $id]);
      return $stmt->fetch();
    } catch (PDOException $e) {
      error_log("Error finding checkin link: " . $e->getMessage());
      return false;
    }
  }
}
