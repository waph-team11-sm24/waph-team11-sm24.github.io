<?php
session_start();
require_once 'session_auth.php'; // Include session settings and CSRF token setup
require_once 'database.php'; // Include database connection and functions

// Check if the user is logged in, if not then redirect to login page
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_SESSION['username'];
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    // Check CSRF token
    if (!isset($_POST['nocsrftoken']) || $_POST['nocsrftoken'] !== $_SESSION['nocsrftoken']) {
        die("CSRF validation failed.");
    }

    // Validate input
    if (!empty($title) && !empty($content)) {
        if (addPost($username, $title, $content)) {
            header("Location: viewposts.php");
            exit;
        } else {
            $error = "Post creation failed.";
        }
    } else {
        $error = "Title and content cannot be empty.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Make a Post</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Make a Post</h1>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="post" action="add_post.php">
            <input type="hidden" name="nocsrftoken" value="<?php echo htmlspecialchars($_SESSION['nocsrftoken']); ?>">
            <input type="text" name="title" placeholder="Title" required>
            <textarea name="content" placeholder="Content" required></textarea>
            <button type="submit" class="button">Post</button>
        </form>
        <a href="viewposts.php">View All Posts</a>
    </div>
</body>
</html>
