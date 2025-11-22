<?php

class ServiceModel
{
    public $conn;
    public function __construct()
    {
        $this -> conn = connectDB();
    }
    public function getAll(){
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
            ON services.supplier_id = suppliers.id";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getDetail($id){
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
        return $stmt->fetch();
    }

    public function create(){
        
    }


}