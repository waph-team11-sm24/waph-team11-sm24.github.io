<?php
session_start();
require_once 'database.php';

// Check if the user is logged in, if not then redirect to login page
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_SESSION['username'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    if (addPost($username, $title, $content)) {
        header("Location: viewposts.php");
    } else {
        $error = "Post creation failed.";
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
            <input type="text" name="title" placeholder="Title" required>
            <textarea name="content" placeholder="Content" required></textarea>
            <button type="submit" class="button">Post</button>
        </form>
        <a href="viewposts.php">View All Posts</a>
    </div>
</body>
</html>
