<?php
// Include the database connection
include '../includes/connection.php';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the movie ID from the POST request
    $movie_id = $_POST['movie_id'];
    $title = $conn->real_escape_string($_POST['title']);
    $release_date = $conn->real_escape_string($_POST['release_date']);
    $director = $conn->real_escape_string($_POST['director']);
    $description = $conn->real_escape_string($_POST['description']);
    $genres = isset($_POST['genres']) ? $_POST['genres'] : []; // This will be an array

    // Handle image upload
    $image_url = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        // Handle file upload and set $image_url
        $target_dir = "../uploads/movies/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        
        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image_url = $target_file; // Store the image path if upload is successful
        } else {
            echo "Error uploading file.";
            exit;
        }
    }

    // Update movie details
    $query = "UPDATE movies SET title = '$title', release_date = '$release_date', director = '$director', description = '$description'";

    if ($image_url) {
        $image_url = $conn->real_escape_string($image_url); // Escape the image URL
        $query .= ", image_url = '$image_url'"; // Add image_url only if it's provided
    }

    $query .= " WHERE movie_id = $movie_id";

    // Execute the update query
    if ($conn->query($query) === TRUE) {
        // Update genres
        // First, delete existing genres for this movie
        $delete_query = "DELETE FROM movie_genres WHERE movie_id = $movie_id";
        $conn->query($delete_query);

        // Insert selected genres
        if (!empty($genres)) {
            foreach ($genres as $genre_id) {
                $insert_query = "INSERT INTO movie_genres (movie_id, genre_id) VALUES ($movie_id, $genre_id)";
                $conn->query($insert_query);
            }
        }

        echo 'Movie updated successfully!';
        header('Location: ../admin/movies.php');
        exit;
    } else {
        echo 'Error updating movie: ' . $conn->error; // Output error message
    }
}
?>
