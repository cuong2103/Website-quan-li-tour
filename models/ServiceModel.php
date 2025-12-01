<?php

class ServiceModel
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    // Lấy tất cả dịch vụ
    public function getAll($keyword = '', $service_type_id = '', $supplier_id = '')
    {
        $sql = "SELECT 
                    s.*, 
                    st.name AS service_type_name,
                    sp.name AS supplier_name,
                    sp.email AS supplier_email,
                    sp.phone AS supplier_phone
                FROM services s
                LEFT JOIN service_types st ON s.service_type_id = st.id
                LEFT JOIN suppliers sp ON s.supplier_id = sp.id
                WHERE 1=1";

        $params = [];

        if ($keyword !== '') {
            $sql .= " AND s.name LIKE :keyword";
            $params[':keyword'] = "%$keyword%";
        }

        if ($service_type_id !== '') {
            $sql .= " AND s.service_type_id = :service_type_id";
            $params[':service_type_id'] = $service_type_id;
        }

        if ($supplier_id !== '') {
            $sql .= " AND s.supplier_id = :supplier_id";
            $params[':supplier_id'] = $supplier_id;
        }

        $sql .= " ORDER BY s.id DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function search($keyword)
    {
        try {
            $sql = "SELECT 
                    s.*, 
                    st.name AS service_type_name,
                    sp.name AS supplier_name,
                    sp.email AS supplier_email,
                    sp.phone AS supplier_phone
                FROM services s
                LEFT JOIN service_types st ON s.service_type_id = st.id
                LEFT JOIN suppliers sp ON s.supplier_id = sp.id
                WHERE s.name LIKE :keyword
                ORDER BY s.id DESC";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':keyword' => "%" . $keyword . "%"
            ]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Lỗi tìm kiếm: " . $e->getMessage();
            return [];
        }
    }



    // Lấy chi tiết dịch vụ
    public function getDetail($id)
    {
        $sql = "SELECT
                    s.*, 
                    st.name AS service_type_name,
                    sp.name AS supplier_name,
                    sp.email AS supplier_email,
                    sp.phone AS supplier_phone
                FROM services s
                LEFT JOIN service_types st ON s.service_type_id = st.id
                LEFT JOIN suppliers sp ON s.supplier_id = sp.id
                WHERE s.id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Xóa dịch vụ
    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM services WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    // Thêm dịch vụ
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
            ':estimated_price'           => $data['estimated_price'],
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
                    updated_at = NOW()
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':service_type_id' => $data['service_type_id'],
            ':supplier_id'     => $data['supplier_id'],
            ':name'            => $data['name'],
            ':description'     => $data['description'],
            ':estimated_price' => $data['estimated_price'],
            ':id'              => $id
        ]);
    }

    // Kiểm tra dịch vụ trùng (tránh tạo mới hoặc cập nhật trùng)
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
            $sql .= " AND id != :id";
            $params[':id'] = $excludeId;
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchColumn() > 0;
    }
    public function getBySupplierID($supplierId)
    {
        $sql = "SELECT * FROM services WHERE supplier_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$supplierId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByServiceType($service_type_id)
    {
        $sql = "SELECT 
                s.*, 
                st.name AS service_type_name,
                sp.name AS supplier_name,
                sp.email AS supplier_email,
                sp.phone AS supplier_phone
            FROM services s
            LEFT JOIN service_types st ON s.service_type_id = st.id
            LEFT JOIN suppliers sp ON s.supplier_id = sp.id
            WHERE s.service_type_id = :service_type_id
            ORDER BY s.id DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':service_type_id' => $service_type_id]);
    }
}
