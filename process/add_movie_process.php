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
    $movieCategory = $_POST['movieCategory'];
    $releaseDate = $_POST['release_date'];
    
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
            
            // Prepare the SQL query to insert the movie data into the database
            $stmt = $conn->prepare("INSERT INTO movies (title, release_date, genre_id, director, description) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("ssiss", $movieName, $releaseDate, $movieCategory, $movieDirector, $movieDescription);

            // Execute the query to insert movie data
            if ($stmt->execute()) {
                $movieId = $conn->insert_id; // Get the last inserted movie ID
                
                // Insert the image URL into the images table
                $stmtImg = $conn->prepare("INSERT INTO images (movie_id, image_url) VALUES (?, ?)");
                $stmtImg->bind_param("is", $movieId, $targetFilePath);
                $stmtImg->execute();

                echo "Movie added successfully!";
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
            $stmtImg->close();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

// Close the database connection
$conn->close();
?>
