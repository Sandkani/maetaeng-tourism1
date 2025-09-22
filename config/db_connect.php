<?php
// PHP Logic:
// 1. Define database connection credentials.
//    - hostname: localhost
//    - username: root (typical for local XAMPP/MAMP setup)
//    - password: '' (no password by default)
//    - dbname: maetaeng_tourism
// 2. Create a new MySQLi object to connect to the database.
// 3. Check for connection errors and terminate the script if an error occurs.
// 4. Set character set to UTF-8 to handle Thai characters properly.

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "maetaeng_tourism";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set character set to UTF-8
$conn->set_charset("utf8mb4");
?>
