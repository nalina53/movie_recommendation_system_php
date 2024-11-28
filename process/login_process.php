<?php
include '../includes/connection.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Retrieve and trim form data
    $formIdentifier = trim($_POST['identifier']);
    $formPassword = trim($_POST['password']);

    // Initialize an array to store error messages
    $errors = [];

    // Validate identifier (username or email)
    if (empty($formIdentifier)) {
        $errors[] = "Username or email is required.";
    } elseif (!filter_var($formIdentifier, FILTER_VALIDATE_EMAIL) && !preg_match('/^[a-zA-Z0-9_]+$/', $formIdentifier)) {
        $errors[] = "Invalid username or email format.";
    }

    // Validate password
    if (empty($formPassword)) {
        $errors[] = "Password is required.";
    } elseif (strlen($formPassword) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    }

    // Display errors if any
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p style='color: red;'>$error</p>";
        }
        exit();
    }

    // Prepare the query to find the user by username or email
    $sql = "SELECT user_id, username, email, password, role FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $formIdentifier, $formIdentifier);

    // Execute the query
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user exists
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($formPassword, $user['password'])) {
            // Password is correct, start a session and log the user in
            session_start();
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'admin') {
                header("Location: ../admin/dashboard.php"); // Redirect to admin dashboard
            } else {
                header("Location: ../views/index.php"); // Redirect to user dashboard or home page
            }

            exit();
        } else {
            echo "<p style='color: red;'>Incorrect password.</p>";
        }
    } else {
        echo "<p style='color: red;'>No user found with the provided username or email.</p>";
    }

    // Close the prepared statement and connection
    $stmt->close();
    $conn->close();
}
?>
