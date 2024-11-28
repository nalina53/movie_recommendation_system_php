<?php include 'dashboard.php'; ?>
<?php
// Include the database connection
include '../includes/connection.php';

// Check if a movie ID is provided in the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $movie_id = $_GET['id'];

    // SQL query to fetch the movie details
    $query = "
        SELECT 
            movies.title, 
            movies.release_date, 
            movies.director, 
            movies.description, 
            images.image_url, 
            GROUP_CONCAT(genres.genre_id) AS genre_ids
        FROM movies
        LEFT JOIN images ON movies.movie_id = images.movie_id
        LEFT JOIN movie_genres ON movies.movie_id = movie_genres.movie_id
        LEFT JOIN genres ON movie_genres.genre_id = genres.genre_id
        WHERE movies.movie_id = ?
        GROUP BY movies.movie_id
    ";

    // Prepare the query
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param('i', $movie_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $movie = $result->fetch_assoc();
    } else {
        echo "Failed to fetch movie details.";
    }
}

// Update the movie details if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $release_date = $_POST['release_date'];
    $director = $_POST['director'];
    $description = $_POST['description'];
    $genres = $_POST['genres']; // Array of selected genre IDs
    $image_url = $_POST['image_url'];

    // Update the movie details in the database
    $update_movie_query = "
        UPDATE movies
        SET title = ?, release_date = ?, director = ?, description = ?
        WHERE movie_id = ?
    ";

    $update_image_query = "
        UPDATE images
        SET image_url = ?
        WHERE movie_id = ?
    ";

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Update movie details
        if ($stmt = $conn->prepare($update_movie_query)) {
            $stmt->bind_param('ssssi', $title, $release_date, $director, $description, $movie_id);
            $stmt->execute();
        }

        // Update image URL
        if ($stmt = $conn->prepare($update_image_query)) {
            $stmt->bind_param('si', $image_url, $movie_id);
            $stmt->execute();
        }

        // Delete old genre associations
        $conn->query("DELETE FROM movie_genres WHERE movie_id = $movie_id");

        // Insert new genre associations
        $insert_genre_query = "INSERT INTO movie_genres (movie_id, genre_id) VALUES (?, ?)";
        foreach ($genres as $genre_id) {
            if ($stmt = $conn->prepare($insert_genre_query)) {
                $stmt->bind_param('ii', $movie_id, $genre_id);
                $stmt->execute();
            }
        }

        // Commit the transaction
        $conn->commit();

        echo "Movie updated successfully!";
    } catch (Exception $e) {
        // Rollback the transaction if something goes wrong
        $conn->rollback();
        echo "Error updating movie: " . $e->getMessage();
    }
}
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">

<div class="container mt-5">
    <h2>Edit Movie</h2>

    <?php if (isset($movie)): ?>
        <form method="post" action="../process/update_movie.php" enctype="multipart/form-data">
            <!-- Hidden field to pass the movie ID -->
            <input type="hidden" name="movie_id" value="<?php echo $movie_id; ?>">

            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($movie['title']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="release_date" class="form-label">Release Date</label>
                <input type="date" class="form-control" id="release_date" name="release_date" value="<?php echo htmlspecialchars($movie['release_date']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="director" class="form-label">Director</label>
                <input type="text" class="form-control" id="director" name="director" value="<?php echo htmlspecialchars($movie['director']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" required><?php echo htmlspecialchars($movie['description']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Upload New Image</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                <small>Current Image: <img src="<?php echo htmlspecialchars($movie['image_url']); ?>" alt="Current Movie Image" width="100"></small>
            </div>
            <div class="mb-3">
    <label class="form-label">Genres</label>
    <div>
        <?php
        // Fetch all genres to display as options
        $genres_query = "SELECT genre_id, genre_name FROM genres";
        $genres_result = $conn->query($genres_query);

        // Split current movie genre IDs into an array
        $current_genres = explode(',', $movie['genre_ids']); // Assuming genre_ids is a comma-separated string

        while ($genre = $genres_result->fetch_assoc()):
        ?>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" 
                       id="genre_<?php echo $genre['genre_id']; ?>" 
                       name="genres[]" 
                       value="<?php echo $genre['genre_id']; ?>" 
                       <?php if (in_array($genre['genre_id'], $current_genres)) echo 'checked'; ?>>
                <label class="form-check-label" for="genre_<?php echo $genre['genre_id']; ?>">
                    <?php echo htmlspecialchars($genre['genre_name']); ?>
                </label>
            </div>
        <?php endwhile; ?>
    </div>
</div>



            <button type="submit" class="btn btn-primary">Update Movie</button>
        </form>
    <?php else: ?>
        <p>Movie not found.</p>
    <?php endif; ?>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
