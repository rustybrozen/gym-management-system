<h2 class="mb-4">Sửa thông tin Admin</h2>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<form action="index.php?page=edit_admin&id=<?php echo $admin['id']; ?>" method="POST">
    <input type="hidden" name="csrf_token" value="<?php echo Csrf::generate(); ?>">
    <div class="mb-3">
        <label for="username" class="form-label">Tên đăng nhập (Username)</label>
        <input type="text" class="form-control" id="username" name="username"
            value="<?php echo htmlspecialchars($admin['username']); ?>" required <?php echo ($admin['username'] === 'admin') ? 'readonly' : ''; ?>>
        <?php if ($admin['username'] === 'admin'): ?>
            <div class="form-text">Không thể đổi username của Admin chính.</div>
        <?php endif; ?>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Mật khẩu mới (Để trống nếu không đổi)</label>
        <input type="password" class="form-control" id="password" name="password">
    </div>
    <div class="mb-3">
        <label for="full_name" class="form-label">Họ tên hiển thị</label>
        <input type="text" class="form-control" id="full_name" name="full_name"
            value="<?php echo htmlspecialchars($admin['full_name']); ?>" required>
    </div>

    <button type="submit" class="btn btn-primary">Cập nhật</button>
    <a href="index.php?page=admins" class="btn btn-secondary">Hủy</a>
</form>