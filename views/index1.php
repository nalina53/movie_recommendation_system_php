<?php
session_start(); // Start the session to access session variables
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

    <!-- Main Content -->
    <div class="container mt-5">
<!-- Image Slider Section -->
<h2 class="mt-5">Movie Slider</h2>
<div id="movieCarousel" class="carousel slide" data-bs-ride="carousel">
<div class="carousel-inner">
    <div class="carousel-item active">
        <img src="../images/UP.jpg" class="d-block w-100" alt="Movie 1" style="height: 60vh; object-fit: contain;">
    </div>
    <div class="carousel-item">
        <img src="../images/D.jpg" class="d-block w-100" alt="Movie 2" style="height: 60vh; object-fit: contain;">
    </div>
    <div class="carousel-item">
        <img src="../images/OIF.jpg" class="d-block w-100" alt="Movie 3" style="height: 60vh; object-fit: contain;">
    </div>
</div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#movieCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#movieCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>

         <!-- Image Section -->
         <h2 class="mb-4">Featured Movies</h2>
        <div class="row">
            <div class="col-md-4">
                <img src="../images/IO.jpg" class="img-fluid" alt="Movie 1">
            </div>
            <div class="col-md-4">
                <img src="../images/K2.jpg" class="img-fluid" alt="Movie 2">
            </div>
            <div class="col-md-4">
                <img src="../images/SS.jpg" class="img-fluid" alt="Movie 3">
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
