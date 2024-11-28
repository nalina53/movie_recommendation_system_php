<?php
// Include the database connection file
include '../includes/connection.php'; // Adjust the path if necessary
include '../includes/cosine_similarity.php'; // Include the external algorithm file
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Genres</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h1>Select Genres</h1>

    <?php
    // Fetch genres
    $genresQuery = "SELECT DISTINCT genre_id, genre_name FROM genres";
    $genresResult = $conn->query($genresQuery);

    // Retrieve selected genres if the form is submitted
    $selectedGenres = isset($_POST['genres']) ? $_POST['genres'] : [];

    // Check if there are genres and display checkboxes
    if ($genresResult->num_rows > 0) {
        echo '<form action="" method="POST">';
        while ($genreRow = $genresResult->fetch_assoc()) {
            $genreId = $genreRow['genre_id'];
            $genreName = htmlspecialchars($genreRow['genre_name']);
            $isChecked = in_array($genreId, $selectedGenres) ? 'checked' : '';
            echo '<div class="form-check">';
            echo '<input class="form-check-input" type="checkbox" name="genres[]" value="' . $genreId . '" id="genre' . $genreId . '" ' . $isChecked . '>';
            echo '<label class="form-check-label" for="genre' . $genreId . '">' . $genreName . '</label>';
            echo '</div>';
        }
        echo '<button type="submit" class="btn btn-primary mt-3">Get Recommendations</button>';
        echo '</form>';
    } else {
        echo "<p>No genres found.</p>";
    }
    ?>

    <?php
    // Process the form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['genres'])) {
        // Fetch all movies with genres
        $moviesQuery = "
            SELECT movies.movie_id, movies.title, images.image_url, GROUP_CONCAT(genres.genre_name) AS genre_names, GROUP_CONCAT(genres.genre_id) AS genre_ids 
            FROM movies 
            JOIN movie_genres ON movies.movie_id = movie_genres.movie_id 
            JOIN genres ON movie_genres.genre_id = genres.genre_id 
            JOIN images ON movies.movie_id = images.movie_id
            GROUP BY movies.movie_id
        ";
        $moviesResult = $conn->query($moviesQuery);

        // Store the recommendations
        $recommendations = [];

        // Calculate cosine similarity for each movie
        if ($moviesResult->num_rows > 0) {
            while ($movieRow = $moviesResult->fetch_assoc()) {
                $movieGenres = explode(',', $movieRow['genre_ids']);
                
                // Use the external function to calculate cosine similarity
                $cosineSimilarity = calculateCosineSimilarity($selectedGenres, $movieGenres);
                
                if ($cosineSimilarity > 0) { // Only add movies with a non-zero similarity score
                    $recommendations[] = [
                        'movie_id' => $movieRow['movie_id'],
                        'title' => $movieRow['title'],
                        'image_url' => $movieRow['image_url'],
                        'genres' => $movieRow['genre_names'],
                        'similarity' => $cosineSimilarity
                    ];
                }
            }
        }

        // Sort recommendations by similarity in descending order
        usort($recommendations, function($a, $b) {
            return $b['similarity'] <=> $a['similarity'];
        });

        // Display recommendations
        if (count($recommendations) > 0) { 
            echo "<h2 class='mt-5'>Recommended Movies</h2>";
            echo "<div class='row'>";
            foreach ($recommendations as $recommendation) {
                echo "<div class='col-md-4 mb-3'>";
                echo "<div class='card'>";
                echo "<img src='" . $recommendation['image_url'] . "' class='card-img-top' alt='Movie Image'>";
                echo "<div class='card-body'>";

                // Make the title a clickable link to movie_details.php and pass movie_id via GET
                echo "<h5 class='card-title'>";
                echo "<a href='movie_details.php?movie_id=" . $recommendation['movie_id'] . "'>";
                echo htmlspecialchars($recommendation['title']) . "</a></h5>";

                echo "<p class='card-text'><strong>Genres:</strong> " . htmlspecialchars($recommendation['genres']) . "</p>";
                echo "<p class='card-text'><strong>Similarity:</strong> " . round($recommendation['similarity'], 2) . "</p>";
                echo "</div></div></div>";
            }
            echo "</div>";
        } else {
            echo "<p>No recommendations found based on your selected genres.</p>";
        }
    }

    $conn->close(); // Close the database connection
    ?>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
