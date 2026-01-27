<h2 class="mb-4">Cập nhật thông tin cá nhân</h2>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<?php if (isset($success)): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<form action="index.php?page=settings" method="POST">
    <input type="hidden" name="csrf_token" value="<?php echo Csrf::generate(); ?>">
    
    <div class="mb-3">
        <label for="username" class="form-label">Tên đăng nhập</label>
        <input type="text" class="form-control" id="username" name="username"
            value="<?php echo htmlspecialchars($user['username']); ?>" readonly style="background-color: #e9ecef;">
    </div>

    <div class="mb-3">
        <label for="current_password" class="form-label">Mật khẩu cũ</label>
        <input type="password" class="form-control" id="current_password" name="current_password">
    </div>

    <div class="mb-3">
        <label for="new_password" class="form-label">Mật khẩu mới</label>
        <input type="password" class="form-control" id="new_password" name="new_password">
    </div>

    <div class="mb-3">
        <label for="full_name" class="form-label">Họ tên hiển thị</label>
        <input type="text" class="form-control" id="full_name" name="full_name"
            value="<?php echo htmlspecialchars($user['full_name']); ?>">
    </div>

    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
    <a href="index.php?page=dashboard" class="btn btn-secondary">Quay lại</a>
</form>