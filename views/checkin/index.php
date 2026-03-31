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
                    minlength="5"
                    autofocus
                >
                <button type="submit" class="checkin-btn">Kiểm tra</button>
            </div>
        </form>
    </div>

    <!-- Result -->
    <?php if (isset($result)): ?>
        <div class="mt-2">

            <?php if ($result['status'] === 'multiple'): ?>
                <div class="checkin-result warning" style="border-left-color: #a07800;">
                    <div class="checkin-status-icon" style="color: #a07800;"><i class="bi bi-people-fill"></i></div>
                    <div class="checkin-status-label" style="color: #a07800;">NHIỀU KẾT QUẢ</div>
                    <div class="checkin-divider"></div>
                    <p class="checkin-msg mb-3">Vui lòng chọn hội viên chính xác:</p>
                    <div class="checkin-multiple-list">
                        <?php foreach ($result['members'] as $m): ?>
                            <form method="POST" action="index.php?page=checkin" style="margin-bottom: 8px;">
                                <input type="hidden" name="csrf_token" value="<?php echo Csrf::generate(); ?>">
                                <input type="hidden" name="member_id" value="<?php echo $m['id']; ?>">
                                <input type="hidden" name="keyword" value="<?php echo htmlspecialchars($result['keyword']); ?>">
                                <button type="submit" class="gym-input d-flex justify-content-between align-items-center" style="cursor: pointer; text-align: left; padding: 12px 16px;">
                                    <div>
                                        <strong style="font-size: 14px; color: var(--black);"><?php echo htmlspecialchars($m['full_name']); ?></strong><br>
                                        <small style="color: var(--gray-mid); font-size: 12px; font-weight: 500;"><i class="bi bi-telephone"></i> <?php echo htmlspecialchars($m['phone_number']); ?></small>
                                    </div>
                                    <span class="act-btn act-view">Chọn</span>
                                </button>
                            </form>
                        <?php endforeach; ?>
                    </div>
                </div>

            <?php elseif ($result['status'] === 'success'): ?>
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