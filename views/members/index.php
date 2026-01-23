<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Danh sách hội viên</h2>

    <div class="d-flex gap-2">
        <form action="index.php" method="GET" class="d-flex">
            <input type="hidden" name="page" value="members">
            <input type="text" name="search" class="form-control me-2" placeholder="Tìm tên hoặc SĐT..."
                value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button type="submit" class="btn btn-outline-secondary">Tìm</button>
        </form>
        <a href="index.php?page=add_member" class="btn btn-primary text-nowrap">Thêm hội viên</a>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Họ tên</th>
                <th>SĐT</th>
                <th>Giới tính</th>
                <th>Ngày sinh</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($members as $member): ?>
                <tr title="<?php echo htmlspecialchars($member['health_notes']); ?>">
                    <td><?php echo $member['id']; ?></td>
                    <td>
                        <strong><?php echo htmlspecialchars($member['full_name']); ?></strong>
                        <?php if (!empty($member['health_notes'])): ?>
                            <span class="badge bg-warning text-dark ms-1">Note</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($member['phone_number']); ?></td>
                    <td><?php echo htmlspecialchars($member['gender']); ?></td>
                    <td><?php echo $member['birth_date'] ? date('d/m/Y', strtotime($member['birth_date'])) : ''; ?></td>
                    <td>
                        <?php if ($member['status'] == 'Active'): ?>
                            <span class="badge bg-success">CÒN HẠN</span>
                        <?php else: ?>
                            <span class="badge bg-danger">HẾT HẠN</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-info text-white">Xem</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>