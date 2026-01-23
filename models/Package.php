<?php

class Package
{
    private $conn;
    private $table = "packages";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAll()
    {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
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

    public function create($package_name, $duration_days, $price)
    {
        $query = "INSERT INTO " . $this->table . " (package_name, duration_days, price) VALUES (:name, :days, :price)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':name', $package_name);
        $stmt->bindParam(':days', $duration_days);
        $stmt->bindParam(':price', $price);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function update($id, $package_name, $duration_days, $price)
    {
        $query = "UPDATE " . $this->table . " SET package_name = :name, duration_days = :days, price = :price WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $package_name);
        $stmt->bindParam(':days', $duration_days);
        $stmt->bindParam(':price', $price);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete($id)
    {
        $query = "DELETE FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function isUsedInSubscriptions($id)
    {
        $query = "SELECT COUNT(*) as count FROM subscriptions WHERE package_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        $row = $stmt->fetch();
        return $row['count'] > 0;
    }
}
?>