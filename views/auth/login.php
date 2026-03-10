<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — Gym Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Barlow:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --black: #0a0a0a;
            --white: #f5f5f0;
            --gray-light: #e8e8e4;
            --gray-mid: #999990;
            --accent: #c8b560;
        }

        * {
            border-radius: 0 !important;
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: var(--black);
            min-height: 100vh;
            display: flex;
            overflow: hidden;
        }

        /* ── Left panel ── */
        .panel-left {
            width: 55%;
            position: relative;
            background: var(--black);
            overflow: hidden;
        }

        .panel-left::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                repeating-linear-gradient(
                    0deg,
                    transparent,
                    transparent 79px,
                    rgba(255,255,255,0.03) 79px,
                    rgba(255,255,255,0.03) 80px
                ),
                repeating-linear-gradient(
                    90deg,
                    transparent,
                    transparent 79px,
                    rgba(255,255,255,0.03) 79px,
                    rgba(255,255,255,0.03) 80px
                );
            z-index: 0;
        }

        .big-text {
            position: absolute;
            bottom: -0.12em;
            left: -0.03em;
            font-size: clamp(120px, 16vw, 220px);
            color: transparent;
            -webkit-text-stroke: 1px rgba(255,255,255,0.08);
            line-height: 1;
            user-select: none;
            z-index: 1;
            letter-spacing: 0.02em;
        }

        .panel-left .brand {
            position: absolute;
            top: 48px;
            left: 48px;
            z-index: 2;
        }

        .brand-name {
            font-size: 28px;
            color: var(--white);
            letter-spacing: 0.12em;
            line-height: 1;
        }

        .brand-sub {
            font-size: 10px;
            letter-spacing: 0.35em;
            color: var(--gray-mid);
            text-transform: uppercase;
            margin-top: 4px;
        }

        .panel-left .tagline {
            position: absolute;
            bottom: 52px;
            left: 48px;
            z-index: 2;
        }

        .tagline-main {
            font-size: clamp(36px, 4vw, 58px);
            color: var(--white);
            line-height: 0.95;
            letter-spacing: 0.04em;
        }

        .tagline-accent {
            color: var(--accent);
        }

        .tagline-sub {
            font-size: 11px;
            letter-spacing: 0.3em;
            color: var(--gray-mid);
            text-transform: uppercase;
            margin-top: 14px;
        }

        /* ── Right panel ── */
        .panel-right {
            width: 45%;
            background: var(--white);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px 56px;
            position: relative;
        }

        .panel-right::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: linear-gradient(to bottom, var(--black) 0%, var(--accent) 50%, var(--black) 100%);
        }

        .login-inner {
            width: 100%;
            max-width: 340px;
        }

        .login-eyebrow {
            font-size: 10px;
            letter-spacing: 0.35em;
            text-transform: uppercase;
            color: var(--gray-mid);
            margin-bottom: 10px;
        }

        .login-title {
            font-size: 42px;
            color: var(--black);
            letter-spacing: 0.06em;
            line-height: 1;
            margin-bottom: 36px;
        }

        .login-title span {
            color: var(--accent);
        }

        /* Alert */
        .alert-danger {
            background: transparent;
            border: 1px solid #c0392b;
            color: #c0392b;
            font-size: 13px;
            padding: 10px 14px;
            margin-bottom: 24px;
            letter-spacing: 0.02em;
        }

        /* Form */
        .form-label {
            font-size: 10px;
            letter-spacing: 0.3em;
            text-transform: uppercase;
            color: var(--gray-mid);
            font-weight: 600;
            margin-bottom: 6px;
        }

        .form-control {
            background: transparent;
            border: none !important;
            border-bottom: 1.5px solid #ccc !important;
            padding: 10px 0;
            font-size: 15px;
            color: var(--black);
            font-weight: 400;
            transition: border-color 0.2s;
            box-shadow: none !important;
        }

        .form-control:focus {
            border-bottom-color: var(--black) !important;
            background: transparent;
            box-shadow: none !important;
        }

        .form-control::placeholder {
            color: #bbb;
            font-weight: 300;
        }

        .mb-form {
            margin-bottom: 28px;
        }

        /* Submit button */
        .btn-login {
            width: 100%;
            background: var(--black);
            color: var(--white);
            border: none;
            padding: 14px 0;
            font-size: 16px;
            letter-spacing: 0.25em;
            text-transform: uppercase;
            cursor: pointer;
            margin-top: 12px;
            position: relative;
            overflow: hidden;
            transition: background 0.25s;
        }

        .btn-login::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            width: 0;
            background: var(--accent);
            transition: width 0.3s ease;
        }

        .btn-login:hover {
            background: #111;
        }

        .btn-login:hover::after {
            width: 100%;
        }

        /* Footer note */
        .login-footer {
            margin-top: 32px;
            font-size: 10px;
            letter-spacing: 0.18em;
            color: var(--gray-mid);
            text-transform: uppercase;
        }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            body { flex-direction: column; }
            .panel-left { width: 100%; height: 220px; flex-shrink: 0; }
            .panel-right { width: 100%; flex: 1; padding: 40px 32px; }
            .panel-right::before { left: 0; top: 0; right: 0; bottom: auto; width: 100%; height: 4px; }
            .big-text { font-size: 100px; }
        }
    </style>
</head>

<body>

    <!-- Left decorative panel -->
    <div class="panel-left">
        <div class="brand">
            <div class="brand-name">IRONCORE</div>
            <div class="brand-sub">Gym Management System</div>
        </div>

        <div class="tagline">
            <div class="tagline-main">TRAIN<br><span class="tagline-accent">HARDER.</span><br>MANAGE<br>SMARTER.</div>
            <div class="tagline-sub">Admin Control Center</div>
        </div>

        <div class="big-text">GYM</div>
    </div>

    <!-- Right login panel -->
    <div class="panel-right">
        <div class="login-inner">

            <div class="login-eyebrow">Restricted Access</div>
            <div class="login-title">ĐĂNG<br>NHẬP <span>HỆ THỐNG</span></div>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form action="index.php?page=login" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo Csrf::generate(); ?>">

                <div class="mb-form">
                    <label for="username" class="form-label">Tài khoản</label>
                    <input
                        type="text"
                        class="form-control"
                        id="username"
                        name="username"
                        placeholder="Nhập tên đăng nhập"
                        required
                        oninput="this.value = this.value.toLowerCase().replace(/[^a-z0-9]/g, '')"
                    >
                </div>

                <div class="mb-form">
                    <label for="password" class="form-label">Mật khẩu</label>
                    <input
                        type="password"
                        class="form-control"
                        id="password"
                        name="password"
                        placeholder="••••••••"
                        required
                    >
                </div>

                <button type="submit" class="btn-login">
                    Đăng Nhập
                </button>
            </form>

            <div class="login-footer">© 2025 Ironcore Gym &nbsp;·&nbsp; System Portal</div>
        </div>
    </div>

</body>
</html>