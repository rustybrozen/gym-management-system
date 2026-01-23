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
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE full_name LIKE ? OR phone_number LIKE ? 
                  ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $searchTerm = "%{$keyword}%";
        $stmt->bindParam(1, $searchTerm);
        $stmt->bindParam(2, $searchTerm);
        $stmt->execute();
        return $stmt->fetchAll();
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
        // First delete subscriptions to avoid foreign key constraint failure if CASCADE isn't set
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
}
?>