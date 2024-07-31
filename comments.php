<?php
session_start();
require 'session_auth.php'; // Include session settings and CSRF token setup
require 'database.php'; // Include database connection and functions

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

// Retrieve posts to display
$posts = get_posts();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comments</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS -->
</head>
<body>
    <h1>Post Comments</h1>
    
    <!-- Display posts -->
    <?php foreach ($posts as $post): ?>
        <div class="post">
            <h2><?php echo htmlspecialchars($post['title']); ?></h2>
            <p><?php echo htmlspecialchars($post['content']); ?></p>
            
            <!-- Comment form -->
            <form action="add_comment.php" method="post">
                <input type="hidden" name="nocsrftoken" value="<?php echo htmlspecialchars($_SESSION['nocsrftoken']); ?>">
                <input type="hidden" name="post_id" value="<?php echo htmlspecialchars($post['post_id']); ?>">
                <textarea name="comment" rows="4" cols="50" placeholder="Add your comment here..."></textarea>
                <button type="submit">Submit Comment</button>
            </form>
            
            <!-- Display comments for this post -->
            <?php
            $comments = get_comments($post['post_id']);
            foreach ($comments as $comment):
            ?>
                <div class="comment">
                    <p><?php echo htmlspecialchars($comment['comment']); ?></p>
                    <small>— <?php echo htmlspecialchars($comment['username']); ?></small>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
</body>
</html>
