<?php
session_start();
require_once 'session_auth.php'; // Include session settings and CSRF token setup
require_once 'database.php'; // Include database connection and functions

// Check if the user is logged in, if not then redirect to login page
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

// Retrieve posts to display
$posts = getAllPosts();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['comment'])) {
        $postID = intval($_POST['postID']);
        $comment = trim($_POST['comment']);
        $username = $_SESSION['username'];

        // Check CSRF token
        if (!isset($_POST['nocsrftoken']) || $_POST['nocsrftoken'] !== $_SESSION['nocsrftoken']) {
            die("CSRF validation failed.");
        }

        // Validate comment content
        if (!empty($comment)) {
            addComment($postID, $comment, $username);
        }
        header("Location: viewposts.php");
        exit;
    } elseif (isset($_POST['delete_post'])) {
        $postID = intval($_POST['postID']);
        $username = $_SESSION['username'];

        // Check CSRF token
        if (!isset($_POST['nocsrftoken']) || $_POST['nocsrftoken'] !== $_SESSION['nocsrftoken']) {
            die("CSRF validation failed.");
        }

        deletePost($postID, $username);
        header("Location: viewposts.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Posts</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        function toggleComments(postID) {
            var commentsSection = document.getElementById('comments-' + postID);
            var button = document.getElementById('toggle-button-' + postID);
            if (commentsSection.style.display === 'none' || commentsSection.style.display === '') {
                commentsSection.style.display = 'block';
                button.textContent = 'Hide Comments';
            } else {
                commentsSection.style.display = 'none';
                button.textContent = 'Show Comments';
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Posts</h1>
        <a href="add_post.php">Make a Post</a>
        <?php foreach ($posts as $post): ?>
            <div class='post' style="border: 1px solid #ddd; padding: 10px; margin-bottom: 20px;">
                <h2><?php echo htmlentities($post['title']); ?></h2>
                <p><?php echo htmlentities($post['content']); ?></p>
                <p><small>Posted by <?php echo htmlentities($post['username']); ?> on <?php echo htmlentities($post['date']); ?></small></p>

                <!-- Comment Form -->
                <div class="comment-form">
                    <form action="viewposts.php" method="POST">
                        <input type="hidden" name="nocsrftoken" value="<?php echo htmlspecialchars($_SESSION['nocsrftoken']); ?>">
                        <input type="hidden" name="postID" value="<?php echo htmlspecialchars($post['postID']); ?>">
                        <div class="comment-input-container">
                            <textarea name="comment" rows="4" placeholder="Add a comment..."></textarea>
                            <input type="submit" value="Post" class="post-button">
                        </div>
                    </form>
                </div>

                <!-- Toggle Comments Button -->
                <button id="toggle-button-<?php echo $post['postID']; ?>" onclick="toggleComments(<?php echo $post['postID']; ?>)">Show Comments</button>

                <!-- Display Comments -->
                <div class="comments" id="comments-<?php echo $post['postID']; ?>" style="display: none;">
                    <?php
                    $comments = getCommentsByPostID($post['postID']);
                    foreach ($comments as $comment):
                    ?>
                        <div class="comment">
                            <p><strong><?php echo htmlentities($comment['username']); ?>:</strong> <?php echo htmlentities($comment['content']); ?></p>
                            <p><small>Posted on <?php echo htmlentities($comment['date']); ?></small></p>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Edit and Delete Post Buttons -->
                <?php if ($post['username'] === $_SESSION['username']): ?>
                    <form action="edit_post.php" method="GET" style="display: inline;">
                        <input type="hidden" name="postID" value="<?php echo htmlspecialchars($post['postID']); ?>">
                        <input type="submit" value="Edit Post" class="edit-button">
                    </form>

                    <form action="viewposts.php" method="POST" style="margin-top: 10px; display: inline;">
                        <input type="hidden" name="nocsrftoken" value="<?php echo htmlspecialchars($_SESSION['nocsrftoken']); ?>">
                        <input type="hidden" name="postID" value="<?php echo htmlspecialchars($post['postID']); ?>">
                        <input type="submit" name="delete_post" value="Delete Post" class="delete-button">
                    </form>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
