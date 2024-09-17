<?php
include '../includes/connection.php';

// Retrieve form data directly from $_POST
$formIdentifier = $_POST['identifier'];
$formPassword = $_POST['password'];

// Prepare the query to find user by username or email
$sql = "SELECT username, email, password, role FROM users WHERE username = ? OR email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $formIdentifier, $formIdentifier);

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

// Check if user exists
if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    // Verify the password
    if (password_verify($formPassword, $user['password'])) {
        // Password is correct, start a session and log the user in
        session_start();
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

// Close connection
$stmt->close();
$conn->close();
?>
