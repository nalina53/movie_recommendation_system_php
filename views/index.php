<?php
session_start(); // Start the session to access session variables
include '../includes/connection.php'; // Include your database connection

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
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">MovieRec</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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
                <form class="d-flex me-3">
                    <input class="form-control me-2" type="search" placeholder="Search movies" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
                <!-- Conditional Login/Signup or Username/Logout -->
                <ul class="navbar-nav">
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

    <!-- Movies Section -->
    <div class="container mt-5">
        <h1>Movie Recommendations</h1>
        
        <?php
        // Check if there are genres and loop through each genre
        if ($genresResult->num_rows > 0) {
            while ($genreRow = $genresResult->fetch_assoc()) {
                $genreId = $genreRow['genre_id'];
                $genreName = $genreRow['genre_name'];
                echo "<h2>$genreName</h2>";
                
                // Fetch movies for the current genre
                $moviesQuery = "SELECT movies.title, movies.description, images.image_url 
                                FROM movies 
                                JOIN images ON movies.movie_id = images.movie_id 
                                WHERE movies.genre_id = $genreId";
                $moviesResult = $conn->query($moviesQuery);
                
                echo "<div class='row'>";
                
                // Check if there are movies for the current genre
                if ($moviesResult->num_rows > 0) {
                    while ($movieRow = $moviesResult->fetch_assoc()) {
                        ?>
                        <div class="col-md-4">
                            <div class="card mb-4">
                                <img src="<?php echo $movieRow['image_url']; ?>" class="card-img-top" alt="Movie Image">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $movieRow['title']; ?></h5>
                                    <p class="card-text"><?php echo $movieRow['description']; ?></p>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "<p>No movies found in this genre.</p>";
                }
                
                echo "</div>"; // Close row
            }
        } else {
            echo "<p>No genres found.</p>";
        }
        ?>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
