<h2 class="mb-4">Thêm gói tập mới</h2>

<form action="index.php?page=add_package" method="POST">
    <input type="hidden" name="csrf_token" value="<?php echo Csrf::generate(); ?>">
    <div class="mb-3">
        <label for="package_name" class="form-label">Tên gói tập</label>
        <input type="text" class="form-control" id="package_name" name="package_name" value="<?php echo isset($package['package_name']) ? htmlspecialchars($package['package_name']) : ''; ?>" required>
    </div>
    <div class="mb-3">
        <label for="duration_days" class="form-label">Thời hạn (ngày)</label>
        <input type="number" class="form-control" id="duration_days" name="duration_days" value="<?php echo isset($package['duration_days']) ? $package['duration_days'] : ''; ?>" required>
    </div>
    <div class="mb-3">
        <label for="price" class="form-label">Giá (VND)</label>
        <input type="number" step="0.01" class="form-control" id="price" name="price" value="<?php echo isset($package['price']) ? $package['price'] : ''; ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Lưu gói tập</button>
    <a href="index.php?page=packages" class="btn btn-secondary">Hủy</a>
</form>