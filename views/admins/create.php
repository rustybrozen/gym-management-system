<h2 class="mb-4">Thêm nhan vien mới</h2>

<?php if (isset($error)): ?>
    <div class="alert alert-danger">
        <?php echo $error; ?>
    </div>
<?php endif; ?>

<form action="index.php?page=add_admin" method="POST">
    <input type="hidden" name="csrf_token" value="<?php echo Csrf::generate(); ?>">
    <div class="mb-3">
        <label for="username" class="form-label">Tên đăng nhập <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="username" name="username" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Mật khẩu <span class="text-danger">*</span></label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <div class="mb-3">
        <label for="full_name" class="form-label">Họ tên hiển thị</label>
        <input type="text" class="form-control" id="full_name" name="full_name" required>
    </div>

    <button type="submit" class="btn btn-primary">Lưu thông tin</button>
    <a href="index.php?page=admins" class="btn btn-secondary">Hủy</a>
</form>