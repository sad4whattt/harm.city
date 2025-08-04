<?php
require_once __DIR__ . '/database.php';

$db = new Database();
$conn = $db->connect();

if (!$conn) {
    die("Database connection failed in setup.php");
}

function runMigration($db, $sql, $successMessage, $errorMessage) {
    try {
        $db->query($sql);
        echo "<p style='color: green;'>" . $successMessage . "</p>";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
            echo "<p style='color: orange;'>" . $errorMessage . " (Column already exists)</p>";
        } else {
            echo "<p style='color: red;'>" . $errorMessage . ": " . $e->getMessage() . "</p>";
        }
    }
}

$createUsersTableSQL = "
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);";
runMigration($db, $createUsersTableSQL, "'users' table ensured.", "Error creating/ensuring 'users' table");

$alterUsersTableEmailSQL = "ALTER TABLE users ADD COLUMN email VARCHAR(255) NOT NULL UNIQUE;";
runMigration($db, $alterUsersTableEmailSQL, "'email' column ensured in 'users' table.", "Error adding 'email' column to 'users' table");

$alterUsersTablePasswordSQL = "ALTER TABLE users ADD COLUMN password_hash VARCHAR(255) NOT NULL;";
runMigration($db, $alterUsersTablePasswordSQL, "'password_hash' column ensured in 'users' table.", "Error adding 'password_hash' column to 'users' table");

$alterUsersTableCreatedAtSQL = "ALTER TABLE users ADD COLUMN created_at DATETIME DEFAULT CURRENT_TIMESTAMP;";
runMigration($db, $alterUsersTableCreatedAtSQL, "'created_at' column ensured in 'users' table.", "Error adding 'created_at' column to 'users' table");

echo "<p style='color: blue;'>Database setup complete. You can now delete or secure this file.</p>";

$createEndpointsTableSQL = "
CREATE TABLE IF NOT EXISTS endpoints (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    url VARCHAR(512) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    last_checked DATETIME NULL,
    last_status VARCHAR(16) NULL,
    interval_seconds INT NOT NULL DEFAULT 300,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);";
runMigration($db, $createEndpointsTableSQL, "'endpoints' table ensured.", "Error creating/ensuring 'endpoints' table");

?>