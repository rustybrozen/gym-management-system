<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Danh sách gói tập</h2>
    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
        <a href="index.php?page=add_package" class="btn btn-primary">Thêm gói tập</a>
    <?php endif; ?>
</div>

<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tên gói</th>
            <th>Thời hạn (ngày)</th>
            <th>Giá (VND)</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($packages as $pkg): ?>
            <tr>
                <td><?php echo $pkg['id']; ?></td>
                <td><?php echo htmlspecialchars($pkg['package_name']); ?></td>
                <td><?php echo $pkg['duration_days']; ?></td>
                <td><?php echo number_format($pkg['price']); ?></td>
                <td>
                    <div class="d-flex gap-1">
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            <a href="index.php?page=edit_package&id=<?php echo $pkg['id']; ?>"
                                class="btn btn-sm btn-warning">Sửa</a>
                            <form action="index.php?page=delete_package" method="POST"
                                onsubmit="return confirm('Bạn có chắc muốn xóa gói tập này?');" style="display:inline;">
                                <input type="hidden" name="csrf_token" value="<?php echo Csrf::generate(); ?>">
                                <input type="hidden" name="id" value="<?php echo $pkg['id']; ?>">
                                <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                            </form>
                        <?php else: ?>
                            <span class="text-muted">Chỉ xem</span>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php if (isset($_GET['error']) && $_GET['error'] == 'used_in_subscription'): ?>
    <div class="alert alert-danger mt-3">Không thể xóa gói tập đang được sử dụng trong các đăng ký!</div>
<?php endif; ?>