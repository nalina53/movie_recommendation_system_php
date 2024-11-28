<?php
// Start the session
session_start();

// Include the database connection file
include '../includes/connection.php'; // Adjust the path if necessary
include '../includes/cosine_similarity.php'; // Include the cosine similarity algorithm

// Check if user_id is set in the session
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page or handle unauthorized access
    header("Location: login.php");
    exit();
}

$userId = intval($_SESSION['user_id']); // Ensure user ID is an integer

// Step 1: Fetch movies from the watchlist with their genres
$watchlistQuery = "
    SELECT m.movie_id, m.title, i.image_url, GROUP_CONCAT(g.genre_name) AS genre_names
    FROM watchlist w
    JOIN movies m ON w.movie_id = m.movie_id
    LEFT JOIN images i ON m.movie_id = i.movie_id
    LEFT JOIN movie_genres mg ON m.movie_id = mg.movie_id
    LEFT JOIN genres g ON mg.genre_id = g.genre_id
    WHERE w.user_id = ?
    GROUP BY m.movie_id
";
$stmt = $conn->prepare($watchlistQuery);
$stmt->bind_param("i", $userId);
$stmt->execute();
$watchlistResult = $stmt->get_result();

// Store watchlist movies
$watchlistMovies = [];
$selectedGenres = []; // To store selected genres from watchlist
$genreFrequency = []; // To store genre frequencies

while ($row = $watchlistResult->fetch_assoc()) {
    $watchlistMovies[] = $row;

    // Get genre IDs for each movie
    $genreIdsQuery = "
        SELECT mg.genre_id
        FROM movie_genres mg
        WHERE mg.movie_id = ?
    ";
    $stmtGenre = $conn->prepare($genreIdsQuery);
    $stmtGenre->bind_param("i", $row['movie_id']);
    $stmtGenre->execute();
    $genreResult = $stmtGenre->get_result();

    while ($genreRow = $genreResult->fetch_assoc()) {
        $selectedGenres[] = $genreRow['genre_id'];

        // Update genre frequency
        if (!isset($genreFrequency[$genreRow['genre_id']])) {
            $genreFrequency[$genreRow['genre_id']] = 0;
        }
        $genreFrequency[$genreRow['genre_id']]++;
    }
    $stmtGenre->close();
}

// Step 2: Fetch recommended movies based on genres from the watchlist
$recommendedMovies = [];
if (!empty($selectedGenres)) {
    $selectedGenres = array_unique($selectedGenres); // Unique genre IDs

    // Fetch all movies that are not in the watchlist
    $recommendationQuery = "
        SELECT m.movie_id, m.title, i.image_url, GROUP_CONCAT(g.genre_name) AS genre_names
        FROM movies m
        LEFT JOIN images i ON m.movie_id = i.movie_id
        LEFT JOIN movie_genres mg ON m.movie_id = mg.movie_id
        LEFT JOIN genres g ON mg.genre_id = g.genre_id
        WHERE m.movie_id NOT IN (SELECT movie_id FROM watchlist WHERE user_id = ?)
        GROUP BY m.movie_id
    ";

    $stmt = $conn->prepare($recommendationQuery);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $recommendationResult = $stmt->get_result();

    // Store recommended movies with similarity score
    while ($row = $recommendationResult->fetch_assoc()) {
        // Get genre IDs for the current movie
        $movieGenresQuery = "
            SELECT mg.genre_id
            FROM movie_genres mg
            WHERE mg.movie_id = ?
        ";
        $stmtMovieGenres = $conn->prepare($movieGenresQuery);
        $stmtMovieGenres->bind_param("i", $row['movie_id']);
        $stmtMovieGenres->execute();
        $movieGenresResult = $stmtMovieGenres->get_result();

        $movieGenres = [];
        while ($genreRow = $movieGenresResult->fetch_assoc()) {
            $movieGenres[] = $genreRow['genre_id'];
        }
        $stmtMovieGenres->close();

        // Calculate similarity score using cosine similarity with frequency
        $similarityScore = calculateCosineSimilarityWithFrequency($selectedGenres, $movieGenres, $genreFrequency);
        $row['similarity_score'] = round($similarityScore, 2); // Round for display

        // Only add if similarity score is above a threshold (e.g., 0.1)
        if ($similarityScore > 0) {
            $recommendedMovies[] = $row;
        }
    }
    $stmt->close();
}

// Sort the recommended movies by similarity score in descending order
usort($recommendedMovies, function($a, $b) {
    return $b['similarity_score'] <=> $a['similarity_score'];
});

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Watchlist and Recommendations</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .card {
            margin: 15px; /* Add some margin for spacing */
            border-radius: 15px; /* Rounded corners */
            transition: transform 0.2s; /* Animation for hover effect */
            text-decoration: none; /* Remove underline */
            color: inherit; /* Inherit text color */
        }
        .card:hover {
            transform: scale(1.05); /* Scale up the card on hover */
        }
        .card-img-top {
            height: 200px; /* Set a fixed height for the image */
            object-fit: cover; /* Ensure the image covers the area without distortion */
            border-top-left-radius: 15px; /* Match the card border */
            border-top-right-radius: 15px; /* Match the card border */
        }
        body {
            background-color: #f8f9fa; /* Light background color */
        }
        h1 {
            margin-top: 20px; /* Spacing above headings */
            margin-bottom: 20px; /* Spacing below headings */
            color: #343a40; /* Dark color for headings */
        }
        /* Remove underline and blue color on hover */
        a {
            text-decoration: none; /* Remove underline */
            color: inherit; /* Inherit color */
        }
        a:hover {
            text-decoration: none; /* Ensure no underline on hover */
            color: inherit; /* Keep the color on hover */
        }
    </style>
</head>
<body>
<h1>Your Watchlist</h1>
<div class="row">
    <?php if (empty($watchlistMovies)): ?>
        <div class="col-12 text-center">
            <p>Your watchlist is empty.</p>
        </div>
    <?php else: ?>
        <?php foreach ($watchlistMovies as $movie): ?>
            <div class="col-sm-6 col-md-4 col-lg-3">
                <a href="movie_details.php?movie_id=<?= $movie['movie_id'] ?>" class="card">
                    <img src="<?= htmlspecialchars($movie['image_url']) ?>" class="card-img-top" alt="<?= htmlspecialchars($movie['title']) ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($movie['title']) ?></h5>
                        <p class="card-text">Genres: <?= htmlspecialchars($movie['genre_names']) ?></p>
                    </div>
                </a>
                <!-- Form for removing the movie from the watchlist -->
                <form action="remove_watchlist.php" method="POST">
                    <input type="hidden" name="movie_id" value="<?= $movie['movie_id'] ?>">
                    <button type="submit" class="btn btn-danger btn-block mt-2">Remove from Watchlist</button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
    <h1>Recommended for You</h1>
    <div class="row">
        <?php if (empty($recommendedMovies)): ?>
            <div class="col-12 text-center">
                <p>No recommendations available.</p>
            </div>
        <?php else: ?>
            <?php foreach ($recommendedMovies as $movie): ?>
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <a href="movie_details.php?movie_id=<?= $movie['movie_id'] ?>" class="card">
                        <img src="<?= htmlspecialchars($movie['image_url']) ?>" class="card-img-top" alt="<?= htmlspecialchars($movie['title']) ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($movie['title']) ?></h5>
                            <p class="card-text">Genres: <?= htmlspecialchars($movie['genre_names']) ?></p>
                            <p class="card-text">Similarity Score: <?= htmlspecialchars($movie['similarity_score']) ?></p>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
