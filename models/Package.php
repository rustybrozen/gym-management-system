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
        $query = "SELECT * FROM " . $this->table . " WHERE is_deleted = 0 OR is_deleted IS NULL";
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
        $query = "UPDATE " . $this->table . " SET is_deleted = 1 WHERE id = ?";
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

    public function getActivePackagesCount()
    {
        $query = "SELECT COUNT(*) as count FROM " . $this->table . " WHERE is_deleted = 0 OR is_deleted IS NULL";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch();
        return $row['count'];
    }

    public function checkDuplicate($name, $duration, $exclude_id = null)
    {
        require_once __DIR__ . '/../utils/StringHelper.php';
        $newNameUnaccent = mb_strtolower(StringHelper::unaccent(trim($name)), 'UTF-8');

        // Check for duplicate name using accent-insensitive matching
        $allPackages = $this->getAll();
        foreach ($allPackages as $pkg) {
            if ($exclude_id && $pkg['id'] == $exclude_id) {
                continue;
            }
            $existingNameUnaccent = mb_strtolower(StringHelper::unaccent(trim($pkg['package_name'])), 'UTF-8');
            if ($existingNameUnaccent === $newNameUnaccent) {
                return 'name';
            }
        }

        // Check for duplicate duration
        $queryDuration = "SELECT id FROM " . $this->table . " WHERE duration_days = :duration AND (is_deleted = 0 OR is_deleted IS NULL)" . ($exclude_id ? " AND id != :exclude_id" : "") . " LIMIT 1";
        $stmtDuration = $this->conn->prepare($queryDuration);
        $stmtDuration->bindParam(':duration', $duration);
        if ($exclude_id) {
            $stmtDuration->bindParam(':exclude_id', $exclude_id);
        }
        $stmtDuration->execute();
        if ($stmtDuration->fetch()) {
            return 'duration'; // Duplicate duration found
        }

        return false; // No duplicates
    }
}
?>