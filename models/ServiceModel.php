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
                services.*, 
                service_types.name AS service_type_name,
                suppliers.name AS supplier_name,
                suppliers.email AS supplier_email,
                suppliers.phone AS supplier_phone
            FROM services
            INNER JOIN service_types 
                ON services.service_type_id = service_types.id
            INNER JOIN suppliers
                ON services.supplier_id = suppliers.id
            WHERE 1=1";

    $loc = [];

    if(!empty($keyword)){
        $sql .= " AND services.name LIKE :keyword";
        $loc[':keyword'] = "%$keyword%";
    }
    if(!empty($service_type_id)){
        $sql .= " AND services.service_type_id = :service_type_id";
        $loc[':service_type_id'] = $service_type_id;
    }
    if(!empty($supplier_id)){
        $sql .= " AND services.supplier_id = :supplier_id";
        $loc[':supplier_id'] = $supplier_id;
    }

    $sql .= " ORDER BY services.id DESC";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute($loc);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


    // Lấy chi tiết dịch vụ
    public function getDetail($id)
    {
        $sql = "SELECT 
                    services.*, 
                    service_types.name AS service_type_name,
                    suppliers.name AS supplier_name,
                    suppliers.email AS supplier_email,
                    suppliers.phone AS supplier_phone
                FROM services
                INNER JOIN service_types 
                    ON services.service_type_id = service_types.id
                INNER JOIN suppliers
                    ON services.supplier_id = suppliers.id
                WHERE services.id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Xóa dịch vụ
    public function delete($id)
    {
        $sql = "DELETE FROM services WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]);
    }

    // Thêm dịch vụ mới
    public function create($data)
    {
        $sql =  "INSERT INTO services 
                    (service_type_id, supplier_id, name, description, price, created_by, created_at)
                 VALUES 
                    (:service_type_id, :supplier_id, :name, :description, :price, :created_by, NOW())";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            'service_type_id' => $data['service_type_id'],
            'supplier_id' => $data['supplier_id'],
            'name' => $data['name'],
            'description' => $data['description'],
            'price' => $data['price'],
            'created_by' => $data['created_by'], // integer
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
                    price = :price,
                    updated_at = NOW()
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'service_type_id' => $data['service_type_id'],
            'supplier_id' => $data['supplier_id'],
            'name' => $data['name'],
            'description' => $data['description'],
            'price' => $data['price'],
            'id' => $id,
        ]);
    }
}