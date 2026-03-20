<?php

class Subscription
{
    private $conn;
    private $table = "subscriptions";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create($member_id, $package_id, $admin_id, $start_date, $end_date, $amount_paid)
    {
        $query = "INSERT INTO " . $this->table . " (member_id, package_id, admin_id, start_date, end_date, amount_paid) 
                  VALUES (:member_id, :package_id, :admin_id, :start_date, :end_date, :amount_paid)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':member_id', $member_id);
        $stmt->bindParam(':package_id', $package_id);
        $stmt->bindParam(':admin_id', $admin_id);
        $stmt->bindParam(':start_date', $start_date);
        $stmt->bindParam(':end_date', $end_date);
        $stmt->bindParam(':amount_paid', $amount_paid);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function getLatestActiveByMember($member_id)
    {
        $today = date('Y-m-d');
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE member_id = ? AND end_date >= ? 
                  ORDER BY end_date DESC LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $member_id);
        $stmt->bindParam(2, $today);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getHistoryByMember($member_id)
    {
        $query = "SELECT s.*, p.package_name, a.full_name as admin_name 
                  FROM " . $this->table . " s
                  JOIN packages p ON s.package_id = p.id
                  LEFT JOIN admins a ON s.admin_id = a.id
                  WHERE s.member_id = ?
                  ORDER BY s.created_at DESC, s.id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $member_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getAll()
    {
        $query = "SELECT s.*, m.full_name, p.package_name 
                  FROM " . $this->table . " s
                  JOIN members m ON s.member_id = m.id
                  JOIN packages p ON s.package_id = p.id
                  ORDER BY s.id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getTotalRevenue()
    {
        $query = "SELECT SUM(amount_paid) as total FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch();
        return $row['total'] ? $row['total'] : 0;
    }

    public function getMonthlyRevenue()
    {
        $query = "SELECT SUM(amount_paid) as total FROM " . $this->table . " 
                  WHERE strftime('%Y-%m', created_at) = strftime('%Y-%m', 'now')";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch();
        return $row['total'] ? $row['total'] : 0;
    }

    public function getMonthlyNewSubscriptions()
    {
        $query = "SELECT COUNT(*) as count FROM " . $this->table . " 
                  WHERE strftime('%Y-%m', created_at) = strftime('%Y-%m', 'now')";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch();
        return $row['count'];
    }

    public function getFilteredStats($start_date, $end_date)
    {
        // Revenue in period
        $queryRev = "SELECT SUM(amount_paid) as revenue FROM " . $this->table . " 
                     WHERE date(created_at) >= ? AND date(created_at) <= ?";
        $stmtRev = $this->conn->prepare($queryRev);
        $stmtRev->bindParam(1, $start_date);
        $stmtRev->bindParam(2, $end_date);
        $stmtRev->execute();
        $revenue = $stmtRev->fetch()['revenue'];

        // Subscriptions in period
        $querySubs = "SELECT COUNT(*) as subs_count FROM " . $this->table . " 
                      WHERE date(created_at) >= ? AND date(created_at) <= ?";
        $stmtSubs = $this->conn->prepare($querySubs);
        $stmtSubs->bindParam(1, $start_date);
        $stmtSubs->bindParam(2, $end_date);
        $stmtSubs->execute();
        $subsCount = $stmtSubs->fetch()['subs_count'];

        return [
            'revenue' => $revenue ? $revenue : 0,
            'subscriptions' => $subsCount ? $subsCount : 0
        ];
    }

    public function getRevenueChartData($start_date, $end_date)
    {
        $query = "SELECT date(created_at) as log_date, SUM(amount_paid) as daily_revenue 
                  FROM " . $this->table . " 
                  WHERE date(created_at) >= ? AND date(created_at) <= ? 
                  GROUP BY date(created_at) 
                  ORDER BY date(created_at) ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $start_date);
        $stmt->bindParam(2, $end_date);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getSubscriptionsChartData($start_date, $end_date)
    {
        $query = "SELECT date(created_at) as log_date, COUNT(*) as daily_subs 
                  FROM " . $this->table . " 
                  WHERE date(created_at) >= ? AND date(created_at) <= ? 
                  GROUP BY date(created_at) 
                  ORDER BY date(created_at) ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $start_date);
        $stmt->bindParam(2, $end_date);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function cancelActiveSubscription($member_id)
    {
        $query = "UPDATE " . $this->table . " 
                  SET end_date = DATE('now', '-1 day') 
                  WHERE id = (
                      SELECT id FROM " . $this->table . " 
                      WHERE member_id = ? AND end_date >= DATE('now') 
                      ORDER BY end_date DESC LIMIT 1
                  )";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $member_id);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>