<?php

require_once 'config/database.php';
require_once 'models/Member.php';
require_once 'models/Package.php';
require_once 'models/Subscription.php';

$database = new Database();
$db = $database->getConnection();
$database->initTables();
echo "Đã khởi tạo bảng...\n";

$memberModel = new Member($db);
$packageModel = new Package($db);
$subscriptionModel = new Subscription($db);

echo "Đang xóa dữ liệu cũ...\n";
$db->exec("DELETE FROM subscriptions");
$db->exec("DELETE FROM members");
$db->exec("DELETE FROM packages");
$db->exec("DELETE FROM sqlite_sequence WHERE name IN ('subscriptions', 'members', 'packages')");
echo "Đã xóa xong.\n";

echo "Bắt đầu tạo dữ liệu mẫu...\n";

$packages = [
    ['1 tháng', 30, 500000],
    ['3 tháng', 90, 1350000],
    ['6 tháng', 180, 2500000],
    ['1 năm', 365, 4500000],
];

foreach ($packages as $pkg) {
    if ($packageModel->create($pkg[0], $pkg[1], $pkg[2])) {
        echo "Đã tạo gói tập: {$pkg[0]}\n";
    }
}

$members = [
    ['Nguyễn Văn A', '0901234567', 'Nam', '1990-01-01', 'Hà Nội', ''],
    ['Trần Thị B', '0912345678', 'Nữ', '1995-05-15', 'Đà Nẵng', ''],
    ['Lê Văn C', '0987654321', 'Nam', '1988-11-20', 'TP.HCM', ''],
];

foreach ($members as $mem) {
    if ($memberModel->create($mem[0], $mem[1], $mem[2], $mem[3], $mem[4], $mem[5])) {
        echo "Đã tạo hội viên: {$mem[0]}\n";
    }
}



echo "Hoàn tất tạo dữ liệu mẫu.\n";
?>