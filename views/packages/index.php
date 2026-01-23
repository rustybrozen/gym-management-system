<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Danh sách gói tập</h2>
    <a href="index.php?page=add_package" class="btn btn-primary">Thêm gói tập</a>
</div>

<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tên gói</th>
            <th>Thời hạn (ngày)</th>
            <th>Giá (VND)</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($packages as $pkg): ?>
            <tr>
                <td><?php echo $pkg['id']; ?></td>
                <td><?php echo htmlspecialchars($pkg['package_name']); ?></td>
                <td><?php echo $pkg['duration_days']; ?></td>
                <td><?php echo number_format($pkg['price']); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>