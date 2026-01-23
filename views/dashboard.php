<h2 class="mb-4">Dashboard</h2>

<div class="row">
    <div class="col-md-4">
        <div class="card text-white bg-primary mb-3">
            <div class="card-header">Tổng thành viên</div>
            <div class="card-body">
                <h5 class="card-title"><?php echo $totalMembers; ?></h5>
                <p class="card-text">Số lượng thành viên đã đăng ký.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-success mb-3">
            <div class="card-header">Tổng doanh thu</div>
            <div class="card-body">
                <h5 class="card-title"><?php echo number_format($totalRevenue); ?> VND</h5>
                <p class="card-text">Tổng doanh thu từ các gói đăng ký.</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-warning mb-3">
            <div class="card-header">Gói tập đang hoạt động</div>
            <div class="card-body">
                <h5 class="card-title"><?php echo $activePackages; ?></h5>
                <p class="card-text">Số lượng gói tập hiện có.</p>
            </div>
        </div>
    </div>
</div>