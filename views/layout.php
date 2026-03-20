<?php $page = $page ?? (isset($_GET['page']) ? $_GET['page'] : 'dashboard'); ?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ Thống Quản Lý Gym</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Barlow:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --black:       #0a0a0a;
            --black-soft:  #111111;
            --black-panel: #161616;
            --black-hover: #1e1e1e;
            --white:       #f5f5f0;
            --white-dim:   #d8d8d4;
            --gray-mid:    #888882;
            --gray-line:   rgba(255,255,255,0.07);
            --accent:      #c8b560;
            --accent-dim:  rgba(200,181,96,0.15);
            --danger:      #c0392b;
            --sidebar-w:   260px;
        }

        * {
            border-radius: 0 !important;
            box-sizing: border-box;
        }

        body {
            background-color: #f0f0eb;
      
            color: var(--black);
            margin: 0;
        }

        /* ══════════════════════════════
           SIDEBAR
        ══════════════════════════════ */
        #sidebar {
            width: var(--sidebar-w);
            min-width: var(--sidebar-w);
            background: var(--black);
            color: var(--white);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 100;
            transition: transform 0.3s ease;
        }

        /* Brand header */
        .sidebar-header {
            padding: 28px 24px 22px;
            border-bottom: 1px solid var(--gray-line);
            position: relative;
        }

        .sidebar-brand {
       
            font-size: 22px;
            letter-spacing: 0.14em;
            color: var(--white);
            line-height: 1;
        }

        .sidebar-brand span {
            color: var(--accent);
        }

        .sidebar-subbrand {
            font-size: 9px;
            letter-spacing: 0.3em;
            text-transform: uppercase;
            color: var(--gray-mid);
            margin-top: 4px;
        }

        /* User badge */
        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 18px 24px;
            border-bottom: 1px solid var(--gray-line);
        }

        .sidebar-avatar {
            width: 34px;
            height: 34px;
            background: var(--accent-dim);
            border: 1px solid rgba(200,181,96,0.35);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .sidebar-avatar i {
            color: var(--accent);
            font-size: 15px;
        }

        .sidebar-username {
            font-size: 13px;
            font-weight: 600;
            color: var(--white);
            letter-spacing: 0.03em;
            line-height: 1.2;
        }

        .sidebar-role {
            font-size: 10px;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--gray-mid);
        }

        /* Nav items */
        .sidebar-nav {
            list-style: none;
            padding: 16px 0 0;
            margin: 0;
            flex: 1;
        }

        .sidebar-section-label {
            font-size: 9px;
            letter-spacing: 0.35em;
            text-transform: uppercase;
            color: var(--gray-mid);
            padding: 14px 24px 6px;
        }

        .sidebar-nav li a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 24px;
            font-size: 13px;
            font-weight: 500;
            letter-spacing: 0.04em;
            color: #9a9a94;
            text-decoration: none;
            border-left: 2px solid transparent;
            transition: color 0.2s, background 0.2s, border-color 0.2s;
        }

        .sidebar-nav li a i {
            font-size: 15px;
            width: 18px;
            text-align: center;
            flex-shrink: 0;
        }

        .sidebar-nav li a:hover {
            color: var(--white);
            background: var(--black-hover);
            border-left-color: rgba(200,181,96,0.4);
        }

        .sidebar-nav li.active > a {
            color: var(--white);
            background: var(--black-hover);
            border-left-color: var(--accent);
        }

        .sidebar-nav li.active > a i {
            color: var(--accent);
        }

        /* Logout */
        .sidebar-bottom {
            padding: 16px 0;
            border-top: 1px solid var(--gray-line);
        }

        .sidebar-bottom a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 24px;
            font-size: 13px;
            font-weight: 500;
            letter-spacing: 0.04em;
            color: #7a7a74;
            text-decoration: none;
            transition: color 0.2s, background 0.2s;
        }

        .sidebar-bottom a:hover {
            color: #e74c3c;
            background: rgba(231, 76, 60, 0.06);
        }

        .sidebar-bottom a i {
            font-size: 15px;
            width: 18px;
            text-align: center;
        }

        /* ══════════════════════════════
           MAIN AREA
        ══════════════════════════════ */
        .main-wrapper {
            margin-left: var(--sidebar-w);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* ── Top navbar ── */
        #topnav {
            background: var(--white);
            border-bottom: 1px solid #ddddd8;
            padding: 0 32px;
            height: 58px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 90;
        }

        .topnav-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        /* Mobile toggle */
        #sidebarCollapse {
            display: none;
            background: var(--black);
            color: var(--white);
            border: none;
            padding: 7px 12px;
            font-size: 13px;
            letter-spacing: 0.08em;
            cursor: pointer;
        }

        .topnav-breadcrumb {
            font-size: 11px;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            color: var(--gray-mid);
        }

        .topnav-breadcrumb span {
            color: var(--black);
            font-weight: 600;
        }

        .topnav-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .topnav-time {
            font-size: 11px;
            letter-spacing: 0.15em;
            color: var(--gray-mid);
            text-transform: uppercase;
        }

        /* ── Content area ── */
        #content {
            flex: 1;
            padding: 32px 32px 24px;
        }

        /* ══════════════════════════════
           FOOTER
        ══════════════════════════════ */
        #mainfooter {
            background: var(--black);
            color: var(--gray-mid);
            padding: 0 32px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-top: 3px solid var(--black-hover);
        }

        .footer-brand {
    
            font-size: 13px;
            letter-spacing: 0.2em;
            color: var(--white);
        }

        .footer-brand span {
            color: var(--accent);
        }

        .footer-copy {
            font-size: 10px;
            letter-spacing: 0.18em;
            text-transform: uppercase;
        }

        .footer-version {
            font-size: 10px;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: #555550;
        }

        /* ══════════════════════════════
           RESPONSIVE
        ══════════════════════════════ */
        @media (max-width: 768px) {
            #sidebar {
                transform: translateX(-100%);
            }

            #sidebar.active {
                transform: translateX(0);
            }

            .main-wrapper {
                margin-left: 0;
            }

            #sidebarCollapse {
                display: inline-flex;
                align-items: center;
                gap: 6px;
            }

            #content {
                padding: 20px 16px;
            }

            #topnav {
                padding: 0 16px;
            }

            #mainfooter {
                padding: 0 16px;
                flex-wrap: wrap;
                height: auto;
                gap: 4px;
                padding-top: 10px;
                padding-bottom: 10px;
            }

            .footer-version {
                display: none;
            }
        }

        /* Sidebar overlay on mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 99;
        }

        .sidebar-overlay.active {
            display: block;
        }

        /* ══════════════════════════════
           SHARED PAGE COMPONENTS
        ══════════════════════════════ */

        /* Page heading */
        .page-eyebrow {
            font-size: 10px;
            letter-spacing: 0.35em;
            text-transform: uppercase;
            color: var(--gray-mid);
            margin-bottom: 6px;
        }

        .page-title {
   
            font-size: 38px;
            letter-spacing: 0.06em;
            color: var(--black);
            line-height: 1;
            margin-bottom: 28px;
        }

        .page-title span { color: var(--accent); }

        /* Add button */
        .btn-add {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: var(--black);
            color: var(--white);
            border: none;
            padding: 9px 22px;
        
            font-size: 14px;
            letter-spacing: 0.18em;
            text-decoration: none;
            cursor: pointer;
            white-space: nowrap;
            transition: background 0.2s;
            position: relative;
            overflow: hidden;
        }

        .btn-add::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0;
            height: 2px; width: 0;
            background: var(--accent);
            transition: width 0.3s;
        }

        .btn-add:hover { background: #1a1a1a; color: var(--white); }
        .btn-add:hover::after { width: 100%; }

        /* Flat error/alert */
        .alert-flat {
            border-left: 3px solid var(--danger);
            background: rgba(192,57,43,0.05);
            color: var(--danger);
            padding: 12px 16px;
            font-size: 13px;
            letter-spacing: 0.02em;
            margin-top: 16px;
        }

        .alert-flat-success {
            border-left: 3px solid #27ae60;
            background: rgba(39,174,96,0.05);
            color: #27ae60;
            padding: 12px 16px;
            font-size: 13px;
            letter-spacing: 0.02em;
            margin-bottom: 20px;
        }

        /* Table panel */
        .table-panel {
            background: #fff;
            border-top: 3px solid var(--black);
        }

        .gym-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .gym-table thead th {
            font-size: 9px;
            letter-spacing: 0.25em;
            text-transform: uppercase;
            color: var(--gray-mid);
            font-weight: 600;
            padding: 14px 16px;
            border-bottom: 1.5px solid #e8e8e4;
            white-space: nowrap;
            background: #fff;
        }

        .gym-table thead th:first-child { padding-left: 20px; }
        .gym-table thead th:last-child  { padding-right: 20px; text-align: right; }

        .gym-table tbody td {
            padding: 13px 16px;
            border-bottom: 1px solid #f0f0eb;
            color: #444;
            vertical-align: middle;
        }

        .gym-table tbody td:first-child { padding-left: 20px; }
        .gym-table tbody td:last-child  { padding-right: 20px; }

        .gym-table tbody tr:last-child td { border-bottom: none; }
        .gym-table tbody tr:hover td { background: #fafaf8; }

        .td-id {
      
            font-size: 15px;
            letter-spacing: 0.1em;
            color: #bbb;
            width: 48px;
        }

        .td-name {
            font-weight: 600;
            color: var(--black);
            letter-spacing: 0.02em;
        }

        .empty-row td {
            text-align: center;
            color: var(--gray-mid);
            padding: 40px 0;
            font-size: 13px;
            letter-spacing: 0.1em;
        }

        /* Action buttons */
        .action-group { display: flex; gap: 6px; justify-content: flex-end; flex-wrap: wrap; }

        .act-btn {
            display: inline-block;
            font-size: 10px;
       
            letter-spacing: 0.15em;
            padding: 4px 11px;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: opacity 0.15s;
            white-space: nowrap;
            background: none;
        }

        .act-btn:hover { opacity: 0.75; }

        .act-edit    { background: #f0f0eb !important; color: var(--black) !important; }
        .act-delete  { background: rgba(192,57,43,0.08) !important; color: var(--danger) !important; border: 1px solid rgba(192,57,43,0.2) !important; }
        .act-history { background: var(--black) !important; color: var(--white) !important; }
        .act-sub     { background: rgba(39,174,96,0.1) !important; color: #27ae60 !important; border: 1px solid rgba(39,174,96,0.25) !important; }
        .act-view    { background: #f0f0eb !important; color: var(--black) !important; }

        /* Status badges */
        .status-badge {
            display: inline-block;
          
            font-size: 11px;
            letter-spacing: 0.2em;
            padding: 3px 9px;
        }

        .status-active  { background: rgba(39,174,96,0.1);  color: #27ae60; }
        .status-expired { background: rgba(192,57,43,0.08); color: var(--danger); }
        .status-pending { background: rgba(200,181,96,0.15); color: #a07800; }

        /* Form panel (add/edit pages) */
        .form-panel {
            background: #fff;
            border-top: 3px solid var(--black);
            padding: 28px 32px;
            max-width: 680px;
        }

        .form-section-label {
            font-size: 9px;
            letter-spacing: 0.3em;
            text-transform: uppercase;
            color: var(--gray-mid);
            font-weight: 600;
            margin-bottom: 18px;
            padding-bottom: 8px;
            border-bottom: 1px solid #e8e8e4;
        }

        .gym-label {
            font-size: 10px;
            letter-spacing: 0.25em;
            text-transform: uppercase;
            color: var(--gray-mid);
            font-weight: 600;
            margin-bottom: 6px;
            display: block;
        }

        .gym-input,
        .gym-select,
        .gym-textarea {
            width: 100%;
            border: 1.5px solid #ddddd8;
            padding: 10px 14px;
            font-size: 14px;
           
            color: var(--black);
            background: #fafaf8;
            outline: none;
            transition: border-color 0.2s, background 0.2s;
            appearance: none;
        }

        .gym-input:focus,
        .gym-select:focus,
        .gym-textarea:focus {
            border-color: var(--black);
            background: #fff;
        }

        .gym-input::placeholder,
        .gym-textarea::placeholder { color: #bbb; font-weight: 300; }

        .gym-textarea { resize: vertical; min-height: 90px; }

        .btn-submit {
            background: var(--black);
            color: var(--white);
            border: none;
            padding: 12px 36px;
         
            font-size: 15px;
            letter-spacing: 0.22em;
            cursor: pointer;
            transition: background 0.2s;
            position: relative;
            overflow: hidden;
        }

        .btn-submit::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0;
            height: 2px; width: 0;
            background: var(--accent);
            transition: width 0.3s;
        }

        .btn-submit:hover { background: #1a1a1a; }
        .btn-submit:hover::after { width: 100%; }

        .btn-cancel {
            background: transparent;
            color: var(--gray-mid);
            border: 1.5px solid #ddddd8;
            padding: 11px 24px;
          
            font-size: 15px;
            letter-spacing: 0.22em;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: border-color 0.2s, color 0.2s;
        }

        .btn-cancel:hover { border-color: var(--black); color: var(--black); }

        /* Search bar (members, subscriptions etc.) */
        .topbar-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .search-form { display: flex; gap: 0; }

        .search-input {
            border: 1.5px solid #ddddd8;
            border-right: none;
            padding: 9px 14px;
            font-size: 13px;
            color: var(--black);
            background: #fff;
            outline: none;
            width: 240px;
            transition: border-color 0.2s;
        }

        .search-input:focus { border-color: var(--black); }
        .search-input::placeholder { color: #bbb; font-weight: 300; }

        .search-btn {
            background: #fff;
            color: var(--black);
            border: 1.5px solid #ddddd8;
            padding: 9px 16px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            letter-spacing: 0.05em;
            transition: background 0.2s;
        }

        .search-btn:hover { background: #f0f0eb; }

        /* ── Members table extras ── */
        .note-tag {
            display: inline-block;
            font-size: 8px;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            background: #fff8e1;
            color: #a07800;
            border: 1px solid #e8d06a;
            padding: 1px 6px;
            margin-left: 6px;
            vertical-align: middle;
        }

        .time-left {
            font-size: 12px;
            font-weight: 600;
            color: #27ae60;
            letter-spacing: 0.04em;
        }

        .time-dash { color: #ccc; }

        .view-only {
            font-size: 10px;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: #bbb;
        }

        /* ── Packages table extras ── */
        .td-pkg-name {
            font-weight: 600;
            color: var(--black);
            letter-spacing: 0.02em;
        }

        .td-price {
            font-size: 16px;
            letter-spacing: 0.06em;
            color: #27ae60;
        }

        .td-price .price-unit {
            font-size: 11px;
            color: var(--gray-mid);
            font-weight: 500;
            margin-left: 2px;
        }

        .td-duration {
            font-size: 16px;
            letter-spacing: 0.06em;
            color: var(--black);
        }

        .td-duration .dur-unit {
            font-size: 11px;
            color: var(--gray-mid);
            font-weight: 500;
            margin-left: 2px;
        }

        /* ── Dashboard ── */
        .stat-card {
            background: #fff;
            border-top: 3px solid var(--black);
            padding: 22px 24px;
            margin-bottom: 20px;
        }

        .stat-card.accent  { border-top-color: var(--accent); }
        .stat-card.positive { border-top-color: #27ae60; }

        .stat-label {
            font-size: 9px;
            letter-spacing: 0.3em;
            text-transform: uppercase;
            color: var(--gray-mid);
            font-weight: 600;
            margin-bottom: 10px;
        }

        .stat-tag {
            display: inline-block;
            font-size: 9px;
            letter-spacing: 0.25em;
            text-transform: uppercase;
            color: var(--gray-mid);
            border: 1px solid #e0e0db;
            padding: 2px 7px;
            margin-bottom: 8px;
        }

        .stat-value {
            font-size: 40px;
            letter-spacing: 0.04em;
            color: var(--black);
            line-height: 1;
        }

        .stat-card.accent .stat-value   { color: #b8991a; }
        .stat-card.positive .stat-value { color: #27ae60; }

        .stat-unit {
            font-size: 13px;
            font-weight: 500;
            color: var(--gray-mid);
            margin-left: 4px;
        }

        .chart-panel {
            background: #fff;
            border-top: 3px solid var(--black);
            padding: 24px;
            margin-bottom: 20px;
        }

        .chart-panel-label {
            font-size: 9px;
            letter-spacing: 0.3em;
            text-transform: uppercase;
            color: var(--gray-mid);
            font-weight: 600;
            margin-bottom: 20px;
        }

        .data-panel {
            background: #fff;
            border-top: 3px solid var(--black);
            padding: 24px;
            margin-bottom: 20px;
        }

        .data-panel-label {
            font-size: 9px;
            letter-spacing: 0.3em;
            text-transform: uppercase;
            color: var(--gray-mid);
            font-weight: 600;
            margin-bottom: 20px;
        }

        /* ── Filter bar (dashboard) ── */
        .filter-bar {
            background: #fff;
            border-top: 3px solid var(--black);
            padding: 20px 24px;
            display: flex;
            align-items: flex-end;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 28px;
        }

        .filter-group { display: flex; flex-direction: column; gap: 5px; }

        .filter-label {
            font-size: 9px;
            letter-spacing: 0.3em;
            text-transform: uppercase;
            color: var(--gray-mid);
            font-weight: 600;
        }

        .filter-input {
            border: 1.5px solid #ddddd8;
            padding: 8px 12px;
            font-size: 13px;
            color: var(--black);
            background: #fafaf8;
            outline: none;
            transition: border-color 0.2s;
        }

        .filter-input:focus { border-color: var(--black); background: #fff; }

        .filter-btn {
            background: var(--black);
            color: var(--white);
            border: none;
            padding: 9px 24px;
            font-size: 14px;
            letter-spacing: 0.2em;
            cursor: pointer;
            transition: background 0.2s;
            align-self: flex-end;
        }

        .filter-btn:hover { background: #222; }

        /* ── Check-in page ── */
        .checkin-wrap { max-width: 640px; margin: 0 auto; }

        .checkin-form-wrap {
            background: #fff;
            border-top: 3px solid var(--black);
            padding: 28px;
            margin-bottom: 28px;
        }

        .checkin-form-label {
            font-size: 10px;
            letter-spacing: 0.3em;
            text-transform: uppercase;
            color: var(--gray-mid);
            font-weight: 600;
            margin-bottom: 10px;
            display: block;
        }

        .checkin-input-row { display: flex; }

        .checkin-input {
            flex: 1;
            border: 1.5px solid #ddddd8;
            border-right: none;
            padding: 13px 16px;
            font-size: 15px;
            color: var(--black);
            background: #fafaf8;
            outline: none;
            transition: border-color 0.2s;
        }

        .checkin-input:focus { border-color: var(--black); background: #fff; }
        .checkin-input::placeholder { color: #bbb; font-weight: 300; }

        .checkin-btn {
            background: var(--black);
            color: var(--white);
            border: none;
            padding: 13px 28px;
            font-size: 15px;
            letter-spacing: 0.2em;
            cursor: pointer;
            transition: background 0.2s;
            white-space: nowrap;
        }

        .checkin-btn:hover { background: #222; }

        .checkin-result {
            border-top: 4px solid transparent;
            padding: 32px;
            text-align: center;
            background: #fff;
        }

        .checkin-result.valid   { border-top-color: #27ae60; }
        .checkin-result.invalid { border-top-color: var(--danger); }
        .checkin-result.warning { border-top-color: #e67e22; }

        .checkin-status-icon { font-size: 52px; line-height: 1; margin-bottom: 10px; }
        .checkin-result.valid   .checkin-status-icon { color: #27ae60; }
        .checkin-result.invalid .checkin-status-icon { color: var(--danger); }
        .checkin-result.warning .checkin-status-icon { color: #e67e22; }

        .checkin-status-label {
            font-size: 32px;
            letter-spacing: 0.2em;
            line-height: 1;
            margin-bottom: 20px;
        }

        .checkin-result.valid   .checkin-status-label { color: #27ae60; }
        .checkin-result.invalid .checkin-status-label { color: var(--danger); }
        .checkin-result.warning .checkin-status-label { color: #e67e22; }

        .checkin-divider {
            width: 40px;
            height: 2px;
            background: #e0e0db;
            margin: 0 auto 24px;
        }

        .checkin-member-name {
            font-size: 26px;
            letter-spacing: 0.1em;
            color: var(--black);
            margin-bottom: 16px;
        }

        .checkin-info {
            display: inline-flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 24px;
            text-align: left;
        }

        .checkin-info-row { display: flex; align-items: center; gap: 10px; font-size: 13px; }

        .checkin-info-row .ci-label {
            font-size: 10px;
            letter-spacing: 0.25em;
            text-transform: uppercase;
            color: var(--gray-mid);
            width: 80px;
            flex-shrink: 0;
        }

        .checkin-info-row .ci-value { font-weight: 600; color: var(--black); letter-spacing: 0.02em; }

        .days-badge {
            display: inline-block;
            background: var(--black);
            color: var(--accent);
            font-size: 13px;
            letter-spacing: 0.2em;
            padding: 3px 10px;
        }

        .checkin-msg { font-size: 14px; color: #666; letter-spacing: 0.02em; }

        .btn-renew {
            display: inline-block;
            background: var(--black);
            color: var(--white);
            border: none;
            padding: 12px 32px;
            font-size: 14px;
            letter-spacing: 0.22em;
            text-decoration: none;
            cursor: pointer;
            transition: background 0.2s;
            margin-top: 8px;
            position: relative;
            overflow: hidden;
        }

        .btn-renew::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0;
            height: 3px; width: 0;
            background: var(--accent);
            transition: width 0.3s ease;
        }

        .btn-renew:hover { background: #1a1a1a; color: var(--white); }
        .btn-renew:hover::after { width: 100%; }

        /* ── Dash/packages topbar layouts ── */
        .dash-eyebrow, .checkin-eyebrow {
            font-size: 10px;
            letter-spacing: 0.35em;
            text-transform: uppercase;
            color: var(--gray-mid);
            margin-bottom: 6px;
        }

        .dash-title, .checkin-title {
            font-size: 38px;
            letter-spacing: 0.06em;
            color: var(--black);
            line-height: 1;
            margin-bottom: 32px;
        }

        .dash-title span, .checkin-title span { color: var(--accent); }

        .packages-topbar {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        /* ── Form field spacing ── */
        .mb-field { margin-bottom: 22px; }

        .form-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 28px;
            padding-top: 20px;
            border-top: 1px solid #e8e8e4;
        }

        /* Readonly input */
        .input-readonly {
            background: #f0f0eb !important;
            color: var(--gray-mid) !important;
            cursor: not-allowed;
        }

        /* ── Member search dropdown ── */
        .search-dropdown {
            display: none;
            position: absolute;
            left: 0;
            right: 0;
            top: calc(100% + 2px);
            background: #fff;
            border: 1.5px solid #ddddd8;
            border-top: 2px solid var(--black);
            z-index: 1000;
            max-height: 280px;
            overflow-y: auto;
        }

        .search-dropdown-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 11px 16px;
            cursor: pointer;
            border-bottom: 1px solid #f0f0eb;
            transition: background 0.15s;
        }

        .search-dropdown-item:last-child { border-bottom: none; }
        .search-dropdown-item:hover { background: #fafaf8; }

        .sd-name {
            font-weight: 600;
            font-size: 13px;
            color: var(--black);
        }

        .sd-phone {
            font-size: 11px;
            letter-spacing: 0.1em;
            color: var(--gray-mid);
        }

        /* Selected member box */
        .member-selected-box {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(10,10,10,0.04);
            border-left: 3px solid var(--black);
            padding: 10px 14px;
            margin-top: 8px;
        }

        .member-selected-info {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            font-weight: 600;
            color: var(--black);
        }

        .member-selected-info i { color: var(--accent); font-size: 16px; }

        .member-clear-btn {
            background: none;
            border: none;
            color: var(--gray-mid);
            font-size: 18px;
            cursor: pointer;
            padding: 0 4px;
            line-height: 1;
            transition: color 0.2s;
        }

        .member-clear-btn:hover { color: var(--danger); }

        /* ── Role badges (admins page) ── */
        .role-badge {
            display: inline-block;
            font-size: 11px;
            letter-spacing: 0.18em;
            padding: 3px 9px;
        }

        .role-admin { background: rgba(192,57,43,0.08); color: var(--danger); }
        .role-staff { background: rgba(10,10,10,0.06);  color: var(--black); }
    </style>
</head>

<body>

    <!-- Mobile overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="d-flex">

        <!-- ── SIDEBAR ── -->
        <nav id="sidebar">

            <div class="sidebar-header">
                <div class="sidebar-brand">IRON<span>CORE</span></div>
                <div class="sidebar-subbrand">Gym Management System</div>
            </div>

            <div class="sidebar-user">
                <div class="sidebar-avatar">
                    <i class="bi bi-person-fill"></i>
                </div>
                <div>
                    <div class="sidebar-username">
                        <?php echo isset($_SESSION['full_name']) ? htmlspecialchars($_SESSION['full_name']) : 'Admin'; ?>
                    </div>
                    <div class="sidebar-role">
                        <?php echo (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') ? 'Quản trị viên' : 'Nhân viên'; ?>
                    </div>
                </div>
            </div>

            <ul class="sidebar-nav list-unstyled">

                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <div class="sidebar-section-label">Tổng quan</div>
                <li class="<?php echo ($page == 'dashboard') ? 'active' : ''; ?>">
                    <a href="index.php?page=dashboard">
                        <i class="bi bi-grid-1x2"></i> Dashboard
                    </a>
                </li>
                <?php endif; ?>

                <div class="sidebar-section-label">Quản lý</div>

                <li class="<?php echo ($page == 'checkin') ? 'active' : ''; ?>">
                    <a href="index.php?page=checkin">
                        <i class="bi bi-door-open"></i> Check-in
                    </a>
                </li>

                <li class="<?php echo ($page == 'members' || $page == 'add_member') ? 'active' : ''; ?>">
                    <a href="index.php?page=members">
                        <i class="bi bi-people"></i> Hội viên
                    </a>
                </li>

                <li class="<?php echo ($page == 'packages' || $page == 'add_package') ? 'active' : ''; ?>">
                    <a href="index.php?page=packages">
                        <i class="bi bi-box-seam"></i> Gói tập
                    </a>
                </li>

                <li class="<?php echo ($page == 'subscriptions') ? 'active' : ''; ?>">
                    <a href="index.php?page=subscriptions">
                        <i class="bi bi-calendar-check"></i> Đăng ký
                    </a>
                </li>

                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <div class="sidebar-section-label">Hệ thống</div>
                <li class="<?php echo ($page == 'admins' || $page == 'add_admin' || $page == 'edit_admin') ? 'active' : ''; ?>">
                    <a href="index.php?page=admins">
                       <i class="bi bi-person-badge"></i> Nhân viên
                    </a>
                </li>
                <?php endif; ?>

                <div class="sidebar-section-label">Tài khoản</div>

                <li class="<?php echo ($page == 'settings') ? 'active' : ''; ?>">
                    <a href="index.php?page=settings">
                        <i class="bi bi-gear"></i> Cập nhật thông tin
                    </a>
                </li>

            </ul>

            <div class="sidebar-bottom">
                <a href="index.php?page=logout">
                    <i class="bi bi-box-arrow-left"></i> Đăng xuất
                </a>
            </div>

        </nav>
        <!-- end #sidebar -->

        <!-- ── MAIN WRAPPER ── -->
        <div class="main-wrapper w-100">

            <!-- Top navbar -->
            <nav id="topnav">
                <div class="topnav-left">
                    <button id="sidebarCollapse">
                        <i class="bi bi-list"></i> Menu
                    </button>
                    <div class="topnav-breadcrumb">
                        IRONCORE &nbsp;/&nbsp; <span>
                            <?php
                            $pageNames = [
                                'dashboard'    => 'Dashboard',
                                'checkin'      => 'Check-in',
                                'members'      => 'Hội viên',
                                'add_member'   => 'Thêm hội viên',
                                'packages'     => 'Gói tập',
                                'add_package'  => 'Thêm gói tập',
                                'subscriptions'=> 'Đăng ký',
                                'admins'       => 'Nhân viên',
                                'add_admin'    => 'Thêm nhân viên',
                                'edit_admin'   => 'Sửa nhân viên',
                                'settings'     => 'Cập nhật thông tin',
                            ];
                            echo isset($pageNames[$page]) ? $pageNames[$page] : 'Hệ thống';
                            ?>
                        </span>
                    </div>
                </div>
                <div class="topnav-right">
                    <div class="topnav-time" id="topnavClock"></div>
                </div>
            </nav>

            <!-- Page content -->
            <div id="content">
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert-flat">
                        <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert-flat-success">
                        <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>
                <?php
                if (isset($content)) {
                    include $content;
                } else {
                    echo "<h2>Chào mừng</h2>";
                }
                ?>
            </div>

            <!-- Footer -->
            <footer id="mainfooter">
                <div class="footer-brand">IRON<span>CORE</span></div>
                <div class="footer-copy">&copy; <?php echo date('Y'); ?> &nbsp;·&nbsp; Gym Management System</div>
                <div class="footer-version">v1.0.0</div>
            </footer>

        </div>
        <!-- end .main-wrapper -->

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sidebar toggle (mobile)
        const toggleBtn  = document.getElementById('sidebarCollapse');
        const sidebar    = document.getElementById('sidebar');
        const overlay    = document.getElementById('sidebarOverlay');

        function openSidebar() {
            sidebar.classList.add('active');
            overlay.classList.add('active');
        }

        function closeSidebar() {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
        }

        if (toggleBtn) {
            toggleBtn.addEventListener('click', function () {
                sidebar.classList.contains('active') ? closeSidebar() : openSidebar();
            });
        }

        overlay.addEventListener('click', closeSidebar);

        function updateClock() {
            const el = document.getElementById('topnavClock');
            if (!el) return;
            const now = new Date();
            const h = String(now.getHours()).padStart(2,'0');
            const m = String(now.getMinutes()).padStart(2,'0');
            const d = now.toLocaleDateString('vi-VN', { weekday: 'short', day: '2-digit', month: '2-digit', year: 'numeric' });
            el.textContent = `${d}  ${h}:${m}`;
        }
        updateClock();
        setInterval(updateClock, 60000);
    </script>

</body>

</html>