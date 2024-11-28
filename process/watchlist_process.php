<?php
// Start the session
session_start();

// Include the database connection file
include '../includes/connection.php'; // Adjust the path if necessary

// Check if movie_id and user_id are set in the URL
if (isset($_GET['movie_id']) && isset($_SESSION['user_id'])) {
    $movieId = intval($_GET['movie_id']); // Ensure the movie ID is an integer
    $userId = intval($_SESSION['user_id']); // Ensure the user ID is an integer

    // Prepare and execute the insert query
    $insertQuery = "INSERT INTO watchlist (user_id, movie_id) VALUES (?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("ii", $userId, $movieId); // Bind parameters
    $stmt->execute();

    // Check for success
    if ($stmt->affected_rows > 0) {
        // Optionally, set a success message in the session
        $_SESSION['message'] = "Movie added to watchlist successfully!";
    } else {
        // Handle duplicate entries or errors
        $_SESSION['message'] = "Failed to add movie to watchlist.";
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();

// Redirect back to the watchlist page with an optional message
header("Location: ../views/watchlist.php");
exit();
?>
