<?php
class DestinationModel{
    public $conn;

    public function __construct()
    {
       $this->conn = connectDB();
    }

    public function getAll(){
        try{
            $sql = "SELECT d.*, c.name AS country_name
                FROM destinations d
                LEFT JOIN countries c ON d.country_id = c.id";
            $stmt =  $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            echo "Lỗi" . $e->getMessage();
        }
    }
}