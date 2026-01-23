<h2 class="mb-4">Đăng ký thành viên</h2>

<form action="index.php?page=subscriptions" method="POST">
    <div class="mb-3">
        <label for="member_id" class="form-label">Chọn hội viên</label>
        <select class="form-select" id="member_id" name="member_id" required>
            <option value="">-- Chọn hội viên --</option>
            <?php foreach ($members as $member): ?>
                <option value="<?php echo $member['id']; ?>">
                    <?php echo htmlspecialchars($member['full_name']); ?> (<?php echo $member['phone_number']; ?>)
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="package_id" class="form-label">Chọn gói tập</label>
        <select class="form-select" id="package_id" name="package_id" required>
            <option value="">-- Chọn gói tập --</option>
            <?php foreach ($packages as $pkg): ?>
                <option value="<?php echo $pkg['id']; ?>">
                    <?php echo htmlspecialchars($pkg['package_name']); ?> - <?php echo $pkg['duration_days']; ?> ngày -
                    <?php echo number_format($pkg['price']); ?> VND
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Đăng ký Gói</button>
</form>