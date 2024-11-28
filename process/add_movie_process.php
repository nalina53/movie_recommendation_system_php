<?php
// Include the database connection
include '../includes/connection.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Retrieve the form data
    $movieName = $_POST['movieName'];
    $movieDescription = $_POST['movieDescription'];
    $movieActors = $_POST['movieActors'];
    $movieDirector = $_POST['movieDirector'];
    $releaseDate = $_POST['release_date'];
    $movieGenres = $_POST['movieGenres']; // Handle multiple genres

    // Handle the image upload
    $targetDir = "../public/uploads/movies/";
    $imageFileName = basename($_FILES["movieImage"]["name"]);
    $targetFilePath = $targetDir . $imageFileName;
    $imageFileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
    $uploadOk = 1;

    // Check if the image file is a valid image
    $check = getimagesize($_FILES["movieImage"]["tmp_name"]);
    if ($check === false) {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check file size (limit: 5MB)
    if ($_FILES["movieImage"]["size"] > 5000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats (jpg, png, jpeg, gif)
    $allowedFileTypes = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($imageFileType, $allowedFileTypes)) {
        echo "Sorry, only JPG, JPEG, PNG, and GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if everything is OK to upload
    if ($uploadOk == 1) {
        // Try to move the uploaded file to the target directory
        if (move_uploaded_file($_FILES["movieImage"]["tmp_name"], $targetFilePath)) {

            // Insert the movie data into the 'movies' table (no genre_id here)
            $stmt = $conn->prepare("INSERT INTO movies (title, release_date, director, description) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $movieName, $releaseDate, $movieDirector, $movieDescription);

            // Execute the query to insert movie data
            if ($stmt->execute()) {
                $movieId = $conn->insert_id; // Get the last inserted movie ID
                
                // Insert the image URL into the 'images' table
                $stmtImg = $conn->prepare("INSERT INTO images (movie_id, image_url) VALUES (?, ?)");
                $stmtImg->bind_param("is", $movieId, $targetFilePath);
                $stmtImg->execute();
                
                // Insert the selected genres into the 'movie_genres' table
                if (!empty($movieGenres)) {
                    foreach ($movieGenres as $genreId) {
                        $stmtGenre = $conn->prepare("INSERT INTO movie_genres (movie_id, genre_id) VALUES (?, ?)");
                        $stmtGenre->bind_param("ii", $movieId, $genreId);
                        $stmtGenre->execute();
                        $stmtGenre->close();
                    }
                }

                echo "<script>alert('Movie added successfully!'); window.location.href='../admin/dashboard.php';</script>";
            } else {
                echo "<script>alert('Error: " . $stmt->error . "'); window.location.href='../admin/dashboard.php';</script>";
            }

            $stmt->close();
            $stmtImg->close();
        } else {
            echo "<script>alert('Sorry, there was an error uploading your file.'); window.location.href='admin/dashboard.php';</script>";
        }
    }
}

// Close the database connection
$conn->close();
?>
