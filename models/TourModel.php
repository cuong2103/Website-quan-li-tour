<?php
class TourModel
{
    private $conn;

    public function __construct()
    {
        $this->conn = connectDB(); // hàm connectDB() trả về PDO
    }

    /**
     * Lấy tất cả tour
     * @return array
     */
    public function getAll()
    {
        $sql = "SELECT id, name, adult_price, child_price 
                FROM tours 
                ORDER BY name";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy chi tiết tour theo ID
     * @param int $id
     * @return array|false
     */
    public function getTourById($id)
    {
        $sql = "SELECT id, name, adult_price, child_price 
                FROM tours 
                WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
