<?php
session_start();
require_once 'database.php';
require 'session_auth.php'; // Include session settings and CSRF token setup


// Check if the user is logged in, if not then redirect to login page
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

$username = $_SESSION['username'];
$userProfile = getUserProfile($username);

// Check if the user is disabled
if ($userProfile['disabled']) {
    die('Your account has been disabled.');
}

// Check if the user is a superuser
$is_superuser = $userProfile['superuser'] == 1;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    if (updateUserProfile($username, $name, $email, $phone)) {
        $success = "Profile updated successfully!";
        $userProfile = getUserProfile($username);
    } else {
        $error = "Profile update failed.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Profile</h1>
        <?php if (isset($success)) echo "<p class='success'>$success</p>"; ?>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="post" action="profile.php">
            <input type="text" name="name" value="<?php echo htmlentities($userProfile['name']); ?>" required>
            <input type="email" name="email" value="<?php echo htmlentities($userProfile['email']); ?>" required>
            <input type="text" name="phone" value="<?php echo htmlentities($userProfile['phone']); ?>" required>
            <button type="submit" class="button">Update Profile</button>
        </form>
        <a href="changepassword.php">Change Password</a>
        <a href="viewposts.php">View All Posts</a>
        <a href="add_post.php">Make a Post</a>
        <a href="logout.php">Logout</a>

        <?php if ($is_superuser): ?>
            <a href="admin_users.php" class="admin-button">Admin Settings</a>
        <?php endif; ?>
    </div>
</body>
</html>
