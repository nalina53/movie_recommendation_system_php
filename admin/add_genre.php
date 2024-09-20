<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Genre</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Add New Genre</h2>
        <form action="../process/genre_process.php" method="POST">
            <div class="mb-3">
                <label for="genre_name" class="form-label">Genre Name</label>
                <input type="text" class="form-control" id="genre_name" name="genre_name" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Genre</button>
        </form>
    </div>
</body>
</html>
