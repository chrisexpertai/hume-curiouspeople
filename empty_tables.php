<?php

// Database configuration
$servername = "127.0.0.1";
$username = "user";
$password = "password";
$dbname = "lms";

try {
    // Create connection
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Disable foreign key checks
    $conn->exec("SET FOREIGN_KEY_CHECKS = 0");

    // Get all table names in the database
    $stmt = $conn->prepare("SHOW TABLES");
    $stmt->execute();
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Loop through each table
    foreach ($tables as $table) {
        // Check if the table name is not 'pusters'
        if ($table != 'pusters') {
            // Truncate the table
            $conn->exec("TRUNCATE TABLE $table");
            echo "Table '$table' has been emptied.\n";
        }
    }

    // Enable foreign key checks
    $conn->exec("SET FOREIGN_KEY_CHECKS = 1");

    echo "All tables except 'users' have been emptied successfully.";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Close connection
$conn = null;
