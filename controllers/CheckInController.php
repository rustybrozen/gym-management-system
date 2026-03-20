<?php

class CheckInController
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function index()
    {
        $result = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $keyword = $_POST['keyword'] ?? '';
            $result = $this->checkSubscription($keyword);
        }

        $content = 'views/checkin/index.php';
        include 'views/layout.php';
    }

    private function checkSubscription($keyword)
    {
        if (empty($keyword)) {
            return ['status' => 'error', 'message' => 'Vui lòng nhập tên hoặc số điện thoại.'];
        }

        // Find member
        require_once __DIR__ . '/../utils/StringHelper.php';
        $sql = "SELECT * FROM members ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $allMembers = $stmt->fetchAll();
        
        $member = null;
        $keywordUnaccent = mb_strtolower(StringHelper::unaccent($keyword), 'UTF-8');
        foreach ($allMembers as $m) {
            $nameUnaccent = mb_strtolower(StringHelper::unaccent($m['full_name']), 'UTF-8');
            if ($m['phone_number'] === $keyword || strpos($nameUnaccent, $keywordUnaccent) !== false) {
                $member = $m;
                break;
            }
        }

        if (!$member) {
            return ['status' => 'error', 'message' => 'Không tìm thấy hội viên!'];
        }

        // Check active subscription
        $sqlSub = "SELECT * FROM subscriptions 
                   WHERE member_id = :member_id 
                   AND end_date >= DATE('now') 
                   ORDER BY end_date DESC LIMIT 1";
        $stmtSub = $this->db->prepare($sqlSub);
        $stmtSub->bindParam(':member_id', $member['id']);
        $stmtSub->execute();
        $subscription = $stmtSub->fetch();

        if ($subscription) {
            $daysLeft = (strtotime($subscription['end_date']) - time()) / (60 * 60 * 24);
            return [
                'status' => 'success',
                'member' => $member,
                'subscription' => $subscription,
                'days_left' => ceil($daysLeft)
            ];
        } else {
            return [
                'status' => 'expired',
                'member' => $member,
                'message' => 'Hội viên chưa có gói tập hoặc đã hết hạn.'
            ];
        }
    }
}
?>