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
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .slider {
            position: relative;
            max-width: 600px;
            margin: auto;
            overflow: hidden;
        }

        .slides {
            display: flex;
            transition: transform 0.5s ease;
        }

        .slide {
            min-width: 100%;
            height: 500px;
            box-sizing: border-box;
            text-align: center;
        }

        .slide img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        button {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background-color: rgba(255, 255, 255, 0.7);
            border: none;
            padding: 10px;
            cursor: pointer;
        }

        .prev {
            left: 10px;
        }

        .next {
            right: 10px;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
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

    <div class="slider">
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
            <!-- Add more slides as needed -->
        </div>
        <button class="prev" onclick="moveSlide(-1)">&#10094;</button>
        <button class="next" onclick="moveSlide(1)">&#10095;</button>
    </div>

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
                $moviesQuery = "SELECT movies.movie_id, movies.title, movies.description, images.image_url 
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
                            <a href="movie_details.php?movie_id=<?php echo $movieRow['movie_id']; ?>" class="card mb-4 text-decoration-none text-dark">
                                <img src="<?php echo $movieRow['image_url']; ?>" class="card-img-top" alt="Movie Image">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($movieRow['title']); ?></h5>
                                    <p class="card-text"><?php echo htmlspecialchars($movieRow['description']); ?></p>
                                </div>
                            </a>
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

    <script>
        let currentSlide = 0;

        function showSlide(index) {
            const slides = document.querySelectorAll('.slide');
            if (index >= slides.length) {
                currentSlide = 0;
            } else if (index < 0) {
                currentSlide = slides.length - 1;
            } else {
                currentSlide = index;
            }
            const offset = -currentSlide * 100;
            document.querySelector('.slides').style.transform = `translateX(${offset}%)`;
        }

        function moveSlide(direction) {
            showSlide(currentSlide + direction);
        }

        // Optionally, auto slide every 3 seconds
        setInterval(() => moveSlide(1), 3000);
    </script>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>

</html>

<?php
$conn->close(); // Close the database connection
?>
