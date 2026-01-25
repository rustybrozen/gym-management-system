<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ Thống Quản Lý Gym</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * {
            border-radius: 0 !important;
        }

        body {
            background-color: #f8f9fa;
        }

        .wrapper {
            display: flex;
            width: 100%;
            align-items: stretch;
            min-height: 100vh;
        }

        #sidebar {
            min-width: 250px;
            max-width: 250px;
            background: #212529;
            color: #fff;
            transition: all 0.3s;
        }

        #sidebar .sidebar-header {
            padding: 20px;
            background: #212529;
            border-bottom: 1px solid #4b545c;
        }

        #sidebar ul.components {
            padding: 20px 0;
        }

        #sidebar ul li {
            padding: 10px;
            border-bottom: 1px solid #2c333a;
        }

        #sidebar ul li a {
            padding: 10px;
            font-size: 1.1em;
            display: block;
            color: #adb5bd;
            text-decoration: none;
        }

        #sidebar ul li a:hover {
            color: #fff;
            background: #343a40;
        }

        #sidebar ul li.active>a {
            color: #fff;
            background: #0d6efd;
        }

        #content {
            width: 100%;
            padding: 20px;
            min-height: 100vh;
        }

        @media (max-width: 768px) {
            #sidebar {
                margin-left: -250px;
            }

            #sidebar.active {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>

    <div class="wrapper">
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3>GYM SYSTEM</h3>
                <div class="fs-6 text-muted mt-2">
                    <strong class="text-white""><?php echo isset($_SESSION['full_name']) ? htmlspecialchars($_SESSION['full_name']) : 'Admin'; ?></strong>
                </div>
            </div>

            <ul class=" list-unstyled components">
                        <li class="<?php echo ($page == 'dashboard') ? 'active' : ''; ?>">
                            <a href="index.php?page=dashboard">Tổng quan</a>
                        </li>
                        <li class="<?php echo ($page == 'checkin') ? 'active' : ''; ?>">
                            <a href="index.php?page=checkin">Check-in</a>
                        </li>
                        <li class="<?php echo ($page == 'members' || $page == 'add_member') ? 'active' : ''; ?>">
                            <a href="index.php?page=members">Hội viên</a>
                        </li>
                        <li class="<?php echo ($page == 'packages' || $page == 'add_package') ? 'active' : ''; ?>">
                            <a href="index.php?page=packages">Gói tập</a>
                        </li>
                        <li class="<?php echo ($page == 'subscriptions') ? 'active' : ''; ?>">
                            <a href="index.php?page=subscriptions">Đăng ký</a>
                        </li>
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            <li
                                class="<?php echo ($page == 'admins' || $page == 'add_admin' || $page == 'edit_admin') ? 'active' : ''; ?>">
                                <a href="index.php?page=admins">Quản lý Admin</a>
                            </li>
                        <?php endif; ?>



                        <li class="mt-5 border-top pt-2">
                            <a href="index.php?page=logout" class="text-danger">Đăng xuất</a>
                        </li>
                        </ul>
        </nav>

        <div id="content">
            <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4 border d-md-none">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-dark">
                        <span>Danh mục</span>
                    </button>
                </div>
            </nav>

            <?php
            if (isset($content)) {
                include $content;
            } else {
                echo "<h2>Chào mừng</h2>";
            }
            ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleBtn = document.getElementById('sidebarCollapse');
            if (toggleBtn) {
                toggleBtn.addEventListener('click', function () {
                    document.getElementById('sidebar').classList.toggle('active');
                });
            }
        });
    </script>
</body>

</html>