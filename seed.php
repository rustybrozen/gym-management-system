<?php

require_once 'config/database.php';
require_once 'models/Member.php';
require_once 'models/Package.php';
require_once 'models/Subscription.php';

$database = new Database();
$db = $database->getConnection();

if ($db) {
    $database->initTables();
    echo "Đã khởi tạo bảng...\n";
    $database->seed();
} else {
    echo "Khởi tạo kết nối cơ sở dữ liệu thất bại.\n";
}
?>