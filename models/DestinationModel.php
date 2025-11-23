<?php
class DestinationModel
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    // Lấy tất cả địa điểm
    public function getAll()
    {
        try {
            $sql = "
                SELECT 
                    d.*, 
                    c.name AS country_name,
                    cg.name AS category_name,
                    (
                        SELECT image_url 
                        FROM destination_images 
                        WHERE destination_id = d.id 
                        ORDER BY id ASC 
                        LIMIT 1
                    ) AS thumbnail,
                    (
                        SELECT COUNT(*) 
                        FROM itineraries i
                        WHERE i.destination_id = d.id
                    ) AS tour_count
                FROM destinations d
                LEFT JOIN countries c ON d.country_id = c.id
                LEFT JOIN categories cg ON c.category_id = cg.id
                ORDER BY d.id DESC
            ";
            $stmt =  $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die("Lỗi: " . $e->getMessage());
        }
    }

    // Thêm địa điểm
    public function create($data)
    {
        try {
            $sql = "INSERT INTO destinations (country_id, name, address, description, created_by) VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                $data['country_id'],
                $data['name'],
                $data['address'],
                $data['description'],
                $data['created_by']
            ]);
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            die("Lỗi: " . $e->getMessage());
        }
    }

    // Cập nhật địa điểm
    public function update($id, $data)
    {
        try {
            $sql = "UPDATE destinations 
                    SET country_id = ?, name = ?, address = ?, description = ?, updated_at = NOW()
                    WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                $data['country_id'],
                $data['name'],
                $data['address'],
                $data['description'],
                $id
            ]);
        } catch (PDOException $e) {
            die("Lỗi: " . $e->getMessage());
        }
    }

    // Lấy địa điểm theo ID
    public function getIdEdit($id)
    {
        try {
            $sql = "SELECT * FROM destinations WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Lỗi: " . $e->getMessage());
        }
    }

    // Lấy danh sách quốc gia
    public function getCountries()
    {
        try {
            $sql = "SELECT * FROM countries ORDER BY name ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Lỗi: " . $e->getMessage());
        }
    }

    // Thêm ảnh
    public function addImage($destination_id, $file_name, $created_by)
    {
        try {
            $sql = "INSERT INTO destination_images (destination_id, image_url, created_by) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$destination_id, $file_name, $created_by]);
        } catch (PDOException $e) {
            die("Lỗi: " . $e->getMessage());
        }
    }

    // Lấy ảnh theo destination
    public function getImagesByDestination($id)
    {
        $sql = "SELECT * FROM destination_images WHERE destination_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy ảnh theo ID
    public function getImageById($id)
    {
        $sql = "SELECT * FROM destination_images WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Xóa ảnh
    public function deleteImage($id)
    {
        $sql = "DELETE FROM destination_images WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]);
    }

    // Xóa địa điểm (và xóa tất cả ảnh)
    public function delete($id)
    {
        try {
            $images = $this->getImagesByDestination($id);
            foreach ($images as $img) {
                $filePath = __DIR__ . '/../../uploads/destinations_image/' . $img['image_url'];
                if (file_exists($filePath)) unlink($filePath);
            }
            $sqlDelImg = "DELETE FROM destination_images WHERE destination_id = ?";
            $stmtDelImg = $this->conn->prepare($sqlDelImg);
            $stmtDelImg->execute([$id]);

            $sql = "DELETE FROM destinations WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            die("Lỗi: " . $e->getMessage());
        }
    }

    public function getDetail($id)
    {
        try {
            // Lấy thông tin cơ bản địa điểm + quốc gia + danh mục
            $sql = "
        SELECT 
            d.*,
            c.name AS country_name,
            cg.name AS category_name,
            d.created_by,
            d.created_at,
            d.updated_at
        FROM destinations d
        LEFT JOIN countries c ON d.country_id = c.id
        LEFT JOIN categories cg ON c.category_id = cg.id
        WHERE d.id = ?
        ";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            $destination = $stmt->fetch(PDO::FETCH_ASSOC);

            // Lấy tất cả ảnh
            $sqlImg = "SELECT * FROM destination_images WHERE destination_id = ?";
            $stmtImg = $this->conn->prepare($sqlImg);
            $stmtImg->execute([$id]);
            $images = $stmtImg->fetchAll(PDO::FETCH_ASSOC);

            // Lấy danh sách tour có địa điểm này trong itinerary
            $sqlTours = "
        SELECT t.id, t.name, t.introduction, t.adult_price, t.child_price, t.created_at, t.updated_at
        FROM tours t
        INNER JOIN itineraries i ON i.tour_id = t.id
        WHERE i.destination_id = ?
        ";
            $stmtTours = $this->conn->prepare($sqlTours);
            $stmtTours->execute([$id]);
            $tours = $stmtTours->fetchAll(PDO::FETCH_ASSOC);

            // Lấy danh sách nhà cung cấp và dịch vụ ở địa điểm này
            $sqlSuppliers = "
        SELECT s.id AS supplier_id, s.name AS supplier_name, s.email, s.phone, s.created_at, s.updated_at,
               srv.id AS service_id, srv.name AS service_name, srv.type, srv.price, srv.created_at AS service_created_at, srv.updated_at AS service_updated_at
        FROM suppliers s
        LEFT JOIN services srv ON srv.supplier_id = s.id
        WHERE s.destination_id = ?
        ";
            $stmtSuppliers = $this->conn->prepare($sqlSuppliers);
            $stmtSuppliers->execute([$id]);
            $suppliers = $stmtSuppliers->fetchAll(PDO::FETCH_ASSOC);

            return [
                'destination' => $destination,
                'images' => $images,
                'tours' => $tours,
                'suppliers' => $suppliers
            ];
        } catch (PDOException $e) {
            die("Lỗi: " . $e->getMessage());
        }
    }

    // LỌC ĐỊA ĐIỂM
    // ==============================
    public function filter($name = '', $country_id = '', $created_from = '', $created_to = '')
    {
        try {
            $sql = "
            SELECT 
                d.*, 
                c.name AS country_name,
                cg.name AS category_name,
                (
                    SELECT image_url 
                    FROM destination_images 
                    WHERE destination_id = d.id 
                    ORDER BY id ASC 
                    LIMIT 1
                ) AS thumbnail,
                (
                    SELECT COUNT(*) 
                    FROM itineraries i
                    WHERE i.destination_id = d.id
                ) AS tour_count
            FROM destinations d
            LEFT JOIN countries c ON d.country_id = c.id
            LEFT JOIN categories cg ON c.category_id = cg.id
            WHERE 1
        ";

            $params = [];

            // Lọc theo tên
            if (!empty($name)) {
                $sql .= " AND d.name LIKE :name";
                $params['name'] = "%" . $name . "%";
            }

            // Lọc theo country_id
            if (!empty($country_id)) {
                $sql .= " AND d.country_id = :country_id";
                $params['country_id'] = $country_id;
            }

            // Lọc theo ngày tạo từ
            if (!empty($created_from)) {
                $sql .= " AND d.created_at >= :from";
                $params['from'] = $created_from . " 00:00:00";
            }

            // Lọc theo ngày tạo đến
            if (!empty($created_to)) {
                $sql .= " AND d.created_at <= :to";
                $params['to'] = $created_to . " 23:59:59";
            }

            // Sắp xếp mới nhất
            $sql .= " ORDER BY d.id DESC";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);


            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die("Lỗi: " . $e->getMessage());
        }
    }
}
