<?php
$servername = "davronrn.beget.tech";
$username = "davronrn_admin_t";
$password = "4!Tee4O7";
$dbname = "davronrn_admin_t";

// Create connection
$conn_products = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn_products->connect_error) {
    die("Connection failed: " . $conn_products->connect_error);
}

?>
