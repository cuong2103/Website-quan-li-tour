<?php
class DestinationModel
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
        try {
            $this->conn->exec("ALTER TABLE destinations DROP INDEX name");
        } catch (\Throwable $th) {
        }
        try {
            $this->conn->exec("ALTER TABLE destinations ADD COLUMN status VARCHAR(20) DEFAULT 'active'");
        } catch (\Throwable $th) {
        }
    }

    // Lấy danh sách địa điểm
    public function getAll()
    {
        $sql = "
            SELECT 
                d.*, 
                cg.name AS category_name,
                (
                    SELECT image_url 
                    FROM destination_images 
                    WHERE destination_id = d.id 
                    ORDER BY id ASC 
                    LIMIT 1
                ) AS thumbnail
            FROM destinations d
            LEFT JOIN categories cg ON d.category_id = cg.id
            WHERE d.status = 'active'
            ORDER BY d.id DESC
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // kiểm tra tên địa điểm trong cùng danh mục
    public function isDuplicateNameInCategory($name, $category_id, $excludeId = null)
    {
        // Quét lấy name trong cùng category để check tại PHP cho chắc ký tự tiếng Việt
        $sql = "SELECT id, name FROM destinations WHERE category_id = ? AND status = 'active'";
        $params = [$category_id];

        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Double check với mb_strtolower của PHP để bắt triệt để Tiếng Việt có dấu hoa/thường
        $inputName = mb_strtolower(trim($name), 'UTF-8');
        foreach ($results as $row) {
            if (mb_strtolower(trim($row['name']), 'UTF-8') === $inputName) {
                return true;
            }
        }
        
        return false;
    }

    // tạo mới
    public function create($data)
    {
        $sql = "INSERT INTO destinations (category_id, name, locations, description, created_by) 
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            $data['category_id'],
            $data['name'],
            $data['locations'],
            $data['description'],
            $data['created_by']
        ]);

        return $this->conn->lastInsertId();
    }

    // cập nhật
    public function update($id, $data)
    {
        $sql = "UPDATE destinations 
                SET category_id = ?, name = ?, locations = ?, description = ?, updated_at = NOW() , updated_by = ?  
                WHERE id = ?";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['category_id'],
            $data['name'],
            $data['locations'],
            $data['description'],
            $data['updated_by'],
            $id
        ]);
    }

    // lấy 1 địa điểm theo id
    public function getIdEdit($id)
    {
        $sql = "SELECT * FROM destinations WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // lấy danh mục
    public function getCategories()
    {
        $stmt = $this->conn->prepare("SELECT * FROM categories");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // thêm ảnh
    public function addImage($destination_id, $file_name, $created_by)
    {
        // Chuẩn hóa đường dẫn (không có dấu "/")
        $cleanName = ltrim($file_name, '/');

        $sql = "INSERT INTO destination_images (destination_id, image_url, created_by)
                VALUES (?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$destination_id, $cleanName, $created_by]);
    }

    public function getImagesByDestination($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM destination_images WHERE destination_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getImageById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM destination_images WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteImage($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM destination_images WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // xóa địa điểm (Xóa mềm - Soft Delete)
    public function delete($id)
    {
        $stmt = $this->conn->prepare("UPDATE destinations SET status = 'deleted' WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // chi tiết
    public function getDetail($id)
    {
        $sql = "
        SELECT d.*, 
               cg.name AS category_name,
               u_created.fullname AS created_by_name,
               u_updated.fullname AS updated_by_name
        FROM destinations d
        LEFT JOIN categories cg ON d.category_id = cg.id
        LEFT JOIN users u_created ON d.created_by = u_created.id
        LEFT JOIN users u_updated ON d.updated_by = u_updated.id
        WHERE d.id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        $destination = $stmt->fetch(PDO::FETCH_ASSOC);

        // Ảnh
        $stmtImg = $this->conn->prepare("SELECT * FROM destination_images WHERE destination_id = ?");
        $stmtImg->execute([$id]);
        $images = $stmtImg->fetchAll(PDO::FETCH_ASSOC);

        // Tour liên quan
        $relatedTours = $this->getRelatedTours($id, 5);

        return [
            'destination' => $destination,
            'images' => $images,
            'relatedTours' => $relatedTours
        ];
    }


    // Lấy các tour liên quan theo itinerary
    public function getRelatedTours($destination_id, $limit = 10)
    {
        $limit = (int)$limit; // đảm bảo là số nguyên

        $sql = "
            SELECT DISTINCT t.id, t.name, t.tour_code, t.adult_price, t.child_price, t.duration_days, t.introduction, t.created_at, t.updated_at
            FROM tours t
            INNER JOIN itineraries i ON i.tour_id = t.id
            WHERE i.destination_id = ?
              AND t.status = 'active'
            ORDER BY t.created_at DESC
            LIMIT $limit
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$destination_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lọc
    public function filter($name = '', $category_id = '', $created_from = '', $created_to = '')
    {
        $sql = "
            SELECT 
                d.*, 
                cg.name AS category_name,
                (
                    SELECT image_url 
                    FROM destination_images 
                    WHERE destination_id = d.id 
                    ORDER BY id ASC 
                    LIMIT 1
                ) AS thumbnail
            FROM destinations d
            LEFT JOIN categories cg ON d.category_id = cg.id
            WHERE d.status = 'active'
        ";

        $params = [];

        if ($name) {
            $sql .= " AND d.name LIKE :name";
            $params['name'] = "%$name%";
        }

        if ($category_id) {
            if (is_array($category_id)) {
                $placeholders = [];
                foreach ($category_id as $k => $id) {
                    $key = "cat_$k";
                    $placeholders[] = ":$key";
                    $params[$key] = $id;
                }
                $sql .= " AND d.category_id IN (" . implode(', ', $placeholders) . ")";
            } else {
                $sql .= " AND d.category_id = :cat";
                $params['cat'] = $category_id;
            }
        }

        if ($created_from) {
            $sql .= " AND d.created_at >= :from";
            $params['from'] = $created_from . " 00:00:00";
        }

        if ($created_to) {
            $sql .= " AND d.created_at <= :to";
            $params['to'] = $created_to . " 23:59:59";
        }

        $sql .= " ORDER BY d.id DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getIdByName($name)
    {
        $sql = "SELECT id FROM destinations WHERE name = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$name]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insertReturnId($name)
    {
        $sql = "INSERT INTO destinations (name) VALUES (?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$name]);
        return $this->conn->lastInsertId();
    }
}
