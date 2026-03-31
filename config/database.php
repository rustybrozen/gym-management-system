<?php

require_once __DIR__ . '/../utils/StringHelper.php';

class Database
{
    private $db_file = __DIR__ . '/../db/database.sqlite';
    public $conn;
    public $is_new_db = false;

    public function getConnection()
    {
        $this->conn = null;
        try {
            $db_dir = dirname($this->db_file);
            if (!is_dir($db_dir)) {
                mkdir($db_dir, 0777, true);
            }

            if (!file_exists($this->db_file)) {
                $this->is_new_db = true;
                touch($this->db_file);
            }

            $this->conn = new PDO("sqlite:" . $this->db_file);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            // Register custom sqlite function for accent-insensitive search (if supported by PDO driver)
            if (method_exists($this->conn, 'sqliteCreateFunction')) {
                $this->conn->sqliteCreateFunction('unaccent', ['StringHelper', 'unaccent'], 1);
            }

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
                role TEXT DEFAULT 'staff',
                is_active INTEGER DEFAULT 1
            );
            
            CREATE TABLE IF NOT EXISTS packages (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                package_name TEXT NOT NULL,
                duration_days INTEGER NOT NULL,
                price REAL NOT NULL,
                is_deleted INTEGER DEFAULT 0
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
                admin_id INTEGER,
                start_date DATE NOT NULL,
                end_date DATE NOT NULL,
                amount_paid REAL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (member_id) REFERENCES members(id),
                FOREIGN KEY (package_id) REFERENCES packages(id),
                FOREIGN KEY (admin_id) REFERENCES admins(id)
            );
            ";

            $this->conn->exec($sql);

            // Check if role column exists in admins table
            $checkColumn = $this->conn->query("PRAGMA table_info(admins)");
            $columns = $checkColumn->fetchAll(PDO::FETCH_COLUMN, 1);
            if (!in_array('role', $columns)) {
                $this->conn->exec("ALTER TABLE admins ADD COLUMN role TEXT DEFAULT 'staff'");
                // Update existing admin to be admin role
                $this->conn->exec("UPDATE admins SET role = 'admin' WHERE username = 'admin'");
            }

            // Check if admin_id column exists in subscriptions table
            $checkSubColumn = $this->conn->query("PRAGMA table_info(subscriptions)");
            $subColumns = $checkSubColumn->fetchAll(PDO::FETCH_COLUMN, 1);
            if (!in_array('admin_id', $subColumns)) {
                $this->conn->exec("ALTER TABLE subscriptions ADD COLUMN admin_id INTEGER REFERENCES admins(id)");
            }
            if (!in_array('created_at', $subColumns)) {
                $this->conn->exec("ALTER TABLE subscriptions ADD COLUMN created_at DATETIME DEFAULT CURRENT_TIMESTAMP");
            }

            // Check if is_deleted column exists in packages table
            $checkPkgColumn = $this->conn->query("PRAGMA table_info(packages)");
            $pkgColumns = $checkPkgColumn->fetchAll(PDO::FETCH_COLUMN, 1);
            if (!in_array('is_deleted', $pkgColumns)) {
                $this->conn->exec("ALTER TABLE packages ADD COLUMN is_deleted INTEGER DEFAULT 0");
            }

            $password = password_hash('123456', PASSWORD_DEFAULT);
            $sql_insert = "INSERT OR IGNORE INTO admins (id, username, password, full_name, role) VALUES (1, 'admin', '$password', 'System Admin', 'admin')";
            $this->conn->exec($sql_insert);

            // Auto-seed data if the database was just created
            if ($this->is_new_db) {
                $this->seed(false);
            }
        }
    }

    // =========================================================================
    // DỮ LIỆU MẪU (SEED DATA)
    // Dev có thể chỉnh sửa các mảng dữ liệu bên dưới để thay đổi dữ liệu seed
    // =========================================================================
    public function seed($verbose = true)
    {
        if (!$this->conn) {
            if ($verbose) echo "Chưa kết nối CSDL.\n";
            return;
        }

        if ($verbose) echo "Đang xóa dữ liệu cũ...\n";
        $this->conn->exec("DELETE FROM subscriptions");
        $this->conn->exec("DELETE FROM members");
        $this->conn->exec("DELETE FROM packages");
        $this->conn->exec("DELETE FROM sqlite_sequence WHERE name IN ('subscriptions', 'members', 'packages')");
        if ($verbose) echo "Đã xóa xong.\n";

        if ($verbose) echo "Bắt đầu tạo dữ liệu mẫu...\n";

        // Định nghĩa các gói tập
        $packages = [
            ['package_name' => '1 tháng', 'duration_days' => 30, 'price' => 500000],
            ['package_name' => '3 tháng', 'duration_days' => 90, 'price' => 1350000],
            ['package_name' => '6 tháng', 'duration_days' => 180, 'price' => 2500000],
            ['package_name' => '1 năm', 'duration_days' => 365, 'price' => 4500000],
        ];

        // Lưu các gói tập vào CSDL
        $stmtPkg = $this->conn->prepare("INSERT INTO packages (package_name, duration_days, price) VALUES (:package_name, :duration_days, :price)");
        foreach ($packages as $pkg) {
            $stmtPkg->execute([
                ':package_name' => $pkg['package_name'],
                ':duration_days' => $pkg['duration_days'],
                ':price' => $pkg['price']
            ]);
            if ($verbose) echo "Đã tạo gói tập: {$pkg['package_name']}\n";
        }

        // Định nghĩa các hội viên
        $members = [
            ['full_name' => 'Nguyễn Văn A', 'phone_number' => '0901234567', 'gender' => 'Nam', 'birth_date' => '1990-01-01', 'address' => 'Hà Nội', 'health_notes' => ''],
            ['full_name' => 'Trần Thị B', 'phone_number' => '0912345678', 'gender' => 'Nữ', 'birth_date' => '1995-05-15', 'address' => 'Đà Nẵng', 'health_notes' => ''],
            ['full_name' => 'Lê Văn C', 'phone_number' => '0987654321', 'gender' => 'Nam', 'birth_date' => '1988-11-20', 'address' => 'TP.HCM', 'health_notes' => ''],
        ];

        // Lưu các hội viên vào CSDL
        $stmtMem = $this->conn->prepare("INSERT INTO members (full_name, phone_number, gender, birth_date, address, health_notes) VALUES (:full_name, :phone_number, :gender, :birth_date, :address, :health_notes)");
        foreach ($members as $mem) {
            $stmtMem->execute([
                ':full_name' => $mem['full_name'],
                ':phone_number' => $mem['phone_number'],
                ':gender' => $mem['gender'],
                ':birth_date' => $mem['birth_date'],
                ':address' => $mem['address'],
                ':health_notes' => $mem['health_notes']
            ]);
            if ($verbose) echo "Đã tạo hội viên: {$mem['full_name']}\n";
        }

        if ($verbose) echo "Hoàn tất tạo dữ liệu mẫu.\n";
    }
}

// Cho phép chạy file này trực tiếp từ CLI để seed data
if (php_sapi_name() === 'cli' && basename(__FILE__) == basename($_SERVER['PHP_SELF'])) {
    $database = new Database();
    $db = $database->getConnection();
    
    if ($db) {
        $database->initTables();
        echo "Cập nhật bảng thành công.\n";
        $database->seed();
    }
}
?>