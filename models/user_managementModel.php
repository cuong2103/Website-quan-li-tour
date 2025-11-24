<?php
class user_managementModel{
    public $conn;
    public function __construct()
    {
        $this->conn = connectDB();
    }
    public function getAll(){
        $sql = "SELECT * FROM users";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
    }
}