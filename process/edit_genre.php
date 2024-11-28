<?php
session_start();
include '../includes/connection.php'; // Include your database connection

// Check if the genre ID is set in the URL
if (isset($_GET['id'])) {
    $genreId = intval($_GET['id']); // Sanitize the input
    $genreQuery = "SELECT * FROM genres WHERE genre_id = ?";
    
    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare($genreQuery);
    $stmt->bind_param("i", $genreId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch the genre data
    if ($result->num_rows > 0) {
        $genreRow = $result->fetch_assoc();
    } else {
        echo "Genre not found.";
        exit; // Stop execution if genre not found
    }
} else {
    echo "No genre ID provided.";
    exit; // Stop execution if no ID is provided
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Genre</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Genre</h2>
        <form action="update_genre.php" method="POST">
            <input type="hidden" name="genre_id" value="<?php echo $genreRow['genre_id']; ?>">
            <div class="mb-3">
                <label for="genre_name" class="form-label">Genre Name</label>
                <input type="text" class="form-control" id="genre_name" name="genre_name" value="<?php echo htmlspecialchars($genreRow['genre_name']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Genre</button>
        </form>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close(); // Close the database connection
?>
