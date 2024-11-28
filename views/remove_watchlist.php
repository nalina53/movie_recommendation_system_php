<?php
// Start the session
session_start();

// Include the database connection
include '../includes/connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page or handle unauthorized access
    header("Location: login.php");
    exit();
}

// Get the user ID from the session
$userId = intval($_SESSION['user_id']); // Ensure it's an integer

// Check if movie ID is provided via POST
if (isset($_POST['movie_id'])) {
    $movieId = intval($_POST['movie_id']); // Ensure movie ID is an integer

    // Delete the movie from the user's watchlist
    $deleteQuery = "DELETE FROM watchlist WHERE user_id = ? AND movie_id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("ii", $userId, $movieId);
    
    if ($stmt->execute()) {
        // Output JavaScript alert and redirect back to the watchlist page
        echo "<script>
                alert('Movie removed from your watchlist.');
                window.location.href = 'watchlist.php';
              </script>";
        exit();
    } else {
        // Handle any errors during deletion
        echo "<script>
                alert('Error removing movie: " . $conn->error . "');
                window.location.href = 'watchlist.php';
              </script>";
    }
} else {
    echo "<script>
            alert('No movie ID provided.');
            window.location.href = 'watchlist.php';
          </script>";
}

// Close the connection
$conn->close();
?>
