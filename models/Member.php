<?php

class Member
{
    private $conn;
    private $table = "members";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAll()
    {
        $query = "SELECT * FROM " . $this->table . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function search($keyword)
    {
        $allMembers = $this->getAll();
        
        require_once __DIR__ . '/../utils/StringHelper.php';
        $keywordUnaccent = mb_strtolower(StringHelper::unaccent($keyword), 'UTF-8');
        
        $results = array_filter($allMembers, function($member) use ($keyword, $keywordUnaccent) {
            $nameUnaccent = mb_strtolower(StringHelper::unaccent($member['full_name']), 'UTF-8');
            $phoneMatch = strpos($member['phone_number'], $keyword) !== false;
            $nameMatch = strpos($nameUnaccent, $keywordUnaccent) !== false;
            
            return $phoneMatch || $nameMatch;
        });
        
        return array_values($results);
    }

    public function getById($id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function create($full_name, $phone_number, $gender, $birth_date, $address, $health_notes)
    {
        $query = "INSERT INTO " . $this->table . " (full_name, phone_number, gender, birth_date, address, health_notes) 
                  VALUES (:full_name, :phone_number, :gender, :birth_date, :address, :health_notes)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':full_name', $full_name);
        $stmt->bindParam(':phone_number', $phone_number);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':birth_date', $birth_date);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':health_notes', $health_notes);

        try {
            if ($stmt->execute()) {
                return true;
            }
        } catch (PDOException $e) {
            return false;
        }
        return false;
    }

    public function update($id, $full_name, $phone_number, $gender, $birth_date, $address, $health_notes)
    {
        $query = "UPDATE " . $this->table . " 
                  SET full_name = :full_name, phone_number = :phone_number, 
                      gender = :gender, birth_date = :birth_date, 
                      address = :address, health_notes = :health_notes 
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':full_name', $full_name);
        $stmt->bindParam(':phone_number', $phone_number);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':birth_date', $birth_date);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':health_notes', $health_notes);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete($id)
    {

        $querySub = "DELETE FROM subscriptions WHERE member_id = ?";
        $stmtSub = $this->conn->prepare($querySub);
        $stmtSub->bindParam(1, $id);
        $stmtSub->execute();

        $query = "DELETE FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function getTotalMembers()
    {
        $query = "SELECT COUNT(*) as count FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch();
        return $row['count'];
    }

    public function getMonthlyNewMembers()
    {
        $query = "SELECT COUNT(*) as count FROM " . $this->table . " 
                  WHERE strftime('%Y-%m', created_at) = strftime('%Y-%m', 'now')";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch();
        return $row['count'];
    }

    public function getRecentMembers($limit = 5)
    {
        $query = "SELECT * FROM " . $this->table . " ORDER BY created_at DESC LIMIT ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>