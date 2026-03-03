<h2 class="mb-4 text-dark">Tổng quan</h2>

<!-- Filter Form -->
<div class="card bg-white border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="index.php" class="row g-3 align-items-end">
            <input type="hidden" name="page" value="dashboard">
            <div class="col-auto">
                <label for="start_date" class="form-label text-muted">Từ ngày</label>
                <input type="date" class="form-control" id="start_date" name="start_date"
                    value="<?php echo htmlspecialchars($startDate); ?>">
            </div>
            <div class="col-auto">
                <label for="end_date" class="form-label text-muted">Đến ngày</label>
                <input type="date" class="form-control" id="end_date" name="end_date"
                    value="<?php echo htmlspecialchars($endDate); ?>">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-dark px-4">Lọc dữ liệu</button>
            </div>
        </form>
    </div>
</div>

<!-- Global Stats -->
<div class="row">
    <div class="col-md-4">
        <div class="card bg-white border-0 shadow-sm mb-4">
            <div class="card-body">
                <h6 class="text-muted text-uppercase mb-2">Tổng thành viên</h6>
                <h3 class="card-title text-dark mb-0"><?php echo number_format($totalMembers); ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-white border-0 shadow-sm mb-4">
            <div class="card-body">
                <h6 class="text-muted text-uppercase mb-2">Gói tập hoạt động</h6>
                <h3 class="card-title text-dark mb-0"><?php echo number_format($activePackages); ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-white border-0 shadow-sm mb-4">
            <div class="card-body">
                <h6 class="text-muted text-uppercase mb-2">Tổng doanh thu hệ thống</h6>
                <h3 class="card-title text-success mb-0"><?php echo number_format($totalRevenue); ?> đ</h3>
            </div>
        </div>
    </div>
</div>

<!-- Filtered Stats -->
<div class="row">
    <div class="col-md-6">
        <div class="card bg-white border-0 shadow-sm mb-4">
            <div class="card-body">
                <h6 class="text-muted text-uppercase mb-2">Số lượt đăng ký (Trong kỳ)</h6>
                <h3 class="card-title text-primary mb-0"><?php echo number_format($filteredStats['subscriptions']); ?>
                </h3>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card bg-white border-0 shadow-sm mb-4">
            <div class="card-body">
                <h6 class="text-muted text-uppercase mb-2">Doanh thu thu về (Trong kỳ)</h6>
                <h3 class="card-title text-primary mb-0"><?php echo number_format($filteredStats['revenue']); ?> đ</h3>
            </div>
        </div>
    </div>
</div>

<!-- Charts -->
<div class="row">
    <div class="col-md-8">
        <div class="card bg-white border-0 shadow-sm mb-4">
            <div class="card-body">
                <h6 class="text-muted text-uppercase mb-4">Biểu đồ doanh thu</h6>
                <canvas id="revenueChart" height="100"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-white border-0 shadow-sm mb-4">
            <div class="card-body">
                <h6 class="text-muted text-uppercase mb-4">Biểu đồ đăng ký</h6>
                <canvas id="subsChart" height="225"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Recent Members -->
<div class="card bg-white border-0 shadow-sm mb-4">
    <div class="card-body">
        <h6 class="text-muted text-uppercase mb-3">Hội viên đăng ký gần đây nhất</h6>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-muted">Họ tên</th>
                        <th class="text-muted">Số ĐT</th>
                        <th class="text-muted">Giới tính</th>
                        <th class="text-muted text-end">Ngày tham gia</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($recentMembers) > 0): ?>
                        <?php foreach ($recentMembers as $rm): ?>
                            <tr>
                                <td class="fw-bold text-dark"><?php echo htmlspecialchars($rm['full_name']); ?></td>
                                <td><?php echo htmlspecialchars($rm['phone_number']); ?></td>
                                <td><?php echo htmlspecialchars($rm['gender']); ?></td>
                                <td class="text-end text-muted"><?php echo date('d/m/Y H:i', strtotime($rm['created_at'])); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted py-3">Chưa có hội viên nào.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Chart.js Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dates = <?php echo $chartDatesJSON; ?>;
        const revenues = <?php echo $chartRevenuesJSON; ?>;
        const subs = <?php echo $chartSubsJSON; ?>;

        // Revenue Line Chart
        const ctxRev = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctxRev, {
            type: 'line',
            data: {
                labels: dates,
                datasets: [{
                    label: 'Doanh thu (VND)',
                    data: revenues,
                    borderColor: '#198754',
                    backgroundColor: 'rgba(25, 135, 84, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { borderDash: [2, 2] } },
                    x: { grid: { display: false } }
                }
            }
        });

        // Subscriptions Bar Chart
        const ctxSubs = document.getElementById('subsChart').getContext('2d');
        new Chart(ctxSubs, {
            type: 'bar',
            data: {
                labels: dates,
                datasets: [{
                    label: 'Lượt Đăng Ký',
                    data: subs,
                    backgroundColor: '#0d6efd',
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 }, grid: { borderDash: [2, 2] } },
                    x: { grid: { display: false } }
                }
            }
        });
    });
</script>