<h2 class="mb-4">Đăng ký thành viên</h2>

<form action="index.php?page=subscriptions" method="POST">
    <input type="hidden" name="csrf_token" value="<?php echo Csrf::generate(); ?>">
    <div class="mb-3 position-relative">
        <label for="member_search" class="form-label">Chọn hội viên</label>
        <input type="text" class="form-control" id="member_search"
            placeholder="Nhập tên hoặc số điện thoại để tìm kiếm..." autocomplete="off">
        <input type="hidden" id="member_id" name="member_id" required
            value="<?php echo $selected_member ? $selected_member['id'] : ''; ?>">

        <div id="selected_member_display"
            class="alert alert-info mt-2 <?php echo $selected_member ? '' : 'd-none'; ?> d-flex justify-content-between align-items-center">
            <span>
                <strong>Đã chọn:</strong> <span
                    id="selected_member_name"><?php echo $selected_member ? htmlspecialchars($selected_member['full_name']) . ' (' . $selected_member['phone_number'] . ')' : ''; ?></span>
            </span>
            <button type="button" class="btn btn-sm btn-close" id="clear_selection"></button>
        </div>

        <div id="search_results" class="list-group position-absolute w-100 shadow"
            style="display:none; z-index: 1000; max-height: 300px; overflow-y: auto;"></div>
    </div>

    <script>
        const searchInput = document.getElementById('member_search');
        const resultsDiv = document.getElementById('search_results');
        const hiddenInput = document.getElementById('member_id');
        const displayDiv = document.getElementById('selected_member_display');
        const displayNameSpan = document.getElementById('selected_member_name');
        const clearBtn = document.getElementById('clear_selection');

        let timeoutId;

        // Automatically hide input if member is already selected
        if (hiddenInput.value) {
            searchInput.style.display = 'none';
        }

        searchInput.addEventListener('input', function () {
            clearTimeout(timeoutId);
            const query = this.value.trim();

            if (query.length === 0) {
                resultsDiv.style.display = 'none';
                return;
            }

            timeoutId = setTimeout(() => {
                fetch(`index.php?page=api_members_search&q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        resultsDiv.innerHTML = '';
                        if (data.length > 0) {
                            resultsDiv.style.display = 'block';
                            data.forEach(member => {
                                const item = document.createElement('a');
                                item.href = '#';
                                item.className = 'list-group-item list-group-item-action';
                                item.textContent = `${member.full_name} (${member.phone_number})`;
                                item.onclick = (e) => {
                                    e.preventDefault();
                                    selectMember(member);
                                };
                                resultsDiv.appendChild(item);
                            });
                        } else {
                            resultsDiv.style.display = 'none';
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }, 300);
        });

        function selectMember(member) {
            hiddenInput.value = member.id;
            displayNameSpan.textContent = `${member.full_name} (${member.phone_number})`;
            displayDiv.classList.remove('d-none');
            searchInput.style.display = 'none';
            resultsDiv.style.display = 'none';
            searchInput.value = '';
        }

        clearBtn.addEventListener('click', function () {
            hiddenInput.value = '';
            displayDiv.classList.add('d-none');
            searchInput.style.display = 'block';
            searchInput.focus();
        });

        // Close results when clicking outside
        document.addEventListener('click', function (e) {
            if (e.target !== searchInput && e.target !== resultsDiv) {
                resultsDiv.style.display = 'none';
            }
        });
    </script>

    <div class="mb-3">
        <label for="package_id" class="form-label">Chọn gói tập</label>
        <select class="form-select" id="package_id" name="package_id" required>
            <option value="">-- Chọn gói tập --</option>
            <?php foreach ($packages as $pkg): ?>
                <option value="<?php echo $pkg['id']; ?>">
                    <?php echo htmlspecialchars($pkg['package_name']); ?> - <?php echo $pkg['duration_days']; ?> ngày -
                    <?php echo number_format($pkg['price']); ?> VND
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Đăng ký Gói</button>
</form>