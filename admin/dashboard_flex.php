<!-- dashboard.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .sidenav {
            height: 100vh;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
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
        }
    </style>
</head>
<body>

    <!-- Sidenav -->
    <div class="sidenav">
    <h4><a class="navbar-brand" href="dashboard.php">Admin Dashboard</a></h4>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="users.php">Users</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="add_genre.php">Add Genre</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="add_movies.php">Add Movies</a>
            </li>
            <li class="nav-item">
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
