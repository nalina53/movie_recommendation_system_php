<?php
// Include the database connection
include '../includes/connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $genre_name = $_POST['genre_name'];

    // Prepare the SQL statement
    $query = $conn->prepare("INSERT INTO genres (genre_name) VALUES (?)");

    // Bind the parameter and execute
    $query->bind_param('s', $genre_name);

    try {
        $query->execute();
        echo "Genre added successfully!";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
