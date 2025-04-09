<?php
// This script initializes the database and creates sample data

require_once 'config.php';

try {
    // Create employees table
    $pdo->exec("DROP TABLE IF EXISTS employees");

    $pdo->exec("CREATE TABLE employees (
        id INT AUTO_INCREMENT PRIMARY KEY,
        first_name VARCHAR(100),
        last_name VARCHAR(100),
        date_of_birth DATE,
        phone_number VARCHAR(20),
        office_number VARCHAR(20),
        employee_category ENUM('full time', 'part time', 'intern', 'contractor'),
        email VARCHAR(100) UNIQUE,
        password VARCHAR(255),
        is_admin TINYINT(1) DEFAULT 0
    )");

    // Insert admin user
    $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO employees (
        first_name, 
        last_name, 
        date_of_birth, 
        phone_number, 
        office_number, 
        employee_category, 
        email, 
        password, 
        is_admin
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->execute([
        'Admin',
        'User',
        '1980-01-01',
        '555-123-4567',
        '101',
        'full time',
        'admin@example.com',
        $admin_password,
        1  // Using 1 instead of true
    ]);

    // Insert regular employee
    $user_password = password_hash('password123', PASSWORD_DEFAULT);
    $stmt->execute([
        'John',
        'Doe',
        '1990-05-15',
        '555-987-6543',
        '102',
        'full time',
        'john@example.com',
        $user_password,
        0  // Using 0 instead of false
    ]);

    echo "Database setup completed successfully!";
    echo "\nSample accounts created:";
    echo "\n- Admin: admin@example.com / admin123";
    echo "\n- Employee: john@example.com / password123";

} catch (PDOException $e) {
    die("Database setup failed: " . $e->getMessage());
}