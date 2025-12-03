<?php
class SupplierModel
{
    public $conn;

    public function __construct()
    {
        // Kết nối database khi khởi tạo model
        $this->conn = connectDB();
    }

    // Lấy toàn bộ nhà cung cấp (kèm tên điểm đến + người tạo/cập nhật)
    public function getALL()
    {
        $sql = "SELECT s.*, 
                       d.name as destination_name,
                       u_create.fullname as creator_name,
                       u_update.fullname as updater_name
                FROM suppliers s
                -- Lấy tên điểm đến theo destination_id
                LEFT JOIN destinations d ON s.destination_id = d.id
                -- Lấy tên người tạo
                LEFT JOIN users u_create ON s.created_by = u_create.id
                -- Lấy tên người cập nhật
                LEFT JOIN users u_update ON s.updated_by = u_update.id
                ORDER BY s.id DESC";

        // Chuẩn bị và thực thi câu lệnh
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        // Trả về danh sách tất cả NCC dạng mảng
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy 1 nhà cung cấp theo id
    public function getByID($id)
    {
        $sql = "SELECT s.*, 
                       d.name as destination_name,
                       u_create.fullname as creator_name,
                       u_update.fullname as updater_name
                FROM suppliers s
                LEFT JOIN destinations d ON s.destination_id = d.id 
                LEFT JOIN users u_create ON s.created_by = u_create.id
                LEFT JOIN users u_update ON s.updated_by = u_update.id
                WHERE s.id = :id";

        // Chuẩn bị câu truy vấn
        $stmt = $this->conn->prepare($sql);
        // Gán giá trị id cho tham số :id
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        // Trả về 1 bản ghi duy nhất
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm nhà cung cấp mới
    public function create($data)
    {
        $sql = "INSERT INTO `suppliers`
                    (`name`, `email`, `phone`, `status`, `destination_id`,
                     `created_by`, `updated_by`, `created_at`, `updated_at`)
                VALUES 
                    (:name, :email, :phone, :status, :destination_id,
                     :created_by, :updated_by, NOW(), NOW())";

        // Chuẩn bị câu lệnh
        $stmt = $this->conn->prepare($sql);

        // Thực thi và truyền dữ liệu vào
        return $stmt->execute([
            ':name'           => $data['name'],
            ':email'          => $data['email'],
            ':phone'          => $data['phone'],
            ':status'         => $data['status'],
            ':destination_id' => $data['destination_id'],
            ':created_by'     => $data['created_by'],
            ':updated_by'     => $data['updated_by']
        ]);
    }

    // Cập nhật nhà cung cấp
    public function update($data)
    {
        $sql = "UPDATE suppliers
                SET name = :name,
                    email = :email,
                    phone = :phone,
                    destination_id = :destination_id,
                    status = :status,
                    updated_by = :updated_by,
                    updated_at = NOW()
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        // Thực thi cập nhật với dữ liệu truyền vào
        return $stmt->execute([
            ':id'             => $data['id'],
            ':name'           => $data['name'],
            ':email'          => $data['email'],
            ':phone'          => $data['phone'],
            ':status'         => $data['status'],
            ':destination_id' => $data['destination_id'],
            ':updated_by'     => $data['updated_by']
        ]);
    }

    // Xóa nhà cung cấp
    public function delete($id)
    {
        try {
            $sql = "DELETE FROM `suppliers` WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
        } catch (PDOException $e) {

            // Nếu lỗi ràng buộc khóa ngoại (nhà cung cấp đang được dùng)
            if ($e->getCode() == '23000') {
                return "FOREIGN_KEY_CONSTRAINT";
            }

            // Nếu không phải lỗi khóa ngoại thì ném lỗi để debug
            throw $e;
        }
    }

    // Lấy dữ liệu đơn giản theo ID (không join bảng)
    public function detail($id)
    {
        $sql = "SELECT * FROM `suppliers` WHERE id = :id";
        $stmt = $this->conn->prepare($sql);

        // Gán id vào truy vấn
        $stmt->bindParam(":id", $id);

        // Thực thi câu lệnh
        $data = $stmt->execute();

        // Trả về kết quả execute (true/false) – không trả dữ liệu (theo code gốc)
        return $data;
    }
}
