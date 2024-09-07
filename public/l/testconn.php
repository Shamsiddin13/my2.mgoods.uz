<?php
// Include the configuration file to establish a database connection
global $conn_products;
require 'config_db.php';

// Define a query to fetch all users from the 'users' table (replace 'users' with your actual table name)
$query = "SELECT * FROM users";

// Prepare and execute the query
$result = $conn_products->query($query);

// Check if the query returned any results
if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        // Assuming your users table has 'id', 'name', and 'email' columns
        echo "ID: " . $row["id"] . " - Name: " . $row["name"] . " - Email: " . $row["email"] . "<br>";
    }
} else {
    echo "No users found.";
}

// Close the database connection
$conn_products->close();
?>
