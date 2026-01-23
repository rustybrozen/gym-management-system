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
}
?>