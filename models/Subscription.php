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
}
?>