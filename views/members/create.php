<h2 class="mb-4">Thêm hội viên mới</h2>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<form action="index.php?page=add_member" method="POST">
    <input type="hidden" name="csrf_token" value="<?php echo Csrf::generate(); ?>">
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="full_name" class="form-label">Họ và tên <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="full_name" name="full_name" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="phone_number" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="phone_number" name="phone_number" required>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="gender" class="form-label">Giới tính</label>
            <select class="form-select" id="gender" name="gender">
                <option value="Nam">Nam</option>
                <option value="Nữ">Nữ</option>
                <option value="Khác">Khác</option>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label for="birth_date" class="form-label">Ngày sinh</label>
            <input type="date" class="form-control" id="birth_date" name="birth_date">
        </div>
    </div>

    <div class="mb-3">
        <label for="address" class="form-label">Địa chỉ</label>
        <textarea class="form-control" id="address" name="address" rows="2"></textarea>
    </div>

    <div class="mb-3">
        <label for="health_notes" class="form-label">Ghi chú sức khỏe/Bệnh lý</label>
        <textarea class="form-control" id="health_notes" name="health_notes" rows="3"
            placeholder="Ghi chú nếu có..."></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Lưu thông tin</button>
    <a href="index.php?page=members" class="btn btn-secondary">Hủy</a>
</form>