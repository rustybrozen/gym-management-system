<h2 class="mb-4">Chỉnh sửa gói tập</h2>

<?php if (isset($error)): ?>
    <div class="alert alert-danger">
        <?php echo $error; ?>
    </div>
<?php endif; ?>

<form action="index.php?page=edit_package&id=<?php echo $package['id']; ?>" method="POST">
    <input type="hidden" name="csrf_token" value="<?php echo Csrf::generate(); ?>">
    <div class="mb-3">
        <label for="package_name" class="form-label">Tên gói tập</label>
        <input type="text" class="form-control" id="package_name" name="package_name"
            value="<?php echo htmlspecialchars($package['package_name']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="duration_days" class="form-label">Thời hạn (ngày)</label>
        <input type="number" class="form-control" id="duration_days" name="duration_days"
            value="<?php echo $package['duration_days']; ?>" required>
    </div>
    <div class="mb-3">
        <label for="price" class="form-label">Giá (VND)</label>
        <input type="number" step="0.01" class="form-control" id="price" name="price"
            value="<?php echo $package['price']; ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
    <a href="index.php?page=packages" class="btn btn-secondary">Hủy</a>
</form>