<?php
// Database connection settings
$servername = "localhost";  // Replace with your database server name or IP
$username = "root";         // Replace with your database username
$password = "";             // Replace with your database password
$dbname = "movie_recommendation_system"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optional: Set the character set to UTF-8
$conn->set_charset("utf8");

<<<<<<< HEAD
=======
echo "Connected successfully";
>>>>>>> be0e652117da6c8388e2c7176617d9d3d1674eb2
?>
