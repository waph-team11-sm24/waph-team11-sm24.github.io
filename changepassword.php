<?php
session_start();
require_once 'database.php'; // Include database connection and functions

// Check if the user is logged in, if not then redirect to login page
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $new_password_confirm = $_POST['new_password_confirm'];
    $username = $_SESSION['username'];

    $errors = [];

    // Validate new password (at least one capital letter, one number, one special character, and minimum 8 characters)
    if (!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $new_password)) {
        $errors[] = "New password does not meet requirements";
    }

    // Confirm new password match
    if ($new_password !== $new_password_confirm) {
        $errors[] = "New passwords do not match";
    }

    if (empty($errors)) {
        // Fetch the current hashed password from the database
        $current_hashed_password = getPasswordByUsername($username);

        // Verify current password
        if (md5($current_password) === $current_hashed_password) {
            // Hash the new password
            $hashed_new_password = md5($new_password);

            // Update the password in the database
            changePassword($username, $hashed_new_password);

            // Redirect to profile page
            header("Location: profile.php");
            exit;
        } else {
            $errors[] = "Current password is incorrect";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Password</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Change Password</h1>
        <?php if (!empty($errors)): ?>
            <div class="errors">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo htmlentities($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <form method="post" action="changepassword.php">
            <input type="password" name="current_password" placeholder="Current Password" required>
            <input type="password" name="new_password" placeholder="New Password" required>
            <input type="password" name="new_password_confirm" placeholder="Confirm New Password" required>
            <button type="submit" class="button">Change Password</button>
        </form>
    </div>
</body>
</html>
