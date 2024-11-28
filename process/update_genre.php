<?php
session_start();
include '../includes/connection.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the posted data
    $genreId = intval($_POST['genre_id']);
    $genreName = trim($_POST['genre_name']);

    // Prepare the SQL statement to prevent SQL injection
    $updateQuery = "UPDATE genres SET genre_name = ? WHERE genre_id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("si", $genreName, $genreId);

    // Execute the update
    if ($stmt->execute()) {
        // Redirect to the genre list page or show success message
        header("Location: ../admin/add_genre.php"); // Adjust this path
        exit();
    } else {
        echo "Error updating genre: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close(); // Close the database connection
?>
