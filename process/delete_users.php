<!-- delete_user.php -->
<?php
// Include the database connection
include '../includes/connection.php';

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Begin transaction to ensure data integrity
    $conn->begin_transaction();

    try {
        // First, delete from search_history table related to the user
        $deleteSearchHistoryQuery = "DELETE FROM search_history WHERE user_id = ?";
        $stmtSearchHistory = $conn->prepare($deleteSearchHistoryQuery);
        $stmtSearchHistory->bind_param("i", $user_id);
        $stmtSearchHistory->execute();

        // Now, delete from users table
        $deleteUserQuery = "DELETE FROM users WHERE user_id = ?";
        $stmtUser = $conn->prepare($deleteUserQuery);
        $stmtUser->bind_param("i", $user_id);

        if ($stmtUser->execute()) {
            // Commit the transaction if both deletions are successful
            $conn->commit();
            echo 'User and associated data deleted successfully!';
            header('Location: ../admin/users.php');
            exit;
        } else {
            // Rollback the transaction in case of an error
            $conn->rollback();
            echo 'Error deleting user.';
        }

    } catch (Exception $e) {
        // Rollback the transaction if an exception occurs
        $conn->rollback();
        echo 'Error occurred: ' . $e->getMessage();
    }

} else {
    echo 'No user selected';
    exit;
}
?>
