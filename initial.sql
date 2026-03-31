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