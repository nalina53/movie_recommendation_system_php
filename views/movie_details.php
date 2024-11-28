<?php
session_start(); // Start the session
include '../includes/connection.php'; // Include the database connection

// Get the movie_id from the URL
if (isset($_GET['movie_id'])) {
    $movie_id = $_GET['movie_id'];

    // Fetch the movie details along with the image URL from the database
    $movieQuery = "
        SELECT movies.*, images.image_url 
        FROM movies 
        LEFT JOIN images ON movies.movie_id = images.movie_id 
        WHERE movies.movie_id = ?";

    $stmt = $conn->prepare($movieQuery);
    $stmt->bind_param("i", $movie_id);
    $stmt->execute();
    $movieResult = $stmt->get_result();

    if ($movieResult->num_rows > 0) {
        $movie = $movieResult->fetch_assoc();
    } else {
        $movie = null; // Set to null if no movie found
    }
} else {
    $movie = null; // Set to null if no movie_id is provided
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($movie['title']) ? htmlspecialchars($movie['title']) : 'Movie Details'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Remove the border from the card body for the details */
        .no-border {
            border: none;
            box-shadow: none;
        }
    </style>
</head>

<body>

<div class="container mt-5">
    <?php if ($movie): ?>
        <div class="row">
            <!-- Movie image in a card -->
            <div class="col-md-4">
                <div class="card mb-4 shadow-sm">
                    <?php if (!empty($movie['image_url'])): ?>
                        <img src="<?php echo htmlspecialchars($movie['image_url']); ?>" class="card-img-top" alt="Movie Image">
                    <?php else: ?>
                        <img src="default-image.jpg" class="card-img-top" alt="No Image Available"> <!-- Fallback image -->
                    <?php endif; ?>
                </div>
            </div>

            <!-- Movie details beside the image (without card borders) -->
            <div class="col-md-8">
                <div class="no-border mb-4">
                    <div class="card-body">
                        <h1 class="card-title"><?php echo htmlspecialchars($movie['title']); ?></h1>
                        <p class="card-text"><strong>Director:</strong> <?php echo htmlspecialchars($movie['director']); ?></p>
                        <p class="card-text"><strong>Release Date:</strong> <?php echo htmlspecialchars($movie['release_date']); ?></p>
                        <p class="card-text"><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($movie['description'])); ?></p>
                        <p class="card-text"><strong>Genres:</strong> 
                            <?php
                            // Fetch genres associated with the movie
                            $genreQuery = "
                                SELECT genre_name 
                                FROM genres 
                                JOIN movie_genres ON genres.genre_id = movie_genres.genre_id 
                                WHERE movie_genres.movie_id = ?";
                                
                            $genreStmt = $conn->prepare($genreQuery);
                            $genreStmt->bind_param("i", $movie_id);
                            $genreStmt->execute();
                            $genreResult = $genreStmt->get_result();

                            $genres = [];
                            while ($genreRow = $genreResult->fetch_assoc()) {
                                $genres[] = htmlspecialchars($genreRow['genre_name']);
                            }
                            echo implode(', ', $genres);
                            ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <p>Movie not found.</p>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

</body>
</html>

<?php
$stmt->close(); // Close the prepared statement
$conn->close(); // Close the database connection
?>
