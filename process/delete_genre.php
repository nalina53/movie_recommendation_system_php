<?php
session_start();
include '../includes/connection.php'; // Include your database connection

// Check if the genre ID is set in the URL
if (isset($_GET['id'])) {
    $genreId = intval($_GET['id']); // Sanitize the input

    // Prepare the delete statement to prevent SQL injection
    $deleteQuery = "DELETE FROM genres WHERE genre_id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $genreId);

    // Execute the delete
    if ($stmt->execute()) {
        // Redirect back to the genres list page with a success message
        header("Location: ../admin/add_genre.php?message=Genre+deleted+successfully");
        exit();
    } else {
        echo "Error deleting genre: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "No genre ID provided.";
}

$conn->close(); // Close the database connection
?>
