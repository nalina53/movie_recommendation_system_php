<<<<<<< HEAD
<!-- dashboard.php -->
=======
>>>>>>> be0e652117da6c8388e2c7176617d9d3d1674eb2
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<<<<<<< HEAD
    <title>Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .sidenav {
            height: 100vh;
=======
    <title>Admin Dashboard - Movie Recommendation System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- External CSS -->
    <link rel="stylesheet" href="../public/css/admin.css">
    <style>
        .main-content {
            margin-left: 250px; /* Adjust according to the sidebar width */
            padding: 20px;
        }
        .sidebar {
>>>>>>> be0e652117da6c8388e2c7176617d9d3d1674eb2
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
<<<<<<< HEAD
            background-color: #343a40;
            padding-top: 20px;
        }
        .sidenav a {
            padding: 10px 15px;
            text-decoration: none;
            font-size: 18px;
            color: #fff;
            display: block;
        }
        .sidenav a:hover {
            background-color: #495057;
        }
        .main-content {
            margin-left: 250px; /* Adjust based on sidenav width */
            padding: 20px;
        }
        .navbar {
            width: calc(100% - 250px);
            left: 250px;
            position: fixed;
            top: 0;
            z-index: 1;
            background-color: #6c757d;
            padding: 10px;
        }
        .navbar-brand, .navbar-nav .nav-link {
            color: #fff !important;
=======
            height: 100%;
            background-color: #343a40;
            color: #fff;
            padding-top: 20px;
        }
        .sidebar a {
            color: #fff;
            text-decoration: none;
        }
        .movie-card {
            margin-bottom: 20px;
        }
        .card-img-top {
            max-height: 300px;
            object-fit: cover;
>>>>>>> be0e652117da6c8388e2c7176617d9d3d1674eb2
        }
    </style>
</head>
<body>
<<<<<<< HEAD

    <!-- Sidenav -->
    <div class="sidenav">
    <h4><a class="navbar-brand" href="dashboard.php">Admin Dashboard</a></h4>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="users.php">Users</a>
=======
    <!-- Sidebar -->
    <div class="sidebar">
        <h4><a class="navbar-brand" href="dashboard.php">Admin Dashboard</a></h4>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="#users">Users</a>
>>>>>>> be0e652117da6c8388e2c7176617d9d3d1674eb2
            </li>
            <li class="nav-item">
                <a class="nav-link" href="add_genre.php">Add Genre</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="add_movies.php">Add Movies</a>
            </li>
            <li class="nav-item">
<<<<<<< HEAD
                <a class="nav-link" href="movies.php">Movies</a>
            </li>
        </ul>

    </div>

    <!-- Uppernav -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Admin Panel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../process/logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main content -->
    <div class="main-content">


    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
=======
                <a class="nav-link" href="#insert">Insert</a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Navbar -->
        <nav class="navbar navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="dashboard.php">Dashboard</a>
                <div class="d-flex">
                    <span class="navbar-text me-3">Welcome, <strong>Admin</strong></span>
                    <a class="btn btn-outline-light" href="../process/logout.php">Logout</a>
                </div>
            </div>
        </nav>

        <!-- Main Sections -->

    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
>>>>>>> be0e652117da6c8388e2c7176617d9d3d1674eb2
