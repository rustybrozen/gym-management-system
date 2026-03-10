<div class="page-eyebrow">Quản lý</div>
<div class="page-title">HỘI <span>VIÊN</span></div>

<div class="topbar-row">
    <form action="index.php" method="GET" class="search-form">
        <input type="hidden" name="page" value="members">
        <input
            type="text"
            name="search"
            class="search-input"
            placeholder="Tìm tên hoặc SĐT..."
            value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
        >
        <button type="submit" class="search-btn">Tìm</button>
    </form>
    <a href="index.php?page=add_member" class="btn-add">
        <i class="bi bi-plus"></i> Thêm hội viên
    </a>
</div>

<div class="table-panel">
    <div class="table-responsive">
        <table class="gym-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Họ tên</th>
                    <th>SĐT</th>
                    <th>Giới tính</th>
                    <th>Ngày sinh</th>
                    <th>Trạng thái</th>
                    <th>Còn lại</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($members)): ?>
                    <?php foreach ($members as $member): ?>
                        <tr title="<?php echo htmlspecialchars($member['health_notes']); ?>">
                            <td class="td-id"><?php echo $member['id']; ?></td>
                            <td class="td-name">
                                <?php echo htmlspecialchars($member['full_name']); ?>
                                <?php if (!empty($member['health_notes'])): ?>
                                    <span class="note-tag">Ghi chú</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($member['phone_number']); ?></td>
                            <td><?php echo htmlspecialchars($member['gender']); ?></td>
                            <td><?php echo $member['birth_date'] ? date('d/m/Y', strtotime($member['birth_date'])) : '—'; ?></td>
                            <td>
                                <?php if ($member['status'] == 'Active'): ?>
                                    <span class="status-badge status-active">Còn hạn</span>
                                <?php else: ?>
                                    <span class="status-badge status-expired">Hết hạn</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($member['status'] == 'Active' && !empty($member['time_left'])): ?>
                                    <span class="time-left"><?php echo $member['time_left']; ?></span>
                                <?php else: ?>
                                    <span class="time-dash">—</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="action-group">
                                    <a href="index.php?page=edit_member&id=<?php echo $member['id']; ?>" class="act-btn act-edit">Sửa</a>

                                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                        <form action="index.php?page=delete_member" method="POST"
                                            onsubmit="return confirm('Bạn có chắc muốn xóa hội viên này?');"
                                            style="display:inline;">
                                            <input type="hidden" name="csrf_token" value="<?php echo Csrf::generate(); ?>">
                                            <input type="hidden" name="id" value="<?php echo $member['id']; ?>">
                                            <button type="submit" class="act-btn act-delete">Xóa</button>
                                        </form>
                                    <?php endif; ?>

                                    <a href="index.php?page=member_history&id=<?php echo $member['id']; ?>" class="act-btn act-history">Lịch sử GD</a>

                                    <?php if ($member['status'] != 'Active'): ?>
                                        <a href="index.php?page=subscriptions&member_id=<?php echo $member['id']; ?>" class="act-btn act-sub">Đăng ký</a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr class="empty-row">
                        <td colspan="8">Chưa có hội viên nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>