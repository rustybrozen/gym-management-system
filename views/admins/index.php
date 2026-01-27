<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Quản lý Nhan Vien</h2>
    <a href="index.php?page=add_admin" class="btn btn-primary">Thêm Nhan Vien</a>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Họ tên</th>
                <th>Vai trò</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($admins as $admin): ?>
                <tr>
                    <td><?php echo $admin['id']; ?></td>
                    <td><?php echo htmlspecialchars($admin['username']); ?></td>
                    <td><?php echo htmlspecialchars($admin['full_name']); ?></td>
                    <td>
                        <?php
                        $role = isset($admin['role']) ? $admin['role'] : 'staff';
                        if ($role === 'admin')
                            echo '<span class="badge bg-danger">Admin</span>';
                        else
                            echo '<span class="badge bg-primary">Nhan Vien</span>';
                        ?>
                    </td>
                    <td>
                         <?php if ($admin['username'] !== 'admin'): ?>
                        <a href="index.php?page=edit_admin&id=<?php echo $admin['id']; ?>"
                            class="btn btn-sm btn-warning">Sửa</a>
                            <?php endif; ?>

                        <?php if ($admin['username'] !== 'admin'): ?>
                            <a href="index.php?page=delete_admin&id=<?php echo $admin['id']; ?>" class="btn btn-sm btn-danger"
                                onclick="return confirm('Bạn có chắc chắn muốn xóa?');">Xóa</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>