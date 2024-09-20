<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Movie</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Add New Movie</h2>
        <form action="process/add_movie.php" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="movieName" class="form-label">Movie Name</label>
                <input type="text" class="form-control" id="movieName" name="movieName" required>
            </div>
            <div class="mb-3">
                <label for="movieImage" class="form-label">Movie Image</label>
                <input type="file" class="form-control" id="movieImage" name="movieImage" accept="image/*" required>
            </div>
            <div class="mb-3">
                <label for="movieDescription" class="form-label">Description</label>
                <textarea class="form-control" id="movieDescription" name="movieDescription" rows="4" required></textarea>
            </div>
            <div class="mb-3">
                <label for="movieActors" class="form-label">Actors</label>
                <input type="text" class="form-control" id="movieActors" name="movieActors" required>
            </div>
            <div class="mb-3">
                <label for="movieDirector" class="form-label">Director</label>
                <input type="text" class="form-control" id="movieDirector" name="movieDirector" required>
            </div>
             <!-- New Movie Category Select Dropdown -->
             <?php
// Include the database connection
include '../includes/connection.php';

// Fetch genres from the database
$query = "SELECT genre_id, genre_name FROM genres";
$result = $conn->query($query);
?>

<div class="mb-3">
    <label for="movieCategory" class="form-label">Genre</label>
    <select class="form-select" id="movieCategory" name="movieCategory" required>
        <option value="">Select Genre</option>

        <?php
        // Loop through the genres and create an option for each one
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<option value="' . $row['genre_id'] . '">' . htmlspecialchars($row['genre_name']) . '</option>';
            }
        } else {
            echo '<option value="">No genres available</option>';
        }
        ?>

    </select>
</div>

            <div class="mb-3">
                <label for="release_date" class="form-label">Release Date</label>
                <input type="date" class="form-control" id="release_date" name="release_date" required>
            </div>
            
           
            <button type="submit" class="btn btn-primary">Add Movie</button>
        </form>
    </div>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
