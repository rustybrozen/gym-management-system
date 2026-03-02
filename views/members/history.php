<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Lịch sử giao dịch:
        <?php echo htmlspecialchars($member['full_name']); ?>
    </h2>
    <a href="index.php?page=members" class="btn btn-secondary text-nowrap">Quay lại</a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <h5 class="card-title">Thông tin hội viên</h5>
        <div class="row">
            <div class="col-md-6">
                <p><strong>SĐT:</strong>
                    <?php echo htmlspecialchars($member['phone_number']); ?>
                </p>
                <p><strong>Ngày sinh:</strong>
                    <?php echo $member['birth_date'] ? date('d/m/Y', strtotime($member['birth_date'])) : 'N/A'; ?>
                </p>
            </div>
            <div class="col-md-6">
                <p><strong>Ghi chú:</strong>
                    <?php echo nl2br(htmlspecialchars($member['health_notes'])); ?>
                </p>
            </div>
        </div>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>Ngày ĐK</th>
                <th>Tên gói tập</th>
                <th>Từ ngày</th>
                <th>Đến ngày</th>
                <th>Số tiền</th>
                <th>Người đăng ký</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($history)): ?>
                <tr>
                    <td colspan="6" class="text-center">Chưa có giao dịch nào.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($history as $transaction): ?>
                    <tr>
                        <td>
                            <?php echo date('H:i d/m/Y', strtotime($transaction['created_at'])); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($transaction['package_name']); ?>
                        </td>
                        <td>
                            <?php echo date('d/m/Y', strtotime($transaction['start_date'])); ?>
                        </td>
                        <td>
                            <?php echo date('d/m/Y', strtotime($transaction['end_date'])); ?>
                        </td>
                        <td>
                            <?php echo number_format($transaction['amount_paid'], 0, ',', '.'); ?> VNĐ
                        </td>
                        <td>
                            <?php echo htmlspecialchars($transaction['admin_name'] ?? 'Hệ thống'); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>