<?php include 'dashboard.php'; ?>
<?php
// Include the database connection
include '../includes/connection.php';

// Set the number of results per page
$results_per_page = 10;

// Determine which page number the user is currently on
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;

// Calculate the starting limit for the SQL query
$starting_limit = ($page - 1) * $results_per_page;

// SQL query to fetch movie details, image URL, and genres with pagination
$query = "
    SELECT 
        movies.movie_id,
        movies.title, 
        movies.release_date, 
        movies.director, 
        movies.description,
        images.image_url, 
        GROUP_CONCAT(genres.genre_name) AS genres
    FROM movies
    LEFT JOIN images ON movies.movie_id = images.movie_id
    LEFT JOIN movie_genres ON movies.movie_id = movie_genres.movie_id
    LEFT JOIN genres ON movie_genres.genre_id = genres.genre_id
    GROUP BY movies.movie_id
    LIMIT $starting_limit, $results_per_page
";
$result = $conn->query($query);

// Find out the total number of results in the database
$total_query = "SELECT COUNT(*) AS total FROM movies";
$total_result = $conn->query($total_query);
$total_row = $total_result->fetch_assoc();
$total_pages = ceil($total_row['total'] / $results_per_page); // Calculate total pages
?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Movies List</h2>

    <?php if ($result->num_rows > 0): ?>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Genres</th>
                        <th>Release Date</th>
                        <th>Director</th>
                        <th>Description</th>
                        <th>Action</th> <!-- Action column -->
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="Movie Image" class="img-thumbnail" width="100"></td>
                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                            <td><?php echo htmlspecialchars($row['genres']); ?></td>
                            <td><?php echo htmlspecialchars($row['release_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['director']); ?></td>
                            <td><?php echo htmlspecialchars($row['description']); ?></td>
                            <td>
                                <!-- Edit button links to an edit form -->
                                <a href="edit_movies.php?id=<?php echo $row['movie_id']; ?>" class="btn btn-sm btn-warning mb-1">Edit</a>
                                <!-- Delete button links to delete_movie.php with a confirmation -->
                                <a href="../process/delete_movie.php?id=<?php echo $row['movie_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this movie?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination links -->
        <nav aria-label="Movies pagination">
            <ul class="pagination justify-content-center">
                <?php if ($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $page - 1; ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>

                <?php if ($page < $total_pages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $page + 1; ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    <?php else: ?>
        <div class="alert alert-warning text-center" role="alert">
            No movies found.
        </div>
    <?php endif; ?>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
