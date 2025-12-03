<?php
class PolicyModel
{
    public $conn;
    public function __construct()
    {
        $this->conn = connectDB();
    }

    // lấy toàn bộ chính sách
    public function getAll()
    {
        $sql = 'SELECT *FROM policies ORDER BY id DESC';
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // lấy chính sách theo id
    public function getByID($id)
    {
        $sql = "SELECT *FROM policies WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // thêm chính sách mới
    public function create($data)
    {
        $sql = "INSERT INTO `policies`(`title`, `content`, `created_by`, `created_at`) 
        VALUES (?, ?, ?, NOW())";
        $stmt = $this->conn->prepare($sql);
        // dd($data['title']);
        return $stmt->execute([$data['title'], $data['content'], $data['created_by']]);
    }
    public function update($data)
    {
        $sql = "UPDATE `policies` 
            SET title = :title, content = :content, created_by = :created_by, updated_at = NOW() 
            WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':id' => $data['id'],
            ':title' => $data['title'],
            ':content' => $data['content'],
            ':created_by' => $data['created_by'],
        ]);
    }

    public function detail($id)
    {
        $sql = "SELECT *FROM policies WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function delete($id)
    {
        $sql = "DELETE FROM policies WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam("id", $id);
        return $stmt->execute();
    }
}
