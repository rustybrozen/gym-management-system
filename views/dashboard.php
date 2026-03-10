<div class="dash-eyebrow">Admin</div>
<div class="dash-title">TỔNG <span>QUAN</span></div>

<form method="GET" action="index.php">
    <input type="hidden" name="page" value="dashboard">
    <div class="filter-bar">
        <div class="filter-group">
            <span class="filter-label">Từ ngày</span>
            <input type="date" class="filter-input" id="start_date" name="start_date"
                value="<?php echo htmlspecialchars($startDate); ?>">
        </div>
        <div class="filter-group">
            <span class="filter-label">Đến ngày</span>
            <input type="date" class="filter-input" id="end_date" name="end_date"
                value="<?php echo htmlspecialchars($endDate); ?>">
        </div>
        <button type="submit" class="filter-btn">Lọc dữ liệu</button>
    </div>
</form>

<div class="row g-0" style="gap:0">
    <div class="col-md-4 pe-md-3">
        <div class="stat-card">
            <div class="stat-label">Tổng thành viên</div>
            <div class="stat-value"><?php echo number_format($totalMembers); ?></div>
        </div>
    </div>
    <div class="col-md-4 pe-md-3">
        <div class="stat-card">
            <div class="stat-label">Gói tập hoạt động</div>
            <div class="stat-value"><?php echo number_format($activePackages); ?></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card positive">
            <div class="stat-label">Tổng doanh thu hệ thống</div>
            <div class="stat-value"><?php echo number_format($totalRevenue); ?><span class="stat-unit">đ</span></div>
        </div>
    </div>
</div>

<div class="row g-0 mt-1">
    <div class="col-md-6 pe-md-3">
        <div class="stat-card accent">
            <div class="stat-tag">Trong kỳ</div>
            <div class="stat-label">Lượt đăng ký</div>
            <div class="stat-value"><?php echo number_format($filteredStats['subscriptions']); ?></div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="stat-card accent">
            <div class="stat-tag">Trong kỳ</div>
            <div class="stat-label">Doanh thu thu về</div>
            <div class="stat-value"><?php echo number_format($filteredStats['revenue']); ?><span class="stat-unit">đ</span></div>
        </div>
    </div>
</div>

<div class="row g-0 mt-1">
    <div class="col-md-8 pe-md-3">
        <div class="chart-panel">
            <div class="chart-panel-label">Biểu đồ doanh thu</div>
            <canvas id="revenueChart" height="100"></canvas>
        </div>
    </div>
    <div class="col-md-4">
        <div class="chart-panel">
            <div class="chart-panel-label">Biểu đồ đăng ký</div>
            <canvas id="subsChart" height="225"></canvas>
        </div>
    </div>
</div>

<div class="data-panel mt-1">
    <div class="data-panel-label">Hội viên đăng ký gần đây</div>
    <div class="table-responsive">
        <table class="gym-table">
            <thead>
                <tr>
                    <th>Họ tên</th>
                    <th>Số ĐT</th>
                    <th>Giới tính</th>
                    <th>Ngày tham gia</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($recentMembers) > 0): ?>
                    <?php foreach ($recentMembers as $rm): ?>
                        <tr>
                            <td class="td-name"><?php echo htmlspecialchars($rm['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($rm['phone_number']); ?></td>
                            <td><?php echo htmlspecialchars($rm['gender']); ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($rm['created_at'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr class="empty-row">
                        <td colspan="4">Chưa có hội viên nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dates    = <?php echo $chartDatesJSON; ?>;
        const revenues = <?php echo $chartRevenuesJSON; ?>;
        const subs     = <?php echo $chartSubsJSON; ?>;

        Chart.defaults.font.family = "'Barlow', sans-serif";
        Chart.defaults.color       = '#888882';

        const gridOpts = { color: 'rgba(0,0,0,0.05)', borderDash: [3, 3] };

        new Chart(document.getElementById('revenueChart').getContext('2d'), {
            type: 'line',
            data: {
                labels: dates,
                datasets: [{
                    label: 'Doanh thu (VND)',
                    data: revenues,
                    borderColor: '#27ae60',
                    backgroundColor: 'rgba(39,174,96,0.07)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.35,
                    pointBackgroundColor: '#27ae60',
                    pointRadius: 3,
                    pointHoverRadius: 5
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: gridOpts, border: { dash: [3,3] } },
                    x: { grid: { display: false } }
                }
            }
        });

        new Chart(document.getElementById('subsChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: dates,
                datasets: [{
                    label: 'Lượt Đăng Ký',
                    data: subs,
                    backgroundColor: 'rgba(10,10,10,0.82)',
                    hoverBackgroundColor: '#c8b560',
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 }, grid: gridOpts },
                    x: { grid: { display: false } }
                }
            }
        });
    });
</script>