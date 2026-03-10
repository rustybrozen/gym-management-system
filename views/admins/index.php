<div class="page-eyebrow">Hệ thống</div>
<div class="page-title">NHÂN <span>VIÊN</span></div>

<div class="topbar-row">
    <div></div>
    <a href="index.php?page=add_admin" class="btn-add">
        <i class="bi bi-plus"></i> Thêm nhân viên
    </a>
</div>

<div class="table-panel">
    <div class="table-responsive">
        <table class="gym-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Họ tên</th>
                    <th>Vai trò</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($admins)): ?>
                    <?php foreach ($admins as $admin): ?>
                        <?php $role = isset($admin['role']) ? $admin['role'] : 'staff'; ?>
                        <tr>
                            <td class="td-id"><?php echo $admin['id']; ?></td>
                            <td><?php echo htmlspecialchars($admin['username']); ?></td>
                            <td class="td-name"><?php echo htmlspecialchars($admin['full_name']); ?></td>
                            <td>
                                <?php if ($role === 'admin'): ?>
                                    <span class="role-badge role-admin">Admin</span>
                                <?php else: ?>
                                    <span class="role-badge role-staff">Nhân viên</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="action-group">
                                    <?php if ($admin['username'] !== 'admin'): ?>
                                        <a href="index.php?page=edit_admin&id=<?php echo $admin['id']; ?>"
                                            class="act-btn act-edit">Sửa</a>
                                        <a href="index.php?page=delete_admin&id=<?php echo $admin['id']; ?>"
                                            class="act-btn act-delete"
                                            onclick="return confirm('Bạn có chắc chắn muốn xóa?');">Xóa</a>
                                    <?php else: ?>
                                        <span class="view-only">Mặc định</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr class="empty-row">
                        <td colspan="5">Chưa có nhân viên nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>