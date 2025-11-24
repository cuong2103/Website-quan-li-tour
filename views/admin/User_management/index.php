<?php 
require_once './views/components/header.php';
require_once './views/components/sidebar.php';
?>

<?php 
require_once './views/components/header.php';
require_once './views/components/sidebar.php';
?>

<main class="container-fluid px-4 py-4">

    <!-- Tiêu đề + nút thêm -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold fs-3 mb-1">Quản lý Dịch vụ</h2>
            <p class="text-muted mb-0">Quản lý các dịch vụ cung cấp cho tour</p>
        </div>
        <a href="?act=service-create" class="btn btn-warning d-flex align-items-center gap-2">
            <i class="bi bi-plus-lg"></i> Thêm Dịch vụ mới
        </a>
    </div>

    <!-- Bộ lọc -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form class="row g-3" method="get">
                <input type="hidden" name="act" value="service">
                
                <div class="col-md-4">
                    <input type="text" name="keyword" class="form-control" placeholder="Tìm theo tên dịch vụ..." value="<?= $_GET['keyword'] ?? '' ?>">
                </div>
                <div class="col-md-3">
                    <select name="service_type_id" class="form-select">
                        <option value="">Tất cả loại</option>
                        <?php foreach($serviceTypes as $type): ?>
                            <option value="<?= $type['id'] ?>" <?= (isset($_GET['service_type_id']) && $_GET['service_type_id']==$type['id']) ? 'selected' : '' ?>>
                                <?= $type['name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="supplier_id" class="form-select">
                        <option value="">Tất cả NCC</option>
                        <?php foreach($suppliers as $supplier): ?>
                            <option value="<?= $supplier['id'] ?>" <?= (isset($_GET['supplier_id']) && $_GET['supplier_id']==$supplier['id']) ? 'selected' : '' ?>>
                                <?= $supplier['name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2 d-grid">
                    <button type="submit" class="btn btn-primary">Lọc</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bảng danh sách dịch vụ -->
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Tên Dịch vụ</th>
                        <th>Loại Dịch vụ</th>
                        <th>Nhà cung cấp</th>
                        <th>Giá</th>
                        <th class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($services as $service): ?>
                    <tr>
                        <td>
                            <div class="fw-semibold"><?= htmlspecialchars($service["name"]) ?></div>
                            <div class="text-muted small"><?= htmlspecialchars($service["description"]) ?></div>
                        </td>
                        <td class="text-center">
                            <?php 
                                $typeClass = 'secondary';
                                if($service["service_type_name"] == 'Khách sạn') $typeClass='primary';
                                elseif($service["service_type_name"] == 'Vận chuyển') $typeClass='purple';
                                elseif($service["service_type_name"] == 'Ăn uống') $typeClass='info';
                            ?>
                            <span class="badge bg-<?= $typeClass ?>"><?= $service["service_type_name"] ?></span>
                        </td>
                        <td class="text-center"><?= $service["supplier_name"] ?></td>
                        <td class="text-center fw-medium"><?= number_format($service["price"]) ?></td>
                        <td class="text-center">
                            <a href="?act=service-detail&id=<?= $service['id'] ?>" class="text-secondary mx-1" data-bs-toggle="tooltip" title="Xem">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="?act=service-edit&id=<?= $service['id'] ?>" class="text-success mx-1" data-bs-toggle="tooltip" title="Sửa">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <a href="?act=service-delete&id=<?= $service['id'] ?>" class="text-danger mx-1" onclick="return confirm('Bạn có chắc chắn muốn xóa dịch vụ này?')" data-bs-toggle="tooltip" title="Xóa">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="card-footer text-muted">
            Hiển thị <?= count($services) ?> trong tổng số <?= $totalServices ?? 0 ?> dịch vụ
        </div>
    </div>

</main>

<?php 
require_once './views/components/footer.php';
?>

<script>
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>


<?php 
require_once './views/components/footer.php';
?>
