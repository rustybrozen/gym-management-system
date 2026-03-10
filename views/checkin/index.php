<div class="checkin-wrap">

    <div class="checkin-eyebrow">Cổng vào</div>
    <div class="checkin-title">CHECK<span>-IN</span></div>

    <!-- Search form -->
    <div class="checkin-form-wrap">
        <form method="POST" action="index.php?page=checkin">
            <input type="hidden" name="csrf_token" value="<?php echo Csrf::generate(); ?>">
            <label class="checkin-form-label">Số điện thoại hoặc tên hội viên</label>
            <div class="checkin-input-row">
                <input
                    type="text"
                    name="keyword"
                    class="checkin-input"
                    placeholder="Nhập để tìm kiếm..."
                    required
                    autofocus
                >
                <button type="submit" class="checkin-btn">Kiểm tra</button>
            </div>
        </form>
    </div>

    <!-- Result -->
    <?php if (isset($result)): ?>
        <div class="mt-2">

            <?php if ($result['status'] === 'success'): ?>
                <div class="checkin-result valid">
                    <div class="checkin-status-icon"><i class="bi bi-check-circle-fill"></i></div>
                    <div class="checkin-status-label">HỢP LỆ</div>
                    <div class="checkin-divider"></div>
                    <div class="checkin-member-name">
                        <?php echo htmlspecialchars($result['member']['full_name']); ?>
                    </div>
                    <div class="checkin-info">
                        <div class="checkin-info-row">
                            <span class="ci-label">Hết hạn</span>
                            <span class="ci-value">
                                <?php echo date('d/m/Y', strtotime($result['subscription']['end_date'])); ?>
                            </span>
                        </div>
                        <div class="checkin-info-row">
                            <span class="ci-label">Còn lại</span>
                            <span class="ci-value">
                                <span class="days-badge"><?php echo $result['days_left']; ?> NGÀY</span>
                            </span>
                        </div>
                    </div>
                </div>

            <?php elseif ($result['status'] === 'expired'): ?>
                <div class="checkin-result invalid">
                    <div class="checkin-status-icon"><i class="bi bi-x-circle-fill"></i></div>
                    <div class="checkin-status-label">KHÔNG HỢP LỆ</div>
                    <div class="checkin-divider"></div>
                    <div class="checkin-member-name">
                        <?php echo htmlspecialchars($result['member']['full_name']); ?>
                    </div>
                    <p class="checkin-msg mb-0"><?php echo $result['message']; ?></p>
                    <a href="index.php?page=subscriptions&member_id=<?php echo $result['member']['id']; ?>"
                        class="btn-renew">
                        Gia hạn ngay
                    </a>
                </div>

            <?php else: ?>
                <div class="checkin-result warning">
                    <div class="checkin-status-icon"><i class="bi bi-exclamation-circle-fill"></i></div>
                    <div class="checkin-status-label">KHÔNG TÌM THẤY</div>
                    <div class="checkin-divider"></div>
                    <p class="checkin-msg"><?php echo $result['message']; ?></p>
                </div>
            <?php endif; ?>

        </div>
    <?php endif; ?>

</div>