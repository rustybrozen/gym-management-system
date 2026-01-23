<?php

class Database
{
    private $db_file = __DIR__ . '/../db/database.sqlite';
    public $conn;

    public function getConnection()
    {
        $this->conn = null;
        try {
            if (!file_exists($this->db_file)) {
                touch($this->db_file);
            }

            $this->conn = new PDO("sqlite:" . $this->db_file);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            $this->conn->exec("PRAGMA foreign_keys = ON;");

        } catch (PDOException $exception) {
            echo "Lỗi kết nối: " . $exception->getMessage();
        }
        return $this->conn;
    }

    public function initTables()
    {
        if ($this->conn) {
            $sql = "
            CREATE TABLE IF NOT EXISTS admins (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                username TEXT NOT NULL UNIQUE,
                password TEXT NOT NULL,
                full_name TEXT,
                is_active INTEGER DEFAULT 1
            );
            
            CREATE TABLE IF NOT EXISTS packages (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                package_name TEXT NOT NULL,
                duration_days INTEGER NOT NULL,
                price REAL NOT NULL
            );

            CREATE TABLE IF NOT EXISTS members (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                full_name TEXT NOT NULL,
                phone_number TEXT UNIQUE,
                gender TEXT CHECK( gender IN ('Nam','Nữ','Khác') ),
                birth_date DATE,
                address TEXT,
                health_notes TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );

            CREATE TABLE IF NOT EXISTS subscriptions (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                member_id INTEGER NOT NULL,
                package_id INTEGER NOT NULL,
                start_date DATE NOT NULL,
                end_date DATE NOT NULL,
                amount_paid REAL,
                FOREIGN KEY (member_id) REFERENCES members(id),
                FOREIGN KEY (package_id) REFERENCES packages(id)
            );
            ";

            $this->conn->exec($sql);

            $password = password_hash('123456', PASSWORD_DEFAULT);
            $sql_insert = "INSERT OR IGNORE INTO admins (id, username, password, full_name) VALUES (1, 'admin', '$password', 'System Admin')";
            $this->conn->exec($sql_insert);


        }
    }
}
?>