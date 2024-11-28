<?php
// Include the database connection
include '../includes/connection.php';

// Check if the movie ID was passed via GET
if (isset($_GET['id'])) { // Using 'id' from the URL
    $movie_id = $_GET['id']; // Assign 'id' to $movie_id

    // First, delete associated records from the `search_history` table
    $delete_search_history_query = "DELETE FROM search_history WHERE movie_id = ?";
    $stmt_search_history = $conn->prepare($delete_search_history_query);
    $stmt_search_history->bind_param("i", $movie_id);
    $stmt_search_history->execute();

    // Then, delete associated genres from the `movie_genres` table
    $delete_genres_query = "DELETE FROM movie_genres WHERE movie_id = ?";
    $stmt_genres = $conn->prepare($delete_genres_query);
    $stmt_genres->bind_param("i", $movie_id);
    $stmt_genres->execute();

    // Delete associated images from the `images` table
    $delete_images_query = "DELETE FROM images WHERE movie_id = ?";
    $stmt_images = $conn->prepare($delete_images_query);
    $stmt_images->bind_param("i", $movie_id);
    $stmt_images->execute();

    // Finally, delete the movie itself from the `movies` table
    $delete_movie_query = "DELETE FROM movies WHERE movie_id = ?";
    $stmt_movie = $conn->prepare($delete_movie_query);
    $stmt_movie->bind_param("i", $movie_id);

    if ($stmt_movie->execute()) {
        echo "<script>alert('Movie deleted successfully!'); window.location.href = '../admin/movies.php';</script>";
        exit;
    } else {
        echo "<script>alert('Error deleting movie: " . $conn->error . "'); window.location.href = '../admin/movies.php';</script>";
    }
} else {
    echo "<script>alert('Movie ID not provided.'); window.location.href = '../admin/movies.php';</script>";
}
?>
