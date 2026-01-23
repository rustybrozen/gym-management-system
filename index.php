<?php

session_start();

require_once 'config/database.php';
require_once 'controllers/DashboardController.php';
require_once 'controllers/MemberController.php';
require_once 'controllers/PackageController.php';
require_once 'controllers/SubscriptionController.php';
require_once 'controllers/AuthController.php';
require_once 'controllers/AdminController.php';
require_once 'utils/Csrf.php';

$database = new Database();
$db = $database->getConnection();
$database->initTables();

$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

if (!isset($_SESSION['user_id']) && $page !== 'login') {
    header("Location: index.php?page=login");
    exit;
}

if (isset($_SESSION['user_id']) && $page === 'login') {
    header("Location: index.php?page=dashboard");
    exit;
}

switch ($page) {
    case 'login':
        $controller = new AuthController($db);
        $controller->login();
        break;

    case 'logout':
        $controller = new AuthController($db);
        $controller->logout();
        break;

    case 'dashboard':
        $controller = new DashboardController($db);
        $controller->index();
        break;

    case 'members':
        $controller = new MemberController($db);
        $controller->index();
        break;

    case 'add_member':
        $controller = new MemberController($db);
        $controller->create();
        break;

    case 'edit_member':
        $controller = new MemberController($db);
        $controller->edit();
        break;

    case 'delete_member':
        $controller = new MemberController($db);
        $controller->delete();
        break;

    case 'api_members_search':
        $controller = new MemberController($db);
        $controller->searchApi();
        break;

    case 'packages':
        $controller = new PackageController($db);
        $controller->index();
        break;

    case 'add_package':
        $controller = new PackageController($db);
        $controller->create();
        break;

    case 'edit_package':
        $controller = new PackageController($db);
        $controller->edit();
        break;

    case 'delete_package':
        $controller = new PackageController($db);
        $controller->delete();
        break;

    case 'subscriptions':
        $controller = new SubscriptionController($db);
        $controller->create();
        break;

    case 'admins':
        $controller = new AdminController($db);
        $controller->index();
        break;

    case 'add_admin':
        $controller = new AdminController($db);
        $controller->create();
        break;

    case 'edit_admin':
        $controller = new AdminController($db);
        $controller->edit();
        break;

    case 'delete_admin':
        $controller = new AdminController($db);
        $controller->delete();
        break;

    default:
        $controller = new DashboardController($db);
        $controller->index();
        break;
}
?>