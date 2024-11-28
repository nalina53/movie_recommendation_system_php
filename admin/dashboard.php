<?php
include 'dashboard_flex.php';
include '../includes/connection.php';

// Query to count users with the role 'user'
$userCountQuery = "SELECT COUNT(*) AS user_count FROM users WHERE role = 'user'";
$userCountResult = $conn->query($userCountQuery);
$userCount = $userCountResult->fetch_assoc()['user_count'];

// Query to count the total number of movies
$movieCountQuery = "SELECT COUNT(*) AS movie_count FROM movies";
$movieCountResult = $conn->query($movieCountQuery);
$movieCount = $movieCountResult->fetch_assoc()['movie_count'];

$conn->close();
?>

<!-- Add a top margin to the container -->
<div class="container mt-5"> <!-- Change mt-4 to mt-5 for more space -->
    <div class="row">
        <!-- Card for Number of Users -->
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body bg-primary text-white text-center rounded">
                    <div class="card-icon mb-3">
                        <i class="fas fa-users fa-3x"></i>
                    </div>
                    <h5 class="card-title">Total Users <strong><?php echo $userCount; ?></strong></h5>
                  
                </div>
            </div>
        </div>
        <!-- Card for Number of Movies -->
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body bg-success text-white text-center rounded">
                    <div class="card-icon mb-3">
                        <i class="fas fa-film fa-3x"></i>
                    </div>
                    <h5 class="card-title">Total Movies<strong><?php echo $movieCount; ?></h5>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
