<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Check-in Hội Viên</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="index.php?page=checkin">
                    <input type="hidden" name="csrf_token" value="<?php echo Csrf::generate(); ?>">
                    <div class="input-group mb-3">
                        <input type="text" name="keyword" class="form-control form-control-lg"
                            placeholder="Nhập số điện thoại hoặc tên hội viên..." required autofocus>
                        <button class="btn btn-success" type="submit">Kiểm tra</button>
                    </div>
                </form>

                <?php if (isset($result)): ?>
                    <div class="mt-4">
                        <?php if ($result['status'] === 'success'): ?>
                            <div class="alert alert-success text-center">
                                <h1><i class="bi bi-check-circle-fill"></i> HỢP LỆ</h1>
                                <hr>
                                <h4>
                                    <?php echo htmlspecialchars($result['member']['full_name']); ?>
                                </h4>
                                <p>Gói tập hết hạn vào: <strong>
                                        <?php echo date('d/m/Y', strtotime($result['subscription']['end_date'])); ?>
                                    </strong></p>
                                <p>Còn lại: <strong>
                                        <?php echo $result['days_left']; ?> ngày
                                    </strong></p>
                            </div>
                        <?php elseif ($result['status'] === 'expired'): ?>
                            <div class="alert alert-danger text-center">
                                <h1><i class="bi bi-x-circle-fill"></i> KHÔNG HỢP LỆ</h1>
                                <hr>
                                <h4>
                                    <?php echo htmlspecialchars($result['member']['full_name']); ?>
                                </h4>
                                <p class="mb-0">
                                    <?php echo $result['message']; ?>
                                </p>
                                <a href="index.php?page=subscriptions&member_id=<?php echo $result['member']['id']; ?>"
                                    class="btn btn-warning mt-3">Đăng ký gia hạn ngay</a>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-warning text-center">
                                <h4>
                                    <?php echo $result['message']; ?>
                                </h4>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>