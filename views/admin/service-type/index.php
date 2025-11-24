<?php
require_once './views/components/header.php';
require_once './views/components/sidebar.php';
?>

<main class="container-fluid px-4 py-4">

    <!-- Tiêu đề -->
    <div class="mb-4">
        <h2 class="fw-bold fs-3 mb-1">Quản lý Loại Dịch vụ</h2>
        <p class="text-muted mb-0">Quản lý các danh mục dịch vụ như Khách sạn, Vận chuyển, Nhà hàng...</p>
    </div>

    <div class="row g-4">

        <!-- Form thêm loại dịch vụ -->
        <div class="col-12 col-lg-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">Thêm Loại Dịch vụ</h5>
                    <form action="<?= BASE_URL . 'index.php?act=service-type-store' ?>" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Tên loại dịch vụ <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="Ví dụ: Khách sạn, Vận chuyển..." required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mô tả</label>
                            <textarea name="description" rows="3" class="form-control" placeholder="Mô tả chi tiết về loại dịch vụ này..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-warning w-100">+ Lưu</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Danh sách loại dịch vụ -->
        <div class="col-12 col-lg-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">Danh sách hiện có (<?= count($serviceTypes) ?>)</h5>
                    <div class="list-group">
                        <?php foreach($serviceTypes as $serviceType): ?>
                        <div class="list-group-item list-group-item-action mb-2 p-3 rounded shadow-sm d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1 fw-semibold"><?= htmlspecialchars($serviceType["name"]) ?></h6>
                                <p class="mb-0 text-muted small"><?= htmlspecialchars($serviceType["description"]) ?></p>
                            </div>
                            <div class="d-flex gap-2">
                                <!-- Xem -->
                                <a href="index.php?act=service-type-detail&id=<?= $serviceType['id'] ?>" class="btn btn-sm btn-outline-secondary" title="Xem">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <!-- Sửa -->
                                <a href="index.php?act=service-type-edit&id=<?= $serviceType['id'] ?>" class="btn btn-sm btn-outline-success" title="Sửa">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <!-- Xóa -->
                                <a href="index.php?act=service-type-delete&id=<?= $serviceType['id'] ?>" onclick="return confirm('Bạn có chắc muốn xóa không?')" class="btn btn-sm btn-outline-danger" title="Xóa">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

    </div>

</main>

<?php
require_once './views/components/footer.php';
?>
