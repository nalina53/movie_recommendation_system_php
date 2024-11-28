<!-- users.php -->
<?php include 'dashboard.php'; ?>
<?php
// Include the database connection
include '../includes/connection.php';

// Fetch users where role is 'user'
$query = "SELECT * FROM users WHERE role = 'user'";
$result = $conn->query($query);
?>

<div class="container mt-5">
    <h2>Users List</h2>
    
    <?php
    if ($result->num_rows > 0) {
        echo '<table class="table table-bordered">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Username</th>';
        echo '<th>Email</th>';
        echo '<th>Registration Date</th>';
        echo '<th>Action</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        
        // Fetch and display user data
        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row['username']) . '</td>';
            echo '<td>' . htmlspecialchars($row['email']) . '</td>';
            echo '<td>' . htmlspecialchars($row['created_at']) . '</td>';
            echo '<td>';
            // Delete button links to delete_user.php with user_id as a parameter
            echo '<a href="../process/delete_users.php?id=' . $row['user_id'] . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure you want to delete this user?\');">Delete</a>';
            echo '</td>';
            echo '</tr>';
            echo '</tr>';
        }
        
        echo '</tbody>';
        echo '</table>';
    } else {
        echo '<p>No users found.</p>';
    }
    ?>
</div>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
