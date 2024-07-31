<?php
session_start();
require_once 'session_auth.php'; // Include session settings and CSRF token setup
require_once 'database.php'; // Include database connection and functions

// Check if the user is logged in and is a superuser
if (!isset($_SESSION['username']) || !$_SESSION['superuser']) {
    header("Location: index.php");
    exit;
}

// Retrieve users from the database
$users = getAllUsers();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_user'])) {
        $username = $_POST['username'];
        $disabled = isset($_POST['disabled']) ? 1 : 0;

        // Check CSRF token
        if (!isset($_POST['nocsrftoken']) || $_POST['nocsrftoken'] !== $_SESSION['nocsrftoken']) {
            die("CSRF validation failed.");
        }

        // Update user status
        updateUserStatus($username, $disabled);
        header("Location: admin_users.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Manage Users</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Manage Users</h1>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo htmlentities($user['username']); ?></td>
                        <td>
                            <?php echo $user['disabled'] ? 'Disabled' : 'Active'; ?>
                        </td>
                        <td>
                            <form action="admin_users.php" method="POST" class="form-inline">
                                <input type="hidden" name="nocsrftoken" value="<?php echo htmlspecialchars($_SESSION['nocsrftoken']); ?>">
                                <input type="hidden" name="username" value="<?php echo htmlspecialchars($user['username']); ?>">
                                <div class="form-check">
                                    <input type="checkbox" name="disabled" class="form-check-input" <?php echo $user['disabled'] ? 'checked' : ''; ?>>
                                    <label class="form-check-label">Disable</label>
                                </div>
                                <input type="submit" name="update_user" value="Update" class="btn btn-primary ml-2">
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
