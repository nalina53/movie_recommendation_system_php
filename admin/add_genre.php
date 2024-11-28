<?php
session_start();
include '../includes/connection.php'; // Database connection

// Fetch genres from the database
$genresQuery = "SELECT * FROM genres";
$genresResult = $conn->query($genresQuery);

// Set the content for the body
$content = 'add_genre_content.php'; // This is the file where your form code will be

// Include the main dashboard template
include 'dashboard_flex.php';
?>

<!-- Separate content file for "add_genre_content.php" -->
<div class="container mt-5">
    <h2>Add New Genre</h2>
    <form action="../process/genre_process.php" method="POST">
        <div class="mb-3">
            <label for="genre_name" class="form-label">Genre Name</label>
            <input type="text" class="form-control" id="genre_name" name="genre_name" required>
        </div>
        <button type="submit" class="btn btn-success">Add Genre</button>
    </form>

    <h2 class="mt-5">Genres List</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Genre ID</th>
                <th>Genre Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($genresResult->num_rows > 0): ?>
                <?php $count = 1; ?>
                <?php while ($genreRow = $genresResult->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $count++; ?></td>
                        <td><?php echo htmlspecialchars($genreRow['genre_name']); ?></td>
                        <td>
                            <a href="../process/edit_genre.php?id=<?php echo $genreRow['genre_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="../process/delete_genre.php?id=<?php echo $genreRow['genre_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this genre?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" class="text-center">No genres found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
            </div>
            </body>
            </html>