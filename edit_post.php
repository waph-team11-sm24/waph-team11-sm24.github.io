<?php
session_start();
require_once 'database.php';

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

$postID = $_GET['postID'] ?? null;
if ($postID === null) {
    header("Location: viewposts.php");
    exit;
}

$post = getPostByID($postID); // Function to get post details

if (!$post) {
    header("Location: viewposts.php");
    exit;
}

// Check if the logged-in user is the owner of the post
if ($_SESSION['username'] !== $post['username']) {
    header("Location: viewposts.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];

    if (updatePost($postID, $title, $content)) {
        $success = "Post updated successfully!";
        $post = getPostByID($postID); // Refresh post details
    } else {
        $error = "Failed to update post.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Post</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Edit Post</h1>
        <?php if (isset($success)) echo "<p class='success'>$success</p>"; ?>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="post" action="edit_post.php?postID=<?php echo htmlspecialchars($postID); ?>">
            <input type="text" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>
            <textarea name="content" required><?php echo htmlspecialchars($post['content']); ?></textarea>
            <button type="submit" class="button">Update Post</button>
        </form>
        <a href="viewposts.php">Back to Posts</a>
    </div>
</body>
</html>
