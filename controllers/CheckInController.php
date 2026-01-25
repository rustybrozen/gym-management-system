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
        $sql = "SELECT * FROM members WHERE phone_number = :keyword OR full_name LIKE :keyword_like LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':keyword', $keyword);
        $stmt->bindValue(':keyword_like', "%$keyword%");
        $stmt->execute();
        $member = $stmt->fetch();

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