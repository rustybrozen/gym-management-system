<?php

class Admin
{
    private $conn;
    private $table = "admins";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function login($username, $password)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE username = :username LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        $user = $stmt->fetch();

        if ($user && $user['password'] === $password) {
            return $user;
        }
        return false;
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

    public function create($username, $password, $fullName)
    {
        $query = "INSERT INTO " . $this->table . " (username, password, full_name) VALUES (:username, :password, :full_name)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':full_name', $fullName);

        try {
            if ($stmt->execute()) {
                return true;
            }
        } catch (PDOException $e) {
            return false;
        }
        return false;
    }

    public function update($id, $username, $password, $fullName)
    {
        if (!empty($password)) {
            $query = "UPDATE " . $this->table . " SET username = :username, password = :password, full_name = :full_name WHERE id = :id";
        } else {
            $query = "UPDATE " . $this->table . " SET username = :username, full_name = :full_name WHERE id = :id";
        }

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':full_name', $fullName);

        if (!empty($password)) {
            $stmt->bindParam(':password', $password);
        }

        try {
            if ($stmt->execute()) {
                return true;
            }
        } catch (PDOException $e) {
            return false;
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
}
?>