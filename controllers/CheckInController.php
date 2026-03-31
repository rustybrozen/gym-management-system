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
            $memberId = $_POST['member_id'] ?? null;
            $result = $this->checkSubscription($keyword, $memberId);
        }

        $content = 'views/checkin/index.php';
        include 'views/layout.php';
    }

    private function checkSubscription($keyword, $memberId = null)
    {
        if (empty($keyword) && empty($memberId)) {
            return ['status' => 'error', 'message' => 'Vui lòng nhập tên hoặc số điện thoại.'];
        }

        $member = null;

        if ($memberId) {
            $sql = "SELECT * FROM members WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $memberId);
            $stmt->execute();
            $member = $stmt->fetch();
        } else {
            // Find member
            require_once __DIR__ . '/../utils/StringHelper.php';
            $sql = "SELECT * FROM members ORDER BY created_at DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $allMembers = $stmt->fetchAll();
            
            $matchedMembers = [];
            $keywordUnaccent = mb_strtolower(StringHelper::unaccent($keyword), 'UTF-8');
            $isPhoneSearch = preg_match('/^[0-9]+$/', $keyword);

            foreach ($allMembers as $m) {
                $nameUnaccent = mb_strtolower(StringHelper::unaccent($m['full_name']), 'UTF-8');
                
                if ($isPhoneSearch) {
                    if ($m['phone_number'] === $keyword) {
                        $matchedMembers[] = $m;
                    }
                } else {
                    if (strpos($nameUnaccent, $keywordUnaccent) !== false) {
                        $matchedMembers[] = $m;
                    }
                }
            }

            if (empty($matchedMembers)) {
                return ['status' => 'error', 'message' => 'Không tìm thấy hội viên!'];
            }

            if (count($matchedMembers) > 1) {
                return [
                    'status' => 'multiple',
                    'keyword' => $keyword,
                    'members' => $matchedMembers
                ];
            }

            $member = $matchedMembers[0];
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