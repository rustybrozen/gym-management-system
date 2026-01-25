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
                <th>Thời hạn còn lại</th>
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
                            <span class="badge bg-warning text-dark ms-1">Ghi chú</span>
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
                        <?php if ($member['status'] == 'Active' && !empty($member['time_left'])): ?>
                            <span class="text-success fw-bold"><?php echo $member['time_left']; ?></span>
                        <?php else: ?>
                            <span class="text-muted">-</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                <a href="index.php?page=edit_member&id=<?php echo $member['id']; ?>"
                                    class="btn btn-sm btn-warning">Sửa</a>

                                <form action="index.php?page=delete_member" method="POST"
                                    onsubmit="return confirm('Bạn có chắc muốn xóa hội viên này?');" style="display:inline;">
                                    <input type="hidden" name="csrf_token" value="<?php echo Csrf::generate(); ?>">
                                    <input type="hidden" name="id" value="<?php echo $member['id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                                </form>
                            <?php endif; ?>

                            <a href="index.php?page=subscriptions&member_id=<?php echo $member['id']; ?>"
                                class="btn btn-sm btn-success">Đăng ký</a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>