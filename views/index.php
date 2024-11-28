<?php
session_start(); // Start the session to access session variables
include '../includes/connection.php'; // Include your database connection
include '../includes/cosine_similarity.php'; 

// Fetch all genres
$genresQuery = "SELECT * FROM genres WHERE genre_name IN ('Romance', 'Horror', 'Thriller')";
$genresResult = $conn->query($genresQuery);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Recommendation System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- External CSS -->
    <link href="../public/css/cards.css" rel="stylesheet">
</head>

<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light py-3">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">MovieRec</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">About</a>
                </li>
            </ul>

            <!-- Search Bar -->
            <form class="d-flex mx-auto" role="search" action="search.php" method="GET" style="width: 400px;">
                <input class="form-control me-1" type="search" name="query" placeholder="Search movies"
                       aria-label="Search" required>
                <div><button class="btn btn-outline-success" type="submit">Search</button></div>
            </form>

            <ul class="navbar-nav">
                <li class="nav-item">
                    <?php if (isset($_SESSION['username'])): ?>
                        <a class="nav-link" href="watchlist.php">Watchlist</a>
                    <?php else: ?>
                        <div class="dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="watchlistDropdown" role="button"
                               data-bs-toggle="dropdown" aria-expanded="false">
                                Watchlist
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="watchlistDropdown">
                                <li><a class="dropdown-item" href="login.php">Sign In</a></li>
                                <li><a class="dropdown-item" href="signup.php">Create an Account</a></li>
                            </ul>
                        </div>
                    <?php endif; ?>
                </li>
                <a class="nav-link" href="recommendations.php">Recommendations</a>

                <!-- Conditional Login/Signup or Username/Logout -->
                <?php if (isset($_SESSION['username'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../process/logout.php">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="signup.php">Sign Up</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Movie Slider -->
<div class="slider mt-5">
    <div class="slides">
        <div class="slide active">
            <img src="../images/D.jpg" alt="Movie 1">
            <h2>Movie Title 1</h2>
        </div>
        <div class="slide">
            <img src="../images/INC.jpg" alt="Movie 2">
            <h2>Movie Title 2</h2>
        </div>
        <div class="slide">
            <img src="../images/IO.jpg" alt="Movie 3">
            <h2>Movie Title 3</h2>
        </div>
    </div>
    <button class="prev" onclick="moveSlide(-1)">&#10094;</button>
    <button class="next" onclick="moveSlide(1)">&#10095;</button>
</div>

<div class="container mt-5">
    <h1 class="mb-4">Movies For You</h1>

    <?php
    if (isset($_SESSION['user_id'])) {
        // Step 1: Fetch the genres from the user's search history
        $searchHistoryQuery = "
            SELECT DISTINCT g.genre_id
            FROM search_history sh
            JOIN movies m ON sh.movie_id = m.movie_id
            JOIN movie_genres mg ON m.movie_id = mg.movie_id
            JOIN genres g ON mg.genre_id = g.genre_id
            WHERE sh.user_id = ?";
        $stmt = $conn->prepare($searchHistoryQuery);
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $searchHistoryResult = $stmt->get_result();

        // Store selected genre IDs
        $selectedGenres = [];
        while ($row = $searchHistoryResult->fetch_assoc()) {
            $selectedGenres[] = $row['genre_id'];
        }
        $stmt->close();

        // Step 2: Fetch the user's watchlist
        $watchlistQuery = "
            SELECT movie_id
            FROM watchlist
            WHERE user_id = ?";
        $stmt = $conn->prepare($watchlistQuery);
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $watchlistResult = $stmt->get_result();

        // Store movie IDs in the watchlist
        $watchlistMovies = [];
        while ($row = $watchlistResult->fetch_assoc()) {
            $watchlistMovies[] = $row['movie_id'];
        }
        $stmt->close();

        // Step 3: Fetch recommended movies based on selected genres from search history
        if (!empty($selectedGenres)) {
            // Create a comma-separated list of genre IDs for the query
            $genreIds = implode(',', array_map('intval', $selectedGenres)); // Sanitize the IDs

            $recommendationQuery = "
                SELECT mh.movie_id, mh.title, i.image_url
                FROM movies mh
                JOIN images i ON mh.movie_id = i.movie_id
                JOIN movie_genres mg ON mh.movie_id = mg.movie_id
                WHERE mg.genre_id IN ($genreIds)
                GROUP BY mh.movie_id
                HAVING COUNT(DISTINCT mg.genre_id) > 0";

            $recommendedMoviesResult = $conn->query($recommendationQuery);

            echo "<div class='row gy-4'>";

            if ($recommendedMoviesResult->num_rows > 0) {
                while ($movieRow = $recommendedMoviesResult->fetch_assoc()) {
                    $isInWatchlist = in_array($movieRow['movie_id'], $watchlistMovies); // Check if the movie is in the watchlist
                    ?>
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <!-- Movie Image with a link to the details page -->
                            <a href="movie_details.php?movie_id=<?php echo $movieRow['movie_id']; ?>">
                                <img src="<?php echo $movieRow['image_url']; ?>" class="card-img-top" alt="Movie Image">
                            </a>
                            <div class="card-body">
                                <!-- Movie Title with a link to the details page -->
                                <h5 class="card-title">
                                    <a href="movie_details.php?movie_id=<?php echo $movieRow['movie_id']; ?>">
                                        <?php echo $movieRow['title']; ?>
                                    </a>
                                </h5>

                                <!-- Button Section -->
                                <div class="mt-3 text-center">
                                    <?php if (isset($_SESSION['user_id'])): ?>
                                        <?php if ($isInWatchlist): ?>
                                            <a  class="btn btn-primary">Added to Watchlist</a>
                                        <?php else: ?>
                                            <a href="../process/watchlist_process.php?movie_id=<?php echo $movieRow['movie_id']; ?>&user_id=<?php echo $_SESSION['user_id']; ?>" class="btn btn-primary">Add to Watchlist</a>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <p class="text-muted">Please log in to add to your watchlist.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "<p>No recommendations available based on your search history.</p>";
            }

            echo "</div>"; // Close row
        } else {
            echo "<p>No search history found.</p>";
        }
    } else {
        // If the user is not logged in or has no search history, recommend random movies
        $randomMovieQuery = "
            SELECT mh.movie_id, mh.title, i.image_url
            FROM movies mh
            JOIN images i ON mh.movie_id = i.movie_id
            ORDER BY RAND()
            LIMIT 9";
        $randomMoviesResult = $conn->query($randomMovieQuery);

        echo "<div class='row gy-4'>";

        while ($randomMovieRow = $randomMoviesResult->fetch_assoc()) {
            ?>
            <div class="col-md-4">
                <div class="card mb-4">
                    <a href="movie_details.php?movie_id=<?php echo $randomMovieRow['movie_id']; ?>">
                        <img src="<?php echo $randomMovieRow['image_url']; ?>" class="card-img-top" alt="Movie Image">
                    </a>
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="movie_details.php?movie_id=<?php echo $randomMovieRow['movie_id']; ?>">
                                <?php echo $randomMovieRow['title']; ?>
                            </a>
                        </h5>
                    </div>
                </div>
            </div>
            <?php
        }

        echo "</div>"; // Close row
    }
    ?>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
<!-- External JS for Slider -->
<script src="../public/js/slider.js"></script>

</body>
</html>
