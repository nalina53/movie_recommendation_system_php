<?php
session_start();
include '../includes/connection.php'; // Include your database connection file
include '../includes//cosine_similarity.php'; // Adjust the path to your cosine_similarity.php file

// Add this line to output the HTML header with the CSS link
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="path/to/your/cards.css"> <!-- Link to your CSS file -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> <!-- Bootstrap CSS -->
    <title>Movie Recommendations</title>
    <style>
        .card-img-top {
            height: 200px; /* Set a fixed height for the images */
            object-fit: cover; /* This will crop the images to fit the height */
        }
        .card-body {
            height: 150px; /* Set a fixed height for card body */
            overflow: hidden; /* Hide overflow to avoid text spilling */
        }
        .card {
            cursor: pointer; /* Change cursor to pointer */
            transition: transform 0.2s; /* Smooth transition for hover effect */
        }
        .card:hover {
            transform: scale(1.05); /* Slightly enlarge the card on hover */
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <?php
        if (isset($_GET['query'])) {
            $searchQuery = htmlspecialchars($_GET['query']);

            // Replace multiple spaces or special characters with a single space for better matching
            $searchQuery = preg_replace('/[^a-zA-Z0-9]/', ' ', $searchQuery);
            $searchQuery = trim($searchQuery);

            // Fetch movie details using REPLACE() and SOUNDEX() for better search flexibility
            $stmt = $conn->prepare("
                SELECT * FROM movies
                WHERE REPLACE(SOUNDEX(title), ' ', '') = REPLACE(SOUNDEX(?), ' ', '')
                   OR title LIKE ?
            ");

            // Prepare search term for LIKE clause
            $searchTerm = "%" . str_replace(' ', '', $searchQuery) . "%";
            $stmt->bind_param("ss", $searchQuery, $searchTerm);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $movie = $result->fetch_assoc();
                $movieId = $movie['movie_id'];

                // Log search history if user is logged in and a movie is found
                if (isset($_SESSION['user_id'])) { // Assuming 'user_id' is stored in session upon login
                    $userId = $_SESSION['user_id'];
                    $historyStmt = $conn->prepare("INSERT INTO search_history (user_id, search_query, movie_id) VALUES (?, ?, ?)");
                    $historyStmt->bind_param("isi", $userId, $searchQuery, $movieId);
                    $historyStmt->execute();
                }

                // Fetch image for the movie from the images table
                $imageStmt = $conn->prepare("SELECT image_url FROM images WHERE movie_id = ?");
                $imageStmt->bind_param("i", $movieId);
                $imageStmt->execute();
                $imageResult = $imageStmt->get_result();
                $movieImage = $imageResult->fetch_assoc()['image_url'];

                // Fetch genres for the movie
                $genreStmt = $conn->prepare("
                    SELECT genre_name 
                    FROM genres 
                    JOIN movie_genres ON genres.genre_id = movie_genres.genre_id 
                    WHERE movie_genres.movie_id = ?
                ");
                $genreStmt->bind_param("i", $movieId);
                $genreStmt->execute();
                $genreResult = $genreStmt->get_result();

                $genres = [];
                while ($row = $genreResult->fetch_assoc()) {
                    $genres[] = $row['genre_name'];
                }

                // Display the movie details including the image
                echo "<h1>" . htmlspecialchars($movie['title']) . "</h1>";
                echo "<div class='row mb-4'>";
                echo "<div class='col-md-4'>";
                echo "<div class='card mb-4 shadow-sm'>";
                echo "<a href='movie_details.php?movie_id=" . $movieId . "'>"; // Link to movie details
                echo "<img src='" . htmlspecialchars($movieImage) . "' alt='" . htmlspecialchars($movie['title']) . "' class='card-img-top'>";
                echo "</a></div></div>";
                echo "<div class='col-md-8'>";
                echo "<div class='card-body'>";
                echo "<h5 class='card-title'>Genres:</h5>";
                echo "<p>" . implode(", ", $genres) . "</p>";
                echo "</div></div></div>";

                // Fetch all movies to calculate cosine similarity
                $allMoviesStmt = $conn->prepare("SELECT m.movie_id FROM movies m WHERE m.movie_id != ?");
                $allMoviesStmt->bind_param("i", $movieId);
                $allMoviesStmt->execute();
                $allMoviesResult = $allMoviesStmt->get_result();

                $recommendations = [];

                while ($recMovie = $allMoviesResult->fetch_assoc()) {
                    // Fetch genres for each movie
                    $recMovieId = $recMovie['movie_id'];
                    $recGenreStmt = $conn->prepare("
                        SELECT genre_name 
                        FROM genres 
                        JOIN movie_genres ON genres.genre_id = movie_genres.genre_id 
                        WHERE movie_genres.movie_id = ?
                    ");
                    $recGenreStmt->bind_param("i", $recMovieId);
                    $recGenreStmt->execute();
                    $recGenreResult = $recGenreStmt->get_result();

                    $recGenres = [];
                    while ($recRow = $recGenreResult->fetch_assoc()) {
                        $recGenres[] = $recRow['genre_name'];
                    }

                    // Calculate cosine similarity
                    $similarityScore = calculateCosineSimilarity($genres, $recGenres);

                    // Store the recommendation if the score is greater than 0
                    if ($similarityScore > 0) {
                        $recommendations[] = [
                            'movie_id' => $recMovieId,
                            'similarity_score' => $similarityScore
                        ];
                    }
                }

                // Sort recommendations by similarity score in descending order
                usort($recommendations, function($a, $b) {
                    return $b['similarity_score'] <=> $a['similarity_score'];
                });

                // Limit to top 5 recommendations
                $recommendations = array_slice($recommendations, 0, 5);

                // Display recommendations in cards
                echo "<h2 class='mt-5'>Recommended Movies</h2>";
                echo "<div class='row'>";
                if (!empty($recommendations)) {
                    foreach ($recommendations as $rec) {
                        // Fetch movie details for recommendations
                        $recMovieStmt = $conn->prepare("SELECT title, image_url FROM movies m JOIN images i ON m.movie_id = i.movie_id WHERE m.movie_id = ?");
                        $recMovieStmt->bind_param("i", $rec['movie_id']);
                        $recMovieStmt->execute();
                        $recMovieResult = $recMovieStmt->get_result();
                        $recMovieData = $recMovieResult->fetch_assoc();

                        echo "<div class='col-md-3'>";
                        echo "<div class='card mb-4 shadow-sm'>";
                        echo "<a href='movie_details.php?movie_id=" . $rec['movie_id'] . "'>"; // Link to movie details
                        echo "<img src='" . htmlspecialchars($recMovieData['image_url']) . "' alt='" . htmlspecialchars($recMovieData['title']) . "' class='card-img-top'>";
                        echo "</a>";
                        echo "<div class='card-body'>";
                        echo "<h5 class='card-title'>" . htmlspecialchars($recMovieData['title']) . "</h5>";
                        echo "<p class='card-text'>Similarity Score: " . number_format($rec['similarity_score'], 2) . "</p>";
                        echo "</div></div></div>";
                    }
                } else {
                    echo "<p>No recommendations available.</p>";
                }
                echo "</div>";
            } else {
                echo "<p>No movies found.</p>";
            }
        } else {
            echo "<p>Please enter a search term.</p>";
        }
        ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
