<?php

class ServiceModel
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB(); // Kết nối database khi khởi tạo model
    }

    // Lấy tất cả dịch vụ (có lọc theo từ khóa, loại dịch vụ, nhà cung cấp)
    public function getAll($keyword = '', $service_type_id = '', $supplier_id = '')
    {
        $sql = "SELECT 
                    s.*, 
                    st.name AS service_type_name,
                    sp.name AS supplier_name,
                    sp.email AS supplier_email,
                    sp.phone AS supplier_phone,
                    uc.fullname AS creator_name,   -- Người tạo
                    uu.fullname AS updater_name    -- Người cập nhật
                FROM services s
                LEFT JOIN service_types st ON s.service_type_id = st.id
                LEFT JOIN suppliers sp ON s.supplier_id = sp.id
                LEFT JOIN users uc ON s.created_by = uc.id
                LEFT JOIN users uu ON s.updated_by = uu.id
                WHERE 1=1";  // 1=1 giúp nối điều kiện động dễ dàng

        $params = [];

        if ($keyword !== '') {
            $sql .= " AND s.name LIKE :keyword"; 
            $params[':keyword'] = "%$keyword%"; // Tìm theo tên
        }

        if ($service_type_id !== '') {
            $sql .= " AND s.service_type_id = :service_type_id"; // Lọc theo loại dịch vụ
            $params[':service_type_id'] = $service_type_id;
        }

        if ($supplier_id !== '') {
            $sql .= " AND s.supplier_id = :supplier_id"; // Lọc theo nhà cung cấp
            $params[':supplier_id'] = $supplier_id;
        }

        $sql .= " ORDER BY s.id DESC"; // Mới nhất lên đầu

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Tìm kiếm dịch vụ theo từ khóa (dạng tìm nhanh)
    public function search($keyword)
    {
        try {
            $sql = "SELECT 
                    s.*, 
                    st.name AS service_type_name,
                    sp.name AS supplier_name,
                    sp.email AS supplier_email,
                    sp.phone AS supplier_phone,
                    uc.fullname AS creator_name,
                    uu.fullname AS updater_name
                FROM services s
                LEFT JOIN service_types st ON s.service_type_id = st.id
                LEFT JOIN suppliers sp ON s.supplier_id = sp.id
                LEFT JOIN users uc ON s.created_by = uc.id
                LEFT JOIN users uu ON s.updated_by = uu.id
                WHERE s.name LIKE :keyword
                ORDER BY s.id DESC";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':keyword' => "%" . $keyword . "%"
            ]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            echo "Lỗi tìm kiếm: " . $e->getMessage(); // Bắt lỗi truy vấn
            return [];
        }
    }

    // Lấy chi tiết một dịch vụ theo id
    public function getDetail($id)
    {
        $sql = "SELECT
                    s.*, 
                    st.name AS service_type_name,
                    sp.name AS supplier_name,
                    sp.email AS supplier_email,
                    sp.phone AS supplier_phone,
                    uc.fullname AS creator_name,
                    uu.fullname AS updater_name
                FROM services s
                LEFT JOIN service_types st ON s.service_type_id = st.id
                LEFT JOIN suppliers sp ON s.supplier_id = sp.id
                LEFT JOIN users uc ON s.created_by = uc.id
                LEFT JOIN users uu ON s.updated_by = uu.id
                WHERE s.id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC); // Trả về một hàng
    }

    // Xóa dịch vụ theo id
    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM services WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    // Tạo mới dịch vụ
    public function create($data)
    {
        $sql = "INSERT INTO services 
                    (service_type_id, supplier_id, name, description, estimated_price, created_by, created_at)
                VALUES
                    (:service_type_id, :supplier_id, :name, :description, :estimated_price, :created_by, NOW())";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':service_type_id' => $data['service_type_id'],
            ':supplier_id'     => $data['supplier_id'],
            ':name'            => $data['name'],
            ':description'     => $data['description'],
            ':estimated_price' => $data['estimated_price'],
            ':created_by'      => $data['created_by']
        ]);
    }

    // Cập nhật dịch vụ
    public function update($id, $data)
    {
        $sql = "UPDATE services SET
                    service_type_id = :service_type_id,
                    supplier_id = :supplier_id,
                    name = :name,
                    description = :description,
                    estimated_price = :estimated_price,
                    updated_by = :updated_by,
                    updated_at = NOW()
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':service_type_id' => $data['service_type_id'],
            ':supplier_id'     => $data['supplier_id'],
            ':name'            => $data['name'],
            ':description'     => $data['description'],
            ':estimated_price' => $data['estimated_price'],
            ':updated_by'      => $data['updated_by'],
            ':id'              => $id
        ]);
    }

    // Kiểm tra dịch vụ trùng (tên + loại + nhà cung cấp)
    // Có excludeId để bỏ qua chính nó khi đang update
    public function isDuplicate($name, $service_type_id, $supplier_id, $excludeId = null)
    {
        $sql = "SELECT COUNT(*) FROM services 
            WHERE name = :name 
            AND service_type_id = :service_type_id 
            AND supplier_id = :supplier_id";

        $params = [
            ':name' => $name,
            ':service_type_id' => $service_type_id,
            ':supplier_id' => $supplier_id
        ];

        if ($excludeId) {
            $sql .= " AND id != :id"; // Loại bỏ bản ghi hiện tại
            $params[':id'] = $excludeId;
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchColumn() > 0; // >0 nghĩa là đã tồn tại
    }

    // Lấy dịch vụ theo nhà cung cấp
    public function getBySupplierID($supplierId)
    {
        $sql = "SELECT * FROM services WHERE supplier_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$supplierId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy dịch vụ theo loại dịch vụ
    public function getByServiceType($service_type_id)
    {
        $sql = "SELECT 
                s.*, 
                st.name AS service_type_name,
                sp.name AS supplier_name,
                sp.email AS supplier_email,
                sp.phone AS supplier_phone,
                uc.fullname AS creator_name,
                uu.fullname AS updater_name
            FROM services s
            LEFT JOIN service_types st ON s.service_type_id = st.id
            LEFT JOIN suppliers sp ON s.supplier_id = sp.id
            LEFT JOIN users uc ON s.created_by = uc.id
            LEFT JOIN users uu ON s.updated_by = uu.id
            WHERE s.service_type_id = :service_type_id
            ORDER BY s.id DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':service_type_id' => $service_type_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
