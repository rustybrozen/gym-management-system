<div class="page-eyebrow">Quản lý</div>
<div class="page-title">ĐĂNG <span>KÝ</span></div>

<div class="form-panel">

    <form action="index.php?page=subscriptions" method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo Csrf::generate(); ?>">

        <!-- Member search -->
        <div class="mb-field position-relative">
            <label class="gym-label">Chọn hội viên</label>

            <input type="text" id="member_search" class="gym-input"
                placeholder="Nhập tên hoặc số điện thoại..."
                autocomplete="off"
                style="<?php echo $selected_member ? 'display:none;' : ''; ?>">

            <input type="hidden" id="member_id" name="member_id" required
                value="<?php echo $selected_member ? $selected_member['id'] : ''; ?>">

            <div id="selected_member_display"
                class="member-selected-box <?php echo $selected_member ? '' : 'd-none'; ?>">
                <div class="member-selected-info">
                    <i class="bi bi-person-check"></i>
                    <span id="selected_member_name">
                        <?php echo $selected_member
                            ? htmlspecialchars($selected_member['full_name']) . ' — ' . $selected_member['phone_number']
                            : ''; ?>
                    </span>
                </div>
                <button type="button" id="clear_selection" class="member-clear-btn">
                    <i class="bi bi-x"></i>
                </button>
            </div>

            <div id="search_results" class="search-dropdown"></div>
        </div>

        <!-- Package select -->
        <div class="mb-field">
            <label class="gym-label" for="package_id">Chọn gói tập</label>
            <select class="gym-select" id="package_id" name="package_id" required>
                <option value="">— Chọn gói tập —</option>
                <?php foreach ($packages as $pkg): ?>
                    <option value="<?php echo $pkg['id']; ?>">
                        <?php echo htmlspecialchars($pkg['package_name']); ?>
                        &nbsp;·&nbsp; <?php echo $pkg['duration_days']; ?> ngày
                        &nbsp;·&nbsp; <?php echo number_format($pkg['price']); ?> đ
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-submit">Đăng ký gói</button>
            <a href="index.php?page=members" class="btn-cancel">Quay lại</a>
        </div>

    </form>
</div>

<script>
    const searchInput  = document.getElementById('member_search');
    const resultsDiv   = document.getElementById('search_results');
    const hiddenInput  = document.getElementById('member_id');
    const displayDiv   = document.getElementById('selected_member_display');
    const displayName  = document.getElementById('selected_member_name');
    const clearBtn     = document.getElementById('clear_selection');
    let timeoutId;

    searchInput.addEventListener('input', function () {
        clearTimeout(timeoutId);
        const query = this.value.trim();
        if (query.length === 0) { resultsDiv.style.display = 'none'; return; }

        timeoutId = setTimeout(() => {
            fetch(`index.php?page=api_members_search&q=${encodeURIComponent(query)}`)
                .then(r => r.json())
                .then(data => {
                    resultsDiv.innerHTML = '';
                    if (data.length > 0) {
                        resultsDiv.style.display = 'block';
                        data.forEach(member => {
                            const item = document.createElement('div');
                            item.className = 'search-dropdown-item';
                            item.innerHTML = `<span class="sd-name">${member.full_name}</span><span class="sd-phone">${member.phone_number}</span>`;
                            item.addEventListener('click', () => selectMember(member));
                            resultsDiv.appendChild(item);
                        });
                    } else {
                        resultsDiv.style.display = 'none';
                    }
                })
                .catch(err => console.error(err));
        }, 300);
    });

    function selectMember(member) {
        hiddenInput.value     = member.id;
        displayName.textContent = `${member.full_name} — ${member.phone_number}`;
        displayDiv.classList.remove('d-none');
        searchInput.style.display = 'none';
        resultsDiv.style.display  = 'none';
        searchInput.value = '';
    }

    clearBtn.addEventListener('click', function () {
        hiddenInput.value = '';
        displayDiv.classList.add('d-none');
        searchInput.style.display = 'block';
        searchInput.focus();
    });

    document.addEventListener('click', function (e) {
        if (!searchInput.contains(e.target) && !resultsDiv.contains(e.target)) {
            resultsDiv.style.display = 'none';
        }
    });
</script>