<?php
session_start();
require 'session_auth.php'; // Include session settings and CSRF token setup
require 'database.php'; // Include database connection and functions

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

// Check CSRF token
if (!isset($_POST['nocsrftoken']) || $_POST['nocsrftoken'] !== $_SESSION['nocsrftoken']) {
    die("CSRF validation failed.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_id']) && isset($_POST['comment'])) {
    $post_id = $_POST['post_id'];
    $comment = $_POST['comment'];
    $username = $_SESSION['username'];

    // Validate comment content (e.g., basic validation)
    if (empty($comment)) {
        die("Comment cannot be empty.");
    }

    // Add the comment to the database
    if (add_comment($post_id, $comment, $username)) {
        echo "Comment added successfully.";
    } else {
        echo "Failed to add comment.";
    }
} else {
    echo "Invalid request.";
}

// Redirect back to the profile or previous page
header('Location: profile.php');
exit();
?>
