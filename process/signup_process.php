<?php
include '../includes/connection.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Retrieve and trim form data
    $formUsername = trim($_POST['username']);
    $formEmail = trim($_POST['email']);
    $formPassword = trim($_POST['password']);
    $formConfirmPassword = trim($_POST['confirm_password']);

    // Initialize an array to store error messages
    $errors = [];

    // Validate username
    if (empty($formUsername)) {
        $errors[] = "Username is required.";
    } elseif (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $formUsername)) {
        $errors[] = "Username must be 3-20 characters long and can only contain letters, numbers, and underscores.";
    }

    // Validate email
    if (empty($formEmail)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($formEmail, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Check if the email or username already exists in the database
    $checkSql = "SELECT user_id FROM users WHERE username = ? OR email = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("ss", $formUsername, $formEmail);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        $errors[] = "Username or email is already taken.";
    }
    $checkStmt->close();

    // Validate password
    if (empty($formPassword)) {
        $errors[] = "Password is required.";
    } elseif (strlen($formPassword) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    }

    // Validate password confirmation
    if ($formPassword !== $formConfirmPassword) {
        $errors[] = "Passwords do not match.";
    }

    // Display errors if any
    if (!empty($errors)) {
        $errorMessage = implode("\\n", $errors); // Join error messages with newline characters
        echo "<script>alert('$errorMessage');</script>";
        exit();
    }

    // Hash the password
    $hashedPassword = password_hash($formPassword, PASSWORD_BCRYPT);

    // Insert into the database
    $sql = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'user')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $formUsername, $formEmail, $hashedPassword);

    if ($stmt->execute()) {
        echo "<script>alert('Registration successful!'); window.location.href='../views/index.php';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
