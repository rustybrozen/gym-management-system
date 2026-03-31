<h2 class="mb-4">Chỉnh sửa hội viên</h2>

<?php if (isset($error)): ?>
    <div class="alert alert-danger">
        <?php echo $error; ?>
    </div>
<?php endif; ?>

<form action="index.php?page=edit_member&id=<?php echo $member['id']; ?>" method="POST">
    <input type="hidden" name="csrf_token" value="<?php echo Csrf::generate(); ?>">
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="full_name" class="form-label">Họ và tên <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="full_name" name="full_name"
                value="<?php echo htmlspecialchars($member['full_name']); ?>" required minlength="5">
        </div>
        <div class="col-md-6 mb-3">
            <label for="phone_number" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="phone_number" name="phone_number"
                value="<?php echo htmlspecialchars($member['phone_number']); ?>" required>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="gender" class="form-label">Giới tính</label>
            <select class="form-select" id="gender" name="gender">
                <option value="Nam" <?php echo $member['gender'] == 'Nam' ? 'selected' : ''; ?>>Nam</option>
                <option value="Nữ" <?php echo $member['gender'] == 'Nữ' ? 'selected' : ''; ?>>Nữ</option>
                <option value="Khác" <?php echo $member['gender'] == 'Khác' ? 'selected' : ''; ?>>Khác</option>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label for="birth_date" class="form-label">Ngày sinh</label>
            <input type="date" class="form-control" id="birth_date" name="birth_date"
                value="<?php echo $member['birth_date']; ?>">
        </div>
    </div>

    <div class="mb-3">
        <label for="address" class="form-label">Địa chỉ</label>
        <textarea class="form-control" id="address" name="address"
            rows="2"><?php echo htmlspecialchars($member['address']); ?></textarea>
    </div>

    <div class="mb-3">
        <label for="health_notes" class="form-label">Ghi chú sức khỏe/Bệnh lý</label>
        <textarea class="form-control" id="health_notes" name="health_notes" rows="3"
            placeholder="Ghi chú nếu có..."><?php echo htmlspecialchars($member['health_notes']); ?></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
    <a href="index.php?page=members" class="btn btn-secondary">Hủy</a>
</form>