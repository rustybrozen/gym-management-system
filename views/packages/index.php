<div class="page-eyebrow">Quản lý</div>
<div class="page-title">GÓI <span>TẬP</span></div>

<div class="packages-topbar">
    <div></div>
    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
        <a href="index.php?page=add_package" class="btn-add">
            <i class="bi bi-plus"></i> Thêm gói tập
        </a>
    <?php endif; ?>
</div>

<div class="table-panel">
    <div class="table-responsive">
        <table class="gym-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên gói</th>
                    <th>Thời hạn</th>
                    <th>Giá</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($packages)): ?>
                    <?php foreach ($packages as $pkg): ?>
                        <tr>
                            <td class="td-id"><?php echo $pkg['id']; ?></td>
                            <td class="td-pkg-name"><?php echo htmlspecialchars($pkg['package_name']); ?></td>
                            <td>
                                <span class="td-duration">
                                    <?php echo $pkg['duration_days']; ?><span class="dur-unit">ngày</span>
                                </span>
                            </td>
                            <td>
                                <span class="td-price">
                                    <?php echo number_format($pkg['price']); ?><span class="price-unit">đ</span>
                                </span>
                            </td>
                            <td>
                                <div class="action-group">
                                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                        <a href="index.php?page=edit_package&id=<?php echo $pkg['id']; ?>"
                                            class="act-btn act-edit">Sửa</a>
                                        <form action="index.php?page=delete_package" method="POST"
                                            onsubmit="return confirm('Bạn có chắc muốn xóa gói tập này?');"
                                            style="display:inline;">
                                            <input type="hidden" name="csrf_token" value="<?php echo Csrf::generate(); ?>">
                                            <input type="hidden" name="id" value="<?php echo $pkg['id']; ?>">
                                            <button type="submit" class="act-btn act-delete">Xóa</button>
                                        </form>
                                    <?php else: ?>
                                        <span class="view-only">Chỉ xem</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr class="empty-row">
                        <td colspan="5">Chưa có gói tập nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if (isset($_GET['error']) && $_GET['error'] == 'used_in_subscription'): ?>
    <div class="alert-flat">
        <i class="bi bi-exclamation-triangle"></i>
        Không thể xóa gói tập đang được sử dụng trong các đăng ký!
    </div>
<?php endif; ?>