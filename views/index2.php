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
                       aria-label="Search" required><div>
                <button class="btn btn-outline-success" type="submit">Search</button></div>
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

<!-- Random Movie Recommendations -->
<div class="container mt-5">
    <h1 class="mb-4">Random Movie Recommendations</h1>

    <?php
    // Fetch 12 random distinct movies from the database
    $randomMoviesQuery = "
        SELECT DISTINCT movies.movie_id, movies.title, images.image_url 
        FROM movies 
        JOIN images ON movies.movie_id = images.movie_id 
        JOIN movie_genres ON movies.movie_id = movie_genres.movie_id 
        JOIN genres ON movie_genres.genre_id = genres.genre_id 
        ORDER BY RAND() 
        LIMIT 12";

    $randomMoviesResult = $conn->query($randomMoviesQuery);

    echo "<div class='row gy-4'>";

    if ($randomMoviesResult->num_rows > 0) {
        while ($movieRow = $randomMoviesResult->fetch_assoc()) {
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
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="../process/watchlist_process.php?movie_id=<?php echo $movieRow['movie_id']; ?>&user_id=<?php echo $_SESSION['user_id']; ?>" class="btn btn-primary">Add to Watchlist</a>
                        <?php else: ?>
                            <p class="text-muted">Please log in to add to your watchlist.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php
        }
    } else {
        echo "<p>No movies found.</p>";
    }

    echo "</div>"; // Close row
    ?>
</div>

<!-- Bootstrap JS and Custom JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../public/js/scripts.js"></script>
<script>
    let slideIndex = 0;
    const slides = document.querySelector('.slides');
    const totalSlides = slides.children.length;

    function moveSlide(n) {
        slideIndex += n;
        if (slideIndex < 0) {
            slideIndex = totalSlides - 1;
        } else if (slideIndex >= totalSlides) {
            slideIndex = 0;
        }
        slides.style.transform = `translateX(-${slideIndex * 100}%)`;
    }

    setInterval(() => {
        moveSlide(1);
    }, 4000); // Auto-slide every 4 seconds
</script>
</body>
</html>
