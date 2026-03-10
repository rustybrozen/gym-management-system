<div class="page-eyebrow">Tài khoản</div>
<div class="page-title">CẬP NHẬT <span>THÔNG TIN</span></div>

<?php if (isset($error)): ?>
    <div class="alert-flat mb-field">
        <i class="bi bi-exclamation-triangle"></i> <?php echo $error; ?>
    </div>
<?php endif; ?>

<?php if (isset($success)): ?>
    <div class="alert-flat-success mb-field">
        <i class="bi bi-check-circle"></i> <?php echo $success; ?>
    </div>
<?php endif; ?>

<div class="form-panel">
    <form action="index.php?page=settings" method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo Csrf::generate(); ?>">

        <div class="form-section-label">Thông tin tài khoản</div>

        <div class="mb-field">
            <label class="gym-label" for="username">Tên đăng nhập</label>
            <input type="text" class="gym-input input-readonly" id="username" name="username"
                value="<?php echo htmlspecialchars($user['username']); ?>" readonly>
        </div>

        <div class="mb-field">
            <label class="gym-label" for="full_name">Họ tên hiển thị</label>
            <input type="text" class="gym-input" id="full_name" name="full_name"
                value="<?php echo htmlspecialchars($user['full_name']); ?>">
        </div>

        <div class="form-section-label" style="margin-top: 28px;">Đổi mật khẩu</div>

        <div class="mb-field">
            <label class="gym-label" for="current_password">Mật khẩu hiện tại</label>
            <input type="password" class="gym-input" id="current_password" name="current_password"
                placeholder="Nhập mật khẩu hiện tại...">
        </div>

        <div class="mb-field">
            <label class="gym-label" for="new_password">Mật khẩu mới</label>
            <input type="password" class="gym-input" id="new_password" name="new_password"
                placeholder="Để trống nếu không đổi...">
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-submit">Lưu thay đổi</button>
            <a href="index.php?page=dashboard" class="btn-cancel">Quay lại</a>
        </div>

    </form>
</div>